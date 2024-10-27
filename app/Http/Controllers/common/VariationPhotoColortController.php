<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Product;

use App\Models\Product_variation_option;
use App\Models\Setting;
use App\Models\Variation_option;
use App\Models\Variation_option_photo;
use Illuminate\Http\Request;
use Validator;
use File;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;


class VariationPhotoColortController extends Controller
{
    function index(Product $product, Request $request){
        if($request->draw){

            return datatables()::of(Variation_option_photo::where('product_id',$product->id))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                return '
                <div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">
                    <button type="button" class="btn btn-info btn-sm edit" data-id="'.$row->id.'" data-variation_id="'.$row->variation_id.'" data-option_id="'.$row->variation_option_id.'" data-oldPhoto="'.$row->photo.'"><span class="feather icon-edit"></span></button>
                    <button type="button" class="btn btn-danger btn-sm delete" data-id="'.$row->id.'" data-option_id="'.$row->variation_option_id.'" ><span class="feather icon-trash"></span></button>
                </div>';
            })
            ->editColumn('variation', function ($row) {
                return $row->variation->title.' <i class="fa fa-long-arrow-right"></i> '.$row->variation_option->title;
            })
            ->addColumn('photo', function($row)use($product) {
                if($row->photo==null){
                    return 'No photo';
                }
                return '<img src="'.$row->thumbs.'" height="50">';
            })

            ->rawColumns(['variation','photo','modify'])->make(true);
        }

        $listViewVariationId = \DB::table('settings')->where('type','variation-at-product-list')->pluck('value')->first();
        $variation_options = Variation_option::where('variation_id',$listViewVariationId)->get();
        return view('common.product.variation-photo.index', compact('product','variation_options'));
    }

    public function store(Product $product, Request $request){
        // dd($request->all());
        if (request()->has('photo')) {
            $check = Variation_option_photo::where(['product_id'=>$product->id,'variation_id'=>explode('|',$request->variation_id)[0], 'variation_option_id'=>explode('|',$request->variation_id)[1]]);
            if($check->count() < 1){
                $fieldFile = request()->photo;
                $mime= $fieldFile->getClientOriginalExtension();
                $imageName = time().".".$mime;

                $feature_path = public_path().'/storage/images/product/color-variant/feature';
                File::isDirectory($feature_path) or File::makeDirectory($feature_path, 0777, true, true);

                $thumbs_path = public_path().'/storage/images/product/color-variant/thumbs';
                File::isDirectory($thumbs_path) or File::makeDirectory($thumbs_path, 0777, true, true);


                // product sizing
                $width = Setting::where('type','product-weight')->pluck('value')->first();
                $height = Setting::where('type','product-height')->pluck('value')->first();

                $thumbsHeight = 288;
                $divide = $height/ $thumbsHeight;
                $thumbsWeight = $width /$divide;
                // dd($thumbsHeight.' = '.$thumbsWeight);
                // $image = Image::make($fieldFile)->resize($height,$width);
                Storage::disk('public')->put("images/product/color-variant/feature/".$imageName, file_get_contents(request()->photo));
                $thumbs = Image::make($fieldFile)->resize($thumbsHeight,$thumbsWeight);

                // Storage::disk('public')->put("images/product/color-variant/feature/".$imageName, (string) $image->encode());
                // Storage::disk('public')->put("images/product/color-variant/thumbs/".$imageName, (string) $thumbs->encode());
                // $image->save('storage/images/product/color-variant/feature/'.$imageName);
                $thumbs->save('storage/images/product/color-variant/thumbs/'.$imageName);

                Variation_option_photo::create([
                    'product_id'=>$product->id,
                    'variation_id'=>explode('|',$request->variation_id)[0], 
                    'variation_option_id'=>explode('|',$request->variation_id)[1],
                    'photo'=>url('/')."/storage/images/product/color-variant/feature/".$imageName,  
                    'thumbs'=>url('/')."/storage/images/product/color-variant/thumbs/".$imageName
                ]);
                return response()->json(['success' => 'Photo has been uploaded succesfully!']);
            }else return response()->json(['error' => '<b class="text-info">'.$check->first()->variation->title.'</b>: <b class="text-primary">'.$check->first()->variation_option->title.'</b> already exist']);


        }else return response()->json(['error' => 'Photo uploading error!!']);

    }

    public function update(Product $product, Request $request){
        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;

            // product sizing
            $width = Setting::where('type','product-weight')->pluck('value')->first();
            $height = Setting::where('type','product-height')->pluck('value')->first();

            $thumbsHeight = 288;
            $divide = $height/ $thumbsHeight;
            $thumbsWeight = $width /$divide;
            // dd($thumbsHeight.' = '.$thumbsWeight);
            // $image = Image::make($fieldFile)->resize($height,$width);
            Storage::disk('public')->put("images/product/color-variant/feature/".$imageName, file_get_contents(request()->photo));
            $thumbs = Image::make($fieldFile)->resize($thumbsHeight,$thumbsWeight);

            // Storage::disk('public')->put("images/product/color-variant/feature/".$imageName, (string) $image->encode());
            // Storage::disk('public')->put("images/product/color-variant/thumbs/".$imageName, (string) $thumbs->encode());
            // $image->save('storage/images/product/color-variant/feature/'.$imageName);
            $thumbs->save('storage/images/product/color-variant/thumbs/'.$imageName);

            if (request()->oldPhoto !=null) {
                \File::delete(public_path('storage/'.request()->oldPhoto));
                \File::delete(public_path('storage/'.str_replace('feature','thumbs',request()->oldPhoto)));
            }

            Variation_option_photo::where(['product_id'=>$product->id,'variation_id'=>explode('|',$request->variation_id)[0], 'variation_option_id'=>explode('|',$request->variation_id)[1]])
            ->update(['product_id'=>$product->id,'variation_id'=>explode('|',$request->variation_id)[0], 'variation_option_id'=>explode('|',$request->variation_id)[1],
                'photo'=>"images/product/color-variant/feature/".$imageName,  'thumbs'=>"images/product/color-variant/thumbs/".$imageName]);
            return response()->json(['success' => 'Photo has been uploaded succesfully!']);
        }else {
            Variation_option_photo::where(['id'=>request()->id])
            ->update([
                'variation_id'=>explode('|',$request->variation_id)[0], 'variation_option_id'=>explode('|',$request->variation_id)[1],
            ]);
            return response()->json(['success' => 'Photo variation has been uploaded succesfully!']);
        }

    }

    public function destroy(Variation_option_photo $variation_option_photo)
    {
        // dd($variation_option_photo);
        try {
            if(\file_exists(public_path('storage/').$variation_option_photo->photo)){
                \File::delete(public_path('storage/').$variation_option_photo->photo);
            }
            $variation_option_photo->delete();
            return response()->json(['success' => 'The record hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }


}
