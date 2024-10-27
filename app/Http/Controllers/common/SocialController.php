<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Social_media;
use Illuminate\Http\Request;
use Validator;

class SocialController extends Controller
{

    function index(Request $request){
        if($request->draw){
            return datatables()::of(Social_media::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($media) {
                return '
                <div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">
                    <button type="button" class="btn btn-danger btn-sm delete" id="'.$media->id.'"><span class="feather icon-trash"></span></button>
                    <button type="button" class="btn btn-info btn-sm edit" id="'.$media->id.'"><span class="feather icon-edit"></span></button>
                </div>
                ';
            })
            ->editColumn('icon', function($media){
                return '<span class="'.$media->media_icon.'"> </span>';
            })


            ->editColumn('status', function($media){
                if($media->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->rawColumns(['icon','status','modify'])->make(true);
        }
        return view('common.setting.social.index');
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'media_name'=>$request->media_name,'media_icon'=>$request->media_icon,'media_link'=>$request->media_link, 'status'=>$request->status];
        $social_media = Social_media::create($data);
        $social_media->save();
        return response()->json(['success' => 'Social media information has been created successfully!']);
    }

    public function show(Social_media $social_media){ return Social_media::find($social_media->id);}


    public function update(Request $request,Social_media $social_media){
        $validator = $this->fields($social_media->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'media_name'=>$request->media_name,'media_icon'=>$request->media_icon,'media_link'=>$request->media_link, 'status'=>$request->status];

        $social_media->update($data);

        return response()->json(['success' => 'Social media information hasn been updated successfully!']);
    }


    public function destroy(Social_media $social_media)
    {
        $social_media->delete();
        return response()->json(['success' => 'Data hasn been deleted successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'media_name'=>'required|unique:social_media,media_name,'.$id,
            'media_link'=>'required','status'=>'required'
        ]); return $validator;
    }


}
