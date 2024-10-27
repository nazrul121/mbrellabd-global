<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Permission_label;
use App\Models\Setting;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    function index(Request $request){
        // $this->save_permissions(); exit;

        if($request->draw){
            return datatables()::of(Permission_label::orderBy('title'))
            ->addIndexColumn()
            ->editColumn('modify', function ($p) {
                return ' <div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">
                    <button type="button" class="btn btn-info btn-sm edit" id="'.$p->id.'"><span class="feather icon-users"></span></button>
                </div> ';
            })
            ->editColumn('title', function ($p) {
                return $p->title.'<br/><small> includes <b class="badge badge-secondary">'.$p->permissions()->count().'</b> options</small>';
            })
            ->editColumn('permissions', function ($p)  {
                $data = '';
                foreach($p->permissions()->get() as $key=>$per){

                    if($key >1){
                        $data .='<button type="button" class="btn btn-primary btn-sm p-0 pl-1 pr-1 viewAll" id="'.$p->id.'" data-title="'.$p->title.'">View all</button>';
                        break;
                    }else{
                        $data .= $per->name.'<br/>';
                    }
                }
                return $data;
            })
            ->rawColumns(['title','permissions','modify'])->make(true);
        }
        return view('common.role.index');
    }


    function setRole(Request $request){
        if($request->accessType){
            $check = Setting::where('type','staff-permission-type')->pluck('value')->first();
            if($check !=null){
                Setting::where('type','staff-permission-type')->update( ['value'=>$request->accessType ]);
            }else{
                Setting::create([ 'type'=>'staff-permission-type','value'=>$request->accessType ]);
            }
        }
        return back()->with('message','Access type setting has been updated successfully!!');
    }


    function label_permissions(Permission_label $permission_label){
        // dd($permission_label);
        $permissions = $permission_label->permissions()->get();
        return view('common.role.permissions',compact('permissions','permission_label'));
    }

    function save_permission_group(Request $request){
       
        if(! $request->ids){ return back(); }
        foreach($request->ids as $perID){
            Permission::where('id',$perID)->update([
                'permission_group_id'=>$request->group_id
            ]);
        }
        return response()->json(['success' => 'Payment type has been created successfully!']);
    }

    function save_permissions(){
        $data = array();
        $data[1] = [
            'view-main-category'=>'View product category',
            'create-main-category'=>'Create category',
            'edit-main-category'=>'Edit category',
            'delete-main-category'=>'Delete category',

            'view-sub-category'=>'View product sub-category',
            'create-sub-category'=>'Create sub category',
            'edit-sub-category'=>'Edit sub category',
            'delete-sub-category'=>'Delete sub-category',

            'view-child-category'=>'View product child-category',
            'create-child-category'=>'Create child category',
            'edit-child-category'=>'Edit child category',
            'delete-child-category'=>'Delete child category',

            'group-ordering'=>'Category ordering',

            'view-size-chirt'=>'View product size-chirt',
            'create-size-chirt'=>'Create size-chirt',
            'delete-size-chirt'=>'Delete size-chirt',

            'view-size-chirt-pdf'=>'View size-chirt PDF',
            'create-size-chirt-pdf'=>'upload size-chirt PDF',

            'view-product-variation'=>'View product Variations',
            'create-product-variation'=>'Create Variations',
            'edit-product-variation'=>'Edit Variations',
            'delete-product-variation'=>'Delete Variations',

            'view-product-variation-option'=>'View product Variation option',
            'create-product-variation-option'=>'Create Variation option',
            'edit-product-variation-option'=>'Edit Variation option',
            'delete-product-variation-option'=>'Delete Variation option',

            'update-product-combination-qty'=>'Update product combination Qty',

            'view-product-list'=>'View product list',
            'create-product'=>'Create product',
            'edit-product'=>'Edit product',
            'delete-product'=>'Delete product',

            'copy-product'=>'Copy product item',


            'view-product-video'=>'View product video',
            'create-product-video'=>'Create product video',
            'edit-product-video'=>'Edit product video',
            'delete-product-video'=>'Delete product video',

            'view-product-meta'=>'View product meta data',
            'create-product-meta'=>'Create product meta data',
            'edit-product-meta'=>'Edit product meta data',
            'delete-product-meta'=>'Delete product meta data',


            'view-product-highlight'=>'View product highlights',
            'create-product-highlight'=>'Create product highlight',
            'edit-product-highlight'=>'Edit product highlight',
            'delete-product-highlight'=>'Delete product highlight',
            'add-product-to-highlight'=>'Assign products into highlight',

            'view-season'=>'View season',
            'view-season-menu'=>'View season menu',
            'create-season'=>'Create product season',
            'edit-season'=>'Edit product season',
            'delete-season'=>'Delete product season',
            'add-product-to-season'=>'Assign products into season',
        ];

        $data[2] = [
            'view-order'=>'View orders',
            'create-order'=>'Create manual order',
            'edit-order'=>'Edit order',
            'take-order-payment'=>'Accept order payment',
            'change-order-status'=>'Change order procidure',
            'ready-order-for-shipment'=>'Make ready orders for shipment',
        ];

        $data[3] =[
            'view-courier-company'=>'View Courier companies',
            'create-courier-company'=>'Create courier company',
            'edit-courier-company'=>'Edit courier company',
            'delete-courier-company'=>'Delete courier company',

            'view-courier-representative'=>'View courier representatives',
            'create-courier-representative'=>'create courier representatives',
            'edit-courier-representative'=>'edit courier representatives',
            'delete-courier-representative'=>'delete courier representatives',

            'view-courier-zone'=>'View courier company Zones',
            'create-courier-zone'=>'create courier Zones',
            'edit-courier-zone'=>'edit courier Zones',
            'delete-courier-zone'=>'delete courier Zones',
        ];

        $data[4]= [
            'view-coupons'=>'View Coupons',
            'create-coupons'=>'Create coupons',
            'edit-coupons'=>'Edit coupons',
            'delete-coupons'=>'Delete coupond',
            'coupon-customer'=>'Customers of the coupon',

            'view-invoice-discount'=>'View Invoice-discount',
            'create-invoice-discount'=>'Create Invoice-discount',
            'edit-invoice-discount'=>'Edit Invoice-discount',
            'delete-invoice-discount'=>'Delete Invoice-discount',

            'view-promotion'=>'View promotion',
            'create-promotion'=>'Create promotion',
            'edit-promotion'=>'Edit promotion',
            'delete-promotion'=>'Delete promotion',

            'view-promotion-products'=>'View promotional products',
            'add-product-to-promotion'=>'Assign products into promotion',
            'delete-product-from-promotion'=>'Delete product from promotion',

            'view-campaign'=>'View campaign',
            'create-campaign'=>'Create campaign',
            'edit-campaign'=>'Edit campaign',
            'delete-campaign'=>'Delete campaign',

            'view-campaign-products'=>'View campaign products',
            'add-product-to-campaign'=>'Assign products into campaign',


            'view-banner'=>'View Banners',
            'create-banner'=>'Create Banners',
            'edit-banner'=>'Edit Banners',
            'delete-banner'=>'Delete Banners',
        ];

        $data[5]= [
            'view-page-video'=>'View videos of pages',
            'create-page-video'=>'Create videos of pages',
            'edit-page-video'=>'Edit videos of pages',
            'delete-page-video'=>'Delete videos of pages',

            'view-home-slider'=>'View sliders',
            'create-home-slider'=>'Create sliders',
            'edit-home-slider'=>'Edit sliders',
            'delete-home-slider'=>'Delete sliders',

            'view-page-post'=>'View page post',
            'create-page-post'=>'Create page post',
            'edit-page-post'=>'Edit page post',
            'delete-page-post'=>'Delete page post',

            'view-policy'=>'View Policies',
            'create-policy'=>'Create  Policy',
            'edit-policy'=>'Edit Policy',
            'delete-policy'=>'Delete  Policies',

            'view-blog'=>'View blog post',
            'create-blog'=>'Create  blog post',
            'edit-blog'=>'Edit blog post',
            'delete-blog'=>'Delete  blog post',


            'view-faq'=>'View FAQs',
            'create-faq'=>'Create  FAQs',
            'edit-faq'=>'Edit FAQs',
            'delete-faq'=>'Delete  FAQs',

        ];

        $data[6] = [
            'view-quick-service'=>'View quick nservice',
            'create-quick-service'=>'Create quick service',
            'edit-quick-service'=>'Edit quick service',
            'delete-quick-service'=>'Delete quick service',
        ];

        $data[7] = [
            'view-testimonial'=>'View quick Testimonials',
            'create-testimonial'=>'Create Testimonials',
            'edit-testimonial'=>'Edit Testimonials',
            'delete-testimonial'=>'Delete Testimonials',
        ];

        $data[8] = [
            'view-customer'=>'View Customers',
            'create-customer'=>'Create customer',
            'edit-customer'=>'Edit customer',
            'delete-customer'=>'Delete customer',

            'view-staff'=>'View Staffs',
            'create-staff'=>'Create Staffs',
            'edit-staff'=>'Edit Staffs',
            'delete-staff'=>'Delete Staffs',

            'view-staff-dept'=>'View Staff department',
            'create-staff-dept'=>'Create Staff department',
            'edit-staff-dept'=>'Edit Staff department',
            'delete-staff-dept'=>'Delete Staff department',

            'view-staff-role'=>'View Staff access labels / role',

            'view-supplier'=>'View Suppliers',
            'create-supplier'=>'Create Suppliers',
            'edit-supplier'=>'Edit Suppliers',
            'delete-supplier'=>'Delete Suppliers',

            'view-admin'=>'View Administrators',
            'create-admin'=>'Create Administrators',
            'edit-admin'=>'Edit Administrators',
            'delete-admin'=>'Delete Administrators',

            'view-access-label'=>'View user types/ Access labels',
        ];

        $data[9] = [
            'system-settings'=>'System Settings',
            'quick-settings'=>'Quick Settings',

            'view-social-media'=>'View Social Media',
            'create-social-media'=>'Create Social Media',
            'edit-social-media'=>'Edit Social Media',
            'delete-social-media'=>'Delete Social Media',

            'view-currency'=>'View System currency',
            'create-currency'=>'Create System currency',
            'edit-currency'=>'Edit System currency',
            'delete-currency'=>'Delete System currency',

            'order-setup'=>'Order Setup',
            'mail-config'=>'eMail Configuration',
        ];

        $data[10] = [
            'view-payment-method'=>'View Payment methods',
            'eidt-payment-method'=>'Edit Payment methods',

            'view-payment-type'=>'View Paymlen types',
            'create-payment-type'=>'Create Paymlen types',
            'edit-payment-type'=>'Edit Paymlen types',
            'delete-payment-type'=>'delete Paymlen types',
        ];

        $data[11] = [
            'view-area'=>'View Area lsit',

            'create-division'=>'Create Division',
            'edit-division'=>'Edit Division',
            'delete-division'=>'Delete Division',

            'create-district'=>'Create District',
            'edit-district'=>'Edit District',
            'delete-district'=>'Delete District',

            'create-city'=>'Create Cities',
            'edit-city'=>'Edit Cities',
            'delete-city'=>'Delete Cities',

            'view-zone'=>'View Delivery zone',
            'create-zone'=>'Create Delivery zone',
            'edit-zone'=>'Edit Delivery zone',
            'delete-zone'=>'Delete Delivery zone',

            'add-city-into-zone'=>'Assign cities into Delivery zone',
        ];

        $data[12] = [
            'view-outlet'=>'View outlet lsit',
            'create-outlet'=>'Create outlet',
            'edit-outlet'=>'Edit outlet',
            'delete-outlet'=>'Delete outlet',
        ];

        $data[13] = [
            'view-career'=>'View career lsit',
            'create-career'=>'Create career',
            'edit-career'=>'Edit career',
            'delete-career'=>'Delete career',
            'career-applicants'=>'Career applicants',
        ];

        $data[14] = [
            'report-export'=>'Report export',
            'area-wize-order-view'=>'Area wize order report',
            'addToCart-report-view'=>'Add to card report',
            'customer-report-view'=>'Customer report views',
            'order-duration-view'=>'Customer order duration views',
            'order-progress-report-view'=>'Order progress report views',
           
        ];


      


        for($i=1; $i<14; $i++){
            $dataValue = array();
            foreach($data[$i] as $key => $value){
                $dataValue = ['permission_label_id'=>$i,'origin'=>$key,'name'=>$value];
                if( Permission::where($dataValue)->count() <1){
                    Permission::create($dataValue);
                }
                
                // print_r($dataValue);
                // // echo 'i='.$i.' '.$key . " : " . $value ;
                // echo "<br><br/>";
            }
        }
    }


}
