<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoSubscription extends Model
{
    protected $fillable = ['user_id','subscription_description','features','subscription_amount','paypal_data', 'subscription', 'subscription_id'];



    public function setPaypalDataAttribute($value)
    {
        $this->attributes['paypal_data'] = serialize($value);
    }

    public function getPaypalDataAttribute($value)
    {
        return unserialize($value);
    }
}
