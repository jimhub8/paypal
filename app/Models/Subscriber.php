<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    public function create($data)
    {

        $plan = Plan::where('plan_id', $data['resource']['plan_id'])->first();
        // $plan = Plan::first();

        $email = $data['resource']['subscriber']['email_address'];

        $subscriber = Subscriber::where('email', $email)->first();
        // $subscriber = Subscriber::where('tenant_id', 'foo')->first();
        $subscriber->at_trial = false;
        $subscriber->status = true;
        $subscriber->email = $email;
        $subscriber->platform = 'Paypal';
        $subscriber->plan = $data['resource']['plan_id'];
        $subscriber->plan_id = 1;
        // $subscriber->plan_id = $plan->id;
        $subscriber->subscription_start = now();
        $subscriber->subscription_expire = Carbon::today()->addDays(30);
        $subscriber->subscription_adddays = 30;
        $subscriber->expired = false;
        $subscriber->save();
    }
}
