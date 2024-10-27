<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Highlight;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class HighlightController extends Controller
{
    function index(Request $request){
        if($request->draw){
            return datatables()::of(Highlight::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-product-highlight')){
                    $data .='<button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-product-highlight')){
                    $data .='<button type="button" class="btn btn-info btn-sm edit" id="'.$row->id.'"><span class="feather icon-edit"></span></button>';
                }
                if(check_access('add-product-to-highlight')){
                    $data .='<button type="button" class="btn btn-secondary btn-sm addProduct" id="'.$row->id.'"><span class="feather icon-plus"></span></button>';
                }
                return '</div>'.$data;
            })
            ->editColumn('photo', function($row){
                return  '<img src="'.url('storage/'.$row->photo).'" height="30">';
            })
            ->editColumn('product', function($row){
                $data =$row->products()->count();
                if(check_access('view-product-highlight')){
                    $data .=' <a target="_blank" href="'.route('common.highlight-products',$row->id).'">Product<sub>s</sub></a>';
                } return $data;
            })

            ->editColumn('status', function($row){
                if($row->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })
            ->editColumn('country', function($row){
                $country = '';
                foreach($row->countries()->select('short_name','flag')->get() as $cnt){
                 $country .= '<img src="'.url($cnt->flag).'" title="'.$cnt->short_name.'"> ';
                }
                return $country;
            })


            ->rawColumns(['country','photo','product','description','status','modify'])->make(true);
        }
        return view('common.product.highlight.index');
    }

    public function store(Request $request, Product $product){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [
            'title'=>$request->title, 'sub_title'=>$request->sub_title, 'description'=>$request->description,
            'meta_title'=>$request->meta_title, 'meta_description'=>$request->meta_description, 'status'=>$request->status
        ];

        $hightlight = Highlight::create($data);

        $hightlight->countries()->attach($request->langs);
        $hightlight->save();
        $this->storeImage($hightlight);
        return response()->json(['success' => 'Highlight has been saved successfully!']);
    }

    public function show(Highlight $highlight){ 
        $highlight['country'] = $highlight->countries()->select('country_id')->get();
        return $highlight;
    }


    public function update(Request $request,Highlight $highlight){
        $validator = $this->fields($highlight->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        
        $data = [
            'title'=>$request->title, 'sub_title'=>$request->sub_title, 'description'=>$request->description,
            'meta_title'=>$request->meta_title, 'meta_description'=>$request->meta_description, 'status'=>$request->status
        ];

        $highlight->update($data);
        $highlight->countries()->sync($request->langs);
        $this->storeImage($highlight,'update');
        return response()->json(['success' => 'Highlight hasn been updated successfully!']);
    }


    public function destroy(Highlight $highlight){
        try {
            if(\file_exists(public_path('storage/').$highlight->photo) && $highlight->photo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$highlight->photo);
            }
            $highlight->delete();
            return response()->json(['success' => 'The record hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }

    }

    function storeImage($hightlight,$type=null){
        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(1800, 800);
            Storage::disk('public')->put("images/highlight/".$imageName, (string) $image->encode());
            $hightlight->update(['photo'=>"images/highlight/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }



    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required|unique:highlights,title,'.$id,
            'sub_title'=>'required',
            'photo'=>'sometimes|nullable|image|max:600',
            'status'=>'required',
            'description'=>'required'
        ]); return $validator;
    }


}
