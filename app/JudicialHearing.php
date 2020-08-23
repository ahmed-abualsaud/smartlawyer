<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JudicialHearing extends Model
{
    //
    protected $fillable = ['cause_id','date','description','results_text'];

    protected $hidden = ['created_at','updated_at'];

    // return cause details
    public function cause(){
        return $this->belongsTo('App\Cause');
    }
}
