<?php

namespace App\Http\Controllers\common;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Validator;

//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller{

    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Banner::orderBy('id','DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-banner')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-banner')){
                    $data .= '<button type="button" class="btn btn-info btn-sm edit" id="'.$cat->id.'"><span class="feather icon-edit"></span></button>';
                }
                $data .= '</div>';  return $data;
            })
            ->editColumn('photo', function($cat){
                return '<img style="max-width:40px" src="'.url('storage/'.$cat->photo).'">';
            })

            ->editColumn('status', function($cat){
                if($cat->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })
            ->rawColumns(['photo','status','modify'])->make(true);
        }
        return view('common.ad.banner.index');
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'title'=>$request->title,'position'=>$request->position,'link'=>$request->link, 'status'=>$request->status];
        $slider = Banner::create($data);
        $slider->save();

        $this->storeImage($slider);
        return response()->json(['success' => 'Banner has been created successfully!']);
    }

    public function show(Banner $banner){ return Banner::find($banner->id);}


    public function update(Request $request,Banner $banner){
        $validator = $this->fields($banner->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'title'=>$request->title,'position'=>$request->position,'link'=>$request->link, 'status'=>$request->status];

        $banner->update($data);
        $this->storeImage($banner,'update');

        return response()->json(['success' => 'The banner hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required|unique:banners,title,'.$id,
            'photo'=>'sometimes|nullable|image',
            'position'=>'required',
            'status'=>'required', 'link'=>'sometimes|nullable',
        ]); return $validator;
    }

    function storeImage($banner,$type=null){

        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(1800, 300);
            Storage::disk('public')->put("images/banner/".$imageName, (string) $image->encode());
            $banner->update(['photo'=>"images/banner/".$imageName]);
            if ($type=='update' && $banner->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }

    public function destroy(banner $banner){
        try {
            if(\file_exists(public_path('storage/').$banner->photo) && $banner->photo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$banner->photo);
            }
            $banner->delete();
            return response()->json(['success' => 'Category hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
