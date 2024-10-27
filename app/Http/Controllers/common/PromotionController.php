<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Product_promotion;
use App\Models\Product_promotion_summary;
use App\Models\Promotion_type;
use App\Models\Promotion;
use App\Models\Promotion_summary;
use App\Models\Group;
use App\Models\Inner_group;
use App\Models\Child_group;
use Illuminate\Http\Request;
use Validator;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PromotionController extends Controller
{
    public function index(Promotion_type $promotion_type, Request $request){
        if($request->draw){
            return datatables()::of(Promotion::where('promotion_type_id',$promotion_type->id)->orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('delete-promotion')){
                    $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-promotion')){
                    $data .= '<button type="button" class="btn btn-info btn-sm edit" id="'.$row->id.'"><span class="feather icon-edit"></span></button>';
                }
                if(check_access('view-promotion-products')){
                    $data .= '<button type="button" class="btn btn-primary btn-sm assign" id="'.$row->id.'"><span class="feather icon-plus"></span></button>';
                }
                if(check_access('add-product-to-promotion')){
                    $data .= '<button type="button" class="btn btn-secondary btn-sm products" id="'.$row->id.'"><span class="feather icon-shopping-cart"></span></button>';
                }
                $data .= '</div>';  return $data;
            })

            ->editColumn('photo', function($data){
                return '<img style="max-width:40px" src="'.url('storage/'.$data->photo).'">';
            })
            ->editColumn('dates', function($data){
                $start = date('M j, Y',strtotime($data->start_date));
                $end = date('M j, Y',strtotime($data->end_date));
                return $start.' - '.$end.'<br/>Time '.$data->start_time.' - '.$data->end_time;
            })
            ->editColumn('status', function($data){
                if($data->status=='1') $status =  '<span class="badge badge-success">Active</span>';
                else $status =  '<span class="badge badge-danger">Inactive</span>';

                $curdate = strtotime(date('Y-m-d H:i'));
                $expiry = strtotime($data->end_date.' '.$data->end_time);

                if($curdate >= $expiry){
                    $expired = ' <span class="badge badge-danger">expired</span>';
                }else $expired = '';

                return $status.$expired;
            })

            ->editColumn('country', function($data){
                $country = '';
                foreach($data->countries()->select('short_name','flag')->get() as $cnt){
                 $country .= '<img src="'.url($cnt->flag).'" title="'.$cnt->short_name.'"> ';
                }
                return $country;
            })

            ->rawColumns(['country','photo','dates','status','modify'])->make(true);
        }
        return view('common.ad.promotion.index', compact('promotion_type'));
    }


    public function store(Request $request){

        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $data = [ 'promotion_type_id'=>$request->promotion_type, 'title'=>$request->title,
            'slug'=>$this->get_slug_unique(Str::slug($request->title)),
            'bg_color'=>$request->bg_color,'text_color'=>$request->text_color,
            'start_date'=>date('Y-m-d',strtotime($request->start_date)),
            'end_date'=>date('Y-m-d',strtotime($request->end_date)),
            'start_time'=>$request->start_time, 'end_time'=>$request->end_time,
            'description'=>$request->description,'status'=>$request->status,
            'expiry_visibility'=>$request->expire_visibility,
        ];
        $promotion = Promotion::create($data);
        $promotion->countries()->attach($request->langs);
        $this->storeImage($promotion);

        return response()->json(['success' => 'Promotion info has been created successfully!']);
    }

    public function show(Promotion $promotion){ 
        $promotion['country'] = $promotion->countries()->select('country_id')->get();
        return $promotion;
    }


    public function update(Request $request,Promotion $promotion){
        $validator = $this->fields($promotion->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if($request->category_id=='all') $cat_id = null; else $cat_id = $request->category_id;
        if($promotion->title != $request->title) $slug = $this->get_slug_unique(Str::slug($request->title));
        else $slug = $promotion->slug;
        $data = [
            'promotion_type_id'=>$request->promotion_type,
            'title'=>$request->title, 'slug'=>$slug,
            'bg_color'=>$request->bg_color,'text_color'=>$request->text_color,
            'start_date'=>date('Y-m-d',strtotime($request->start_date)),
            'end_date'=>date('Y-m-d',strtotime($request->end_date)),
            'start_time'=>$request->start_time, 'end_time'=>$request->end_time,
            'description'=>$request->description,
            'expiry_visibility'=>$request->expire_visibility,
            'status'=>$request->status
        ];

        $promotion->update($data);
        $promotion->countries()->sync($request->langs);
        $this->storeImage($promotion,'update');

        Product_promotion::where('promotion_id',$promotion->id)->update(['status'=>$request->status]);
        return response()->json(['success' => 'The promotion hasn been updated and all promotional product has been updated accordingly!']);

    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'promotion_type'=>'required',
            'title'=>'required',
            'photo'=>'sometimes|nullable|image',
            'start_date'=>'required','end_date'=>'required',
            'start_time'=>'required','end_time'=>'required',
            'description'=>'required','expire_visibility'=>'required',
            'status'=>'required',
        ]); return $validator;
    }

    function storeImage($promotion,$type=null){
        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(885, 430);
            $fileSaved = Storage::disk('public')->put("images/promotion/".$imageName, (string) $image->encode());
	    
	    $promotion->update(['photo'=>"images/promotion/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }

    function get_slug_unique($slug){
        $data = Promotion::where('slug',$slug)->first();
        if($data==null) return $slug = $slug;  else return $slug.'-'.Promotion::count();
    }


    public function destroy(Promotion $promotion){
        try {
            if(\file_exists(public_path('storage/').$promotion->photo) && $promotion->photo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$promotion->photo);
            }

            $summary = Promotion_summary::where('promotion_id',$promotion->id)->first();
            if($summary !=null){
                Product_promotion_summary::where('promotion_summary_id',$summary->id)->delete();
                $summary->delete();

                $promotion->delete();

                return response()->json(['success' => 'promotion hasn been deleted successfully!']);
            }

        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be a foreign key constrate error!!']);
        }
    }


    public function main2sub_categories(Promotion $promotion, Group $group){
        $promotionCountry = $promotion->countries()->select('country_id')->distinct()->pluck('country_id')->toArray();
        $innerIds = \DB::table('country_inner_group')->whereIn('country_id',$promotionCountry)->select('inner_group_id')->distinct()->pluck('inner_group_id')->toArray();
        $inner = Inner_group::whereIn('id',$innerIds)->where('group_id',$group->id)->get();
        return $inner;
    }

    public function sub2child_categories(Promotion $promotion, Inner_group $inner_group){
        $promotionCountry = $promotion->countries()->select('country_id')->distinct()->pluck('country_id')->toArray();
        $childIds = \DB::table('child_group_country')->whereIn('country_id',$promotionCountry)->select('child_group_id')->distinct()->pluck('child_group_id')->toArray();
        $child = Child_group::whereIn('id',$childIds)->where('inner_group_id',$inner_group->id)->get();
        // dd($child, $inner_group);
        return $child;
    }






}
