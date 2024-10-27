<?php

namespace App\Http\Controllers\common;
use App\Http\Controllers\Controller;
use App\Models\Quick_service;
use Illuminate\Http\Request;
use Validator;

//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class Q_serviceController extends Controller{

    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Quick_service::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-quick-service')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-quick-service')){
                    $data .= ' <button type="button" class="btn btn-info btn-sm edit" id="'.$cat->id.'"><span class="feather icon-edit"></span></button>';
                }
                return '</div>'.$data;
            })
            ->editColumn('photo', function($cat){
                return '<img style="max-width:40px" src="'.url('storage/'.$cat->photo).'">';
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

            ->rawColumns(['country','photo','status','modify'])->make(true);
        }
        return view('common.quick-service.index');
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'title'=>$request->title,'description'=>$request->description,'type'=>$request->type, 'type_info'=>$request->type_info, 'status'=>$request->status];
        $Quick_service = Quick_service::create($data);
        $Quick_service->countries()->attach($request->langs);
        $Quick_service->save();

        $this->storeImage($Quick_service);
        return response()->json(['success' => 'Quick_service has been created successfully!']);
    }

    public function show(Quick_service $quick_service){ 
        $quick_service['country'] = $quick_service->countries()->select('country_id')->get();
        return $quick_service;
    }


    public function update(Request $request,Quick_service $Quick_service){
        $validator = $this->fields($Quick_service->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'title'=>$request->title,'description'=>$request->description,'type'=>$request->type, 'type_info'=>$request->type_info, 'status'=>$request->status];

        $Quick_service->update($data);
        $this->storeImage($Quick_service,'update');
        $Quick_service->countries()->sync($request->langs);
        return response()->json(['success' => 'The Quick_service hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required|unique:quick_services,title,'.$id,
            'photo'=>'sometimes|nullable|image',
            'type'=>'required','type_info'=>'required',
            'description'=>'required',
            'status'=>'required',
        ]); return $validator;
    }

    function storeImage($Quick_service,$type=null){

        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(77, 77);
            Storage::disk('public')->put("images/Quick_service/".$imageName, (string) $image->encode());
            $Quick_service->update(['photo'=>"images/Quick_service/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }


    public function destroy(Quick_service $Quick_service){
        try {
            if(\file_exists(public_path('storage/').$Quick_service->photo) && $Quick_service->photo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$Quick_service->photo);
            }
            $Quick_service->delete();
            return response()->json(['success' => 'Category hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
