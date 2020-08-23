<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\FreeOffers;


class FreeCause extends Model
{
    protected $fillable = ['title','number','judgment_date','judgment_text','court_name','judicial_chamber',
    'consideration_text', 'type','is_public','status','related_cause_number','user_id','lawyer_id'];

    protected $hidden = ['created_at','updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function freeoffer()
    {
        return $this->hasOne(FreeOffers::class);
    }

    public function attachments(){
        return $this->hasMany('App\FreecauseAttachment');
    }
}
