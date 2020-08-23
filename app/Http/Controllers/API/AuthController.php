<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\HelperController;
use App\Http\Requests\API\RegisterRequest;
use App\Http\Requests\API\ChangePasswordRequest;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\ResetPasswordRequest;
use App\Media;
use App\User;
use App\UserDeviceToken;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use Illuminate\Foundation\Auth\VerifiesEmails;

class AuthController extends Controller
{
    use SendsPasswordResetEmails,VerifiesEmails;

    /**
     * Register
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] national_id
     * @param  [string] role
     * @param  [file] file if role is office
     * @return [array] msg
     * @return [object] user data
     * @throws \SMartins\PassportMultiauth\Exceptions\MissingConfigException
     */
    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            #Create new user
            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'national_id' => $request->national_id,
                'phone' => $request->phone,
                'role' => $request->role,
                'status' => 1,
            ]);

            # create device token for user
            if($request->has('device_token') && $request->has('device_id'))
                self::storeOrUpdateDeviceToken($newUser->id,$request->device_token,$request->device_id);

            // store file if user is office
            $fileName = time().'.'.request()->file->getClientOriginalExtension();
            \Storage::disk('public')->putFileAs('files/commercial/', $request->file, $fileName);
            // store file path
            Media::create([
                'user_id' => $newUser->id,
                'file' => 'files/commercial/'.$fileName
            ]);

            #create access token for user
            $user = User::where('id',$newUser->id)->first();
            $user->sendEmailVerificationNotification();
            DB::commit();

            return response()->json(['msg' => [__('messages.verify_email')]],200);

        }catch (\Exception $e) {
            DB::rollback();
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('messages.failed_request')]],403);
        }

    }

    /**
     * Login
     *
     * @param  [string] name
     * @param  [string] password
     * @return [array] msg
     * @return [object] user data
     * @throws \SMartins\PassportMultiauth\Exceptions\MissingConfigException
     */
    public function login(LoginRequest $request)
    {
        try {
            #Check if user is authenticated or no
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $user = User::find(Auth::user()->id);
                if(is_null($user->email_verified_at)){
                    return response()->json(['msg'=>[__('messages.verify_email')]],401);
                }

                if($user->status == 0 || is_null($user->email_verified_at)){
                    return response()->json(['msg'=>[__('messages.email_is_inactive')]],401);
                }
                # create new or update device token
                if($request->has('device_token') && $request->has('device_id'))
                    self::storeOrUpdateDeviceToken($user->id,$request->device_token,$request->device_id);

                #create access token for user
                $token =  $user->createToken('smart')->accessToken;
                $user->access_token = $token;
            } else {
                return response()->json(['msg'=>[__('messages.incorrect_email_or_password')]],401);
            }

            return response()->json($user,200);

        }catch (\Exception $e) {
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('messages.failed_request')]],403);
        }

    }

    /**
     * Logout user (Revoke the token)
     *
     * @param  [string] token
     * @return [array] msg
     */
    public function logout(Request $request){

        try {
            $request->user()->token()->revoke();
            return response()->json([ 'msg' => [__('messages.success_request')]], 200);
        }catch (\Exception $e) {
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('messages.failed_request')]],403);
        }
    }

    /**
     * Create token password reset
     * @param  [string] email
     * @return [array] msg
     */
    public function reset(ResetPasswordRequest $request)
    {
        try {
            $this->sendResetLinkEmail($request);
            return response()->json(['msg' => [__('messages.reset_password')]],200);

        } catch (\Exception $e) {
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('messages.failed_request')]],403);
        }

    }

    /**
     * Change Password
     *
     * @param  [string] old_password
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [array] msg
     */
    public function changePassword(ChangePasswordRequest $request){

        try {
            DB::beginTransaction();
            $user = User::where('id',$request->user()->id)->first();
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->save();
            } else {
                return response()->json(['msg'=>[__('dashboard.old_password_is_incorrect')]],403);
            }
            DB::commit();
            return response()->json(['msg' => [__('dashboard.updated_successfully')]],200);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('dashboard.failed_request')]],403);
        }
    }

    // store device token
    public static function storeOrUpdateDeviceToken($userId,$deviceToken,$deviceId){
        $userDeviceToken = UserDeviceToken::where('user_id',$userId)->where('device_id',$deviceId)->first();
        if(is_null($userDeviceToken)){
            UserDeviceToken::create([
                'user_id' => $userId,
                'device_token' => $deviceToken,
                'device_id' => $deviceId
            ]);
        }else{
            UserDeviceToken::where('user_id',$userId)->where('device_id',$deviceId)->update([
                'device_token' => $deviceToken
            ]);
        }
        return true;
    }
}
