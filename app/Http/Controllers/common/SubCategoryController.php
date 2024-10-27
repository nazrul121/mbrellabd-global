<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Inner_group;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{

    public function index(Request $request,$sortBy=null){

        if ($request->draw) {
            if($sortBy=='1'){
                if($request->group){
                    $data = Inner_group::orderBy('sort_by')->where(['group_id'=>$request->group]);
                }else $data = Inner_group::orderBy('sort_by')->where('status','1');
            }else {
                if($request->group){
                    $data = Inner_group::where(['group_id'=>$request->group]);
                }else $data = Inner_group::select('*');
            }

            return datatables()::of($data)
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-sub-category')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-sub-category')){
                    $data .= '<button type="button" class="btn btn-info btn-sm edit" id="'.$cat->id.'"><span class="feather icon-edit"></span></button>';
                }
                $data .= '<button type="button" class="meta btn btn-primary" title="Meta info" id="'.$cat->id.'"><span><i class="feather icon-info"></i> </span></button>';
                $data .= '</div>';  return $data;
            })
            ->editColumn('photo', function($cat){
                return '<img style="max-width:40px" src="'.url('storage/'.$cat->photo).'">';
            })

            ->editColumn('group_id', function($cat){
                return  $cat->group->title;
            })
           
            ->editColumn('products', function($cat){
                return $cat->products()->count().' <a href="'.route('common.sub-category-products',$cat->id).'" target="_blank">products</a>';
            })
            ->editColumn('status', function($cat){
                if($cat->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })
            ->editColumn('country', function($cat){
                $country = '';
                foreach($cat->countries()->select('short_name','flag')->get() as $cnt){
                 $country .= '<img src="'.url($cnt->flag).'" title="'.$cnt->short_name.'"> ';
                }
                return $country;
            })

            ->rawColumns(['country','photo','group_id','child_group','products','status','modify'])->make(true);
        }
        $categories = Group::all();
        return view('common.category.sub.index',['categories'=>$categories]);
    }


    public function store(Request $request){
        // dd($request->all());
        $validator = $this->fields($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if (isset($request->is_top)) $is_top = '1'; else $is_top = '0';

        $data = [
            'group_id'=>$request->category, 'title'=>$request->title,'display_name'=>$request->display_name,
            'description'=>$request->description, 'is_top'=>$is_top, 'status'=>$request->status
        ];

        $inner_group = Inner_group::create($data);
        $inner_group->save();
        $inner_group->countries()->attach($request->langs);
        $inner_group->slug = $this->get_slug_unique(Str::slug($request->title));
        $inner_group->save(); //slug stays "my-name"
        $this->storeImage($inner_group);
        return response()->json(['success' => 'Sub-group hasn been created successfully!']);
    }

    public function show(Inner_group $inner_group){ 
        $group = Inner_group::find($inner_group->id);
        $group['country'] = $group->countries()->select('country_id')->get();
        return $group;
    }


    public function update(Request $request,Inner_group $inner_group)
    {
        $validator = $this->fields($request, 'update');
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if (isset($request->is_top)) $is_top = "1"; else $is_top = '0';

        // dd($is_top);

        $data = [
            'group_id'=>$request->category,
            'title'=>$request->title, 'slug'=>$request->slug ,'display_name'=>$request->display_name,
            'description'=>$request->description,
            'is_top'=>$is_top, 'status'=>$request->status
        ];
        $data['slug']= $this->get_slug_unique(Str::slug($request->title));
        // dd($data);
        $inner_group->update($data);
        $inner_group->countries()->sync($request->langs);
        $this->storeImage($inner_group,'update');
        return response()->json(['success' => 'The sub-group hasn been updated successfully!']);
    }

    private function fields($request, $type=null){
        if($type=='update'){
            $validator = Validator::make(request()->all(), [
                'title' => 'required',
                'display_name'=>'required',
                'photo'=>'sometimes|nullable|image',
                'description'=>'sometimes|nullable',
                'status'=>'required',
            ]);
        }else{
            $validator = Validator::make(request()->all(), [
                'title'  => [ 'required',
                    Rule::unique('inner_groups')->where(function ($query) use($request) {
                        return $query->where('title',$request->title)->where('group_id', $request->category);
                    })
                ], 
                'display_name'=>'required',
                'photo'=>'sometimes|nullable|image',
                'description'=>'sometimes|nullable',
                'status'=>'required',
            ]);
        }
        return $validator;
    }

    function storeImage($inner_group,$type=null){
        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(210, 210);
            Storage::disk('public')->put("images/sub-group/".$imageName, (string) $image->encode());
            $inner_group->update(['photo'=>"images/sub-group/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }


    public function destroy(Inner_group $inner_group)
    {
        try {
            if(\file_exists(public_path('storage/').$inner_group->photo) && $inner_group->photo !='images/thumbs_photo.png' ){
                \File::delete(public_path('storage/').$inner_group->photo);
            }
            $inner_group->delete();
            return response()->json(['success' => 'Category hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }


    function get_slug_unique($slug){
        $blog = Inner_group::where('slug',$slug)->first();
        if($blog==null) return $slug = $slug;  else return $slug.'-'.rand(2,3);
    }


}
