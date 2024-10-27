<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Staff_type;
use Illuminate\Http\Request;
use Validator;

class EmployeeCategoryController extends Controller
{
    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Staff_type::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-staff-dept')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-staff-dept')){
                    $data .= ' <button type="button" class="btn btn-info btn-sm edit" id="'.$cat->id.'"><span class="feather icon-edit"></span></button>';
                }
                return '</div>'.$data;
            })

            ->editColumn('staffs', function($cat){
                return $cat->staffs()->count();
            })
            ->editColumn('status', function($cat){
                if($cat->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->rawColumns(['photo','staffs','status','modify'])->make(true);
        }
        return view('common.user.employee.category.index');
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'title'=>$request->title,'description'=>$request->description,'status'=>$request->status];
        $staff_type = Staff_type::create($data);
        $staff_type->save();

        return response()->json(['success' => 'Employee-category has been created successfully!']);
    }

    public function show(Staff_type $staff_type){ return Staff_type::find($staff_type->id);}


    public function update(Request $request,Staff_type $staff_type){
        $validator = $this->fields($staff_type->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = ['title'=>$request->title,'description'=>$request->description,'status'=>$request->status];

        $staff_type->update($data);
        return response()->json(['success' => 'The staff category hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required|unique:staff_types,title,'.$id,
            'description'=>'sometimes|nullable',
            'status'=>'required',
        ]); return $validator;
    }


    public function destroy(Staff_type $staff_type){
        try {
            $staff_type->delete();
            return response()->json(['success' => 'Category hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }


}
