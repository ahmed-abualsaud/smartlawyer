<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreeLawyer extends Model
{
    // mass assignment files
    protected $fillable =['user_id','lawyer_id','title','details','reply','status'];

    // return owner(user)
    public function user(){
        return $this->belongsTo('App\User');
    }

    // return lawyer
    public function lawyer(){
        return $this->belongsTo('App\User','lawyer_id','id');
    }
}
