<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = ['offerable_id','offerable_type','lawyer_id','price','description','status','lawyer_rate',
        'user_rate','payment_id','payment_token','payment_status'];

    protected $hidden = ['created_at','updated_at'];

    /**
     * Get the owning imageable model.
     */
    public function offerable()
    {
        return $this->morphTo();
    }

    // return lawyer
    public function lawyer(){
        return $this->belongsTo('App\User','lawyer_id','id');
    }

    public function payment()
    {
        $this->hasOne(Payment::class);
    }
}
