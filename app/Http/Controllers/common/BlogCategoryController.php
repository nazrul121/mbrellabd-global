<?php

namespace App\Http\Controllers\common;
use App\Http\Controllers\Controller;
use App\Models\Blog_category;
use Illuminate\Http\Request;
use Validator;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class BlogCategoryController extends Controller{

    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Blog_category::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-blog')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-blog')){
                    $data .= '<button type="button" class="btn btn-info btn-sm edit" id="'.$cat->id.'"><span class="feather icon-edit"></span></button>';
                }
                $data .= '</div>';  return $data;
            })
            ->editColumn('photo', function($cat){
                return '<img style="max-width:40px" src="'.url('storage/'.$cat->photo).'">';
            })
            ->editColumn('posts', function($cat){
                return $cat->blogs()->count();
            })
            ->editColumn('status', function($cat){
                if($cat->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->rawColumns(['photo','posts','status','modify'])->make(true);
        }
        return view('common.blog.category.index');
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'title'=>$request->title,'description'=>$request->description,'status'=>$request->status];
        $blog_category = Blog_category::create($data);
        $blog_category->save();

        $this->storeImage($blog_category);
        return response()->json(['success' => 'Blog_category has been created successfully!']);
    }

    public function show(Blog_category $blog_category){ return Blog_category::find($blog_category->id);}


    public function update(Request $request,Blog_category $blog_category){
        $validator = $this->fields($blog_category->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = ['title'=>$request->title,'description'=>$request->description,'status'=>$request->status];

        $blog_category->update($data);
        $this->storeImage($blog_category,'update');

        return response()->json(['success' => 'The blog_category hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required|unique:blog_categories,title,'.$id,
            'photo'=>'sometimes|nullable|image',
            'description'=>'sometimes|nullable',
            'status'=>'required',
        ]); return $validator;
    }

    function storeImage($blog_category,$type=null){

        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(1800, 800);
            Storage::disk('public')->put("images/blog/category/".$imageName, (string) $image->encode());
            $blog_category->update(['photo'=>"images/blog/category/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }


    public function destroy(Blog_category $blog_category){
        try {
            if(\file_exists(public_path('storage/').$blog_category->photo) && $blog_category->photo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$blog_category->photo);
            }
            $blog_category->delete();
            return response()->json(['success' => 'Category hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

}
