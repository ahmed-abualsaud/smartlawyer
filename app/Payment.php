<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = ['offer_id', 'customer_id', 'payment_id', 'payment_status', 'charge_token'];
    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function offer()
    {
        $this->belongsTo(Offer::class);
    }
}
