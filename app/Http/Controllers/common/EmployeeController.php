<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Employee_category;
use App\Models\Permission;
use App\Models\Permission_label;
use App\Models\Permission_user;
use App\Models\Staff;
use App\Models\Staff_type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{

    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Staff::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($employee) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-staff')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$employee->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-staff')){
                    $data .= ' <button type="button" class="btn btn-info btn-sm edit" id="'.$employee->id.'"><span class="feather icon-edit"></span></button>
                    <button type="button" class="btn btn-primary btn-sm loginInfo" id="'.$employee->id.'"><span class="feather icon-log-in"></span></button>';
                }
                if(check_access('view-staff-role')){
                    $data .= '<button type="button" class="btn btn-secondary btn-sm access" id="'.$employee->id.'"><span class="feather icon-crop"></span></button>';
                }
                return '</div>'.$data;
            })

            ->editColumn('photo', function($employee){
                return '<img style="max-width:40px" src="'.url('storage/'.$employee->photo).'">';
            })

            ->editColumn('status', function($employee){
                if($employee->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->rawColumns(['photo','status','modify'])->make(true);
        }
        $categories = Staff_type::orderBy('title','ASC')->get();
        return view('common.user.employee.index', compact('categories'));
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $validator=  $this->userFields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $user = User::create(['user_type_id'=>3,'email'=>$request->email, 'phone'=>$request->phone,'password'=>Hash::make($request->password)]);
        $data = [
            'user_id'=>$user->id, 'staff_type_id'=>$request->employee_category,
            'first_name'=>$request->first_name, 'last_name'=>$request->last_name,
            'position'=>$request->position,  'sex'=>$request->sex,
            'address'=>$request->address, 'salary'=>$request->salary,'status'=>$request->status,
        ];

        $employee = Staff::create($data);
        $employee->save();

        $this->storeImage($employee);
        return response()->json(['success' => 'Employee has been created successfully!']);
    }

    public function show(Staff $staff){ return $staff;}

    public function login_info(Staff $staff){
        $user = User::where('id',$staff->user_id)->first();
        return view('common.user.employee.loginInfo',compact('user','staff'));
    }


    public function update(Request $request,Staff $staff){
        $validator = $this->fields($staff->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [
            'staff_type_id'=>$request->employee_category,
            'first_name'=>$request->first_name, 'last_name'=>$request->last_name,
            'position'=>$request->position,  'sex'=>$request->sex,
            'address'=>$request->address, 'salary'=>$request->salary,'status'=>$request->status
        ];

        $staff->update($data);
        $this->storeImage($staff,'update');

        return response()->json(['success' => 'The employee hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'first_name'=>'required','last_name'=>'required', 'sex'=>'required',
            'photo'=>'sometimes|nullable|image',
            'position'=>'required','salary'=>'required', 'address'=>'required', 'status'=>'required',
        ]); return $validator;
    }

    private function userFields($id=null){
        $validator = Validator::make(request()->all(), [
            'email'=>'required|unique:users,email,'.$id,
            'phone'=>'required|unique:users,phone,'.$id,
            'password'=>'required',
        ]); return $validator;
    }

    function storeImage($staff,$type=null){

        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(350, 350);
            Storage::disk('public')->put("images/user/staff/".$imageName, (string) $image->encode());
            $staff->update(['photo'=>"images/user/staff/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/user.jpg') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }


    public function destroy(Staff $staff){
        try {
            if(\file_exists(public_path('storage/').$staff->photo) && $staff->photo !='images/user.jpg'){
                \File::delete(public_path('storage/').$staff->photo);
            }
            $staff->delete();
            User::where('id',$staff->user_id)->delete();
            return response()->json(['success' => 'Employee hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }



    function user_permission(Request $request, Staff $staff){
        $labels = Permission_label::orderBy('title')->get();
        // dd($labels);
        return view('common.user.employee.permissions',['staff'=>$staff, 'labels'=>$labels]);
    }

    function save_staff_permission(Request $request){
        //delete all staff permission first
        Permission_user::where('user_id',$request->user_id)->delete();

        foreach($request->ids as $id){
            Permission_user::create([
                'user_id'=>$request->user_id,
                'permission_label_id'=>explode('|',$id)[0],
                'permission_id'=>explode('|',$id)[1],
            ]);
        }
        return back();
    }

}
