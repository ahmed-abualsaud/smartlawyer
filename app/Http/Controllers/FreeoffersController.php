<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FreeOffers;
use Validator;

class FreeoffersController extends Controller
{
    //

    public function index()
    {
        return FreeOffers::all();
    }

    public function show($id)
    {
        $freeoffer = FreeOffers::find($id);
        if(is_null($freeoffer))
            return response()->json(["message" => "DataNot Found"], 404);
        else
            return response()->json($freeoffer, 200);
    }

    public function store(Request $request)
    {
        if($request['price'] != 'مجانا')
            return response()->json(['message' => 'يجب أن يكون العرض مجاني']);

        if($request['price'] == null)
            $request['price'] = "مجانا";
            
        $freeoffer = FreeOffers::create($request->all());
        return response()->json($freeoffer, 201);
    }

    public function update(Request $request, $id)
    {
        $freeoffer = FreeOffers::find($id);
        if(is_null($freeoffer))
            return response()->json(["message" => "DataNot Found"], 404);
        else
        {
            $freeoffer->update($request->all());
            return response()->json($freeoffer, 200);
        }
    }

    public function delete($id)
    {
        $freeoffer = FreeOffers::find($id);
        if(is_null($freeoffer))
            return response()->json(["message" => "DataNot Found"], 404);
        else
        {
            $freeoffer->delete();
            return response()->json(null, 204);
        }

    }
}
