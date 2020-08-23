<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use App\FreeCause;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role','avatar','phone','bio','address','experience','casecount','office_id','national_id','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','created_at','updated_at','email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be append to object.
     *
     * @var array
     */
    protected $appends = ['rate','office'];
    /**
     * @var mixed
     */

    /**
     * The image attribute that should be cast.
     *
     * @return string
     */
    public function getAvatarAttribute($value){
        if(is_null($value)){
            return asset('user.jpg');
        }
        return config('app.url') . Storage::url($value);
    }

    /**
     * The rate attribute.
     *
     * @return string
     */
    public function getRateAttribute(){
        $role = $this->role;
        if($role == "user"){
            $causesIds = Cause::where('user_id',$this->id)->pluck('id');
            $consultationsIds = Consultation::where('user_id',$this->id)->pluck('id');
            $rate = Offer::Where(function($query) use($causesIds) {
                                $query->where('offerable_type', 'App\Cause')->whereIn('offerable_id',$causesIds);
                            })->orWhere(function($query) use($consultationsIds) {
                                $query->where('offerable_type', 'App\Consultation')->whereIn('offerable_id',$consultationsIds);
                            })->avg('lawyer_rate');
        }else{
            $rate = Offer::where('lawyer_id',$this->id)->avg('lawyer_rate');
        }

        return number_format($rate,1);
    }

    /**
     * The office.
     *
     * @return string
     */

    public function getOfficeAttribute(){
        if ($this->office_id != 0){
            return User::where('id',$this->office_id)->value('name');
        }
    }

    /**
     * Get user devices tokens.
     *
     * @return string
     */
    public function devicesTokens(){
        return $this->hasMany('App\UserDeviceToken');
    }

    public function freecauses()
    {
        return $this->hasMany(FreeCause::class);
    }



    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function payments()
    {
        $this->hasMany(Payment::class);
    }
}
