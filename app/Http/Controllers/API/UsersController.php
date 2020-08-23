<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\RegisterRequest;
use App\Http\Requests\API\UpdateProfileRequest;
use App\Media;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class UsersController extends Controller
{

    /**
     * Update profile
     *
     * @param  [object] request
     * @return [object] user data
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            DB::beginTransaction();
            #Update user
            $user = User::find($request->user()->id);
            // update user data
            $user->name = $request->name;
            $user->email = $request->email;
            $user->national_id = $request->national_id;
            $user->phone = $request->phone;
            $user->bio = $request->bio;
            $user->address = $request->address;

            // store file if user is office
            if($request->avatar){
                $avatarName = time().'.'.request()->avatar->getClientOriginalExtension();
                \Storage::disk('local')->putFileAs('/profiles/', $request->avatar, $avatarName);
                if(!is_null($user->getOriginal()['avatar'])){
                    $image_path = public_path().'/storage/'.$user->getOriginal()['avatar'];
                    unlink($image_path);
                }
                $user->avatar = 'profiles/'.$avatarName; // update user avatar
            }
            $user->update();
            DB::commit();
            return response()->json($user,200);

        }catch (\Exception $e) {
            DB::rollback();
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('messages.failed_request')]],403);
        }

    }

    /**
     * Update profile
     *
     * @param  [object] request
     * @return [object] user data
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        try {
            $data = User::find($request->user()->id);
            return response()->json($data,200);
        }catch (\Exception $e) {
            DB::rollback();
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('messages.failed_request')]],403);
        }

    }



}
