<?php

namespace App\Http\Controllers\common;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Validator;

//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller{

    public function index(Request $request){

        if($request->draw){
            return datatables()::of(Testimonial::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-testimonial')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-testimonial')){
                    $data .= ' <button type="button" class="btn btn-info btn-sm edit" id="'.$cat->id.'"><span class="feather icon-edit"></span></button>';
                }
                return '</div>'.$data;
            })
            ->editColumn('photo', function($cat){
                return '<img style="max-width:40px" src="'.url('storage/'.$cat->photo).'">';
            })
            ->editColumn('testimonial', function($cat){
                if(strlen($cat->testimonial) >80){
                    $comment = substr($cat->testimonial,0,80).' ...';
                }else $comment = $cat->testimonial;

                return $comment.' <br/> &nbsp; ---'.$cat->name;
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

            ->rawColumns(['country','photo','testimonial','status','modify'])->make(true);
        }
        return view('common.testimonial.index');
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'name'=>$request->name, 'title'=>$request->title, 'testimonial'=>$request->testimonial,'status'=>$request->status];
        $Testimonial = Testimonial::create($data);
        $Testimonial->save();
        $Testimonial->countries()->attach($request->langs);

        $this->storeImage($Testimonial);
        return response()->json(['success' => 'Testimonial has been created successfully!']);
    }

    public function show(Testimonial $testimonial){ 
        $testimonial['country'] = $testimonial->countries()->select('country_id')->get();
        return $testimonial;
    }


    public function update(Request $request,Testimonial $testimonial){
        $validator = $this->fields($testimonial->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'name'=>$request->name, 'title'=>$request->title, 'testimonial'=>$request->testimonial,'status'=>$request->status];

        $testimonial->update($data);
        $testimonial->countries()->sync($request->langs);
        $this->storeImage($testimonial,'update');

        return response()->json(['success' => 'The Testimonial hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'name'=>'required',
            'photo'=>'sometimes|nullable|image',
            'title'=>'required',
            'testimonial'=>'required|min:25',
            'status'=>'required'
        ]); return $validator;
    }

    function storeImage($testimonial,$type=null){

        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(200, 200);
            Storage::disk('public')->put("images/testimonial/".$imageName, (string) $image->encode());
            $testimonial->update(['photo'=>"images/testimonial/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }


    public function destroy(Testimonial $testimonial){
        try {
            if(\file_exists(public_path('storage/').$testimonial->photo) && $testimonial->photo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$testimonial->photo);
            }
            $testimonial->delete();
            return response()->json(['success' => 'The Testimonial hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
