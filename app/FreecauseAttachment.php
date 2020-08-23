<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreecauseAttachment extends Model
{
    //
    protected $fillable = ['cause_id','file'];

    protected $hidden = ['created_at','updated_at'];

    // return cause details
    public function cause(){
        return $this->belongsTo('App\FreeCause');
    }

    // return full path of file
    public function getFileAttribute($value){
        return config('app.url') . Storage::url($value);
    }
}
