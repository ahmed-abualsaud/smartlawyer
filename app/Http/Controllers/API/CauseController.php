<?php

namespace App\Http\Controllers\API;

use App\Cause;
use App\CauseAttachment;
use App\Events\OffersEvent;
use App\Http\Controllers\HelperController;
use App\Http\Requests\API\AcceptOfferRequest;
use App\Http\Requests\API\CauseRequest;
use App\Http\Requests\API\UploadAttachmentRequest;
use App\Mail\AcceptationOfferMail;
use App\Offer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CauseController extends Controller
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
    public function store(CauseRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['user_id'] = $request->user()->id;
            // store new cause
            $cause = Cause::create($data);
            // store files of cause if uploaded
            if($request->has('attachments')){
                foreach ($data['attachments'] as $file){
                    $path = HelperController::storeFile($file,'causes');
                    CauseAttachment::create([
                        'cause_id' => $cause->id,
                        'file' => $path
                    ]);
                }
            }
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
    public function show($id,Request $request)
    {
        try {
            if($id == "list"){
                $data = Cause::where('user_id',$request->user()->id)->orderByDesc('created_at')->paginate(10);
            }elseif ($id == 0){
                $data = Cause::where('user_id',$request->user()->id)->orderByDesc('created_at')->where('is_public',0)->paginate(10);
            }else{
                $data = Cause::with(['attachments','offers','offers.lawyer:id,name','judicialHearing','messages'=>function($query) use ($request){
                    $query->where('receiver_id',$request->user()->id);
                },'messages.sender'])
                    ->where('user_id',$request->user()->id)->where('id',$id)->first();
            }

            return response()->json($data,200);
        }catch (\Exception $e){
            dd($e);
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
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $causeId = $request->cause_id;
            $data['user_id'] = $request->user()->id;
            // update cause
            // store files of cause if uploaded
            if($request->has('attachments')){
                foreach ($data['attachments'] as $file){
                    $path = HelperController::storeFile($file,'causes');
                    CauseAttachment::create([
                        'cause_id' => $causeId,
                        'file' => $path
                    ]);
                }
            }
            unset($data['cause_id']);
            unset($data['attachments']);
            if($request->is_public == 1 && $request->has('lawyer_id')){
                unset($data['lawyer_id']);
            }
            Cause::where('id',$causeId)->update($data);
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
    public function destroy(Cause $cause)
    {
        try {
            DB::beginTransaction();
            $cause->delete();
            DB::commit();
            return response()->json(['msg' => [__('dashboard.deleted_successfully')]],200);
        }catch (\Exception $e){
            DB::rollBack();
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('dashboard.failed_request')]],403);
        }
    }

    /**
     * Display the specified resource offers.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function offers($id,Request $request)
    {
        try {
            $data = Cause::with('offers','offers.lawyer:id,name')->where('id',$id)
                        ->where('user_id',$request->user()->id)->get();
            return response()->json($data,200);
        }catch (\Exception $e){
            \Log::debug($e->getMessage());
            $data = null;
            return response()->json($data,200);
        }
    }

    /**
     * Accept Offer.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptOffer(AcceptOfferRequest $request)
    {
        try {
            DB::beginTransaction();
            $offer = Offer::where('id',$request->offer_id)->first();

            // change status of cause
            $cause = Cause::where('id',$offer->offerable_id)->first();
            if($cause->status != 0){
                return response()->json(['msg' => [__('dashboard.cause_is_accepted_offer_before')] ],200);
            }

            if($cause->user_id != $request->user()->id){
                return response()->json(['msg' => [__('dashboard.not_your_cause')] ],200);
            }

            $cause->status = 1;
            $cause->is_public = 0;
            $cause->lawyer_id = $offer->lawyer_id;
            $cause->update();

            // change status of offer
            Offer::where('offerable_id',$offer->offerable_id)->where('offerable_type','App\Cause')
                    ->where('id','!=',$request->offer_id)->update(['status' => 2]);
            $offer->status = 1;
            $offer->update();

            // lawyer data
            $lawyer = User::where('id',$offer->lawyer_id)->first();

            // assign data for mail
            $data = [];
            $data['type'] = __('dashboard.cause');
            $data['number'] = $cause->number;
            // send mail to lawyer
//            Mail::to($lawyer->email)->send(new AcceptationOfferMail($data));
            event(new OffersEvent($offer));
            DB::commit();
            return response()->json(['msg' => [__('dashboard.offer_accepted_successfully')] ],200);
        }catch (\Exception $e){
            \Log::debug($e->getMessage());
            DB::rollBack();
            return response()->json(['msg' => [__('dashboard.failed_request')]],200);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAttachments(UploadAttachmentRequest $request)
    {
        try {
            DB::beginTransaction();
            $cause = Cause::where('number',$request->number)->first();
            if($cause->user_id != auth()->id())
                return response()->json(['msg' => [__('dashboard.not_your_cause')]],200);
            // store files of cause if uploaded
            if($request->has('attachments')){
                foreach ($request->attachments as $file){
                    $path = HelperController::storeFile($file,'causes');
                    CauseAttachment::create([
                        'cause_id' => $cause->id,
                        'file' => $path
                    ]);
                }
            }
            DB::commit();
            return response()->json(['msg' => [__('dashboard.uploaded_successfully')]],200);
        }catch (\Exception $e){
            dd($e);
            DB::rollBack();
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('dashboard.failed_request')]],403);
        }
    }

}
