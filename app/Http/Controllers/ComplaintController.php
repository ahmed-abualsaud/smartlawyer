<?php

namespace App\Http\Controllers;

use App\Consultation;
use App\Complaint;
use App\ComplaintReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ComplaintController extends Controller
{
    // return complaints view
    public function index(){
        return view('complaints.index');
    }

    // fetch complaints for datatable
    public function fetchComplaints()
    {
        $rows = Complaint::with('user')->orderByDesc('created_at')->get();

        return DataTables::of($rows)
            ->addColumn('number', function ($row) {
                return $row->number;
            })
            ->addColumn('title', function ($row) {
                return $row->title;
            })
            ->addColumn('details', function ($row) {
                return '<p title="'.$row->details.'">'.substr($row->details,0,50).'</p>';
            })
            ->addColumn('user_id', function ($row) {
                return $row->user->name;
            })
            ->addColumn('action', function ($row) {
                return '<a class="btn action-btn" href="/complaints/replies/'.$row->id.'">'
                    .'<span class="fa fa-file" title="'.__('dashboard.complaints_replies').'"></span></a>'.
                    '<a class="btn action-btn" href="/complaints/add-reply/'.$row->id.'">'
                    .'<span class="fa fa-pencil" title="'.__('dashboard.add_reply').'"></span></a>'.
                    '<a class="btn action-btn" onclick="deleteComplaint('.$row->id.')" title="'.__('dashboard.delete').'">
                        <span class="fa fa-close"></span></a>';
            })
            ->rawColumns(['action','details'])
            ->make(true);
    }

    // delete causes
    public function delete(Request $request){
        try {
            $complaint = Complaint::where('id',$request->id)->first();
            $complaint->delete();
            return response()->json(['status'=>true,'msg'=>__('dashboard.deleted_successfully')]);
        }catch (\Exception $e){
            return response()->json(['status'=>false,'msg'=>__('dashboard.failed_request')]);
        }
    }

    // return add reply view
    public function addReply($id){
        $complaint = Complaint::find($id);
        if(is_null($complaint))
            abort(404);
        return view('complaints.add_reply',compact('complaint'));
    }

    // store reply
    public function storeReply(Request $request){
        validator($request->all(),[
           'reply_text' => 'required',
           'complaint_id' => 'required|numeric|exists:complaints,id',
        ]);

        ComplaintReply::create([
            'complaint_id'=>$request->complaint_id,
            'reply_text'=>$request->reply_text,
            'user_id'=>Auth::user()->id
        ]);
        toastr()->success(__('dashboard.created_successfully'));


        return redirect()->route('complaints.replies',['id'=>$request->complaint_id]);
    }

    // return replies view
    public function replies($id){
        $complaint = Complaint::find($id);
        if(is_null($complaint))
            abort(404);

        return view('complaints.replies',compact('complaint'));
    }

    // fetch causes for datatable
    public function fetchReplies($id)
    {
        ComplaintReply::where('complaint_id',$id)->update(['status'=>1]);
        $rows = ComplaintReply::where('complaint_id',$id)->orderByDesc('created_at')->get();

        return DataTables::of($rows)
            ->addColumn('reply_text', function ($row) {
                return $row->reply_text;
            })
            ->addColumn('user_id', function ($row) {
                return $row->user->name;
            })
            ->addColumn('role', function ($row) {
                return $row->user->role == "user" ? __('dashboard.client') : __('dashboard.admin');
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at;
            })
            ->addColumn('action', function ($row) {
                return '<a class="btn action-btn" onclick="deleteReply('.$row->id.')" title="'.__('dashboard.delete').'"><span class="fa fa-close"></span></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // delete reply
    public function deleteReply(Request $request){
        try {
            $reply = ComplaintReply::where('id',$request->id)->first();
            $reply->delete();
            return response()->json(['status'=>true,'msg'=>__('dashboard.deleted_successfully')]);
        }catch (\Exception $e){
            return response()->json(['status'=>false,'msg'=>__('dashboard.failed_request')]);
        }
    }
}
