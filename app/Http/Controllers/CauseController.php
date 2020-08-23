<?php

namespace App\Http\Controllers;

use App\Cause;
use App\CauseAttachment;
use App\Message;
use App\Offer;
use App\User;
use App\UserDeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use VMdevelopment\TapPayment\Facade\TapPayment;

class CauseController extends Controller
{
    // return users view
    public function index(){
        return view('causes.index');
    }

    // fetch causes for datatable
    public function fetchCauses()
    {
        if(auth()->user()->role == "admin"){
            $rows = Cause::with('user')->orderByDesc('created_at')->get();
        }else{
            $lawyersIds = User::where('office_id',auth()->user()->id)->orWhere('id',auth()->user()->id)->pluck('id');
            $rows = Cause::where(function ($query) use ($lawyersIds) {
                $query->whereIn('lawyer_id',$lawyersIds)
                    ->where('is_public',0);
            })->orWhere(function ($query) {
                $query->where('is_public',1)
                    ->where('status',0);
            })->get();
        }

        return DataTables::of($rows)
            ->addColumn('title', function ($row) {
                return $row->title;
            })
            ->addColumn('number', function ($row) {
                return $row->number;
            })
            ->addColumn('judgment_date', function ($row) {
                return $row->judgment_date;
            })
            ->addColumn('judgment_text', function ($row) {
                return $row->judgment_text;
            })
            ->addColumn('court_name', function ($row) {
                return $row->court_name;
            })
            ->addColumn('judicial_chamber', function ($row) {
                return $row->judicial_chamber;
            })
            ->addColumn('consideration_text', function ($row) {
                return $row->consideration_text;
            })
            ->addColumn('consideration_text', function ($row) {
                return $row->consideration_text;
            })
            ->addColumn('type', function ($row) {
                return $row->type;
            })
            ->addColumn('is_public', function ($row) {
                return $row->is_public == 1 ? __('dashboard.public') : __('dashboard.private');
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
            ->addColumn('related_cause_number', function ($row) {
                return !is_null($row->related_cause_number) ? $row->related_cause_number : '' ;
            })
            ->addColumn('lawyer', function ($row) {
                return isset($row->lawyer->name) ? $row->lawyer->name : '';
            })
            ->addColumn('action', function ($row) {
                if(auth()->user()->role == "office"){
                    $msgCount = Message::where('messageable_id',$row->id)
                        ->where('messageable_type','App\Cause')
                        ->where('receiver_id', auth()->id())
                        ->where('seen',0)
                        ->count();

                    $class = $msgCount > 0 ? 'count' : '';
                    $btn = '';
                    if($row->status == 0 ){
                        $btn .= '<a class="btn action-btn" href="/causes/add-offer/'.$row->id.'"><span class="fa fa-plus" title="'.__('dashboard.add_offer').'"></span></a>
                            <a class="btn action-btn" href="/causes/offers/'.$row->id.'"><span class="fa fa-money" title="'.__('dashboard.offers').'"></span></a>';
                    }elseif(!is_null($row->related_cause_number)){
                        $btn .= '<a class="btn action-btn" href="/causes/add-new-stage/'.$row->id.'">'
                            .'<span class="fa fa-plus" title="'.__('dashboard.add_stage').'"></span></a>';
                    }
                    $btn .= '<a class="btn action-btn" onclick="sendMessage('.$row->id.')" title="'.__('dashboard.send_message').'">
                        <span class="fa fa-telegram"></span></a><a class="btn action-btn '.$class.'" href="/messages/inbox/cause/'.$row->id.'">'
                        .'<span class="fa fa-envelope" title="'.__('dashboard.inbox').'"></span></a>';
                    return '<a class="btn action-btn" href="/causes/attachments/'.$row->id.'">'
                        .'<span class="fa fa-file" title="'.__('dashboard.attachments').'"></span></a>
                        '.$btn;
                }
                return '<a class="btn action-btn" href="/causes/offers/'.$row->id.'">'
                    .'<span class="fa fa-money" title="'.__('dashboard.offers').'"></span></a>
                        <a class="btn action-btn" href="/causes/attachments/'.$row->id.'">'
                    .'<span class="fa fa-file" title="'.__('dashboard.attachments').'"></span></a>'.
                    '<a class="btn action-btn" onclick="deleteCause('.$row->id.')" title="'.__('dashboard.delete').'">
                        <span class="fa fa-close"></span></a>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    // delete causes
    public function delete(Request $request){
        try {
            $cause = Cause::where('id',$request->id)->first();
            if ($cause->status != 1){
                $cause->delete();
            }
            $msg = $cause->status == 1 ? __('dashboard.cause_in_progress') : __('dashboard.deleted_successfully');
            return response()->json(['status'=>true,'msg'=>$msg]);
        }catch (\Exception $e){
            return response()->json(['status'=>false,'msg'=>__('dashboard.failed_request')]);
        }
    }


    // return users view
    public function offers($id){
        return view('causes.offers',compact('id'));
    }

    // fetch causes for datatable
    public function fetchOffers($id)
    {
        if(auth()->user()->role == "admin"){
            $rows = Offer::where('offerable_id',$id)->where('offerable_type','App\Cause')->get();
        }else{
            $lawyersIds = User::where('id',auth()->id())->orWhere('office_id',auth()->id())->pluck('id')->toArray();
            $rows = Offer::where('offerable_id',$id)->where('offerable_type','App\Cause')
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
                        $status = __('dashboard.offer_pending');
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
        $cause = Cause::find($id);
        if (is_null($cause))
            abort(404);
        $lawyers = User::where('office_id',auth()->user()->id)->orWhere('id',auth()->user()->id)->get();
        return view('causes.add_offer',compact('cause','lawyers'));
    }

    // add offer to cause
    public function storeOffer(Request $request){

        validator($request->all(),[
            'cause_id' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $checkLawyer = User::find($request->lawyer_id);
        if(!is_null($checkLawyer->office_id)){
            $lawyersIds = User::where('office_id',$checkLawyer->office_id)->pluck('id');
        }

        $checkHasOfferBefore = Offer::where('offerable_type','App\Cause')->where('offerable_id',$request->cause_id)
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
            'offerable_id' => $request->cause_id,
            'offerable_type' => 'App\Cause',
            'price' => $request->price,
            'description' => $request->description,
            'lawyer_id' => $request->lawyer_id,
        ]);
        $cause = Cause::find($request->cause_id);
        $title = __('dashboard.offer_title');
        $body = __('dashboard.offer_body',['number'=>$cause->number,'type'=>__('dashboard.cause'),'lawyer_name'=>$checkLawyer->name]);
        $tokens = UserDeviceToken::where('user_id',$cause->user_id)->pluck('device_token');
        foreach ($tokens as $token){
            HelperController::sendFireBaseNotification($title,$body,$token);
        }

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
            $msg = $offer->status == 1 ? __('dashboard.cause_in_progress') : __('dashboard.deleted_successfully');
            return response()->json(['status'=>true,'msg'=>$msg]);
        }catch (\Exception $e){
            return response()->json(['status'=>false,'msg'=>__('dashboard.failed_request')]);
        }
    }

    // return attachments view
    public function attachments($id){
        return view('causes.attachments',compact('id'));
    }

    // fetch causes attachments for datatable
    public function fetchAttachments($id)
    {
        $rows = CauseAttachment::where('cause_id',$id)->get();

        return DataTables::of($rows)
            ->addColumn('consideration_text', function ($row) {
                return $row->id;
            })
            ->addColumn('action', function ($row) {
                return '<a class="btn action-btn" href="/causes/download-attachment/'.$row->id.'">'
                    .'<span class="fa fa-download" title="'.__('dashboard.download_attachemnt').'"></span></a>'.
                    '<a class="btn action-btn" onclick="deleteAttachment('.$row->id.')" title="'.__('dashboard.delete').'">
                       <span class="fa fa-close"></span></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // download attachment
    public function downloadAttachment($id)
    {
        try{
            $attachemnt = CauseAttachment::where('id',$id)->first();
            return \Storage::disk('local')->download($attachemnt->getOriginal()['file']);
        }catch(\Exception $e){
            dd($e);
            return abort(404);
        }
    }

    // download attachment
    public function deleteAttachment(Request $request)
    {
        try {
            $attachemnt = CauseAttachment::where('id',$request->id)->first();
            \Storage::disk('local')->delete($attachemnt->getOriginal()['file']);
            $attachemnt->delete();
            return response()->json(['status'=>true,'msg'=>__('dashboard.deleted_successfully')]);
        }catch (\Exception $e){
            return response()->json(['status'=>false,'msg'=>__('dashboard.failed_request')]);
        }

    }

    public function addNewStage($id){
        $cause = Cause::find($id);
        if (is_null($cause))
            abort(404);
        $lawyers = User::where('office_id',auth()->user()->id)->orWhere('id',auth()->user()->id)->get();

        return view('causes.add_stage',compact('cause','lawyers'));
    }

    public function storeNewStage(Request $request){
        try {

            validator($request->all(),[
                'related_cause_number' => 'required|string|exists:causes,number',
                'old_judgment_text' => 'required|string',
                'lawyer_id' => 'required|numeric',
                'title' => 'required|string',
                'stage_name' => 'required|string',
                'court_name' => 'required|string',
                'number' => 'required|string',
                'stage_date' => 'required|date',
            ]);

            DB::beginTransaction();

            $cause = Cause::where('number',$request->related_cause_number)->first();

            // store new stage
            $stage = new Cause();
            $stage->related_cause_number = $request->related_cause_number;
            $stage->title = $request->title;
            $stage->number = $request->number;
            $stage->judgment_date = $request->stage_date;
            $stage->judgment_text	 = $request->judgment_text;
            $stage->court_name = $request->court_name;
            $stage->judicial_chamber = $request->judicial_chamber;
            $stage->consideration_text = $request->consideration_text;
            $stage->type = $request->stage_name;
            $stage->is_public = 0;
            $stage->lawyer_id = $request->lawyer_id;
            $stage->status = 1;
            $stage->user_id = $cause->user_id;
            $stage->save();
            // store files of cause if uploaded
            if($request->has('attachment')){
                $path = HelperController::storeFile($request->attachment,'causes');
                CauseAttachment::create([
                    'cause_id' => $cause->id,
                    'file' => $path
                ]);
            }
            DB::commit();
            toastr()->success(__('dashboard.created_successfully'));
            return back();
        }catch (\Exception $e){
            dd($e);
            DB::rollBack();
            \Log::debug($e->getMessage());
            toastr()->warning(__('dashboard.failed_request'));
            return back();
        }
    }
}
