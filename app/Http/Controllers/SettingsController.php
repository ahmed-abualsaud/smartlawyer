<?php

namespace App\Http\Controllers;

use App\Cause;
use App\Consultation;
use App\Message;
use App\UserDeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Setting;
use App\User;

class SettingsController extends Controller
{
    // return setting view
    public function index(){
        $admin = User::where('role','admin')->first();
        $setting = Setting::query()->first();
        return view('settings.index',compact('setting', 'admin'));
    }

    // fetch settings data
    public function fetchSettings(){


        $rows = Setting::get();

        return DataTables::of($rows)
            ->addColumn('commission', function ($row) {
                return $row->commission;
            })
            ->addColumn('action', function ($row) {
                return '<a class="btn action-btn" onclick="updateSettings()" title="'.__('dashboard.update').'">
                        <span class="fa fa-pencil"></span></a>';
            })

            ->rawColumns(['action', 'status'])
            ->make(true);

    }

    // update settings
    public function updateSetting(Request $request){
        try {
            DB::beginTransaction();
            validator($request->all(),[
                'commission' => 'required|numeric',
            ]);
            // store image
            Setting::where('id',1)->update([
                'commission' => $request->commission,
            ]);
            DB::commit();
            return response()->json(['status'=>'success','msg' => [__('dashboard.update_successfully')]],200);
        }catch (\Exception $e){
            dd($e);
            DB::rollBack();
            \Log::debug($e->getMessage());
            return response()->json(['status'=>'error','msg' => [__('dashboard.failed_request')]],403);
        }
    }

    public function updateCaseCount()
    {
        request()->validate(['casecount' => 'required']);
        $casecount = (int) request('casecount');
        $userid = auth()->user()->id;
        User::where(['id'=> $userid, 'role' => 'admin'])->update(['casecount' => $casecount]);
        return back();
    }

}
