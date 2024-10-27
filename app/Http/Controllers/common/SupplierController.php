<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Supplier::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($supplier) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-supplier')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$supplier->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-supplier')){
                    $data .= ' <button type="button" class="btn btn-info btn-sm edit" id="'.$supplier->id.'"><span class="feather icon-edit"></span></button>
                    <button type="button" class="btn btn-primary btn-sm representative" id="'.$supplier->id.'"><span class="feather icon-user"></span></button>';
                }
                return '</div>'.$data;
            })

            ->editColumn('logo', function($supplier){
                return '<img style="max-width:40px" src="'.url('storage/'.$supplier->logo).'">';
            })
            ->editColumn('products', function($supplier){
                return '<b>'.$supplier->products()->count(). '</b> products';
            })
            ->editColumn('area', function($supplier){
                return $supplier->division->name.' <i class="feather icon-arrow-right"></i> '.$supplier->district->name.'<br/>'.$supplier->city->name;
            })
            ->editColumn('status', function($supplier){
                if($supplier->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->rawColumns(['logo','products','area','status','modify'])->make(true);
        }
        return view('common.user.supplier.index');
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        // $validator=  $this->userFields();
        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()->all()]);
        // }
        // $user = User::create(['user_type_id'=>4,'email'=>$request->email, 'phone'=>$request->phone,'password'=>Hash::make($request->password)]);

        $data = [
            'user_id'=>null, 'company_name'=>$request->company_name,
            'division_id'=>$request->division, 'district_id'=>$request->district,
            'city_id'=>$request->city, 'address'=>$request->address,
        ];
        $supplier = Supplier::create($data);
        $supplier->save();

        $this->storeImage($supplier);
        return response()->json(['success' => 'Supplier has been created successfully!']);
    }

    public function show(Supplier $supplier){ return Supplier::find($supplier->id);}

    public function login_info(Supplier $supplier){
        $user = User::where('id',$supplier->user_id)->first();
        return view('common.user.supplier.loginInfo',compact('user','supplier'));
    }


    public function update(Request $request,Supplier $supplier){
        $validator = $this->fields($supplier->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [
            'company_name'=>$request->company_name,
            'division_id'=>$request->division, 'district_id'=>$request->district,
            'city_id'=>$request->city, 'address'=>$request->address,
        ];

        $supplier->update($data);
        $this->storeImage($supplier,'update');

        return response()->json(['success' => 'The supplier hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'company_name'=>'required|unique:suppliers,company_name,'.$id,
            'logo'=>'sometimes|nullable|image',
            'division'=>'required','district'=>'required','city'=>'required',
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

    function storeImage($supplier,$type=null){

        if (request()->has('logo')) {
            $fieldFile = request()->logo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(350, 350);
            Storage::disk('public')->put("images/user/supplier/".$imageName, (string) $image->encode());
            $supplier->update(['logo'=>"images/user/supplier/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/user.jpg') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }


    public function destroy(Supplier $supplier){
        try {
            if(\file_exists(public_path('storage/').$supplier->photo) && $supplier->photo !='images/user.jpg'){
                \File::delete(public_path('storage/').$supplier->photo);
            }
            $supplier->delete();
            User::where('id',$supplier->user_id)->delete();
            return response()->json(['success' => 'Supplier hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
