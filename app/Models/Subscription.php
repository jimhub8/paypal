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
        $subscriber = Subscriber::first();
        // $subscriber->tenant_id = $data->tenant_id;
        $subscriber->status = true;
        $subscriber->platform = 'Paypal';
        // $subscriber->plan = $data->plan;
        $subscriber->plan = $data['resource']['plan_id'];
        $subscriber->agreement_id = $data['resource']['id'];
        // $subscriber->trial_ends = $data->trial_ends;
        // $subscriber->subscription_start = $data->subscription_start;
        $subscriber->subscription_expire = Carbon::parse($subscriber->subscription_expire)->addDays(30);
        $subscriber->subscription_adddays = 30;
        $subscriber->expired = false;
        $subscriber->save();


        $subscription = new Subscription();
        $subscription->subscriber_id = $subscriber->id;
        $subscription->billing_agreement_id = $data['resource']['id'];
        // $subscription->amount = $data['amount']['total'];
        // $subscription->trial_ends = $data->resource;
        $subscription->subscription_start = now();
        $subscription->subscription_expire = Carbon::today()->addDays(30);
        $subscription->subscription_adddays = 30;
        $subscription->expired = false;
        $subscription->save();
    }
    public function renew($data)
    {


        $billing_agreement_id = (array_key_exists('billing_agreement_id', $data['resource'])) ? $data['resource']['billing_agreement_id'] : 'I-BW452GLLEP1G';



        // $subscriber = Subscriber::where('agreement_id', $billing_agreement_id)->first();
        $subscriber = Subscriber::first();
        // $subscriber->tenant_id = $data->tenant_id;
        $subscriber->status = true;
        $subscriber->platform = 'Paypal';
        // $subscriber->plan = $data->plan;
        // $subscriber->plan = $data['resource']['plan_id'];
        // $subscriber->agreement_id = $billing_agreement_id;
        // $subscriber->trial_ends = $data->trial_ends;
        // $subscriber->subscription_start = $data->subscription_start;
        $subscriber->subscription_expire = Carbon::parse($subscriber->subscription_expire)->addDays(30);
        $subscriber->subscription_adddays = 30;
        $subscriber->expired = false;
        $subscriber->save();


        $subscription = new Subscription();
        $subscription->subscriber_id = $subscriber->id;
        $subscription->billing_agreement_id = $billing_agreement_id;
        // $subscription->amount = $data['amount']['total'];
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
