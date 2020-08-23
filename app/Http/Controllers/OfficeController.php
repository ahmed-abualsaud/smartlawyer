<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OfficeController extends Controller
{
    // return offices view
    public function index(){
        return view('offices.index');
    }

    // fetch offices for datatable
    public function fetchOffices()
    {
        $rows = User::where('role', 'office')->get();

        return DataTables::of($rows)
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('phone', function ($row) {
                return $row->phone;
            })
            ->addColumn('national_id', function ($row) {
                return $row->national_id;
            })
            ->addColumn('bio', function ($row) {
                return $row->bio;
            })
            ->addColumn('address', function ($row) {
                return $row->address;
            })
            ->addColumn('experience', function ($row) {
                return $row->experience;
            })
            ->addColumn('office_id', function ($row) {
                return $row->office;
            })
            ->addColumn('status', function ($row) {
                if($row->status == 0){
                    return __('dashboard.not_active');
                }else{
                    return __('dashboard.active');
                }
            })
            ->addColumn('avatar', function ($row) {
                if($row->avatar){
                    return '<img src="'.$row->image.'" style="width:30px;">';
                }else{
                    return '<img src="https://placeimg.com/30/30/nature" style="width:30px;">';
                }
            })
            ->addColumn('action', function ($row) {
                    $status = $row->status == 1 ? 0 : 1;
                    $color = $row->status == 1 ? "red" : "green";
                    $title = $row->status == 1 ? "do_not_active" : "do_active";
                    return '<a class="btn action-btn" onclick=\'changeStatus('.$row->id.','.$status.')\'
                    title="'.__('dashboard.'.$title).'"><span class="fa fa-hand-paper-o" style="color: '.$color.'"></span></a>';
            })
            ->rawColumns(['action', 'avatar'])
            ->make(true);
    }

    // change status office
    public function changeStatus(Request $request){
        try {
            User::where('id',$request->id)->update(['status' => $request->status]);
            $msg = $request->status == 0 ? __('dashboard.not_active_successfully') : __('dashboard.active_successfully');
            return response()->json(['status'=>true,'msg'=>$msg]);
        }catch (\Exception $e){
            return response()->json(['status'=>false,'msg'=>__('dashboard.failed_request')]);
        }
    }
}
