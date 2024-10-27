<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Courier_representative;
use Illuminate\Http\Request;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Validator;

class CourierManController extends Controller
{
    function index(Request $request){
        if($request->draw){
            return datatables()::of(Courier_representative::orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('modify', function ($row) {
                $data = '<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">';
                if(check_access('edit-courier-representative')){
                    $data .= '<button type="button" class="btn btn-info btn-sm edit" id="'.$row->id.'"><span class="feather icon-edit"></span></button>';
                }
                if(check_access('delete-courier-representative')){
                    $data .= ' <button type="button" class="btn btn-danger btn-sm delete" id="'.$row->id.'"><span class="feather icon-trash"></span></button>';
                }
                $data.= '</div>'; return $data;
            })
            ->editColumn('photo', function($row){
                return '<img style="max-width:40px" src="'.url('storage/'.$row->photo).'">';
            })
            ->editColumn('company', function($row){
                return $row->courier_company->name;
            })

            ->editColumn('status', function($row){
                if($row->status=='1') return '<span class="badge badge-success">Active</span>';
                else return  '<span class="badge badge-danger">Inactive</span>';
            })

            ->rawColumns(['photo','company','status','modify'])->make(true);
        }
        return view('common.courier.representative.index');
    }

    function ready_to_ship(){
        return view('common.courier.ready-to-ship.index');
    }


    public function store(Request $request)
    {
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = ['courier_company_id'=>$request->courier_company, 'name'=>$request->name, 'phone'=>$request->phone,'address'=>$request->address,  'status'=>$request->status];

        $courier_representative = Courier_representative::create($data);

        $this->storeImage($courier_representative);
        return response()->json(['success' => 'Representative hasn been created successfully!']);
    }

    public function show(Courier_representative $courier_representative){
        return Courier_representative::find($courier_representative->id);
    }


    public function update(Request $request,Courier_representative $courier_representative)
    {
        $validator = $this->fields($courier_representative->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = ['courier_company_id'=>$request->courier_company, 'name'=>$request->name, 'phone'=>$request->phone,'address'=>$request->address,  'status'=>$request->status];

        $courier_representative->update($data);
        $this->storeImage($courier_representative,'update');

        return response()->json(['success' => 'The Representative hasn been updated successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'courier_company'=>'required', 'name'=>'required',
            'phone'=>'required|unique:courier_representatives,phone,'.$id,
            'photo'=>'sometimes|nullable|image',
            'address'=>'sometimes|nullable',
            'status'=>'required',
        ]); return $validator;
    }

    function storeImage($courier_representative,$type=null){
        if (request()->has('photo')) {
            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = time().".".$mime;
            $image = Image::make($fieldFile)->resize(300, 300);
            Storage::disk('public')->put("images/courier/".$imageName, (string) $image->encode());
            $courier_representative->update(['photo'=>"images/courier/".$imageName]);
            if ($type=='update' && request()->oldPhoto !='images/thumbs_photo.png') {
                \File::delete(public_path('storage/'.request()->oldPhoto));
            }
        }
    }

    public function destroy(Group $group)
    {
        try {
            if(\file_exists(public_path('storage/').$group->photo) && $group->photo !='images/thumbs_photo.png'){
                \File::delete(public_path('storage/').$group->photo);
            }
            $group->delete();
            return response()->json(['success' => 'Group hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }
}
