<?php

namespace App\Http\Controllers;

use App\Complaint;
use App\ComplaintReply;
use App\FreeLawyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class FreeLawyerController extends Controller
{
    // return free lawyer view
    public function index(){
        return view('free_lawyer.index');
    }

    // fetch complaints for datatable
    public function fetchQuestions()
    {
        if(\auth()->user()->role == "admin"){
            $rows = FreeLawyer::with('user')->orderByDesc('created_at')->get();
        }else{
            $rows = FreeLawyer::with('user')->orderByDesc('created_at')
                                ->where('status',0)->orWhere('lawyer_id',\auth()->id())->get();
        }

        return DataTables::of($rows)
            ->addColumn('title', function ($row) {
                return $row->title;
            })
            ->addColumn('details', function ($row) {
                return '<p title="'.$row->details.'">'.substr($row->details,0,50).'</p>';
            })
            ->addColumn('reply', function ($row) {
                return '<p title="'.$row->reply.'">'.substr($row->reply,0,50).'</p>';
            })
            ->addColumn('user_id', function ($row) {
                return $row->user->name;
            })
            ->addColumn('lawyer_id', function ($row) {
                return isset($row->lawyer_id) ? $row->lawyer->name : '';
            })
            ->addColumn('action', function ($row) {
                if(\auth()->user()->role == "office" && $row->status == 0){
                    return '<a class="btn action-btn" href="/free-lawyer/add-reply/'.$row->id.'">'
                        .'<span class="fa fa-pencil" title="'.__('dashboard.add_reply').'"></span></a>';
                }
                return '<a class="btn action-btn" onclick="deleteComplaint('.$row->id.')" title="'.__('dashboard.delete').'">
                        <span class="fa fa-close"></span></a>';
            })
            ->rawColumns(['action','details','reply'])
            ->make(true);
    }

    // delete question
    public function delete(Request $request){
        try {
            $question = FreeLawyer::where('id',$request->id)->first();
            $question->delete();
            return response()->json(['status'=>true,'msg'=>__('dashboard.deleted_successfully')]);
        }catch (\Exception $e){
            return response()->json(['status'=>false,'msg'=>__('dashboard.failed_request')]);
        }
    }

    // return add reply view
    public function addReply($id){
        $question = FreeLawyer::find($id);
        if(is_null($question))
            abort(404);
        return view('free_lawyer.add_reply',compact('question'));
    }

    // store reply
    public function storeReply(Request $request){
        validator($request->all(),[
            'reply' => 'required',
        ]);

        $checkQuestionIfHasReply = FreeLawyer::find($request->id);
        if($checkQuestionIfHasReply->status == 1){
            toastr()->success(__('dashboard.replied_before'));
            return redirect()->back();
        }


        FreeLawyer::where('id',$request->id)->update([
            'reply'=>$request->reply_text,
            'status'=>1,
            'lawyer_id'=>Auth::user()->id
        ]);
        toastr()->success(__('dashboard.created_successfully'));

        return redirect()->route('complaints');
    }
}
