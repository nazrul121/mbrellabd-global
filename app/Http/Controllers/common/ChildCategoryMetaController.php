<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Child_group;
use App\Models\Child_group_meta;
use App\Models\Inner_group_meta;
use Illuminate\Http\Request;
use Validator;


class ChildCategoryMetaController extends Controller
{
    public function index(Request $request, Child_group $child_group){

        if($request->draw){
            return datatables()::of(Child_group_meta::where('child_group_id',$child_group->id))
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
        return view('common.category.child.meta.index', compact('child_group'));
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'child_group_id'=>$request->child_group_id, 'meta_type'=>$request->meta_type,'meta_content'=>$request->meta_content];
        $slider = Child_group_meta::create($data);
        $slider->save();

        return response()->json(['success' => 'Meta has been created successfully!']);
    }

    public function show(Child_group_meta $child_group_meta){
        return $child_group_meta;
    }


    public function update(Request $request, Child_group_meta $child_group_meta){
        $validator = $this->fields($child_group_meta->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = ['meta_type'=>$request->meta_type,'meta_content'=>$request->meta_content];
        $child_group_meta->update($data);
        return response()->json(['success' => 'The Meta hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'child_group_id'=>'required',
            'meta_type'=>'required',
            'meta_content'=>'required',
        ]); return $validator;
    }


    public function destroy(Child_group_meta $child_group_meta){
        try {
            $child_group_meta->delete();
            return response()->json(['success' => 'Meta info hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
