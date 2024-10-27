<?php

namespace App\Http\Controllers\common;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Validator;

//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller{

    public function index(Request $request){
        if($request->draw){
            return datatables()::of(Slider::orderBy('sort_by'))
            ->addIndexColumn()
            ->editColumn('modify', function ($cat) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-home-slider')){
                    $data .= ' <button type="button" class="btn btn-danger btn-sm delete" id="'.$cat->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-home-slider')){
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
            ->editColumn('country', function($cat){
                $country = '';
                foreach($cat->countries()->select('short_name','flag')->get() as $cnt){
                 $country .= '<img src="'.url($cnt->flag).'" title="'.$cnt->short_name.'"> ';
                }
                return $country;
            })

            ->setRowId('{{$id}}')
            ->rawColumns(['country','photo','status','modify'])->make(true);
        }
        $this->set_country_slider_all();
        return view('common.slider.index');
    }

    private function set_country_slider_all(){
        foreach (get_currency() as $key => $value) {
            $sliders = Slider::where('status','!=','0')->get();
            foreach ($sliders as $item) {
                // echo $value->name.' = '.$item->title.'<br/>';
                $checkDB = \DB::table('country_slider')->where(['country_id'=>$value->id,'slider_id'=>$item->id]);
                if($checkDB->count() <1){
                    \DB::table('country_slider')->insert([
                        'country_id'=>$value->id,'slider_id'=>$item->id
                    ]);
                }
            }
        //   echo $value->name.'<br/>';
        }
        // exit;
    }


    public function store(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 'title'=>$request->title,'description'=>$request->description,'link'=>$request->link,
        'text_color'=>$request->text_color, 'status'=>$request->status];

        $slider = Slider::create($data);
        $slider->save();
        $slider->countries()->attach($request->langs);

        $this->storeImage($slider);
        return response()->json(['success' => 'Slider has been created successfully!']);
    }

    public function show(Slider $slider){ 
        $slider['country'] = $slider->countries()->select('country_id')->get();
        return $slider;
    }


    public function update(Request $request,Slider $slider){
        $validator = $this->fields($slider->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = ['title'=>$request->title,'description'=>$request->description,'link'=>$request->link,
        'text_color'=>$request->text_color, 'status'=>$request->status];

        $slider->update($data);
        $this->storeImage($slider,'update');
        $slider->countries()->sync($request->langs);

        return response()->json(['success' => 'The slider hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required|unique:sliders,title,'.$id,
            'photo'=>'sometimes|nullable|image',
            'description'=>'sometimes|nullable',
            'status'=>'required', 'link'=>'required',
        ]); return $validator;
    }

    function storeImage($slider,$type=null){

        if (request()->has('photo')) {
           
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            Storage::disk('public')->put("images/slider/".$imageName, file_get_contents(request()->photo));
            $slider->update(['photo'=>"images/slider/".$imageName]);

            // dd($name);
            // $fieldFile = request()->photo;
            // $mime= $fieldFile->getClientOriginalExtension();
            // $imageName = time().".".$mime;
            // $image = Image::make($fieldFile)->resize(1800, 800);
            // Storage::disk('public')->put("images/slider/".$imageName, (string) $image->encode());
            // $slider->update(['photo'=>"images/slider/".$imageName]);
            // if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
            //     \File::delete(public_path('storage/'.request()->oldPhoto));
            // }
        }
    }

    public function destroy(Slider $slider){
        try {
            if(\file_exists(public_path('storage/').$slider->photo) && $slider->photo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$slider->photo);
            }
            $slider->delete();
            return response()->json(['success' => 'Category hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
