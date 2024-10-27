<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Blog_category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Validator; use Session;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    function index(Request $request){
        $blog = New Blog();
        if($request->draw){
            return datatables()::of(Blog::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-blog')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-blog')){
                    $data .= '<a href="'.route('common.blog.edit',$row->id).'" class="btn btn-info btn-sm"><span class="feather icon-edit"></span></a>';
                }
                $data .= '</div>';  return $data;
            })
            ->editColumn('photo', function($row){
                return '<img style="max-width:40px" src="'.url('storage/'.$row->photo).'">';
            })
            ->editColumn('description', function($row){
                return substr(strip_tags($row->description),0,80).' ...';
            })

            ->editColumn('status', function($row){
                if($row->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->editColumn('country', function($cat){
                $country = '';
                foreach($cat->countries()->select('short_name','flag')->get() as $cnt){
                 $country .= '<img src="'.url($cnt->flag).'" title="'.$cnt->short_name.'"> ';
                }
                return $country;
            })

            ->rawColumns(['country','photo','description','status','modify'])->make(true);
        }
        return view('common.blog.index',compact('blog'));
    }

    public function create(Request $request){
        $blog = new Blog();
        $blog_categories = Blog_category::all();
        return view('common.blog.create', ['blog'=>$blog, 'blog_categories'=>$blog_categories]);
    }

    public function store(Request $request){
        Session::flash('message', 'yes'); Session::flash('alert', 'Validation error! Please check the form');
        $data = $this->fields();
		$data['slug']= $this->get_slug_unique(Str::slug($request->title));
        $blog = Blog::create($data);
        $blog->blog_categories()->attach($request->categories);
        $blog->countries()->attach($request->langs);
        $this->storeImage($blog);

        Session::flash('success', 'The psot has been saved successfully');
        return back();
    }

    public function show(Blog $blog){ 
        $blog['country'] = $blog->countries()->select('country_id')->get();
        return $group;
    }


    public function edit(Blog $blog){
        $blog_categories = Blog_category::all();
        return view('common.blog.edit', compact('blog', 'blog_categories'));
    }

    public function update(Request $request,Blog $blog){
        // dd($request->all());
        Session::flash('message', 'yes'); Session::flash('alert', 'Validation error! Please check the form');
        $data = $this->fields();
        $data['slug']= $this->get_slug_unique(Str::slug($request->title));
        $blog->update($data);
        $blog->blog_categories()->sync($request->categories);
        $this->storeImage($blog,'update');
        $blog->countries()->sync($request->langs);
        Session::flash('success', 'The psot has been updated successfully');
        return back();
    }

    private function fields($id=null){
        return request()->validate([
            'title'=>'required',
            'photo'=>'sometimes|nullable|image',
            'description'=>'sometimes|nullable|min:50',
            'status'=>'required',
        ]);
    }


    function storeImage($blog,$type=null){

        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            // product sizing
            $width = Setting::where('type','blog-weight')->pluck('value')->first();
            $height = Setting::where('type','blog-height')->pluck('value')->first();

            $image = Image::make($fieldFile)->resize( $width, $height);
            Storage::disk('public')->put("images/blog/".$imageName, (string) $image->encode());
            $blog->update(['photo'=>"images/blog/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }


    public function destroy(Blog $blog){
        try {
            if(\file_exists(public_path('storage/').$blog->photo) &&  $blog->photo!='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$blog->photo);
            }
            $blog->delete();
            return response()->json(['success' => 'The post hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

    function get_slug_unique($slug){
        $blog = Blog::where('slug',$slug)->first();
        if($blog==null) return $slug = $slug;  else return $slug.'-'.Blog::count();
    }


}
