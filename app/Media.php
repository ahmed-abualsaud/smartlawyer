<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    //
    protected $fillable = ['user_id','file','cause_phase_id'];
}
