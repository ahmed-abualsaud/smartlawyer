<?php

namespace App\Http\Controllers\API;

use App\Cause;
use App\Consultation;
use App\Http\Controllers\HelperController;
use App\Offer;
use App\User;
use App\UserDeviceToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use VMdevelopment\TapPayment\Facade\TapPayment;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {

        try{
            $offer = Offer::where('id',$request->offer_id)->first();
            $url = url('/payment-response');
            $payment = TapPayment::createCharge();
            $payment->setCustomerName(auth()->user()->name);
            $payment->setCustomerPhone('00966' , auth()->user()->phone);
            $payment->setDescription( "Accept of with number ".$offer->id );
            $payment->setAmount( $offer->price );
            $payment->setCurrency( "KWD" );
            $payment->setSource( "src_kw.knet" );
            $payment->setRedirectUrl($url);
            $invoice = $payment->pay();
            // update payment id for offer
            $offer->payment_id = $invoice->getId();
            $offer->update();

        } catch( \Exception $exception )
        {
            return response()->json(['msg'=>__('dashboard.failed_request')],402);
        }
        return response()->json(['payment_url'=>$invoice->getPaymetUrl()],200);
    }

    // redirect response after pay
    public function paymentResponse(Request $request)
    {

        $offer = Offer::where('payment_id',$request->query()['tap_id'])->first();

        if($request->session()->token()){
            $lawyer = User::find($offer->lawyer_id);
            if($offer->offerable_type == "App\Cause"){
                $offerType = Cause::where('id',$offer->offerable_id)->first();
                $type = __('dashboard.cause');
                $number = $offerType->number;
            }else{
                $offerType = Consultation::where('id',$offer->offerable_id)->first();
                $type = __('dashboard.consultation');
                $number = $offerType->number;
            }
            $title = __('dashboard.payment_title');
            $body = __('dashboard.payment_body',['number'=>$number,'type'=>$type]);
            $tokens = UserDeviceToken::where('user_id',$lawyer->id)->pluck('device_token');
            foreach ($tokens as $token){
                HelperController::sendFireBaseNotification($title,$body,$token);
            }
            $offer->update(['payment_token'=>$request->session()->token(),'payment_status'=>1]);
            $msg = __('dashboard.payment_success_with_number',['token'=>$request->session()->token()]);
        }else{
            $msg = __('dashboard.failed_request');
        }
        return view('payment_success',compact('msg'));
    }
}
