<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product_variation_option;
use App\Models\Variation;
use Illuminate\Http\Request;
use Validator;

class VariantController extends Controller
{
    function index(Request $request){

        if($request->draw){
            return datatables()::of(Variation::orderBy('title'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                $data = '';
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';

                if (check_access('edit-product-variation')){
                    $data .= ' <button type="button" class="btn btn-info btn-sm edit" id="'.$row->id.'"><span class="feather icon-edit"></span></button>';

                }
                if(check_access('delete-product-variation')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
                }
                if (check_access('create-product-variation-option')){
                    $data .='<button type="button" class="btn btn-secondary btn-sm extention" data-title="'.$row->title.'" id="'.$row->id.'"><span class="feather icon-plus"></span></button>';
                }
                $data .= '</div>';  return $data;
            })
            ->editColumn('products', function ($row) {
                return $row->product_variation_options()->count().' products';
            })
            ->editColumn('options', function ($row) {
                $data = $row->variation_options()->count();
                if (check_access('view-product-variation-option')){
                    $data .=' <a href="javaScript:;" class="variationOptions" data-title="'.$row->title.'" data-id="'.$row->id.'">options</a>';
                }
                return $data;
            })

            ->editColumn('status', function($row){
                if($row->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->rawColumns(['options','products','status','modify'])->make(true);
        }
        return view('common.product.variant.index');
    }

    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'title'=>$request->title,'origin'=>str_replace(' ', '', strtolower($request->origin)),'status'=>$request->status];
        try {
            Variation::create($data);
            return response()->json(['success' => 'Variant type has been saved successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Creation failed. Its may be Data duplicate error!!']);
        }

    }

    public function show(Variation $variation){ return $variation; }


    public function update(Request $request, Variation $variation){
        $validator = $this->fields($variation->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $data = [ 'title'=>$request->title,'origin'=>str_replace(' ', '', strtolower($request->origin)),'status'=>$request->status];
        $variation->update($data);
        return response()->json(['success' => 'Variant type hasn been updated successfully!']);
    }


    public function destroy(Variation $variation)
    {
        try {
            $variation->delete();
            return response()->json(['success' => 'The record hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }



    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required|unique:variations,title,'.$id,
            'origin'=>'required|unique:variations,origin,'.$id,
            'status'=>'required',
        ]); return $validator;
    }


}
