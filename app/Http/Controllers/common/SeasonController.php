<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Child_group;
use App\Models\Child_group_product;
use App\Models\Child_group_season;
use App\Models\Child_group_season_product;
use App\Models\Group;
use App\Models\Group_product;
use App\Models\Group_season;
use App\Models\Group_season_product;
use App\Models\Inner_group;
use App\Models\Inner_group_product;
use App\Models\Inner_group_season;
use App\Models\Inner_group_season_product;
use App\Models\Product;
use App\Models\Product_season;
use App\Models\Country_product;
use App\Models\Review;
use App\Models\Season;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SeasonController extends Controller
{
    public function index(Request $request){
        if($request->draw){
            return datatables()::of(Season::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($season) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="Actions">';
                if(check_access('delete-season')){
                    $data .='<button type="button" class="btn btn-danger btn-sm delete" id="'.$season->id.'"><span class="feather icon-trash"></span></button>';
                }
                if(check_access('edit-season')){
                    $data .='<button type="button" class="btn btn-info btn-sm edit" id="'.$season->id.'"><span class="feather icon-edit"></span></button>';
                }
                if(check_access('add-product-to-season')){
                    $data .='<button type="button" class="btn btn-success btn-sm addItem" id="'.$season->id.'"><span class="feather icon-plus"></span></button>
                    <button type="button" class="btn btn-secondary btn-sm product" id="'.$season->id.'"><span class="feather icon-shopping-cart"></span></button>';
                }
                if(check_access('view-season-menu')){
                    $data .='<button type="button" class="btn btn-primary btn-sm groups" id="'.$season->id.'"><span class="feather icon-tag"></span></button>';
                }

                return '</div>'.$data;
            })
            ->editColumn('photo', function($season){
                return '<img style="max-width:40px" src="'.url('storage/'.$season->photo).'">';
            })

            ->editColumn('status', function($season){
                if($season->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })
            ->editColumn('country', function($season){
               $country = '';
               foreach($season->countries()->select('short_name','flag')->get() as $cnt){
                $country .= '<img src="'.url($cnt->flag).'" title="'.$cnt->short_name.'"> ';
               }
               return $country;
            })

            ->rawColumns(['country','photo','status','modify'])->make(true);
        }
        return view('common.season.index');
    }


    public function store(Request $request){

        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [ 
            'title'=>$request->title,'slug'=>$request->slug,'description'=>$request->description, 
            'meta_title'=>$request->meta_title, 'meta_description'=>$request->meta_description,'status'=>$request->status
        ];
        // $data['slug'] = $this->get_slug_unique(Str::slug($request->title));
        $season = Season::create($data);

        $season->save(); //slug stays "my-name"
        $season->countries()->attach($request->langs);
        $this->storeImage($season);
        return response()->json(['success' => 'Season has been created successfully!']);
    }

    public function show(Season $season){ 
        $season['country'] = $season->countries()->select('country_id')->get();
        return $season;
    }


    public function update(Request $request,Season $season){
        $validator = $this->fields($season->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [
            'title'=>$request->title,'slug'=>$request->slug,'description'=>$request->description, 
            'meta_title'=>$request->meta_title, 'meta_description'=>$request->meta_description,'status'=>$request->status
        ];

        // $data['slug']= $this->get_slug_unique(Str::slug($request->title));
        $season->update($data);
        $season->countries()->sync($request->langs);
        $this->storeImage($season,'update');
        return response()->json(['success' => 'The Season hasn been updated successfully!']);
    }

    function select_product(Season $season, Request $request){
        if($request->add_items){
            $ids = $request->ids;
            // dd($ids);
            // arsort($ids);

            if($request->child_group){
                // echo 'season: '.$season->id.' <br/>';
              
                $this->update_group_season($season->id, $request->group);
                $group_season_id = Group_season::where(['group_id'=>$request->group, 'season_id'=>$season->id])->pluck('id')->first();
                Group_season_product::where('group_season_id',$group_season_id)->delete();
                foreach($ids as $id){
                    $data = ['group_season_id'=>$group_season_id, 'product_id'=>$id ];
                    Group_season_product::create($data);
                }


                $this->update_inner_group_season($season->id, $request->inner_group);
                $inner_season_id = Inner_group_season::where([
                    'group_id'=>$request->group, 'inner_group_id'=>$request->inner_group,
                    'season_id'=>$season->id])->pluck('id')->first();
                Inner_group_season_product::where('inner_group_season_id',$inner_season_id)->delete();
                // dd($request->inner_group);
                foreach($ids as $id){
                    $data = ['inner_group_season_id'=>$inner_season_id, 'product_id'=>$id ];
                    Inner_group_season_product::create($data);
                }


                $this->update_child_group_season($season->id, $request->child_group);
                $child_season_id = Child_group_season::where(['child_group_id'=>$request->child_group,'season_id'=>$season->id])->pluck('id')->first();
                Child_group_season_product::where('child_group_season_id',$child_season_id)->delete();
                // dd('child group deleted');
                foreach($ids as $id){
                    $data = ['inner_group_id'=>$request->inner_group, 'child_group_season_id'=>$child_season_id, 'product_id'=>$id ];
                    Child_group_season_product::create($data);
                }
                $data = ['group_id'=>$request->group, 'inner_group_id'=>$request->inner_group, 'child_group_id'=>$request->child_group, 'season_id'=>$season->id];
                Product_season::where($data)->delete();
            }
            else if($request->inner_group){
                $this->update_group_season($season->id, $request->group);
                $group_season_id = Group_season::where(['group_id'=>$request->group, 'season_id'=>$season->id])->pluck('id')->first();
                Group_season_product::where('group_season_id',$group_season_id)->delete();
                foreach($ids as $id){
                    $data = ['group_season_id'=>$group_season_id, 'product_id'=>$id ];
                    Group_season_product::create($data);
                }

                $this->update_inner_group_season($season->id, $request->inner_group);
                $inner_season_id = Inner_group_season::where([
                    'group_id'=>$request->group, 'inner_group_id'=>$request->inner_group,
                    'season_id'=>$season->id])->pluck('id')->first();

                Inner_group_season_product::where('inner_group_season_id',$inner_season_id)->delete();

                // dd($request->inner_group);
                foreach($ids as $id){
                    $data = ['inner_group_season_id'=>$inner_season_id, 'product_id'=>$id ];
                    Inner_group_season_product::create($data);
                }
                $data = ['group_id'=>$request->group, 'inner_group_id'=>$request->inner_group, 'season_id'=>$season->id];
                Product_season::where($data)->delete();
            }
            else{
                $this->update_group_season($season->id, $request->group);

                $group_season_id = Group_season::where(['group_id'=>$request->group, 'season_id'=>$season->id])->pluck('id')->first();
                Group_season_product::where('group_season_id',$group_season_id)->delete();

                foreach($ids as $id){
                    $data = ['group_season_id'=>$group_season_id, 'product_id'=>$id ];
                    Group_season_product::create($data);
                }

                $data = ['group_id'=>$request->group, 'season_id'=>$season->id];
                Product_season::where($data)->delete();
            }


            //add to product_season table
            foreach($ids as $id){
                Product_season::create([
                    'season_id'=>$season->id, 'product_id'=>$id, 'group_id'=>$request->group,'inner_group_id'=>$request->inner_group,'child_group_id'=>$request->child_group
                ]);
            }

            return view('common.season.index');
        }else{
            $seasonCountry = $season->countries()->select('country_id')->distinct()->pluck('country_id')->toArray();
            // Get products associated with the country IDs
            // $productCountry = Country_product::whereIn('country_id', $seasonCountry)->select('product_id')->distinct()->get()->toArray();

            $catIds = \App\Models\Country_group::whereIn('country_id',$seasonCountry)->select('group_id')->distinct()->pluck('group_id')->toArray();
            $categories =  \App\Models\Group::whereIn('id',$catIds)->orderBy('title')->get();
            
            return view('common.season.search',compact('season','categories','seasonCountry'));
        }
    }

    function get_products(Season $season, $type,$id){
        $seasonCountry = $season->countries()->select('country_id')->distinct()->pluck('country_id')->toArray();
        $countryProducts = \DB::table('country_product')->whereIn('country_id', $seasonCountry)->select('product_id')->distinct()->pluck('product_id')->toArray();
       
        if($type=='child-group'){
            $products = Child_group_product::whereIn('product_id',$countryProducts)->where('child_group_id',$id)->get();
        }else if($type=='inner-group'){
            
            $products =  Inner_group_product::whereIn('product_id',$countryProducts)->where('inner_group_id',$id)->get();
            // dd($products);
        }else{
            $products =  Group_product::whereIn('product_id',$countryProducts)->where('group_id',$id)->get();
        }
        return view('common.season.select-product',compact('products','type','id'));
    }

    //setup groups, inner groups and child groups for menu show up
    function menu_setup(Season $season, Request $request){

        if($request->updateMenu=='save'){
            Group_season::where('season_id',$season->id)->update(['status'=>'0']);
            Child_group_season::where('season_id',$season->id)->update(['status'=>'0']);
            Inner_group_season::where('season_id',$season->id)->update(['status'=>'0']);

            if(!empty($request->category_ids)){
                foreach($request->category_ids as $id){
                    Group_season::where(['group_id'=>$id,'season_id'=>$season->id])->update(['status'=>'1']);
                }
            }

            if(!empty($request->sub_category_ids)){
                foreach($request->sub_category_ids as $sub_id){
                    Inner_group_season::where(['inner_group_id'=>$sub_id,'season_id'=>$season->id])->update(['status'=>'1']);
                }
            }

            if(!empty($request->child_category_ids)){
                foreach($request->child_category_ids as $child_id){
                    Child_group_season::where(['child_group_id'=>$child_id,'season_id'=>$season->id])->update(['status'=>'1']);
                }
            }

            return view('common.season.index');
        }else{
            return view('common.season.groups',compact('season'));
        }
    }


    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'title'=>'required',
            'slug'=>'required|unique:seasons,slug,'.$id,
            'photo'=>'sometimes|nullable|image',
            'description'=>'sometimes|nullable',
            'status'=>'required',
        ]); return $validator;
    }

    function storeImage($Season,$type=null){

        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(1800, 800);
            Storage::disk('public')->put("images/season/".$imageName, (string) $image->encode());
            $Season->update(['photo'=>"images/season/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }


    public function destroy(Season $Season){
        try {
            if(\file_exists(public_path('storage/').$Season->photo) && $Season->photo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$Season->photo);
            }
            $Season->delete();
            return response()->json(['success' => 'Season hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }

    function get_slug_unique($slug){
        $season = Season::where('slug',$slug)->first();
        if($season==null) return $slug = $slug;  else return $slug.'-'.Season::count();
    }


    // update group at group season on category selection
    function update_group_season($season_id, $group_id){
        $data = ['group_id'=>$group_id,'season_id'=>$season_id];
        $check = Group_season::where($data);
        if($check->count() < 1) Group_season::create($data);
    }
    // update sub-group at group season on category selection
    function update_inner_group_season($season_id, $inner_group){
        $inner_group = Inner_group::find($inner_group);
        $data = ['group_id'=>$inner_group->group->id, 'inner_group_id'=>$inner_group->id,'season_id'=>$season_id];
        $check = Inner_group_season::where($data);
        if($check->count() < 1) Inner_group_season::create($data);
    }
    // update child group at group season on category selection
    function update_child_group_season($season_id, $child_group_id){
        $child_group = Child_group::find($child_group_id);
        $data = ['inner_group_id'=>$child_group->inner_group->id, 'child_group_id'=>$child_group->id,'season_id'=>$season_id];
        $check = Child_group_season::where($data);
        if($check->count() < 1) Child_group_season::create($data);
    }

}
