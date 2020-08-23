<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $fillable = ['sender_id','receiver_id','message','seen','messageable_id','messageable_type'];


    // return sender data
    public function sender(){
        return $this->belongsTo('App\User','sender_id','id');
    }

    // return receiver data
    public function receiver(){
        return $this->belongsTo('App\User','receiver_id','id');
    }

    /**
     * Get the owning commentable model.
     */
    public function messageable()
    {
        return $this->morphTo();
    }
}
