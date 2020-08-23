<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CauseAttachment extends Model
{
    //
    protected $fillable = ['cause_id','file'];

    protected $hidden = ['created_at','updated_at'];

    // return cause details
    public function cause(){
        return $this->belongsTo('App\Cause');
    }

    // return full path of file
    public function getFileAttribute($value){
        return config('app.url') . Storage::url($value);
    }
}
