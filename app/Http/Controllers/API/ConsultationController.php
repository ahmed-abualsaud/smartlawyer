<?php

namespace App\Http\Controllers\API;

use App\Cause;
use App\CauseAttachment;
use App\Consultation;
use App\Http\Controllers\HelperController;
use App\Http\Requests\API\AcceptOfferRequest;
use App\Http\Requests\API\CauseRequest;
use App\Http\Requests\API\ConsultationRequest;
use App\Mail\AcceptationOfferMail;
use App\Offer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ConsultationController extends Controller
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
    public function store(ConsultationRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['user_id'] = $request->user()->id;
            // store new consultation
            Consultation::create($data);
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
        //
        try {
            if($id === "list"){
                $data = Consultation::where('user_id',$request->user()->id)->orderByDesc('created_at')->get();
            }else{
                $data = Consultation::with(['offers','offers.lawyer:id,name','messages'=>function($query) use ($request){
                    $query->where('receiver_id',$request->user()->id);
                },'messages.sender'])
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
    public function update(ConsultationRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $consultationId = $request->consultation_id;
            $data['user_id'] = $request->user()->id;
            // update cause
            unset($data['consultation_id']);
            Consultation::where('id',$consultationId)->update($data);
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
    public function destroy(Consultation $consultation)
    {
        try {
            DB::beginTransaction();
            $consultation->delete();
            DB::commit();
            return response()->json(['msg' => [__('dashboard.deleted_successfully')]],200);
        }catch (\Exception $e){
            DB::rollBack();
            \Log::debug($e->getMessage());
            return response()->json(['msg' => [__('dashboard.failed_request')]],403);
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
            $consultation = Consultation::where('id',$offer->offerable_id)->first();
            if($consultation->status != 0){
                return response()->json(['msg' => [__('dashboard.consultation_is_accepted_offer_before')] ],200);
            }

            if($consultation->user_id != $request->user()->id){
                return response()->json(['msg' => [__('dashboard.not_your_consultation')] ],200);
            }

            $consultation->status = 1;
            $consultation->update();

            // change status of offer
            Offer::where('offerable_id',$offer->offerable_id)->where('offerable_type','App\Consultation')
                ->where('id','!=',$request->offer_id)->update(['status' => 2]);
            $offer->status = 1;
            $offer->update();

            // lawyer data
            $lawyer = User::where('id',$offer->lawyer_id)->first();
            // assign data for mail
            $data = [];
            $data['type'] = __('dashboard.consultation');
            $data['number'] = $consultation->number;
            // send mail to lawyer
            Mail::to($lawyer->email)->send(new AcceptationOfferMail($data));
            DB::commit();
            return response()->json(['msg' => [__('dashboard.offer_accepted_successfully')] ],200);
        }catch (\Exception $e){
            \Log::debug($e->getMessage());
            DB::rollBack();
            return response()->json(['msg' => [__('dashboard.failed_request')]],200);
        }
    }
}
