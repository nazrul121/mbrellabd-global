<?php

namespace App\Http\Controllers\common;
use App\Http\Controllers\Controller;
use App\Models\Show_room;
use Illuminate\Http\Request;
use Validator;

//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ShowroomController extends Controller{

    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Show_room::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-outlet')){
                    $data .='<button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-outlet')){
                    $data .='<button type="button" class="btn btn-info btn-sm edit" id="'.$cat->id.'"><span class="feather icon-edit"></span></button>';
                }
                return '</div>'.$data;
            })
            ->editColumn('photo', function($cat){
                return '<img style="max-width:40px" src="'.url('storage/'.$cat->photo).'">';
            })
            ->editColumn('location', function($cat){
                if(strlen($cat->location)>40) return substr($cat->location, 0,40).' ...';
                else return $cat->location;
            })
            ->editColumn('description', function($cat){
                if(strlen($cat->description)>30) return substr($cat->description, 0,30).' ...';
                else return $cat->description;
            })

            ->editColumn('status', function($cat){
                if($cat->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->rawColumns(['photo','location','description','status','modify'])->make(true);
        }
        $districts = \App\Models\District::all();
        return view('common.showroom.index', compact('districts'));
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 
            'district_id'=>$request->district,
            'title'=>$request->title,
            'description'=>$request->description,
            'phone'=>$request->phone,
            'location'=>$request->location ,
            'embed_code'=>$request->embed_code,
            'status'=>$request->status
        ];
        $show_room = Show_room::create($data);

        $this->storeImage($show_room);
        return response()->json(['success' => 'Showroom has been created successfully!']);
    }

    public function show(Show_room $show_room){return $show_room;}


    public function update(Request $request,Show_room $show_room){
        $validator = $this->fields($show_room->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 
            'district_id'=>$request->district,
            'title'=>$request->title,
            'description'=>$request->description,
            'phone'=>$request->phone,
            'location'=>$request->location ,
            'embed_code'=>$request->embed_code,
            'status'=>$request->status
        ];

        $show_room->update($data);
        $this->storeImage($show_room,'update');

        return response()->json(['success' => 'The Showroom hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required|unique:show_rooms,title,'.$id,
            'photo'=>'sometimes|nullable|image',
            'location'=>'required',
            'district'=>'required',
            'phone'=>'required|unique:show_rooms,phone,'.$id,
            'description'=>'sometimes|nullable',
            'status'=>'required'
        ]); return $validator;
    }

    function storeImage($show_room,$type=null){

        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(500, 500);
            Storage::disk('public')->put("images/show-room/".$imageName, (string) $image->encode());
            $show_room->update(['photo'=>"images/show-room/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }


    public function destroy(Show_room $show_room){
        try {
            if(\file_exists(public_path('storage/').$show_room->photo) && $show_room->photo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$show_room->photo);
            }
            $show_room->delete();
            return response()->json(['success' => 'Showroom hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
