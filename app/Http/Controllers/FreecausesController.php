<?php

namespace App\Http\Controllers;

use App\FreecauseOffer;
use App\Message;
use App\PaginationEngine;
use App\User;
use App\UserDeviceToken;
use Validator;
use Illuminate\Http\Request;
use App\FreeCause;
use Yajra\DataTables\Facades\DataTables;
use App\FreecauseAttachment;

class FreecausesController extends Controller
{
    //implicit route model binding

    public function index($pages, $i)
    {
        $casecount = FreeCause::count();
        $numpages = ceil($casecount/$pages);
        $toskip = $i*$pages;
        $cases = FreeCause::skip($toskip)->take($pages)->get();
        $counter = 1;
        return view('freecauses.index', compact('cases', 'counter', 'numpages', 'pages'));
    }

    public function show($id)
    {
        $freecause = FreeCause::find($id);
        if(is_null($freecause))
            return response()->json(["message" => "DataNot Found"], 404);
        else
            return response()->json($freecause, 200);
    }

    public function store(Request $request)
    {
        $causes = FreeCause::where('user_id', auth()->user()->id)->get;
        $causesnum = count($causes);
        $admin = User::where('role', 'admin')->get();
        $casecount = $admin['casecount'];

        if($casecount == null)
            $casecount = 5;

        if ($causesnum < $casecount) {
            $freecause = FreeCause::create($request->all());
            return response()->json($freecause, 201);
        }
        else{
            return  response()->json(['message' => "You exceeded the maximum number of free cases"]);
        }
    }

    public function update(Request $request, $id)
    {
        $freecause = FreeCause::find($id);
        if(is_null($freecause))
            return response()->json(["message" => "DataNot Found"], 404);
        else
        {
            $freecause->update($request->all());
            return response()->json($freecause, 200);
        }
    }

    public function delete($id)
    {
        $freecause = FreeCause::find($id);
        if(is_null($freecause))
            return response()->json(["message" => "DataNot Found"], 404);
        else
        {
            $freecause->delete();
            return response()->json(null, 204);
        }

    }
    // functions called back by $this->index() function

    public function freecausesDetails($id)
    {
        $cause = FreeCause::with('user')->where('id',$id)->first();
        if(is_null($cause))
            abort(404);
        return view('freecauses.details',compact('cause'));
    }

    public function deleteFreecauses($id){
        try {
            $cause = FreeCause::where('id', $id)->first();
            if ($cause->status != 1){
                $cause->delete();
            }
            $msg = $cause->status == 1 ? __('dashboard.cause_in_progress') : __('dashboard.deleted_successfully');
            return response()->json(['status'=>true,'msg'=>$msg]);
        }catch (\Exception $e){
            return response()->json(['status'=>false,'msg'=>__('dashboard.failed_request')]);
        }
    }

    public function attachments($id)
    {
        return view('freecauses.attachments',compact('id'));
    }

    public function fetchAttachments($id)
    {
        $rows = FreecauseAttachment::where('cause_id',$id)->get();

        return DataTables::of($rows)
            ->addColumn('consideration_text', function ($row) {
                return $row->id;
            })
            ->addColumn('action', function ($row) {
                return '<a class="btn action-btn" href="/freecauses-download-attachment/'.$row->id.'">'
                    .'<span class="fa fa-download" title="'.__('dashboard.download_attachemnt').'"></span></a>'.
                    '<a class="btn action-btn" onclick="deleteAttachment('.$row->id.')" title="'.__('dashboard.delete').'">
                       <span class="fa fa-close"></span></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function deleteAttachment(Request $request)
    {
        try {
            $attachemnt = FreecauseAttachment::where('id',$request->id)->first();
            \Storage::disk('local')->delete($attachemnt->getOriginal()['file']);
            $attachemnt->delete();
            return response()->json(['status'=>true,'msg'=>__('dashboard.deleted_successfully')]);
        }catch (\Exception $e){
            return response()->json(['status'=>false,'msg'=>__('dashboard.failed_request')]);
        }

    }

    public function downloadAttachment($id)
    {
        try{
            $attachemnt = FreecauseAttachment::where('id',$id)->first();
            return \Storage::disk('local')->download($attachemnt->getOriginal()['file']);
        }catch(\Exception $e){
            dd($e);
            return abort(404);
        }
    }

    public function offers($id){
        return view('freecauses.offers',compact('id'));
    }

    public function fetchOffers($id)
    {
        if(auth()->user()->role == "admin"){
            $rows = FreecauseOffer::where('offerable_id',$id)->where('offerable_type','App\FreeCause')->get();
        }else{
            $lawyersIds = User::where('id',auth()->id())->orWhere('office_id',auth()->id())->pluck('id')->toArray();
            $rows = Offer::where('offerable_id',$id)->where('offerable_type','App\FreeCause')
                ->whereIn('lawyer_id',$lawyersIds)->get();
        }

        return \Yajra\DataTables\DataTables::of($rows)
            ->addColumn('lawyer_id', function ($row) {
                return $row->lawyer->name;
            })
            ->addColumn('lawyer_image', function ($row) {
                if($row->lawyer->avatar){
                    return '<img src="'.$row->avatar.'" style="width:30px;">';
                }else{
                    return '<img src="https://placeimg.com/30/30/nature" style="width:30px;">';
                }
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
            ->rawColumns(['action','lawyer_image','status'])
            ->make(true);
    }

    public function addOffer($id){
        $cause = FreeCause::find($id);
        if (is_null($cause))
            abort(404);
        $lawyers = User::where('office_id',auth()->user()->id)->orWhere('id',auth()->user()->id)->get();
        return view('freecauses.add_offer',compact('cause','lawyers'));
    }

    public function storeOffer(Request $request)
    {

        validator($request->all(),[
            'cause_id' => 'required|numeric',
        ]);

        $checkLawyer = User::find($request->lawyer_id);
        if(!is_null($checkLawyer->office_id)){
            $lawyersIds = User::where('office_id',$checkLawyer->office_id)->pluck('id');
        }

        $checkHasOfferBefore = FreecauseOffer::where('offerable_type','App\FreeCause')->where('offerable_id',$request->cause_id)
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
            'offerable_type' => 'App\FreeCause',
            'price' => $request->price,
            'description' => $request->description,
            'lawyer_id' => $request->lawyer_id,
        ]);
        $cause = FreeCause::find($request->cause_id);
        $title = __('dashboard.offer_title');
        $body = __('dashboard.offer_body',['number'=>$cause->number,'type'=>__('dashboard.cause'),'lawyer_name'=>$checkLawyer->name]);
        $tokens = UserDeviceToken::where('user_id',$cause->user_id)->pluck('device_token');
        foreach ($tokens as $token){
            HelperController::sendFireBaseNotification($title,$body,$token);
        }

        toastr()->success(__('dashboard.offer_sent_successfully'));
        return redirect()->back();
    }

    public function deleteOffer(Request $request){
        try {
            $offer = FreecauseOffer::where('id',$request->id)->first();
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

}


