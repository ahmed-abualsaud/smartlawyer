<?php

namespace App\Http\Controllers\API;

use App\FreeLawyer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FreeLawyerController extends Controller
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
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $checkIfUserUsedBefore = FreeLawyer::where('user_id',$request->user()->id)->count();
            if($checkIfUserUsedBefore > 0){
                return response()->json(['msg' => [__('dashboard.this_service_for_only_once')]],200);
            }
            $data = $request->all();
            $data['user_id'] = $request->user()->id;
            // store new compliant
            FreeLawyer::create($data);
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
            $data = FreeLawyer::with('lawyer:id,name,phone,email')
                                ->where('user_id',$request->user()->id)->first();
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
