<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Group;
use App\Models\Child_group;
use App\Models\Child_group_product;
use App\Models\Group_product;
use App\Models\Highlight;
use App\Models\Highlight_product;
use App\Models\Inner_group;
use App\Models\Inner_group_product;
use App\Models\Product;
use App\Models\Product_combination;
use App\Models\Product_photo;
use App\Models\Product_season;
use App\Models\Product_User;
use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Session;
use File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Rules\FloatValue;


class ProductController extends Controller
{
    function index(Request $request){
        if($request->draw){
            $datatable = datatables()::of(Product::where('status','!=','delete'));
            return $this->products($datatable);
        }

        $variationImg = \App\Models\Setting::where('type','variation-image-with')->pluck('value')->first();
        Session::put('variation_image', $variationImg);

        // $this->set_country_product_all();

        return view('common.product.index');
    }

    private function set_country_product_all(){
        foreach (get_currency() as $key => $value) {
            $products = Product::where('status','!=','delete')->get();
            foreach ($products as $item) {
                // echo $value->name.' = '.$item->title.'<br/>';
                $checkDB = \DB::table('country_product')->where(['country_id'=>$value->id,'product_id'=>$item->id]);
                if($checkDB->count() <1){
                    \DB::table('country_product')->insert([
                        'country_id'=>$value->id,'product_id'=>$item->id
                    ]);
                }
            }
        //   echo $value->name.'<br/>';
        }
        // exit;
    }

    function design_code_product($design_code){
        if($design_code){
            $datatable = datatables()::of(Product::where('design_year',$design_code)->where('products.status','!=','delete'));
            return $this->products($datatable);
        }
    }


    function category_products(Request $request, Group $group){
        if($request->draw){
            $datatable = datatables()::of($group->products()->where('products.status','!=','delete')->get());
            return $this->products($datatable);
        }
        return view('common.product.index');
    }

    public function sub_category_products(Inner_group $inner_group, Request $request){
        if($request->draw){
            $datatable = datatables()::of($inner_group->products()->where('products.status','!=','delete')->get());
            return $this->products($datatable);
        }
        return view('common.product.index');
    }

    public function child_category_products(Child_group $child_group, Request $request){
        if($request->draw){
            $datatable = datatables()::of($child_group->products()->where('products.status','!=','delete')->get());
            return $this->products($datatable);
        }
        return view('common.product.index');
    }

    public function brand_products(Brand $brand, Request $request){
        if($request->draw){
            $datatable = datatables()::of(Product::where('status','!=','delete')->where('brand_id',$brand->id));
            return $this->products($datatable);
        }
        return view('common.product.index');
    }

    function highlight_products(Request $request, Highlight $highlight){
        if($request->draw){
            $product_ids = Highlight_product::where('highlight_id',$highlight->id)->select('product_id')->distinct('product_id')->get()->toArray();
            // dd($product_ids);
            $datatable = datatables()::of(Product::where('status','!=','delete')->whereIn('id',$product_ids));
            // dd($product_ids);
            return $this->products($datatable);
        }
        return view('common.product.index');
    }

    public function create(Request $request){
        $product = new Product();
        $groupCats = \DB::table('country_group')->select('group_id')->distinct()->get()->pluck('group_id')->toArray();
        
        $categories = Group::whereIn('id',$groupCats)->where('status','1')->orderBy('title','ASC')->get();
        $brands = Brand::orderBy('title','ASC')->get();
        $suppliers = Supplier::orderBy('company_name','ASC')->get();
        return view('common.product.create', compact('product','categories','brands','suppliers'));
    }

    public function store(Request $request){

        // dd($request->all());
        Session::flash('message', true);
        Session::flash('alert', 'Validation error! Please check the form');
       
        $data = $this->fields();
        $data['slug']= $this->get_slug_unique(Str::slug($request->title));
        unset($data['langs']);

        // $currency = \App\Models\Country::where('is_default','1')->first();
        // $data['net_price'] = $request->net_price / $currency->value;
        // $data['sale_price'] = $request->sale_price / $currency->value;

        if (isset($request->is_group)) $data['is_group'] = '1'; else $data['is_group']  = '0';
        if (isset($request->portable)) $data['portable'] = '1'; else $data['portable'] = '0';
        if (isset($request->cod)) $data['cod'] = '1'; else $data['cod'] = '0';
        if (isset($request->newArrival)) $data['newArrival'] = '1'; else $data['newArrival'] = '0';

        $product = Product::create($data);
        $product->countries()->attach($request->langs);

        if(!empty($request->fields)){
            $product->update([
                'additional_field'=> implode(',',$request->fields),
                'additional_value'=>implode(',',$request->field_values)
            ]);
        }

        if(!empty($request->category_ids)){ 
            $product->groups()->attach($request->category_ids); 
        }
        if(!empty($request->sub_category_ids)){
            // $product->inner_groups()->attach($request->sub_category_ids);
            $this->add_inner_group_product($request->sub_category_ids,$product);
        }
        if(!empty($request->child_category_ids)){
            // $product->child_groups()->attach($request->child_category_ids);
            $this->add_child_group_product($request->child_category_ids,$product);
        }

        $this->storeFeaturePhoto($product);
        $this->upload_images($product);
        $this->entry_user($product,'create');
        Session::flash('success', 'The product has been saved successfully.');
        Session::put('id', $product->id);
        Session::put('title', $product->title);
        return back();
    }

    public function show(Product $product){ return Product::find($product->id);}


    public function edit(Product $product){
        $groupCats = \DB::table('country_group')->select('group_id')->distinct()->get()->pluck('group_id')->toArray();
        $categories = Group::whereIn('id',$groupCats)->orderBy('title','ASC')->get();
        $brands = Brand::orderBy('title','ASC')->get();
        $suppliers = Supplier::orderBy('company_name','ASC')->get();
        return view('common.product.edit', compact('product','categories','brands','suppliers'));
    }

    function product_photos($id){ return Product_photo::where('product_id',$id)->get();}


    function quick_update(Request $request,Product $product){
        $product->countries()->sync($request->langs);

        if (isset($request->newArrival)) $newArrival = '1'; else $newArrival = '0';

        $product->update(['newArrival'=>$newArrival]);

        return response()->json(['success' => 'Quick edit has been executed successfully!']);
    }

    public function update(Request $request,Product $product){
        // dd($request->all());
        Session::flash('alert', 'Validation error! Please check the form');
        $data = $this->fields($product->id);
        $data = $this->fields($product->id);

        if($request->title != $product->title){
            $data['slug']= $this->get_slug_unique(Str::slug($request->title));
        }else $data['slug']= $product->slug;
        
        unset($data['langs']);

        if (isset($request->is_group)) $data['is_group'] = '1'; else $data['is_group']  = '0';
        if (isset($request->portable)) $data['portable'] = '1'; else $data['portable'] = '0';
        if (isset($request->cod)) $data['cod'] = '1'; else $data['cod'] = '0';
        if (isset($request->newArrival)) $data['newArrival'] = '1'; else $data['newArrival'] = '0';


        // $currency = \App\Models\Country::where('is_default','1')->first();
        // $data['net_price'] = $request->net_price / $currency->value;
        // $data['sale_price'] = $request->sale_price / $currency->value;
        
        $product->update($data);

        $this->change_related_status($product);

        if(!empty($request->fields)){
            $product->update([
                'additional_field'=> implode(',',$request->fields),
                'additional_value'=>implode(',',$request->field_values)
            ]);
        }

        $product->countries()->sync($request->langs);

        if(!empty($request->category_ids)){ $product->groups()->sync($request->category_ids); }
        if(!empty($request->sub_category_ids)){

            $this->add_inner_group_product($request->sub_category_ids,$product);
            // $product->inner_groups()->sync($request->sub_category_ids);
        }
        if(!empty($request->child_category_ids)){
            // $product->child_groups()->sync($request->child_category_ids);
            $this->add_child_group_product($request->child_category_ids,$product);
        }


        $this->storeFeaturePhoto($product,'update');
        $this->upload_images($product);
        $this->entry_user($product,'update');
        Session::flash('id', $product->id);

        return back()->with('success', 'The product has been updated successfully.');
    }

    private function products($datatable){
        return $datatable
        ->addIndexColumn()
        ->editColumn('checkbox', function($row){
            return '<input type="checkbox" style="height:40px;width: 25px;" class="checkProduct" value="'.$row->id.'" name="selectedIds[]">';
        })
        ->editColumn('modify', function ($row) {
            $data = '';
            $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';

            if (check_access('edit-product')){
                $data .= '<a href="'.route('common.product.edit',$row->id).'" class="btn btn-info btn-sm"><span class="feather icon-edit"></span></a>';
            }
            if(check_access('delete-product')){
                $data .= '<button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
            }

            $data .= '<div class="btn-group card-option" style="min-height:20px">
            <button type="button" class="dropdown-toggle btn btn-secondary btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> </button>
            <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right" style="max-height:200px;overflow-x:scroll">';

            if(check_access('view-product-variation')){
                $data .= '<li class="dropdown-item"><a class="colors" id="'.$row->id.'"><span><i class="feather icon-camera"></i> Vairation Image </span></a></li> <hr/>';
            }
            if(check_access('view-product-video')){
                $data .= '<li class="dropdown-item"><a href="'.url('common/catalog/product/videos/'.$row->id).'" target="_"><span><i class="feather icon-video"></i> Product Videos</span></a></li><hr/>';
            }
            if(check_access('copy-product')){
                $data .= '<li class="dropdown-item"><a href="'.url('common/catalog/product/copy/'.$row->id).'" target="_"><span><i class="feather icon-copy"></i> Copy Product</span></a></li> <hr/>';
            }
            if(check_access('view-product-meta')){
                $data .= '<li class="dropdown-item"><a class="meta" href="javaScript:;" id="'.$row->id.'"><span><i class="feather icon-info"></i> Meta info</span></a></li><hr/>';
            }
            if (check_access('edit-product')){
                $data .= '<li class="dropdown-item"><a class="quickEdit" href="javaScript:;" gross_weight="'.$row->gross_weight.'" vol_weight="'.$row->vol_weight.'" id="'.$row->id.'"><span><i class="feather icon-edit"></i> Quick edit</span></a></li>';
            }
            $data .= '</ul></div></div>';
            $data .= '</div>';  return $data;

        })
        ->editColumn('photo', function($row){
            return '<img style="height:43px" src="'.$row->thumbs.'">';
        })

        ->editColumn('title', function($row){
            $data = $row->title;
            if(check_access('view-product-variation')){
                $data .= ' <br/>Total of <button class="variants badge badge-info pl-3 p-1 pr-3" data-title="'.$row->title.'" id="'.$row->id.'">'.$row->product_combinations()->count().'</button> variations';
            } return $data;
        })

        ->editColumn('qty', function($row){
            $qty = array();
            $productCombinations = Product_combination::where('product_id',$row->id)->get();

            foreach($productCombinations as $comb){ $qty[] = $comb->qty;}

            if(array_sum($qty) >0) return array_sum($qty);
            else return $row->qty;
        })
        ->editColumn('categories', function($row){
            $data = '';
            foreach($row->group_products()->select('group_id')->get() as $gp){
                $data .= '<span class="text-info">'.$gp->group->title.'</span>';
                foreach($row->inner_group_products()->where('group_id',$gp->group_id)->select('inner_group_id')->get() as $subKey=>$igp){
                    if($subKey==0) $data .=' <i class="fa fa-long-arrow-right"></i> ';
                    else $data .=', ';
                    $data .= ' <span class="text-primary">'.$igp->inner_group->title.'</span>';

                    foreach($row->child_group_products()->where('group_id',$gp->group_id)->select('child_group_id')->get() as $childKey=>$cgp){
                        if($childKey==0) $data .='<i class="fa fa-long-arrow-right"></i> ';
                        else $data .=', ';
                        $data .= '<span class="text-warning">'.$cgp->child_group->title.'</span>';
                    }
                } $data .='<br/>';
            }
            return $data;
        })

        ->editColumn('status', function($row){
            if($row->status=='1') return '<span class="badge badge-success status" data-status="'.$row->status.'" style="cursor:pointer" data-id="'.$row->id.'">Active</span>';
            else return  '<span class="badge badge-danger status" data-status="'.$row->status.'" style="cursor:pointer" data-id="'.$row->id.'">Hidden</span>';
        })
        ->editColumn('country', function($row){
            $country = '';
            foreach( $row->countries()->get() as $key=>$cnt){
             $country .= '<img src="'.url($cnt->flag).'"  title="'.$cnt->short_name.'"  id="'.$cnt->id.'" style="height:10px"> ';
             if($key==2) $country .='<br/>';
            }
            return $country;
        })
        ->rawColumns(['country','photo','title','categories','qty','status','modify'])->make(true);
    }

    function change_status(Product $product){
        if($product->status=='1') $status = '0';
        else $status = '1';
        // dd($status);
        $product->where('id',$product->id)->update(['status'=>$status]);
    }

    function change_related_status(Product $product){
        Product_season::where(['product_id'=>$product->id])->update(['status'=>$product->status]);
    }

    function assign2groups(Group $group, $product_ids){
        foreach(explode(',',$product_ids) as $product_id){
            $check = Group_product::where(['group_id'=>$group->id, 'product_id'=>$product_id]);
            if($check->count() < 1){
                Group_product::create([ 'group_id'=>$group->id, 'product_id'=>$product_id]);
            }
        }
        return response()->json(['success'=>'selected product items has been assigned to tha category!']);
    }

    private function fields($id=null){
        return request()->validate([
            'title'=>'required',
            'slug'=>'sometimes|nullable|unique:products,slug,'.$id,
            'brand_id'=>'required',
            'design_code' => [
                'required',
                Rule::unique('products', 'design_code')->ignore($id)->where(function ($query) {
                    return $query->whereIn('status', ['0', '1']);
                }),
                'required_if:status,0,1',
            ],
            'design_year'=>'required',
            'sku'=>'required|unique:products,sku,'.$id,
            'barcode'=>'required|unique:products,barcode,'.$id,
            'net_price'=>'required','sale_price'=>'required',
            'vat_type'=>'required', 'vat'=>'required', 'qty'=>'required',
            'tags'=>'required','description'=>'required',
            'feature_photo'=>'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status'=>'required', 'supplier_id'=>'required',
            'cod'=>'sometimes|nullable',
            'portable'=>'sometimes|nullable',
            'langs' => 'required|array|min:1',
            'is_group'=>'sometimes|nullable','size_chirt_id'=>'sometimes|nullable',
            // 'gross_weight'=> ['required', new FloatValue],
            // 'vol_weight'=> ['required', new FloatValue],
            // 'hs_code' => 'sometimes|nullable',
        ]);
    }


    function storeFeaturePhoto($product,$type=null){

        if (request()->has('feature_photo')) {
            $fieldFile = request()->feature_photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;


            $feature_path = public_path().'/storage/images/product/feature';
            File::isDirectory($feature_path) or File::makeDirectory($feature_path, 0777, true, true);

            $thumbs_path = public_path().'/storage/images/product/thumbs';
            File::isDirectory($thumbs_path) or File::makeDirectory($thumbs_path, 0777, true, true);

            // product sizing
            $width = Setting::where('type','product-weight')->pluck('value')->first();
            $height = Setting::where('type','product-height')->pluck('value')->first();
            $thumbsHeight = 288;
            $divide = $height/ $thumbsHeight;
            $thumbsWeight = $width /$divide;
            // dd($thumbsHeight.' = '.$thumbsWeight);


            // to finally create image instances
            // $image = $manager->make($fieldFile)->resize($height,$width);


            $thumbs = Image::make($fieldFile)->resize($thumbsHeight,$thumbsWeight);

            // create a new Image instance for inserting
            // $this->watermark_effect($image);

            Storage::disk('public')->put("images/product/feature/".$imageName, file_get_contents(request()->feature_photo));
            
            // Storage::disk('public')->put("images/product/thumbs/".$imageName, (string) $thumbs->encode());
            // $image->save('storage/images/product/feature/'.$imageName);
            $thumbs->save('storage/images/product/thumbs/'.$imageName);

            // dd( url('/')."/storage/images/product/feature/".$imageName );
            $product->update([
                'feature_photo'=> url('/')."/storage/images/product/feature/".$imageName,
                'thumbs'=>url('/')."/storage/images/product/thumbs/".$imageName,
            ]);

            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(request()->oldPhoto);
                \File::delete(str_replace('feature','thumbs',request()->oldPhoto));
            }
        }
    }

    private function upload_images($product,$type=null){
        
        if($files=request()->file('photos')){
            foreach($files as $file){
                // $name=$file->getClientOriginalName();
                $mime= $file->getClientOriginalExtension();
                $name = rand().".".$mime;
                $file->move('storage/images/product/',$name);
                Product_photo::create(['product_id'=>$product->id,'photo'=>url('storage/images/product/'.$name)]);
            }
        }

        // if($files=request()->file('photos')){
        //     foreach($files as $key=>$fieldFile){
        //         $mime= $fieldFile->getClientOriginalExtension();
        //         $imageName = rand().".".$mime;
        //         // sizing..
        //         $width = Setting::where('type','product-weight')->pluck('value')->first();
        //         $height = Setting::where('type','product-height')->pluck('value')->first();

        //         // $image = Image::make($fieldFile)->resize($height,$width);
        //         // // create a new Image instance for inserting
        //         // $this->watermark_effect($image);

        //         Storage::disk('public')->put("images/product/".$imageName, file_get_contents($imageName));
        //         Product_photo::create(['product_id'=>$product->id,'photo'=>"images/product/".$imageName]);
        //     }
        // }
    }


    public function destroy(Product $product){
        try {
            $checkOrder = \App\Models\Order_item::where('product_id',$product->id);
            if($checkOrder->count() <1){
                // \App\Models\Group_product::where('product_id',$product->id)->delete();
                // \App\Models\Inner_group_product::where('product_id',$product->id)->delete();
                // \App\Models\Child_group_product::where('product_id',$product->id)->delete();

                // \App\Models\Product_combination::where('product_id',$product->id)->delete();
                // \App\Models\Product_variation_option::where('product_id',$product->id)->delete();
                // $variationPhotos =  \App\Models\Variation_option_photo::where('product_id',$product->id)->get();
                // foreach($variationPhotos as $vp){
                    // if(\file_exists(public_path('storage/').$vp->thumbs) && $vp->thumbs !='images/thumbs_photo.png'){
                    //     \File::delete(public_path('storage/').$vp->photo);
                    //     \File::delete(public_path('storage/').$vp->thumbs);
                    // }
                    // $vp->delete();
                // }

                // if(\file_exists(public_path('storage/').$product->thumbs) && $product->thumbs !='images/thumbs_photo.png'){
                //     \File::delete(public_path('storage/').$product->feature_photo);
                //     \File::delete(public_path('storage/').$product->thumbs);
                // }


                $this->entry_user($product,'delete');
                $product->update(['status'=>'delete']);
                return response()->json(['success' => 'The product hasn been deleted successfully!']);
            }else{
                return response()->json(['error' => 'The product item is in order list. No option to remove !!']);
            }

        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrain error!!']);
        }
    }

    function remove_product_photo(Product_photo $product_photo){

        if ($this->urlExists($product_photo->photo)) {
            \File::delete($product_photo->photo);
        } 
        return response()->json(['error' => 'Product photo has been removed successfully!']);
    }

        private function urlExists($url){
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Disable SSL host verification
            curl_exec($ch);
            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        
            return $responseCode == 200;
        }


    private function watermark_effect($image){
        $effectPermit = \App\Models\Setting::where('type','product-watermark')->pluck('value')->first();
        $logo = \App\Models\General_info::where('field','watermark_logo')->pluck('value')->first();
        if($effectPermit=='1'){
            $watermark = Image::make( public_path('storage/'.$logo) )->opacity(20);
            $image->insert($watermark, 'center');
        }

    }

    private function get_slug_unique($slug){
        $products = Product::where('slug','LIKE','%'.$slug . '%');
        if($products->count()>0){
            $count = $products->count() + rand(9,100);
            $slug = $slug.'-'.$count;
        }
        return $slug;
    }

    //save who create/update the post?
    private function entry_user($product,$action){
        if($action=='create'){
            Product_User::create(['product_id'=>$product->id,'user_id'=>Auth::user()->id,'action'=>$action]);
        }
        if($action=='update'){
            $check = Product_User::where('product_id',$product->id)->where('action',$action);
            if($check->count()>0){
                Product_User::where(['product_id'=>$product->id,'action'=>$action])->update(['action'=>$action]);
            }else{
                Product_User::create(['product_id'=>$product->id,'user_id'=>Auth::user()->id,'action'=>$action]);
            }

        }
        if($action=='delete'){
            Product_User::create(['product_id'=>$product->id,'user_id'=>Auth::user()->id,'action'=>$action]);
        }
    }

    function copy(Product $product){
        $categories = Group::orderBy('title','ASC')->get();
        $brands = Brand::orderBy('title','ASC')->get();
        $suppliers = Supplier::orderBy('company_name','ASC')->get();
        return view('common.product.copy.create', compact('product','categories','brands','suppliers'));
    }

    private function add_inner_group_product($inner_group_ids, $product){
        Inner_group_product::WHERE(['product_id'=> $product->id])->delete();
        foreach($inner_group_ids as $inner_id){
            $inner = Inner_group::where('id',$inner_id)->first();
            $data = [ 'group_id'=>$inner->group_id,'product_id'=> $product->id ];
            $data[ 'inner_group_id'] = $inner_id;
            Inner_group_product::create($data);
        }
    }

    private function add_child_group_product($child_group_ids, $product){
        // DD($child_group_ids);
        Child_group_product::where('product_id',$product->id)->delete();
        foreach($child_group_ids as $child_id){
            $child = Child_group::where('id',$child_id)->select('inner_group_id')->first();

            $data = [
                'group_id'=>$child->inner_group->group_id,
                'inner_group_id'=>$child->inner_group_id,
                'product_id'=>$product->id, 'child_group_id' => $child_id
            ];
            Child_group_product::create($data);
        }
    }

}
