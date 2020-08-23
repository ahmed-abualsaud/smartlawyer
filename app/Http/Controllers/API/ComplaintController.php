<?php

namespace App\Http\Controllers\API;

use App\Cause;
use App\CauseAttachment;
use App\Complaint;
use App\ComplaintReply;
use App\Events\RepliesEvent;
use App\Http\Controllers\HelperController;
use App\Http\Requests\API\ComplaintRequest;
use App\Http\Requests\API\ReplyComplaintRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ComplaintRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['user_id'] = $request->user()->id;
            $data['number'] = mt_rand(10000,10000000000);
            // store new compliant
            Complaint::create($data);
            DB::commit();
            return response()->json(['msg' => [__('dashboard.created_successfully')]],200);
        }catch (\Exception $e){
            DB::rollBack();
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('dashboard.failed_request')]],403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        try {
            if($id === "list"){
                $data = Complaint::where('user_id',$request->user()->id)->orderBy('created_at','desc')->paginate(10);
            }else{
                $data = Complaint::with('replies','replies.user')
                    ->where('user_id',$request->user()->id)->where('id',$id)->first();
            }
            return response()->json($data,200);
        }catch (\Exception $e){
            \Log::debug($e->getMessage());
            $data = null;
            return response()->json($data,200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ComplaintRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $complaintId = $request->complaint_id;
            $data['user_id'] = $request->user()->id;
            // update complaint
            unset($data['complaint_id']);
            Complaint::where('id',$complaintId)->update($data);
            DB::commit();
            return response()->json(['msg' => [__('dashboard.updated_successfully')]],200);
        }catch (\Exception $e){
            DB::rollBack();
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('dashboard.failed_request')]],403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Complaint $complaint)
    {
        try {
            DB::beginTransaction();
            $complaint->delete();
            DB::commit();
            return response()->json(['msg' => [__('dashboard.deleted_successfully')]],200);
        }catch (\Exception $e){
            DB::rollBack();
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('dashboard.failed_request')]],403);
        }
    }

    /**
     * Reply to the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reply(ReplyComplaintRequest $request)
    {
        try {
            DB::beginTransaction();
            $reply = ComplaintReply::create([
                'complaint_id' => $request->complaint_id,
                'reply_text' => $request->reply_text,
                'user_id' => $request->user()->id
            ]);
            DB::commit();
            event(new RepliesEvent($reply));

            return response()->json(['msg' => [__('dashboard.created_successfully')]],200);
        }catch (\Exception $e){
            DB::rollBack();
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('dashboard.failed_request')]],403);
        }
    }

    /**
     * Remove Reply the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteReply($id,Request $request)
    {
        try {
            DB::beginTransaction();
            $reply = ComplaintReply::where('id',$id)->first();
            if($reply->user_id != $request->user()->id){
                return response()->json(['msg' => [__('dashboard.not_your_reply')]],200);
            }
            $reply->delete();
            DB::commit();
            return response()->json(['msg' => [__('dashboard.deleted_successfully')]],200);
        }catch (\Exception $e){
            DB::rollBack();
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('dashboard.failed_request')]],403);
        }
    }


}
