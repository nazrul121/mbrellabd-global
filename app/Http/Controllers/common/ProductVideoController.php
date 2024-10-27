<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product_video;
use Illuminate\Http\Request;
use Validator;

class ProductVideoController extends Controller
{
    function index(Request $request, Product $product){
        if($request->draw){
            return datatables()::of(Product_video::where('product_id',$product->id)->orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-product-video')){
                    $data .='<button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-product-video')){
                    $data .='<button type="button" class="btn btn-info btn-sm edit" id="'.$row->id.'"><span class="feather icon-edit"></span></button>';
                }
                return '</div>'.$data;
            })
            ->editColumn('videos', function($row){
                return '<button class="btn btn-outline-secondary btn-sm video" data-id="'.$row->id.'" type="button">SHow video</button>';
            })
            ->editColumn('product', function($row){
                return $row->product->title;
            })

            ->editColumn('status', function($row){
                if($row->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->rawColumns(['product','videos','status','modify'])->make(true);
        }
        return view('common.product.video.index',compact('product'));
    }

    public function store(Request $request, Product $product){
        // dd($request->all());
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'product_id'=>$product->id,'type'=>$request->type,'video_link'=>$request->video_link, 'status'=>$request->status];
        $product_video = Product_video::create($data);
        $product_video->save();
        $this->upload_video($product_video);
        return response()->json(['success' => 'Product video has been saved successfully!']);
    }

    public function show(Product_video $product_video){
        // return $product_video;
        return view('common.product.video.showVideo',compact('product_video'));
    }

    public function single_video(Product_video $product_video){
        return $product_video;
    }


    public function update(Request $request,Product_video $product_video){
        $validator = $this->fields($product_video->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = ['type'=>$request->type,'video_link'=>$request->video_link, 'status'=>$request->status];

        $product_video->update($data);
        $this->upload_video($product_video,'update');
        return response()->json(['success' => 'Product video hasn been updated successfully!']);
    }


    public function destroy(Product_video $product_video)
    {
        if ($product_video->type=='video') {
            \File::delete(public_path('storage/'.$product_video->video_link));
        }
        $product_video->delete();
        return response()->json(['success' => 'Data hasn been deleted successfully!']);
    }

    function upload_video($product_video,$type=null){
        if (request()->has('file')) {
            $file =  request()->file('file');
            $filename = $file->getClientOriginalName();
            $mime= $file->getClientOriginalExtension();
            $videoName = time().".".$mime;

            $path = public_path().'/storage/videos/product';
            $file->move($path, $videoName);
            $product_video->update([
                'video_link'=>"videos/product/".$videoName,
            ]);

            if ($type=='update' && request()->type=='video') {
                \File::delete(public_path('storage/'.request()->oldVideo));
            }
        }
    }


    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'type'=>'required',
            'video_link'=>'sometimes|nullable','status'=>'required',
            'file'  => 'sometimes|nullable|required|mimes:mp4,mov,ogg,qt'
        ]); return $validator;
    }


}
