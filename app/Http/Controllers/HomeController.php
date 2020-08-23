<?php

namespace App\Http\Controllers;

use App\Cause;
use App\Consultation;
use App\Offer;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function setOffers(){

        Offer::query()->truncate();
        $causes = Cause::get();
        $consultations = Consultation::get();
        foreach ($causes as $cause){
            $lawyer = User::where('role','office')->inRandomOrder()->first();
            Offer::create([
                'offerable_id' => $cause->id,
                'offerable_type' => 'App\Cause',
                'price' => 1500,
                'lawyer_id' => $lawyer->id,
                'description' => "افشخ محامي في مصر"
            ]);
        }

        foreach ($consultations as $consultation){
            $lawyer = User::where('role','office')->inRandomOrder()->first();
            Offer::create([
                'offerable_id' => $consultation->id,
                'offerable_type' => 'App\Consultation',
                'price' => 2600,
                'lawyer_id' => $lawyer->id,
                'description' => "محامي خلع"
            ]);
        }
        flash('اي خدمه يا ابو الصحاااب :)');

        return redirect('/');

    }
}
