<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintReply extends Model
{
    protected $fillable = ['reply_text','complaint_id','user_id','status'];

    // return owner(user)
    public function user(){
        return $this->belongsTo('App\User')->select('id','name','role');
    }

    // return complaint
    public function complaint(){
        return $this->belongsTo('App\Complaint');
    }
}
