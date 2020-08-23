<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = ['title','details','number','user_id'];

    // return owner(user)
    public function user(){
        return $this->belongsTo('App\User');
    }

    // return replies
    public function replies(){
        return $this->hasMany('App\ComplaintReply');
    }

}
