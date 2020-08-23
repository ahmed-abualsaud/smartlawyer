<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cause extends Model
{
    //
    protected $fillable = ['title','number','judgment_date','judgment_text','court_name','judicial_chamber',
                            'consideration_text', 'type','is_public','status','related_cause_number','user_id','lawyer_id'];

    protected $hidden = ['created_at','updated_at'];

    // return owner(user)
    public function user(){
        return $this->belongsTo('App\User');
    }

    // return lawyer
    public function lawyer(){
        return $this->belongsTo('App\User','lawyer_id','id');
    }

    // return attachment
    public function attachments(){
        return $this->hasMany('App\CauseAttachment');
    }

    // return offers
    public function offers()
    {
        return $this->morphMany('App\Offer', 'offerable');
    }

    // return judicial hearing
    public function judicialHearing(){
        return $this->hasMany('App\JudicialHearing');
    }

    /**
     * Get all of the consultation's messages.
     */
    public function messages()
    {
        return $this->morphMany('App\Message', 'messageable');
    }

}
