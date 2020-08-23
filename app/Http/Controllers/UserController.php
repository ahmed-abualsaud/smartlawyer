<?php

namespace App\Http\Controllers;

use App\FreeCause;
use App\Media;
use App\User;
use App\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    //
    // return users view
    public function index(){
        if(auth()->user()->role == "admin"){
            return view('users.index');
        }elseif (auth()->user()->role =="office" && auth()->user()->office_id == 0){
            return view('employees.index');
        }
    }

    // fetch users for datatable
    public function fetchUsers()
    {
        if(auth()->user()->role == "admin"){
            $rows = User::where('role', 'user')->get();
        }elseif (auth()->user()->role =="office" && auth()->user()->office_id == 0){
            $rows = User::where('role', 'office')->where('id',auth()->id())->orWhere('office_id',auth()->id())->get();
        }

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
            ->addColumn('status', function ($row) {
                if($row->status == 0){
                    return __('dashboard.not_active');
                }else{
                    return __('dashboard.active');
                }
            })
            ->addColumn('avatar', function ($row) {
                if($row->avatar){
                    return '<img src="'.$row->avatar.'" style="width:30px;">';
                }else{
                    return '<img src="https://placeimg.com/30/30/nature" style="width:30px;">';
                }
            })
            ->addColumn('action', function ($row) {
                $status = $row->status == 1 ? 0 : 1;
                $color = $row->status == 1 ? "red" : "green";
                $title = $row->status == 1 ? "do_not_active" : "do_active";
                $btn = '';
                if(auth()->user()->role == "office" && auth()->user()->office_id == 0){
                    $btn = '<a class="btn action-btn" href="/users/update-employee/'.$row->id.'">'
                        .'<span class="fa fa-pencil" title="'.__('dashboard.update_employee').'"></span></a>
                        <a class="btn action-btn" href="/users/log/'.$row->id.'">'
                        .'<span class="fa fa-clock-o" title="'.__('dashboard.log').'"></span></a>';
                }
                return '<a class="btn action-btn" onclick=\'changeStatus('.$row->id.','.$status.')\'
                    title="'.__('dashboard.'.$title).'"><span class="fa fa-hand-paper-o" style="color: '.$color.'"></span></a>'.$btn;
            })
            ->rawColumns(['action', 'avatar'])
            ->make(true);
    }

    // change status users
    public function changeStatus(Request $request){
        try {
            User::where('id',$request->id)->update(['status' => $request->status]);
            $msg = $request->status == 0 ? __('dashboard.not_active_successfully') : __('dashboard.active_successfully');
            return response()->json(['status'=>true,'msg'=>$msg]);
        }catch (\Exception $e){
            return response()->json(['status'=>false,'msg'=>__('dashboard.failed_request')]);
        }
    }

    // store new employee
    public function storeEmployee(Request $request){
        try {
            validator($request->all(),[
                'name' => 'required|min:1|max:250|string',
                'email' => 'required|email|unique:users,email,',
                'phone' => 'required|string|max:25',
                'national_id' => 'required|unique:users,national_id,',
                'password' => 'required',
                'address' => 'required',
            ]);

            DB::beginTransaction();
            #Create new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'address' => $request->address,
                'bio' => $request->bio,
                'experience' => $request->experience,
                'role' => 'office',
                'office_id' => auth()->id(),
                'status' => 0,
            ]);

            #create access token for user
            $user = User::where('id',$user->id)->first();
            // store file if user is office
            if($request->has('avatar')){
                $avatarName = time().'.'.$request->avatar->getClientOriginalExtension();
                \Storage::disk('local')->putFileAs('/profiles/', $request->avatar, $avatarName);
                $user->avatar = 'profiles/'.$avatarName; // update user avatar
                $user->update();
            }
            $user->sendEmailVerificationNotification();

            DB::commit();
            toastr()->success(__('dashboard.verify_email'));
            return redirect()->route('users');
        }catch (\Exception $e) {
            DB::rollback();
            \Log::debug($e->getMessage());
            toastr()->success("__('messages.verify_email')");
            return back();
        }
    }

    // return update form view
    public function updateForm($id){
        $user = User::find($id);
        return view('employees.edit',compact('user'));
    }

    // store new employee
    public function updateEmployee(Request $request){
        try {
            validator($request->all(),[
                'id' => 'required|min:1|max:250|exists:users,id',
                'name' => 'required|min:1|max:250|string',
                'email' => 'required|email|unique:users,email,'.$request->id,
                'phone' => 'required|string|max:25',
                'national_id' => 'required|unique:users,national_id,'.$request->id,
                'address' => 'required',
            ]);

            DB::beginTransaction();
            #Update Employee
            $user = User::where('id',$request->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'address' => $request->address,
                'bio' => $request->bio,
                'experience' => $request->experience,
            ]);

            if($request->has('password')){
                User::where('id',$request->id)->update([
                    'password' => bcrypt($request->password),
                ]);
            }
            $user = User::where('id',$request->id)->first();
            // store file if user is office
            if($request->has('avatar')){
                $avatarName = time().'.'.$request->avatar->getClientOriginalExtension();
                \Storage::disk('local')->putFileAs('/profiles/', $request->avatar, $avatarName);
                $user->avatar = 'profiles/'.$avatarName; // update user avatar
            }
            $user->update();

            DB::commit();
            toastr()->success(__('dashboard.updated_successfully'));
            return redirect()->route('users');
        }catch (\Exception $e) {
            dd($e);
            DB::rollback();
            \Log::debug($e->getMessage());
            toastr()->success("__('messages.failed_request')");
            return back();
        }
    }

    // return users view
    public function log($id){
        $user = User::find($id);
        if((is_null($user) || $user->office_id != auth()->id()) && $user->id != auth()->id())
            abort(404);
        return view('employees.log',compact('user'));
    }

    // fetch users for datatable
    public function fetchLog($id)
    {
        $rows = UserLog::where('user_id', $id)->get();

        return DataTables::of($rows)
            ->addColumn('date', function ($row) {
                return $row->date;
            })
            ->addColumn('from', function ($row) {
                return $row->from;
            })
            ->addColumn('to', function ($row) {
                return $row->to;
            })
            ->rawColumns([])
            ->make(true);
    }

    // update profile
    public function updateProfile(Request $request){
        try {
            validator($request->all(),[
                'national_id' => 'required|unique:users,national_id,'.$request->id,
            ]);

            DB::beginTransaction();
            #Update Employee
            $user = User::where('id',auth()->id())->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'address' => $request->address,
                'bio' => $request->bio,
                'experience' => $request->experience,
                'casecount' => $request->casecount
            ]);

            if($request->has('password') && !is_null($request->password)){
                User::where('id',auth()->id())->update([
                    'password' => bcrypt($request->password),
                ]);
            }
            $user = User::where('id',auth()->id())->first();
            // store file if user is office
            if($request->has('avatar') && !is_null($request->avatar)){
                $avatarName = time().'.'.$request->avatar->getClientOriginalExtension();
                \Storage::disk('local')->putFileAs('/profiles/', $request->avatar, $avatarName);
                User::where('id',auth()->id())->update([
                    'avatar' => 'profiles/'.$avatarName
                ]);
            }

            DB::commit();
            toastr()->success(__('dashboard.updated_successfully'));
            return redirect()->route('profile');
        }catch (\Exception $e) {
            dd($e);
            DB::rollback();
            \Log::debug($e->getMessage());
            toastr()->success("__('messages.failed_request')");
            return back();
        }
    }

    // show to admin
    public function ShowToAdmin()
    {
        $users = User::Where('role', 'user')->get();
        $counter = 1;
        return view('freecauses.showtoadmin', compact('users', 'counter'));
    }
    public function getCaseToAdmin($id)
    {
        $cases = FreeCause::where('user_id', $id)->get();

        $counter = 1;
        return view('freecauses.getusercases', compact('cases', 'counter'));
    }
}
