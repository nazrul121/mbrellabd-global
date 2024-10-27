<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Session;

use Illuminate\Support\Str;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
class BrandController extends Controller
{
    function index(){
        $brand = Brand::find(1);
        return view('common.brand.index',compact('brand'));
    }

    public function update(Request $request,Brand $brand){
        Session::flash('message', 'yes'); Session::flash('alert', 'Validation error! Please check the form');
        $data = $this->fields();
        $data['slug']= Str::slug($request->title);
        $brand->update($data);

        $this->storeImage($brand,'update');

        Session::flash('success', 'Brand information has been updated successfully');
        return back();
    }

    private function fields($id=null){
        return request()->validate([
            'title'=>'required',
            'photo'=>'sometimes|nullable|image',
            'location'=>'sometimes|nullable',
            'status'=>'required',
        ]);
    }


    function storeImage($brand,$type=null){

        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(300, 300);
            Storage::disk('public')->put("images/brand/".$imageName, (string) $image->encode());
            $brand->update(['photo'=>"images/brand/".$imageName]);
            if ($type=='update' && $brand->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }

}
