<?php

namespace App\Http\Controllers\API;

use App\Cause;
use App\Consultation;
use App\Http\Controllers\HelperController;
use App\Message;
use App\UserDeviceToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    // store message
    public function sendMessage(Request $request){
        try {
            DB::beginTransaction();
            validator($request->all(),[
                'message' => 'required|string',
            ]);
            if($request->type == 'cause'){
                $data = Cause::where('id',$request->id)->first();
                $model = 'App\Cause';
            }else{
                $data = Consultation::where('id',$request->id)->first();
                $model = 'App\Consultation';
            }
            if($data->user_id != $request->user()->id){
                return response()->json(['status'=>'error','msg' => [__('dashboard.failed_request')]],403);
            }
            $getOldMessageSenderId = Message::where('messageable_id',$request->id)->where('messageable_type',$model)->first();
            if(!is_null($getOldMessageSenderId)){
                Message::where('messageable_id',$request->id)->where('messageable_type',$model)->update(['seen'=>1]);
            }
            // store image
            Message::create([
                'sender_id' =>  $request->user()->id,
                'receiver_id' => is_null($getOldMessageSenderId) ? $data->lawyer_id : $getOldMessageSenderId->sender_id,
                'message' => $request->message,
                'messageable_type' => $model,
                'messageable_id' => $request->id
            ]);
            DB::commit();
            return response()->json(['status'=>'success','msg' => [__('dashboard.send_successfully')]],200);
        }catch (\Exception $e){
            DB::rollBack();
            \Log::debug($e->getMessage());
            return response()->json(['status'=>'error','msg' => [__('dashboard.failed_request')]],403);
        }
    }

}
