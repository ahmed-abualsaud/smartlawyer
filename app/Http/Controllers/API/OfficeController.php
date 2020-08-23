<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfficeController extends Controller
{
    // return office list
    public function List(Request $request){
        try {
            $data = User::where('status',1)->where('role','office')->paginate(10);
            return response()->json($data,200);
        }catch (\Exception $e){
            \Log::debug($e->getMessage());
            $data = null;
            return response()->json( $data,200);
        }
    }
}
