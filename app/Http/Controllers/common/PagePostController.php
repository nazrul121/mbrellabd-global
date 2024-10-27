<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Page_post;
use App\Models\Page_post_type;
use Illuminate\Http\Request;
use Validator; use Session;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PagePostController extends Controller
{
    function index(Request $request, Page_post_type $page_post_type){
        // dd($page_post_type);
        $page_post = New Page_post();
        if($request->draw){
            return datatables()::of(Page_post::where('page_post_type_id',$page_post_type->id)->orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-page-post')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-page-post')){
                    $data .= ' <a href="'.route('common.page-post.edit',$row->id).'" class="btn btn-info btn-sm"><span class="feather icon-edit"></span></a>';
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

            ->rawColumns(['photo','description','status','modify'])->make(true);
        }
        return view('common.page.index', ['page_post'=>$page_post, 'page_post_type'=>$page_post_type]);
    }

    public function create(Request $request, Page_post_type $page_post_type){

        $page_post_type = Page_post_type::where('id',$page_post_type->id)->first();
        $page_post = new Page_post();
        return view('common.page.create', ['page_post_type'=>$page_post_type,'page_post'=>$page_post]);
    }

    public function store(Request $request){
        Session::flash('message', 'yes'); Session::flash('alert', 'Validation error! Please check the form');
        $data = $this->fields();
		$data['slug']= $this->get_slug_unique(Str::slug($request->title));

        $page_post = Page_post::create($data);
        $page_post->save();

        $this->storeImage($page_post);
        Session::flash('success', 'The psot has been saved successfully');
        return back();
    }

    public function show(Page_post $page_post){ return Page_post::find($page_post->id);}


    public function edit(Page_post $page_post){
        return view('common.page.edit',  ['page_post_type'=>$page_post->page_post_type,'page_post'=>$page_post]);
    }
    public function update(Request $request,Page_post $page_post){
        // dd($request->all());
        Session::flash('message', 'yes'); Session::flash('alert', 'Validation error! Please check the form');
        $data = $this->fields();
        $data['slug']= $this->get_slug_unique(Str::slug($request->title));
        $page_post->update($data);

        $this->storeImage($page_post,'update');

        Session::flash('success', 'The psot has been updated successfully');
        return back();
    }

    private function fields($id=null){
        return request()->validate([
            'page_post_type_id'=>'required',
            'title'=>'required',
            'photo'=>'sometimes|nullable|image',
            'description'=>'sometimes|nullable',
            'meta_title'=>'sometimes|nullable',
            'meta_description'=>'sometimes|nullable',
            'status'=>'required',
        ]);
    }

    function storeImage($page_post,$type=null){
        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(1800, 800);
            Storage::disk('public')->put("images/page-post/".$imageName, (string) $image->encode());
            $page_post->update(['photo'=>"images/page-post/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }

    public function destroy(Page_post $page_post){
        try {
            if(\file_exists(public_path('storage/').$page_post->photo && $page_post->photo !='images/thumbs_photo.png')){
                \File::delete(public_path('storage/').$page_post->photo);
            }
            $page_post->delete();
            return response()->json(['success' => 'The post hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

    function coverPhoto(Page_post_type $page_post_type, Request $request){
        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = 'page_'.time().".".$mime;
            $image = Image::make($fieldFile)->resize(1900, 400);
            Storage::disk('public')->put("images/cover/".$imageName, (string) $image->encode());
            $page_post_type->update(['photo'=>"images/cover/".$imageName]);
            if ($request->oldPhoto !='') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
        return back();
    }

    function get_slug_unique($slug){
        $blog = Page_post::where('slug',$slug)->first();
        if($blog==null) return $slug = $slug;  else return $slug.'-'.Page_post::count();
    }


}
