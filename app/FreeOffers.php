<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FreeCause;

class FreeOffers extends Model
{
    protected $fillable = ['offerable_id','offerable_type','lawyer_id','price','description','status','lawyer_rate',
        'user_rate','cause_id'];

    protected $hidden = ['created_at','updated_at'];


    public function freecause()
    {
        return $this->belongsTo(FreeCause::class);
    }
}
