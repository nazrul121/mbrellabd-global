<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    function index(Request $request){
        if($request->draw){
            return datatables()::of(Video::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                return '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">
                    <button type="button" class="btn btn-info btn-sm edit" id="'.$row->id.'"><span class="feather icon-edit"></span></button>
                    <button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>
                </div>';
            })
            ->editColumn('videos', function($row){
                return '<button class="btn btn-outline-secondary btn-sm video" data-id="'.$row->id.'" type="button">SHow video</button>';
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

            ->rawColumns(['country','videos','status','modify'])->make(true);
        }
        return view('common.video.index');
    }

    public function store(Request $request, Video $video){
        // dd($request->all());
        if (strpos($request->video_link, '?v=') !== false) {
            $url = $request->video_link;
            // Parse the URL to get the query part
            $query = parse_url($url, PHP_URL_QUERY);
            // Parse the query string to get the parameters
            parse_str($query, $params);
            $keyword = isset($params['v']) ? $params['v'] : null;

           $newLink = 'https://www.youtube.com/embed/'.$keyword;
        }else $newLink = $request->video_link;


        $validator = $this->fields($video->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = ['title'=>$request->title, 'url'=>$request->page, 'type'=>$request->type,'video_link'=>$newLink, 'status'=>$request->status];
        $video = Video::create($data);
        $video->countries()->attach($request->langs);
        $video->save();
        $this->upload_video($video);
        return response()->json(['success' => 'The video has been saved successfully!']);
    }

    public function show(Video $video){
        // return $video;
        return view('common.video.showVideo',compact('video'));
    }

    public function single_video(Video $video){
        $video['country'] = $video->countries()->select('country_id')->get();
        return $video;
    }


    public function update(Request $request,Video $video){

        
        if (strpos($request->video_link, '?v=') !== false) {
            $url = $request->video_link;
            // Parse the URL to get the query part
            $query = parse_url($url, PHP_URL_QUERY);
            // Parse the query string to get the parameters
            parse_str($query, $params);
            $keyword = isset($params['v']) ? $params['v'] : null;

           $newLink = 'https://www.youtube.com/embed/'.$keyword;
        }else $newLink = $request->video_link;


        $validator = $this->fields($video->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = ['title'=>$request->title,'url'=>$request->page,'type'=>$request->type,'video_link'=>$newLink, 'status'=>$request->status];

        $video->update($data);
        $video->countries()->sync($request->langs);
        $this->upload_video($video,'update');
        return response()->json(['success' => 'Video hasn been updated successfully!']);
    }


    public function destroy(video $video){
        if ($video->type=='video') {
            \File::delete(public_path('storage/'.$video->video_link));
        }
        $video->delete();
        return response()->json(['success' => 'Data hasn been deleted successfully!']);
    }

    function upload_video($video,$type=null){

        if (request()->has('file')) {
            $slug = Str::slug(request()->title, '-');
            $file =  request()->file('file');
            $filename = $file->getClientOriginalName();
            $mime= $file->getClientOriginalExtension();
            $videoName = $slug.'-'.time().".".$mime;

            $path = public_path().'/storage/videos/';
            $file->move($path, $videoName);
            $video->update(['video_link'=>"videos/".$videoName]);

            if ($type=='update') {
                \File::delete(public_path('storage/'.request()->oldVideo));
            }
        }
    }


    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required', 'type'=>'required', 'page'=>'required',
            'video_link'=>'sometimes|nullable','status'=>'required',
            'file'  => 'sometimes|nullable|required|mimes:mp4,mov,ogg,qt'
        ]); return $validator;
    }
}
