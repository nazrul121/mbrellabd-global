<?php

namespace App\Http\Controllers\common;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller{

    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Campaign::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-campaign')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-campaign')){
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
        return view('common.ad.campaign.index');
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'title'=>$request->title,'description'=>$request->description,
        'start_date'=>date('Y-m-d',strtotime($request->start_date)),'end_date'=>date('Y-m-d',strtotime($request->end_date)), 'status'=>$request->status];
        $campaign = Campaign::create($data);
        $campaign->save();

        $this->storeImage($campaign);
        return response()->json(['success' => 'campaign has been created successfully!']);
    }

    public function show(Campaign $campaign){ return Campaign::find($campaign->id);}


    public function update(Request $request,Campaign $campaign){
        $validator = $this->fields($campaign->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'title'=>$request->title,'description'=>$request->description,
        'start_date'=>date('Y-m-d',strtotime($request->start_date)),'end_date'=>date('Y-m-d',strtotime($request->end_date)), 'status'=>$request->status];

        $campaign->update($data);
        $this->storeImage($campaign);


        return response()->json(['success' => 'The campaign hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required|unique:campaigns,title,'.$id,
            'photo'=>'sometimes|nullable|image', 'video'=>'sometimes|nullable|mimes:mp4,ogx,oga,ogv,ogg,webm',
            'description'=>'sometimes|nullable',
            'start_date'=>'required','end_date'=>'required','status'=>'required',
        ]); return $validator;
    }

    function storeImage($campaign,$type=null){
        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(1800, 800);
            Storage::disk('public')->put("images/campaign/".$imageName, (string) $image->encode());
            $campaign->update(['photo'=>"images/campaign/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }

    public function destroy(campaign $campaign){
        try {
            if(\file_exists(public_path('storage/').$campaign->photo) && $campaign->photo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$campaign->photo);
            }
            if(\file_exists(public_path('storage/').$campaign->video)){
                \File::delete(public_path('storage/').$campaign->video);
            }
            $campaign->delete();
            return response()->json(['success' => 'Category hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
