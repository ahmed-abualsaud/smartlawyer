<?php

namespace App\Http\Controllers;

use App\Cause;
use App\Consultation;
use App\Message;
use App\Offer;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ConsultationController extends Controller
{
    // return consultations view
    public function index(){
        return view('consultations.index');
    }

    // fetch consultations for datatable
    public function fetchConsultations()
    {
        $rows = Consultation::with('user')->orderByDesc('created_at')->get();
        if(auth()->user()->role == "office"){
            $rows = Consultation::with('user')->where('is_publish',1)
                                ->where('status',0)->orderByDesc('created_at')->get();
        }

        return DataTables::of($rows)
            ->setRowId(function ($row) {
                return $row->id;
            })
            ->addColumn('title', function ($row) {
                return $row->title;
            })
            ->addColumn('number', function ($row) {
                return $row->number;
            })
            ->addColumn('address', function ($row) {
                return $row->address;
            })
            ->addColumn('details', function ($row) {
                return substr($row->details, 0, 100).'...';

            })
            ->addColumn('is_publish', function ($row) {
                return $row->is_publish == 1 ? __('dashboard.published') : __('dashboard.archive');
            })
            ->addColumn('user_id', function ($row) {
                return $row->user->name;
            })
            ->addColumn('status', function ($row) {
                switch ($row->status){
                    case 1:
                        $status = __('dashboard.in_progress');
                        $color = 'blue';
                        break;
                    case 2:
                        $status = __('dashboard.complete');
                        $color = 'green';
                        break;
                    default :
                        $status = __('dashboard.pending');
                        $color = 'red';
                        break;
                }
                return '<h5 style="color: '.$color.'">'.$status.'</h5>';
            })
            ->addColumn('action', function ($row) {
                if(auth()->user()->role == "office"){
                    $msgCount = Message::where('messageable_id',$row->id)
                        ->where('receiver_id', auth()->id())
                        ->where('messageable_type','App\Consultation')
                        ->where('seen',0)
                        ->count();
                    $class = $msgCount > 0 ? 'count' : '';
                    $btn = '<a class="btn action-btn" onclick="sendMessage('.$row->id.')" title="'.__('dashboard.send_message').'">
                        <span class="fa fa-telegram"></span></a><a class="btn action-btn '.$class.'" href="/messages/inbox/consultation/'.$row->id.'">'
                        .'<span class="fa fa-envelope" title="'.__('dashboard.inbox').'"></span></a>';
                    return '<a class="btn action-btn" href="/consultations/add-offer/'.$row->id.'">'
                        .'<span class="fa fa-plus" title="'.__('dashboard.add_offer').'"></span></a>
                        <a class="btn action-btn" href="/consultations/offers/'.$row->id.'">'
                        .'<span class="fa fa-money" title="'.__('dashboard.offers').'"></span></a>
                        '.$btn;
                }
                return '<a class="btn action-btn" href="/consultations/offers/'.$row->id.'">'
                    .'<span class="fa fa-money" title="'.__('dashboard.offers').'"></span></a>'.
                    '<a class="btn action-btn" onclick="deleteConsultation('.$row->id.')" title="'.__('dashboard.delete').'">
                        <span class="fa fa-close"></span></a>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    // delete consultation
    public function delete(Request $request){
        try {
            $cause = Consultation::where('id',$request->id)->first();
            if ($cause->status != 1){
                $cause->delete();
            }
            $msg = $cause->status == 1 ? __('dashboard.consultation_in_progress') : __('dashboard.deleted_successfully');
            return response()->json(['status'=>true,'msg'=>$msg]);
        }catch (\Exception $e){
            return response()->json(['status'=>false,'msg'=>__('dashboard.failed_request')]);
        }
    }

    // return offers view
    public function offers($id){
        return view('consultations.offers',compact('id'));
    }

    // fetch consultations offers for datatable
    public function fetchOffers($id)
    {
        if(auth()->user()->role == "admin"){
            $rows = Offer::where('offerable_id',$id)->where('offerable_type','App\Consultation')->get();
        }else{
            $lawyersIds = User::where('id',auth()->id())->orWhere('office_id',auth()->id())->pluck('id')->toArray();
            $rows = Offer::where('offerable_id',$id)->where('offerable_type','App\Consultation')
                ->whereIn('lawyer_id',$lawyersIds)->get();
        }

        return DataTables::of($rows)
            ->addColumn('lawyer_id', function ($row) {
                return $row->lawyer->name;
            })
            ->addColumn('price', function ($row) {
                return $row->price;
            })
            ->addColumn('description', function ($row) {
                return $row->description;
            })
            ->addColumn('status', function ($row) {
                switch ($row->status){
                    case 1:
                        $status = __('dashboard.accepted');
                        $color = 'green';
                        break;
                    case 2:
                        $status = __('dashboard.rejected');
                        $color = 'red';
                        break;
                    default :
                        $status = __('dashboard.pending');
                        $color = 'blue';
                        break;
                }
                return '<h5 style="color: '.$color.'">'.$status.'</h5>';
            })
            ->addColumn('lawyer_rate', function ($row) {
                return $row->lawyer_rate;
            })
            ->addColumn('user_rate', function ($row) {
                return $row->user_rate;
            })

            ->addColumn('action', function ($row) {
                if(auth()->user()->role == "office" && $row->status != 1){
                    return '<a class="btn action-btn" onclick="deleteOffer('.$row->id.')" title="'.__('dashboard.delete').'">
                        <span class="fa fa-close"></span></a>';
                }
                return '';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    // add offer view
    public function addOffer($id){
        $consultation = Consultation::find($id);
        if (is_null($consultation))
            abort(404);
        $lawyers = User::where('office_id',auth()->user()->id)->orWhere('id',auth()->user()->id)->get();
        return view('consultations.add_offer',compact('consultation','lawyers'));
    }

    // add offer to cause
    public function storeOffer(Request $request){

        validator($request->all(),[
            'consultation_id' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $checkLawyer = User::find($request->lawyer_id);
        if(!is_null($checkLawyer->office_id)){
            $lawyersIds = User::where('office_id',$checkLawyer->office_id)->pluck('id');
        }

        $checkHasOfferBefore = Offer::where('offerable_type','App\Consultation')->where('offerable_id',$request->consultation_id)
            ->where(function ($query) use ($lawyersIds,$checkLawyer) {
                $query->where('lawyer_id',$checkLawyer->office_id)
                    ->orWhereIn('lawyer_id',$lawyersIds);
            })->first();
        if(!is_null($checkHasOfferBefore)){
            toastr()->warning(__('dashboard.you_sent_offer_before_remove_other_offers_before_new'));
            return redirect()->back();
        }

        // store offer
        Offer::create([
            'offerable_id' => $request->consultation_id,
            'offerable_type' => 'App\Consultation',
            'price' => $request->price,
            'description' => $request->description,
            'lawyer_id' => $request->lawyer_id,
        ]);

        toastr()->success(__('dashboard.offer_sent_successfully'));
        return redirect()->back();
    }

    // delete offer
    public function deleteOffer(Request $request){
        try {
            $offer = Offer::where('id',$request->id)->first();
            $lawyersIds = User::where('id',auth()->id())->orWhere('office_id',auth()->id())->pluck('id')->toArray();
            if(!in_array($offer->lawyer_id,$lawyersIds)){
                abort(404);
            }
            if ($offer->status != 1){
                $offer->delete();
            }
            $msg = $offer->status == 1 ? __('dashboard.consultation_in_progress') : __('dashboard.deleted_successfully');
            return response()->json(['status'=>true,'msg'=>$msg]);
        }catch (\Exception $e){
            dd($e);
            return response()->json(['status'=>false,'msg'=>__('dashboard.failed_request')]);
        }
    }

    // return cause details
    public function details($id){
        $consultation = Consultation::with('user')->where('id',$id)->first();
        if(is_null($consultation))
            abort(404);
        return view('consultations.details',compact('consultation'));
    }

}
