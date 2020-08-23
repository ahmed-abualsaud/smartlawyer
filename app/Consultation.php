<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = ['title','number','address','details','is_publish','user_id','status'];

    protected $hidden = ['created_at','updated_at'];

    // return owner(user)
    public function user(){
        return $this->belongsTo('App\User');
    }

    // return offers
    public function offers()
    {
        return $this->morphMany('App\Offer', 'offerable');
    }

    /**
     * Get all of the consultation's messages.
     */
    public function messages()
    {
        return $this->morphMany('App\Message', 'messageable');
    }
}
