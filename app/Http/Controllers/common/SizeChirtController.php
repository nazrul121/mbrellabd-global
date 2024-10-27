<?php

namespace App\Http\Controllers\common;
use App\Http\Controllers\Controller;
use App\Models\General_info;
use App\Models\Size;
use App\Models\Size_chirt;
use App\Models\Slider;
use Illuminate\Http\Request;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class SizeChirtController extends Controller{

    public function index(Request $request){
        $photos = Size_chirt::orderBy('id','DESC')->paginate(24);
        return view('common.size-chirt.index',compact('photos'));
    }


    public function store(Request $request){
        // dd($request->all());

        if($request->hasfile('images'))
        {
            foreach($request->images as $key=>$fieldFile){
                $mime= $fieldFile->getClientOriginalExtension();
                $imageName = rand().".".$mime;
                $image = Image::make($fieldFile);
                Storage::disk('public')->put("images/size-chirt/".$imageName, (string) $image->encode());
                Size_chirt::create(['title'=>date('y~m~d . h:i').'-'.$key, 'photo'=>"images/size-chirt/".$imageName]);
            }
            echo '<script>alert("Size chirt has been uploaded successfully")</script>';
        }else{
            echo '<script>alert("Something error occured. Please try again!!")</script>';
        }
        return back();
    }

    public function show(Slider $slider){ return Slider::find($slider->id);}


    public function update(Request $request,Slider $slider){
        $validator = $this->fields($slider->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = ['title'=>$request->title,'description'=>$request->description,'link'=>$request->link,'status'=>$request->status];

        $slider->update($data);
        $this->storeImage($slider,'update');

        return response()->json(['success' => 'The slider hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required|unique:sliders,title,'.$id,
            'photo'=>'sometimes|nullable|image',
            'description'=>'sometimes|nullable',
            'status'=>'required', 'link'=>'required',
        ]); return $validator;
    }


    function save_common_size_chirt(Request $request){
        if($request->pdf_file){
            $old_chirt = General_info::where('field','size-chirt')->pluck('value')->first();
            \File::delete(public_path($old_chirt));

            $filename = 'sizeGuird-'.time() . '.' . $request->pdf_file->extension();
            $request->file('pdf_file')->move('storage/',$filename);

            if($old_chirt){
                General_info::where('field','size-chirt')->update(['value'=>'storage/'.$filename]);
            }else {
                General_info::where('field','size-chirt')->create(['field'=>'size-chirt', 'value'=>'storage/'.$filename]);
            }

            return back()->with('success','The size chirt have been updated successfully!!');
        }

    }

    function savePDF($request){

        if (request()->has('pdf_file')) {
            // dd($request->pdf_file);
            //dd($request->pdf_file->extension());
            $file = $request->pdf_file;
            $filename = time() . '.' . $request->pdf_file->extension();
            $filePath = public_path() . '/';
            $file->move($filePath, $filename);


            // \File::delete(public_path('storage/'.request()->oldPhoto));

        }
    }


    public function destroy(Size_chirt $size_chirt){
        try {
            if(\file_exists(public_path('storage/').$size_chirt->photo) && $size_chirt->photo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$size_chirt->photo);
            }
            $size_chirt->delete();
            return response()->json(['success' => 'Chirt hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
