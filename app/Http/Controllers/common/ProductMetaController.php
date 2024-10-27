<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product_meta;
use Illuminate\Http\Request;
use Validator;

class ProductMetaController extends Controller
{
    public function index(Request $request, Product $product){

        if($request->draw){
            return datatables()::of(Product_meta::where('product_id',$product->id))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-product-meta')){
                    $data .='<button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-product-meta')){
                    $data .='<button type="button" class="btn btn-info btn-sm edit" id="'.$row->id.'"><span class="feather icon-edit"></span></button>';
                }
                return '</div>'.$data;
            })
            ->rawColumns(['modify'])->make(true);
        }
        return view('common.product.meta.index', compact('product'));
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'product_id'=>$request->product_id, 'meta_type'=>$request->meta_type,'meta_content'=>$request->meta_content];
        $slider = Product_meta::create($data);
        $slider->save();

        return response()->json(['success' => 'Meta has been created successfully!']);
    }

    public function show(Product_meta $product_meta){ return $product_meta;}


    public function update(Request $request,Product_meta $product_meta){
        $validator = $this->fields($product_meta->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = ['meta_type'=>$request->meta_type,'meta_content'=>$request->meta_content];
        $product_meta->update($data);
        return response()->json(['success' => 'The Meta hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'product_id'=>'required',
            'meta_type'=>'required',
            'meta_content'=>'required',
        ]); return $validator;
    }


    public function destroy(Product_meta $product_meta){
        try {
            $product_meta->delete();
            return response()->json(['success' => 'Meta info hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
