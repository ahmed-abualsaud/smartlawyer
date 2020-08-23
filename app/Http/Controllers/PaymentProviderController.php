<?php

namespace App\Http\Controllers;

use App\Cause;
use App\Consultation;
use App\Offer;
use App\PaginationEngine;
use App\Payment;
use App\User;
use App\UserDeviceToken;
use Illuminate\Http\Request;
use App\Country;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

define('SECRET_API_KEY', env('SECRET_API_KEY'));
class PaymentProviderController extends Controller
{
    public function index($params)
    {

        $params = [
            'id'=>$params,
            'charge'=> null
        ];
        return view('payment.paypage', compact('params'));
    }
    public function paymentResponse(Request $request, $id)
    {
        $charge = $this->retrieveCharge($request['tap_id']);
        /*$params = [
            'id'=>$id,
            'charge'=> $charge
        ];*/

        $this->editPayment($charge, $id);

        return response()->json($charge,200);
    }
    public function editPayment($charge ,$offer_id)
    {
        $payment = [
            'offer_id'=>$offer_id,
            'customer_id'=>auth()->user()->id,
            'payment_id'=>$charge['reference']['payment'],
            'payment_status'=>1,
            'charge_token'=>$charge['source']['id']
        ];

        DB::table('payments')->insert($payment);
    }

    public function createCard($card)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/tokens",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"card\":{\"number\":".$card['number'].",\"exp_month\":".$card['exp_month'].",\"exp_year\":".$card['exp_year'].",\"cvc\":".$card['cvc'].",\"name\":\"".$card['name']."\",\"address\":{\"country\":\"".$card['country']."\",\"line1\":\"\",\"city\":\"".$card['city']."\",\"street\":\"\",\"avenue\":\"\"}},\"client_ip\":\"".$card['client_ip']."\"}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer ".SECRET_API_KEY,
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response()->json(['cURL_Error'=>$err]);
        } else {
            return json_decode($response,true);
        }
    }
    public function verifyCard($charge)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/card/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"currency\":\"".$charge['currency']."\",\"threeDSecure\":true,\"save_card\":false,\"metadata\":{\"udf1\":\"test1\",\"udf2\":\"test2\"},\"customer\":{\"first_name\":\"".$charge['first_name']."\",\"middle_name\":\"\",\"last_name\":\"\",\"email\":\"".$charge['email']."\",\"phone\":{\"country_code\":\"".$charge['country_code']."\",\"number\":\"".$charge['number']."\"}},\"source\":{\"id\":\"".$charge['source_id']."\"},\"redirect\":{\"url\":\"".env('MERCHANT_DOMAIN')."/payment/response\"}}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer sk_test_XKokBfNWv6FIYuTMg5sLPjhJ",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response()->json(['cURL_Error'=>$err]);
        } else {
            return json_decode($response,true);
        }
    }
    public function createCharge($charge, $id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/charges",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"amount\":".$charge['amount'].",\"currency\":\"".$charge['currency']."\",\"threeDSecure\":true,\"save_card\":false,\"description\":\"Test Description\",\"statement_descriptor\":\"Sample\",\"metadata\":{\"udf1\":\"test 1\",\"udf2\":\"test 2\"},\"reference\":{\"transaction\":\"txn_0001\",\"order\":\"ord_0001\"},\"receipt\":{\"email\":false,\"sms\":true},\"customer\":{\"first_name\":\"".$charge['first_name']."\",\"middle_name\":\"\",\"last_name\":\"\",\"email\":\"".$charge['email']."\",\"phone\":{\"country_code\":\"".$charge['country_code']."\",\"number\":\"".$charge['number']."\"}},\"source\":{\"id\":\"".$charge['source_id']."\"},\"post\":{\"url\":\"".env('MERCHANT_DOMAIN')."/payment/response\"},\"redirect\":{\"url\":\"".env('MERCHANT_DOMAIN')."/payment/response/".$id."\"}}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer ".SECRET_API_KEY,
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response()->json(['cURL_Error'=>$err]);
        } else {
            return json_decode($response,true);
        }
    }
    public function retrieveCharge($charge_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/charges/".$charge_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer ".SECRET_API_KEY
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response()->json(['cURL_Error'=>$err]);
        } else {
            return json_decode($response,true);
        }
    }

    public function pay(Request $request, $id)
    {
        $offer = Offer::where('id', $id)->first();

        $ip = $request->ip();
        $date = $request['expiration_date'];
        $dateArr = explode('/', $date);
        foreach ($dateArr as $item)
            trim($item);

        $card = [
            'number'=>(int)$request['card_number'],
            'exp_month'=>(int)$dateArr[0],
            'exp_year'=>(int)$dateArr[1],
            'cvc'=>(int)$request['cvv'],
            'name'=>$request['card_holder'],
            'country'=>Country::getCountryInfoByIP($ip)['country'],
            'city'=>Country::getCountryInfoByIP($ip)['city'],
            'client_ip'=>$ip
        ];
       // return $card;

       $card_token = $this->createCard($card, $id);
       if(isset($card_token['status']) && $card_token['status'] == 'fail')
           return response()->json(['card_creation_error'=>$card_token]);
       else
       {
           $charge = [
               'amount'=>$offer['price'],
               'currency'=>"SAR",
               'first_name'=>auth()->user()->name,
               'email'=>auth()->user()->email,
               'country_code'=>Country::getCountryCallingCodeByIP($ip),
               'number'=>auth()->user()->phone,
               'source_id'=>$card_token['id'],
           ];

          $verifier = $this->verifyCard($charge);
           if(isset($verifier['status']) &&($verifier['status'] == "VALID" || $verifier['status'] == "INITIATED"))
           {
               $charge_token = $this->createCharge($charge, (string)$id);
               if(isset($charge_token['transaction']['url']))
                   return redirect()->to($charge_token['transaction']['url']);
               else if(!(isset($charge_token['errors']['0']['code'])) && !(isset($charge_token['transaction']['url'])))
               {
                   /*$params = [
                       'id'=>$offer['id'],
                       'charge'=>$charge_token
                   ];*/

                   $this->editPayment($charge_token, $id);
                   //return view('payment.paypage', compact('params'));
                   return response()->json($charge_token,200);
               }
               else return response()->json(['card_charge_error'=>$charge_token]);
           }

           else
               return response()->json(['card_validation_error'=>$verifier]);
       }
    }

    public function showPayment()
    {
        $users = User::where('role', 'user')->get();
        $usercount = User::where('role', 'user')->count();
        $tableArray = [
            /*'headers'=>[
                'name'  => __('dashboard.username'),
                'email' => __('dashboard.email'),
                'number'=> __('dashboard.paymentnumber')
            ]*/
        ];
        for ($i = 0; $i < $usercount; $i++)
        {
            $userid = $users[(string)$i]['id'];
            $perusercount = Payment::where('customer_id', $userid)->count();
            $tableArray[(string)$i] = [
                'id'=>$userid,
                'name'=>$users[(string)$i]['name'],
                'email'=>$users[(string)$i]['email'],
                'number'=>$perusercount
            ];
        }
        /*$operationsArray = [
            '0'=>[
                'constrains'=>['number<>0'],
                'operations'=>['<td width="10%">
                                        <a href="{{route(\'listPaymentsPerUser\', $user[\'id\'])}}"
                                           class="badge badge-secondary btn-customize">{{ __(\'dashboard . showpayments\') }}</a>
                                    </td>']
            ]
        ];*/
        $paginationEngine = PaginationEngine::class;
        $counter = 1;
        return view('payment.showtoadmin', compact('paginationEngine','tableArray', 'counter'));
    }

    public function getPage(Request $request)
    {
        return PaginationEngine::getPage($request);
    }
    public function perUserPayments($id)
    {
        $nums = Payment::where('customer_id', $id)->count();
        $payments = Payment::where('customer_id', $id)->get();
        $details = [];
        for ($i = 0; $i < $nums; $i++)
        {
            $offer = Offer::where('id', $payments[(string)$i]['offer_id'])->first();
            $user = User::where('id', $payments[(string)$i]['customer_id'])->first();
            $lawyer = User::where('id', $offer['lawyer_id'])->first();
            $cause = Cause::where('user_id', $user['id'])->where('lawyer_id', $lawyer['id'])->first();
            if(!isset($cause))
                $cause['number'] = 'nothing';
            $details[(string)$i] = [
                'cause_number'=>$cause['number'],
                'user_name'=>$user['name'],
                'lawyer_name'=>$lawyer['name'],
                'price'=>$offer['price'],
                'payment_id'=>$payments[(string)$i]['payment_id'],
                'payment_time'=>$payments[(string)$i]['updated_at']
            ];
        }
        $counter = 0;
        return view('payment.payments_per_user', compact('details', 'counter'));
    }

    public function listPayments()
    {
        $lawyer = User::where('id', auth()->user()->id)->first();
        $cause = Cause::where('lawyer_id', $lawyer['id'])->get();
        $causeCount = Cause::where('lawyer_id', $lawyer['id'])->count();
        $details = [];
        for ($i = 0; $i < $causeCount; $i++)
        {
            $payments = Payment::where('customer_id', $cause[(string)$i]['user_id'])->get();
            $paymentCount = Payment::where('customer_id', $cause[(string)$i]['user_id'])->count();

            for ($j = 0; $j < $paymentCount; $j++)
            {
                $offer = Offer::where('id', $payments[(string)$j]['offer_id'])->first();
                if($offer['lawyer_id'] == $lawyer['id'])
                {
                    $user = User::where('id', $payments[(string)$j]['customer_id'])->first();
                    $details[(string)$i] = [
                        'cause_number'=>$cause[(string)$i]['number'],
                        'user_name'=>$user['name'],
                        'lawyer_name'=>$lawyer['name'],
                        'price'=>$offer['price'],
                        'payment_id'=>$payments[(string)$i]['payment_id'],
                        'payment_time'=>$payments[(string)$i]['updated_at']
                    ];
                }
                //return response()->json($offer,200); pagination

            }
        }
        $counter = 0;
        return view('payment.show_to_office', compact('details', 'counter'));
    }
    public function toPayPageAPI(Request $request)
    {
        $path = '/payment/index'.(string)$request['offer_id'];
        return redirect()->to($path);
    }
}
