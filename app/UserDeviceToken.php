<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDeviceToken extends Model
{
    //
    protected $fillable = ['user_id','device_token','device_id'];
}
