<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Customer::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($customer) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-customer')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$customer->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-customer')){
                    $data .= ' <button type="button" class="btn btn-info btn-sm edit" id="'.$customer->id.'"><span class="feather icon-edit"></span></button>
                    <button type="button" class="btn btn-primary btn-sm loginInfo" id="'.$customer->id.'"><span class="feather icon-log-in"></span></button>';
                }
                return '</div>'.$data;
            })
  
            ->editColumn('address', function($customer){
                if(strlen($customer->address) > 50){
                    $words = explode(' ', $customer->address);
                    $firstThreeWords = array_slice($words, 0, 5);
                    return implode(' ', $firstThreeWords);
                }
                return $customer->address;
            })
            ->editColumn('orders', function($customer){
                return '<b>'.$customer->orders()->count(). '</b> orders';
            })
            ->editColumn('name', function($customer){
                return '<img style="max-width:40px" src="'.url('storage/'.$customer->photo).'"> '.$customer->first_name.' '.$customer->last_name;
            })
            ->editColumn('area', function($customer){

                if($customer->division_id!=null){ $div =  $customer->division->name.' <i class="feather icon-arrow-right"></i> ';} else $div = '';

                if($customer->district_id!=null){$dis =  $customer->district->name.'<br/>';}else $dis = '';

                if($customer->city_id!=null){ $city = $customer->city->name;} else $city = '';

                return $div.$dis.$city;
            })
            ->editColumn('status', function($customer){
                if($customer->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->rawColumns(['orders','name','area','address','status','modify'])->make(true);
        }
        return view('common.user.customer.index');
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

        $user = User::create(['user_type_id'=>4,'email'=>$request->email, 'phone'=>$request->phone,'password'=>Hash::make($request->password)]);
        $data = [
            'user_id'=>$user->id,
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'phone'=>$user->phone,
            'sex'=>$request->sex,
            'division_id'=>$request->division,
            'district_id'=>$request->district,
            'city_id'=>$request->city,
            'address'=>$request->address
        ];
        $customer = Customer::create($data);
        $customer->save();

        $this->storeImage($customer);
        return response()->json(['success' => 'Customer has been created successfully!']);
    }


    public function show(Customer $customer){ return Customer::find($customer->id);}

    public function login_info(Customer $customer){
        $user = User::where('id',$customer->user_id)->first();
        return view('common.user.customer.loginInfo',compact('user','customer'));
    }


    public function update(Request $request,Customer $customer){
        $validator = $this->fields($customer->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'sex'=>$request->sex,
            'division_id'=>$request->division,
            'district_id'=>$request->district,
            'city_id'=>$request->city,
            'address'=>$request->address,
        ];

        $customer->update($data);
        $this->storeImage($customer,'update');

        return response()->json(['success' => 'The customer hasn been updated successfully!']);
    }



    function customer_order(Request $request){
        if($request->customer){
            $customer = Customer::where('phone',$request->customer)
            ->orWhere('first_name','LIKE', '%'.$request->customer.'%')
            ->orWhere('last_name','LIKE', '%'.$request->customer.'%')->first();
        }else $customer = null;

        return view('common.user.customer.orders', compact('customer'));
    }















    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'first_name'=>'required','last_name'=>'required', 'sex'=>'required',
            'photo'=>'sometimes|nullable|image',
            'division'=>'required','district'=>'required','city'=>'required',
            'address'=>'required'
        ]); return $validator;
    }

    private function userFields($id=null){
        $validator = Validator::make(request()->all(), [
            'email'=>'required|unique:users,email,'.$id,
            'phone'=>'required|unique:users,phone,'.$id,
            'password'=>'required',
        ]); return $validator;
    }

    function storeImage($customer,$type=null){
        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(350, 350);
            Storage::disk('public')->put("images/user/customer/".$imageName, (string) $image->encode());
            $customer->update(['photo'=>"images/user/customer/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/user.jpg') {
                File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }


    public function destroy(Customer $customer){
        try {
            if(\file_exists(public_path('storage/').$customer->photo) && $customer->photo !='images/user.jpg'){
                File::delete(public_path('storage/').$customer->photo);
            }
            $customer->delete();
            User::where('id',$customer->user_id)->delete();
            return response()->json(['success' => 'Customer hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

    public function update_login(Request $request,User $user){
        $data = [
            'phone'=>$request->phone,
            'email'=>$request->email,
            'password'=> Hash::make($request->password),
        ];

        Customer::where('user_id', $user->id)->update(['phone'=>$request->phone]);

        $user->update($data);
        
        return response()->json(['success' => 'The customer password hasn been updated successfully!']);
    }
}
