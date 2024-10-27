<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Permission;
use App\Models\Permission_label;
use App\Models\Permission_user_type;
use App\Models\Staff;
use App\Models\User;
use App\Models\User_type;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
// use Illuminate\Support\Facades\Schema;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
class AdminController extends Controller
{
    public function __construct(){
        $this->middleware(function ($request, $next) {
            if(Auth::user()->user_type_id =='1' || Auth::user()->user_type_id =='2');
            return $next($request);
        });
    }

    public function index(Request $request){
        if($request->draw){
            return datatables()::of(Admin::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($admin) {
                return '
                <div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">
                    <button type="button" class="btn btn-danger btn-sm delete" id="'.$admin->id.'"><span class="feather icon-trash"></span></button>
                    <button type="button" class="btn btn-info btn-sm edit" id="'.$admin->id.'"><span class="feather icon-edit"></span></button>
                    <button type="button" class="btn btn-primary btn-sm login" id="'.$admin->id.'"><span class="feather icon-log-in"></span></button>
                </div>
                ';
            })

            ->editColumn('photo', function($admin){
                return '<img style="max-width:40px" src="'.url('storage/'.$admin->photo).'">';
            })

            ->editColumn('status', function($admin){
                if($admin->is_super=='1') $super = '<i class="feather icon-check text-white bg-primary p-1" title="Super admin"></i> ';
                else $super = '';

                if($admin->status=='1') return '<span class="badge badge-success"> '.$super.' Active</span>';
                else return  '<span class="badge badge-danger">'.$super.' Inactive</span>';
            })

            ->rawColumns(['photo','role','status','modify'])->make(true);
        }
        return view('common.user.admin.index');
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

        if(isset($request->is_super)){ $type= 1; $is_super = '1';}
        else{ $type = 2;$is_super = '0'; }
        $user = User::create(['user_type_id'=>$type,'email'=>$request->email, 'phone'=>$request->phone,'password'=>Hash::make($request->password)]);

        $data = [
            'user_id'=>$user->id, 'first_name'=>$request->first_name,
            'last_name'=>$request->last_name, 'sex'=>$request->sex,
            'position'=>$request->position, 'address'=>$request->address,
            'is_super'=>$is_super, 'has_permission'=>$request->has_permission
        ];
        // dd($data);
        $admin = Admin::create($data);
        $admin->save();

        $this->storeImage($admin);
        return response()->json(['success' => 'Admin has been created successfully!']);
    }

    public function show(Admin $admin){ return Admin::find($admin->id);}

    public function login_info(Admin $admin){
        $user = User::where('id',$admin->user_id)->first();
        return view('common.user.admin.loginInfo',compact('user','admin'));
    }


    public function update(Request $request,Admin $admin){
        $validator = $this->fields($admin->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if(isset($request->is_super)){ $type= 1; $is_super = '1';}
        else{ $type = 2;$is_super = '0'; }
        User::where('id',$admin->user_id)->update(['user_type_id'=>$type]);

        $data = [
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name, 'sex'=>$request->sex,
            'position'=>$request->position, 'address'=>$request->address,
            'is_super'=>$is_super, 'has_permission'=>$request->has_permission,
        ];

        $admin->update($data);
        $this->storeImage($admin,'update');

        return response()->json(['success' => 'The admin hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'first_name'=>'required',
            'last_name'=>'required',
            'sex'=>'required',
            'photo'=>'sometimes|nullable|image',
            'position'=>'required',
            'address'=>'required',
        ]); return $validator;
    }

    private function userFields($id=null){
        $validator = Validator::make(request()->all(), [
            'email'=>'required|unique:users,email,'.$id,
            'phone'=>'required|unique:users,phone,'.$id,
            'password'=>'required',
        ]); return $validator;
    }

    function storeImage($admin,$type=null){
        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(350, 350);
            Storage::disk('public')->put("images/user/admin/".$imageName, (string) $image->encode());
            $admin->update(['photo'=>"images/user/admin/".$imageName]);
            if ($type=='update' && $admin->oldPhoto !='images/user.jpg') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }


    public function destroy(Admin $admin){
        try {
            if($admin->user_id=='1'){
                return response()->json(['error' => 'Deletion failed. Prime super-admin cannot be deleted']);
            }
            if(\file_exists(public_path('storage/').$admin->photo) && $admin->photo !='images/user.jpg'){
                \File::delete(public_path('storage/').$admin->photo);
            }
            $admin->delete();
            User::where('id',$admin->user_id)->delete();
            return response()->json(['success' => 'Admin hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

    function login_access(User $user, $type){
        if($type=='allow'){ $status='1';} else $status = '0';
        $user->update(['status'=>$status]);
        return back();
    }



    function user_types(Request $request){
        if($request->draw){
            return datatables()::of(User_type::orderBy('title'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                if($row->title !='customer'){
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">
                        <button type="button" class="btn btn-primary btn-sm access" id="'.$row->id.'"><span class="feather icon-crop"></span></button>
                    </div>';
                }
            })

            ->editColumn('users', function($row){
                return $row->users()->count();
            })

            ->editColumn('title', function($row){
                return $row->title;
            })

            ->rawColumns(['title','users','modify'])->make(true);
        }
        return view('common.user.user_type.index');
    }


    function user_type_permission(User_type $user_type){
        $labels = Permission_label::all();
        return view('common.user.user_type.permissions',['user_type'=>$user_type,'labels'=>$labels]);
    }

    function save_user_type_permission(Request $request){
        // dd($request->all());
        //delete all staff permission first
        Permission_user_type::where('user_type_id',$request->user_type_id)->delete();

        foreach($request->ids as $id){
            Permission_user_type::create([
                'user_type_id'=>$request->user_type_id,
                'permission_label_id'=>explode('|',$id)[0],
                'permission_id'=>explode('|',$id)[1],
            ]);
        }
        return back();
    }



}
