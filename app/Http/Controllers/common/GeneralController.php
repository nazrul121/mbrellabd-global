<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\General_info;

use Illuminate\Http\Request;

//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use DB; use Session;

class GeneralController extends Controller
{
    public function index(){
        $infos = General_info::orderBy('id','DESC')->get();
        return view('common.setting.index',['infos'=>$infos]);
    }

    public function update(Request $request)
    {
        Session::flash('message', 'yes'); Session::flash('alert', 'Validation error! Please check the form');
        $data = $this->validationFields();
        // dd($data);
        foreach ($data as $key => $value) {
            General_info::where('field',$key) ->update([
                'value'=> $value, 'updated_at'=>date('Y-m-d h:i:s')
            ]);
        }


        $this->storeLogo(); $this->storeFavicon(); $this->storeWatermarkLogo();
        Session::flash('success', 'Data has been updated successfully');
        return back();
    }

    private function validationFields(){
        return request()->validate([
            'system_title'=>'required|min:3',
            'system_slogan'=>'required|min:10',
            'system_email'=>'required|email',
            'system_phone'=>'required',
            'system_domain'=>'required',
            'system_helpline'=>'required',
            'system_fax'=>'required',
            'office_address'=>'required',
            'header_logo'=>'sometimes|nullable|image|max:1000',
            'footer_logo'=>'sometimes|nullable|image|max:1000',
            'favicon'=>'sometimes|nullable|image|max:1000',
            'watermark_logo'=>'sometimes|nullable|image',
            'invoice_logo'=>'sometimes|nullable|image',
			'system_description'=>'sometimes|nullable',
			'bin'=>'sometimes|nullable',
			'mushak'=>'sometimes|nullable',
        ]);
    }

    private function storeLogo(){
        if (request()->has('header_logo')) {
            $g = DB::table('general_infos')->where('field', 'header_logo');

            $fieldFile = request()->header_logo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = 'header-logo-.'.$mime;
            $image = Image::make($fieldFile)->resize(210, 66);
            Storage::disk('public')->put("images/".$imageName, (string) $image->encode());

            $g->update(['value'=>'images/'.$imageName, 'updated_at'=>date('Y-m-d h:i:s')]);
        }

        if (request()->has('footer_logo')) {
            $g = DB::table('general_infos')->where('field', 'footer_logo');

            $fieldFile = request()->footer_logo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = 'footer-logo.'.$mime;
            $image = Image::make($fieldFile)->resize(210, 66);
            Storage::disk('public')->put("images/".$imageName, (string) $image->encode());

            $g->update(['value'=>'images/'.$imageName, 'updated_at'=>date('Y-m-d h:i:s')]);
        }


        if (request()->has('invoice_logo')) {
            $g = DB::table('general_infos')->where('field', 'invoice_logo');

            $fieldFile = request()->invoice_logo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = 'invoice-logo.'.$mime;
            $image = Image::make($fieldFile)->resize(210, 66);
            Storage::disk('public')->put("images/".$imageName, (string) $image->encode());

            $g->update(['value'=>'images/'.$imageName, 'updated_at'=>date('Y-m-d h:i:s')]);
        }
    }

    private function storeFavicon(){
        if (request()->has('favicon')) {
            $g = DB::table('general_infos')->where('field', 'favicon');

            $fieldFile = request()->favicon;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = 'favicon.'.$mime;
            $image = Image::make($fieldFile)->resize(25, 25);
            Storage::disk('public')->put("images/".$imageName, (string) $image->encode());

           $g->update(['value'=>'images/'.$imageName, 'updated_at'=>date('Y-m-d h:i:s')]);
        }
    }

    private function storeWatermarkLogo(){
        if (request()->has('watermark_logo')) {
            $fieldFile = request()->watermark_logo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = 'watermark-logo.'.$mime;
            $image = Image::make($fieldFile)->resize(524, 216);
            Storage::disk('public')->put("images/".$imageName, (string) $image->encode());

           DB::table('general_infos')
            ->where('field', 'watermark_logo')
            ->update(['value'=>'images/'.$imageName, 'updated_at'=>date('Y-m-d h:i:s')]);
        }
    }


}
