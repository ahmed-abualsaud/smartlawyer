<?php

namespace App\Http\Controllers;

use App\Cause;
use App\Consultation;
use App\Message;
use App\User;
use App\UserDeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use function GuzzleHttp\Promise\all;

class MessageController extends Controller
{
    public function index($type,$id){
        if($type == 'cause'){
            $data = Cause::where('id',$id)->first();
            $model = 'App\Cause';
        }else{
            $data = Consultation::where('id',$id)->first();
            $model = 'App\Consultation';
        }

        return view('messages.inbox',compact('type','id'));
    }

    public function fetchMessages($type,$id){
        if($type == 'cause'){
            $data = Cause::where('id',$id)->first();
            $model = 'App\Cause';
        }else{
            $data = Consultation::where('id',$id)->first();
            $model = 'App\Consultation';
        }
        Message::where('messageable_id',$id)->where('messageable_type',$model)
                ->where('receiver_id', auth()->id())->update(['seen'=>1]);

        if(auth()->user()->role == "admin"){
            $rows = Message::with('sender','receiver')->where('messageable_id',$id)
                ->where('messageable_type',$model)
                ->orderByDesc('created_at')
                ->get();
        }else{
            $rows = Message::with('sender','receiver')->where('messageable_id',$id)
                ->where('messageable_type',$model)
                ->where(function ($query) {
                    $query->where('sender_id', auth()->id())
                        ->orWhere('receiver_id', auth()->id());
                })
                ->orderByDesc('created_at')
                ->get();
        }



        return DataTables::of($rows)
            ->addColumn('from', function ($row) {
                return $row->sender_id != auth()->id() ? $row->sender->name : 'انا';
            })
            ->addColumn('to', function ($row) {
                return $row->receiver_id != auth()->id() ? $row->receiver->name : 'انا';
            })
            ->addColumn('message', function ($row) {
                return $row->message;
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at;
            })
            ->make(true);

    }

//        try {
//
//            validator($request->all(),[
//                'related_cause_number' => 'required|string|exists:causes,number',
//                'old_judgment_text' => 'required|string',
//                'lawyer_id' => 'required|numeric',
//                'title' => 'required|string',
//                'stage_name' => 'required|string',
//                'court_name' => 'required|string',
//                'number' => 'required|string',
//                'stage_date' => 'required|date',
//            ]);
//
//            DB::beginTransaction();
//
//            $cause = Cause::where('number',$request->related_cause_number)->first();
//
//            // store new stage
//            $stage = new Cause();
//            $stage->related_cause_number = $request->related_cause_number;
//            $stage->title = $request->title;
//            $stage->number = $request->number;
//            $stage->judgment_date = $request->stage_date;
//            $stage->judgment_text	 = $request->judgment_text;
//            $stage->court_name = $request->court_name;
//            $stage->judicial_chamber = $request->judicial_chamber;
//            $stage->consideration_text = $request->consideration_text;
//            $stage->type = $request->stage_name;
//            $stage->is_public = 0;
//            $stage->lawyer_id = $request->lawyer_id;
//            $stage->status = 1;
//            $stage->user_id = $cause->user_id;
//            $stage->save();
//            // store files of cause if uploaded
//            if($request->has('attachment')){
//                $path = HelperController::storeFile($request->attachment,'causes');
//                CauseAttachment::create([
//                    'cause_id' => $cause->id,
//                    'file' => $path
//                ]);
//            }
//            DB::commit();
//            toastr()->success(__('dashboard.created_successfully'));
//            return back();
//        }catch (\Exception $e){
//            dd($e);
//            DB::rollBack();
//            \Log::debug($e->getMessage());
//            toastr()->warning(__('dashboard.failed_request'));
//            return back();
//        }
//    }

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
            $getOldMessageSenderId = Message::where('messageable_id',$request->id)->where('messageable_type',$model)->first();
            if(!is_null($getOldMessageSenderId)){
                Message::where('messageable_id',$request->id)->where('messageable_type',$model)->update(['seen'=>1]);
            }
            $receiverId = isset($request->lawyer_id) ? $request->lawyer_id : $data->user_id;
            // store image
            Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $receiverId,
                'message' => $request->message,
                'messageable_type' => $model,
                'messageable_id' => $request->id
            ]);
            $title = __('dashboard.new_message',['from'=>auth()->user()->name,'type'=>__('dashboard.'.$request->type),'number'=>$data->number]);
            $body = $request->message;
            $tokens = UserDeviceToken::where('user_id',$data->user_id)->pluck('device_token');
            foreach ($tokens as $token){
                HelperController::sendFireBaseNotification($title,$body,$token);
            }
            DB::commit();
            return response()->json(['status'=>'success','msg' => [__('dashboard.send_successfully')]],200);
        }catch (\Exception $e){
            DB::rollBack();
            \Log::debug($e->getMessage());
            return response()->json(['status'=>'error','msg' => [__('dashboard.failed_request')]],403);
        }
    }

    // return all list
    public function list(Request $request){
        $messages = Message::with('receiver')->where('sender_id',auth()->id())
                            ->orderByDesc('created_at')->get();
        return response()->json(['messages' => $messages],200);
    }

    // return specific list
    public function specificList($receiver_id){
        $messages = Message::with('receiver')->where('sender_id',auth()->id())
            ->where('receiver_id',$receiver_id)->orderByDesc('created_at')->get();
        return response()->json(['messages' => $messages],200);
    }

    // return all list of specific cause or consultation
    public function messagesLists(Request $request){
        if($request->type == 1){
            $messages = Message::whereIn('sender_id',[auth()->id(),$request->lawyer_id])
                ->whereIn('receiver_id',[auth()->id(),$request->lawyer_id])
                ->where('messageable_id',$request->type_id)
                ->where('messageable_type',"App\Cause")
                ->orderByDesc('created_at')->paginate(10);
        }else{
            $messages = Message::whereIn('sender_id',[auth()->id(),$request->lawyer_id])
                                ->whereIn('receiver_id',[auth()->id(),$request->lawyer_id])
                                ->where('messageable_id',$request->type_id)
                                ->where('messageable_type',"App\Consultation")
                                ->orderByDesc('created_at')->paginate(10);
        }
        return response()->json(['messages' => $messages],200);
    }


}
