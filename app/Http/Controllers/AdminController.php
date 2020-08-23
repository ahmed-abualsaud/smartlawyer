<?php

namespace App\Http\Controllers;

use App\SupplierDept;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use App\City;
use DB;
use Auth;
use Image;

class AdminController extends Controller
{
    // return employees view
    public function index(){
        return view('employees.index');
    }

    // fetch employees for datatable
    public function fetchAdmins()
    {
        $admins = User::where('role', 'Admin')->get();

        return DataTables::of($admins)
            ->addColumn('name', function ($admin) {
                return $admin->name;
            })
            ->addColumn('email', function ($admin) {
                return $admin->email;
            })
            ->addColumn('mobile', function ($admin) {
                return $admin->mobile;
            })
            ->addColumn('phone', function ($admin) {
                return $admin->phone;
            })
            ->addColumn('whatsapp', function ($admin) {
                return $admin->whatsapp_number;
            })
            ->addColumn('address', function ($admin) {
                return $admin->address;
            })
            ->addColumn('city', function ($admin) {
                return $admin->city->name;
            })
            ->addColumn('status', function ($admin) {
                if($admin->status == 0){
                    return 'Not Active';
                }else{
                    return 'Active';
                }
            })
            ->addColumn('image', function ($admin) {
                if($admin->image){
                    return '<img src="'.$admin->image.'" style="width:30px;">';
                }else{
                    return '<img src="https://placeimg.com/30/30/nature" style="width:30px;">';
                }
            })
            ->addColumn('action', function ($admin) {

                if($admin->id != Auth::user()->id){
                    return '
                    <a class="btn action-btn" href=\'/admin/edit/'.$admin->id.'\'><span class="fa fa-pencil"></span></a>
                    <a class="btn action-btn" onclick=\'deleteAdmin('.$admin->id.')\'><span class="fa fa-trash-o"></span></a>';
                        ;
                }else{
                    return '
                    <a class="btn action-btn" href=\'/admin/edit/'.$admin->id.'\'><span class="fa fa-pencil"></span></a>';
                    ;
                }

            })
            ->rawColumns(['action', 'image'])
            ->make(true);
    }

    // return edit view
    public function editForm($id){
        $admin = User::find($id);
        $cities = City::get();
        return view('employees.edit',compact('admin','cities'));
    }

    // store edit
    public function storeEdit(Request $request){

        $rules = [
            'name' => 'required|string',
            'email' => 'required|string',
            'mobile' => 'required|string',
            'address' => 'required|string',
            'city_id' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $this->validate($request,$rules);

        if($request->has('status')){
            $request->merge(['status' => 1]);
        }else{
            $request->merge(['status' => 0]);
        }
        // upload image if exist
        if($request->hasFile('image')){
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $path = 'images/employees/'.$fileName;

            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->stream(); // <-- Key point
            \Storage::disk('public')->put('/images/employees'.'/'.$fileName, $img);
        }else{
            $user = User::find($request->id);
            $path = $user->image;
        }
        if(!is_null($request->password)){
            User::where('id',$request->id)->update(['password'=>bcrypt($request->password)]);
        }
        User::where('id',$request->id)->update($request->except(['id', '_token','password']));
        User::where('id',$request->id)->update(['image'=>$path]);



        \Session::flash('success', 'Admin data is updated successfully');
        return redirect()->route('admin.index');

    }

    // return add view
    public function addForm(){
        $cities = City::get();
        return view('employees.add',compact('cities'));
    }

    // store new admin
    public function storeAdding(Request $request){
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:6',
            'mobile' => 'required|string',
            'address' => 'required|string',
            'city_id' => 'required|numeric',
            'activity' => 'required|string',
        ];

        $this->validate($request,$rules);

        $new_admin = new User();
        $new_admin->name = $request->name;
        $new_admin->email = $request->email;
        $new_admin->password = bcrypt($request->password);
        $new_admin->phone = $request->phone;
        $new_admin->mobile = $request->mobile;
        $new_admin->whatsapp_number = $request->whatsapp_number;
        $new_admin->address = $request->address;
        $new_admin->city_id = $request->city_id;
        $new_admin->status = 1;
        $new_admin->image = 'user.jpg';
        $new_admin->activity = $request->activity;
        $new_admin->role = 'Admin';
        $new_admin->save();

        \Session::flash('success', 'Admin is created successfully');
        return redirect()->route('admin.index');
    }

    // delete admin
    public function delete(Request $request){
        User::where('id',$request->id)->delete();
        \Session::flash('success', 'Admin data is updated successfully');
        return redirect()->route('admin.index');
    }

}

