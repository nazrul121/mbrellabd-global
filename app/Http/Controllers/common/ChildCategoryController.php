<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Child_group;
use App\Models\Group;
use App\Models\Inner_group;
use App\Models\Season;

use Illuminate\Http\Request;
use Validator;

use Illuminate\Support\Str;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ChildCategoryController extends Controller{

    public function index(Request $request){
        
        if($request->draw){
            if($request->innerGroup){
                $data = Child_group::where('inner_group_id',$request->innerGroup);
            }else $data = Child_group::select('*');
    
            return datatables()::of($data)
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-child-category')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-child-category')){
                    $data .= '<button type="button" class="btn btn-info btn-sm edit" id="'.$cat->id.'"><span class="feather icon-edit"></span></button>';
                }
                $data .= '<button type="button" class="meta btn btn-primary" title="Meta info" id="'.$cat->id.'"><span><i class="feather icon-info"></i> </span></button>';
                $data .= '</div>';  return $data;
            })
            ->editColumn('photo', function($cat){
                return '<img style="max-width:40px" src="'.url('storage/'.$cat->photo).'">';
            })

            ->editColumn('sub_category', function($cat){
                return $cat->inner_group->group->title.' <span class="fas fa-long-arrow-right"></span> '.$cat->inner_group->title;
            })
            ->editColumn('products', function($cat){
                return $cat->products()->count().' <a href="'.route('common.child-category-products',$cat->id).'" target="_blank">products</a>';;
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

            ->rawColumns(['country','photo','sub_category','products','status','modify'])->make(true);
        }
        $categories = Group::where('status','1')->get();
        return view('common.category.child.index',['categories'=>$categories]);
    }


    public function main2sub_categories(Season $season, Group $group){
        $seasonCountry = $season->countries()->select('country_id')->distinct()->pluck('country_id')->toArray();
        $innerIds = \DB::table('country_inner_group')->whereIn('country_id',$seasonCountry)->select('inner_group_id')->distinct()->pluck('inner_group_id')->toArray();
        $inner = Inner_group::whereIn('id',$innerIds)->where(['group_id'=>$group->id, 'status'=>'1'])->get();
        return $inner;
    }

    public function sub2child_categories(Season $season, Inner_group $inner_group){
        $seasonCountry = $season->countries()->select('country_id')->distinct()->pluck('country_id')->toArray();
        $childIds = \DB::table('child_group_country')->whereIn('country_id',$seasonCountry)->select('child_group_id')->distinct()->pluck('child_group_id')->toArray();
        $child = Child_group::whereIn('id',$childIds)->where('inner_group_id',$inner_group->id)->get();
        // dd($child, $inner_group);
        return $child;
    }

    public function store(Request $request){
        $validator = $this->fields($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if (isset($request->is_top)) $is_top = '0'; else $is_top = '1';

        $data = [
            'inner_group_id'=>$request->sub_category, 'title'=>$request->title, 'display_name'=>$request->display_name, 'slug'=>$request->title ,
            'description'=>$request->description,  'status'=>$request->status
        ];
        $data['slug'] = $this->get_slug_unique(Str::slug($request->title));
        $child_group = Child_group::create($data);
        $child_group->save();

        $child_group->save(); //slug stays "my-name"
        $child_group->countries()->attach($request->langs);

        $this->storeImage($child_group);
        return response()->json(['success' => 'child category hasn been created successfully!']);
    }

    public function show(Child_group $child_group)
    {
        $data = Child_group::find($child_group->id);
        // return $data;
        $data['inner_group'] = $data->inner_group->title;
        $data['country'] = $child_group->countries()->select('country_id')->get();
        return $data;
    }


    public function update(Request $request,Child_group $child_group)
    {
        $validator = $this->fields($request, 'update');

        if ($validator->fails($request,'update')) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [
            'inner_group_id'=>$request->sub_category, 'title'=>$request->title,'display_name'=>$request->display_name, 'slug'=>$request->title ,
            'description'=>$request->description,  'status'=>$request->status
        ];
        $data['slug']= $this->get_slug_unique(Str::slug($request->title));

        $child_group->update($data);
        $this->storeImage($child_group,'update');
        $child_group->countries()->sync($request->langs);
        return response()->json(['success' => 'The category hasn been updated successfully!']);
    }

    private function fields($request, $type=null){

        if($type=='update'){
            $validator = Validator::make(request()->all(), [
                'title'=>'required',
                'display_name'=>'required',
                'sub_category'=>'required',
                'photo'=>'sometimes|nullable|image',
                'description'=>'sometimes|nullable',
                'status'=>'required',
            ]);
        }else{
            $validator = Validator::make(request()->all(), [
                'title'  => [ 'required',
                    Rule::unique('child_groups')->where(function ($query) use($request) {
                        return $query->where('title',$request->title)->where('inner_group_id', $request->sub_category);
                    })
                ],
                'display_name'=>'required',
                'photo'=>'sometimes|nullable|image',
                'description'=>'sometimes|nullable',
                'sub_category'=>'required',
                'status'=>'required',
            ]);
        }
        return $validator;
        // $validator = Validator::make(request()->all(), [
        //     'title'=>'required|unique:child_groups,title,'.$id,
        //     'sub_category'=>'required',
        //     'photo'=>'sometimes|nullable|image',
        //     'description'=>'sometimes|nullable',
        //     'status'=>'required',
        // ]); return $validator;
    }

    function storeImage($child_group,$type=null){
        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(210, 210);
            Storage::disk('public')->put("images/child-category/".$imageName, (string) $image->encode());
            $child_group->update(['photo'=>"images/child-category/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }


    public function destroy(Child_group $child_group)
    {
        try {
            if(\file_exists(public_path('storage/').$child_group->photo) && $child_group->photo !='images/thumbs_photo.png' ){
                \File::delete(public_path('storage/').$child_group->photo);
            }
            $child_group->delete();
            return response()->json(['success' => 'Category hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

    function get_slug_unique($slug){
        $blog = Child_group::where('slug',$slug)->first();

        if($blog==null) return $slug = $slug;  else return $slug.'-'.rand(9,99);
    }
}

