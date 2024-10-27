<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{

    public function index(){
        return view('common.includes.dashboard');
        // return view('staff.includes.dashboard');
    }

    public function profile(){
        return view('staff.profile.index');
    }

    public function update(Request $request){
        Session::flash('message', 'yes'); Session::flash('alert', 'Validation error! Please check the form');
        $data = $this->fields();
        $staff = Staff::where('id',Auth::user()->staff->id)->update($data);

        $this->storeImage($staff,'update');

        Session::flash('success', 'Profile information has been updated successfully');
        return back();
    }

    private function fields($id=null){
        return request()->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'sex'=>'required',
            'photo'=>'sometimes|nullable|image',
            'position'=>'required',
            'address'=>'sometimes|nullable',
        ]);
    }

    private function storeImage($staff){
        if (request()->has('photo')) {

            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = request()->first_name.'-'.Auth::user()->admin->id.'.'.$mime;
            $image = Image::make($fieldFile)->fit(300, 300);
            Storage::disk('public')->put("images/user/staff/".$imageName, (string) $image->encode());

           $staff->update(['photo'=>'images/'.$imageName]);
        }
    }
}
