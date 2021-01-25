<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public function subscribe($data)
    {
        $subscriber = Subscriber::find(1);
        // $subscriber->tenant_id = $data->tenant_id;
        $subscriber->status = true;
        $subscriber->platform = 'Paypal';
        // $subscriber->plan = $data->plan;
        $subscriber->plan = $data['resource']['plan_id'];
        // $subscriber->trial_ends = $data->trial_ends;
        // $subscriber->subscription_start = $data->subscription_start;
        $subscriber->subscription_expire = Carbon::parse($subscriber->subscription_expire)->addDays(30);
        $subscriber->subscription_adddays = 30;
        $subscriber->expired = false;
        $subscriber->save();


        $subscription = new Subscription();
        $subscription->subscriber_id = $subscriber->id;
        // $subscription->trial_ends = $data->resource;
        $subscription->subscription_start = now();
        $subscription->subscription_expire = Carbon::today()->addDays(30);
        $subscription->subscription_adddays = 30;
        $subscription->expired = false;
        $subscription->save();
    }
    public function expired()
    {
        $subscriber = Subscriber::find(1);
        $subscriber->status = false;
        $subscriber->expired = true;
        $subscriber->subscription_adddays = 7;
        $subscriber->save();
    }
}
