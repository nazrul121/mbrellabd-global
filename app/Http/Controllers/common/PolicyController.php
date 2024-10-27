<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Models\Policy_type;
use Illuminate\Http\Request;
use Validator;use Session;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PolicyController extends Controller
{
    function index(Request $request, $slug=null){
        $policy_type = Policy_type::where('slug',$slug)->first();
        // dd($policy_type);
        if($request->draw){
            return datatables()::of(Policy::where('policy_type_id',$policy_type->id)->orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-policy')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-policy')){
                    $data .= '<a href="'.route('common.policy.edit',$row->id).'" class="btn btn-info btn-sm"><span class="feather icon-edit"></span></a>';
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
        return view('common.policy.index', compact('policy_type'));
    }

    public function create(Request $request, $policy_type=null){
        $policy_type = Policy_type::where('slug',$policy_type)->first();
        $policy = new Policy();
        return view('common.policy.create', ['policy_type'=>$policy_type,'policy'=>$policy]);
    }

    public function store(Request $request){
        Session::flash('message', 'yes'); Session::flash('alert', 'Validation error! Please check the form');
        $data = $this->fields();
        $policy = Policy::create($data);
        $policy->save();
        $this->storeImage($policy);
        Session::flash('success', 'The policy has been saved successfully');
        $policy_type = Policy_type::where('id',$request->policy_type_id)->first();
        return view('common.policy.index', compact('policy_type'));
    }

    public function show(Policy $policy){ return Policy::find($policy->id);}


    public function edit(Policy $policy){
        return view('common.policy.edit',  ['policy_type'=>$policy->policy_type,'policy'=>$policy]);
    }
    public function update(Request $request,Policy $policy){
        // dd($request->all());
        Session::flash('message', 'yes'); Session::flash('alert', 'Validation error! Please check the form');
        $data = $this->fields();
        $policy->update($data);
        $this->storeImage($policy,'update');
        Session::flash('success', 'The policy has been updated successfully');
        return back();
    }

    private function fields($id=null){
        return request()->validate([
            'policy_type_id'=>'required',
            'title'=>'required',
            'photo'=>'sometimes|nullable|image',
            'description'=>'sometimes|nullable',
            'meta_title'=>'sometimes|nullable',
            'meta_description'=>'sometimes|nullable',
            'status'=>'required',
        ]);
    }


    function storeImage($policy,$type=null){

        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(1800, 800);
            Storage::disk('public')->put("images/policy/".$imageName, (string) $image->encode());
            $policy->update(['photo'=>"images/policy/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }


    public function destroy(Policy $policy){
        try {
            if(\file_exists(public_path('storage/').$policy->photo) && $policy->photo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$policy->photo);
            }
            $policy->delete();
            return response()->json(['success' => 'The post hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

    function coverPhoto(Policy_type $policy_type, Request $request){
        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = 'policy_'.time().".".$mime;
            $image = Image::make($fieldFile)->resize(1900, 400);
            Storage::disk('public')->put("images/cover/".$imageName, (string) $image->encode());
            $policy_type->update(['photo'=>"images/cover/".$imageName]);
            if ($request->oldPhoto !='') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
        return back();
    }

    function get_slug_unique($slug){
        $blog = Policy::where('slug',$slug)->first();
        if($blog==null) return $slug = $slug;  else return $slug.'-'.Policy::count();
    }

}
