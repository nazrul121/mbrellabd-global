<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Variation;
use App\Models\Variation_option;
use Illuminate\Http\Request;
use Validator;

class VariationOptionController extends Controller
{
    function index(Request $request, Variation $variation){
        if($request->draw){
            return datatables()::of(Variation_option::where('variation_id',$variation->id)->orderBy('title'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                $data = '';
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';

                if (check_access('edit-product-variation-option')){
                    $data .= ' <button type="button" class="btn btn-info btn-sm edit" id="'.$row->id.'"><span class="feather icon-edit"></span></button>';

                }
                if(check_access('delete-product-variation-option')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
                }

                $data .= '</div>';  return $data;

            })
            ->editColumn('products', function ($row) {
                return $row->product_variation_options()->count().' products';
            })

            ->editColumn('status', function($row){
                if($row->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->rawColumns(['products','status','modify'])->make(true);
        }
        return view('common.product.variant.option.index',compact('variation'));
    }

    public function store(Request $request){
        // dd($request->all());
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'variation_id'=>$request->variation_id, 'title'=>$request->title,'origin'=>str_replace(' ', '', strtolower($request->origin)),'code'=>$request->code, 'status'=>$request->status];
        try {
            Variation_option::create($data);
            return response()->json(['success' => 'Variant option type has been saved successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Creation failed. Its may be Data duplicate error!!']);
        }

    }

    public function show(Variation_option $variation_option){ return $variation_option; }


    public function update(Request $request, Variation_option $variation_option){
        $validator = $this->fields($variation_option->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = ['title'=>$request->title,'origin'=>str_replace(' ', '', strtolower($request->origin)),'code'=>$request->code, 'status'=>$request->status];
        $variation_option->update($data);
        return response()->json(['success' => 'Variant option hasn been updated successfully!']);
    }


    public function destroy(Variation_option $variation_option)
    {
        try {
            $variation_option->delete();
            return response()->json(['success' => 'The record hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }



    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'variation_id'=>'required',
            'title'=>'required',
            'origin'=>'required|unique:variation_options,origin,'.$id,
            'status'=>'required',
        ]); return $validator;
    }
}
