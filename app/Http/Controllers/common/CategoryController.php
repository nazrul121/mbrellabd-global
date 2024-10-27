<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Inner_group;
use App\Models\Child_group;
use Illuminate\Http\Request;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Group::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-main-category')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-main-category')){
                    $data .= '<button type="button" class="btn btn-info btn-sm edit" id="'.$cat->id.'"><span class="feather icon-edit"></span></button>';
                }
                $data .= '<button type="button" class="meta btn btn-primary" title="Meta info" id="'.$cat->id.'"><span><i class="feather icon-info"></i> </span></button>';

                $data .= '</div>';  return $data;
            })
            ->editColumn('photo', function($cat){
                return '<img style="max-width:40px" src="'.url('storage/'.$cat->photo).'">';
            })

            ->editColumn('sub_category', function($cat){
                return $cat->inner_groups()->count();
            })
            ->editColumn('child_category', function($cat){
                return $cat->get_child_category_number($cat->id);
            })
            ->editColumn('products', function($cat){
                return $cat->products()->count().' <a href="'.route('common.category-products',$cat->id).'" target="_blank">products</a>';
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

            ->rawColumns(['country','photo','sub_category','child_category','products','status','modify'])->make(true);
        }
        $this->set_country_group_all();
        return view('common.category.main.index');
    }

    private function set_country_group_all(){
        foreach (get_currency() as $key => $value) {
            $groups = Group::where('status','!=','delete')->get();
            foreach ($groups as $item) {
                // echo $value->name.' = '.$item->title.'<br/>';
                $checkDB = \DB::table('country_group')->where(['country_id'=>$value->id,'group_id'=>$item->id]);
                if($checkDB->count() <1){
                    \DB::table('country_group')->insert([
                        'country_id'=>$value->id,'group_id'=>$item->id
                    ]);
                }
            }
            $inner_groups = Inner_group::where('status','!=','delete')->get();
            foreach ($inner_groups as $item) {
                // echo $value->name.' = '.$item->title.'<br/>';
                $checkDB = \DB::table('country_inner_group')->where(['country_id'=>$value->id,'inner_group_id'=>$item->id]);
                if($checkDB->count() <1){
                    \DB::table('country_inner_group')->insert([
                        'country_id'=>$value->id,'inner_group_id'=>$item->id
                    ]);
                }
            }

            $child_groups = Child_group::where('status','!=','delete')->get();
            foreach ($child_groups as $item) {
                // echo $value->name.' = '.$item->title.'<br/>';
                $checkDB = \DB::table('child_group_country')->where(['country_id'=>$value->id,'child_group_id'=>$item->id]);
                if($checkDB->count() <1){
                    \DB::table('child_group_country')->insert([
                        'country_id'=>$value->id,'child_group_id'=>$item->id
                    ]);
                }
            }
            //echo $value->name.'<br/>';
        }
        // exit;
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [
            'title'=>$request->title, 'display_name'=>$request->display_name, 'slug'=>$request->title ,
            'description'=>$request->description,  'status'=>$request->status
        ];

        $group = Group::create($data);
        $group->countries()->attach($request->langs);
   
        $group->save();
        $group->slug = $this->get_slug_unique(Str::slug($request->title));
        $group->save(); //slug stays "my-name"

        $this->storeImage($group);
        return response()->json(['success' => 'Group hasn been created successfully!']);
    }

    public function show(Group $group){
        $group = Group::find($group->id);
        $group['country'] = $group->countries()->select('country_id')->get();
        return $group;
    }


    public function update(Request $request,Group $group)
    {
        $validator = $this->fields($group->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [
            'title'=>$request->title, 'display_name'=>$request->display_name, 'slug'=>$request->slug ,
            'description'=>$request->description, 'status'=>$request->status
        ];
        $data['slug']= $this->get_slug_unique(Str::slug($request->title));

        $group->countries()->sync($request->langs);

        $group->update($data);
        $this->storeImage($group,'update');

        return response()->json(['success' => 'The category hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required|unique:groups,title,'.$id,
            'display_name'=>'required',
            'photo'=>'sometimes|nullable|image',
            'description'=>'sometimes|nullable',
            'status'=>'required',
        ]); return $validator;
    }

    function storeImage($group,$type=null){
        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(600, 600);
            Storage::disk('public')->put("images/category/".$imageName, (string) $image->encode());
            $group->update(['photo'=>"images/category/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }

    public function destroy(Group $group)
    {
        try {
            if(\file_exists(public_path('storage/').$group->photo) && $group->photo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$group->photo);
            }
            $group->delete();
            return response()->json(['success' => 'Group hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
    function get_slug_unique($slug){
        $blog = Group::where('slug',$slug)->first();
        if($blog==null) return $slug = $slug;  else return $slug.'-'.Group::count();
    }




    function sub_categories(Group $group){
        return \DB::table('inner_groups')->select('id','title')->where(['group_id'=>$group->id])->get();
    }

    function child_categories(Inner_group $inner_group){
        return \DB::table('child_groups')->select('id','title')->where(['inner_group_id'=>$inner_group->id])->get();
    }


}
