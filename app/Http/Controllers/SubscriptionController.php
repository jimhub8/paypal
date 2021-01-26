<?php

namespace App\Http\Controllers;

use App\Models\BillingAgreement;
use App\Models\Log as ModelsLog;
use App\Models\Plan;
use App\Models\PoSubscription;
use App\Models\Subscriber;
use App\Models\Subscription;
use App\Paypal\PaypalAgreement;
use App\Paypal\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Plan::all();
        // $subscriptions = PoSubscription::all();

        return view('subscription', compact('subscriptions'));
        $data = PoSubscription::first();
        return PoSubscription::all();
        dd($data['paypal_data']->payment_definitions[0]->id);
    }
    public function pricing()
    {
        $subscriptions = Plan::all();

        return view('pricing', compact('subscriptions'));
        // $data = PoSubscription::first();
        // return PoSubscription::all();
        // dd($data['paypal_data']->payment_definitions[0]->id);
    }

    /**
     *
     */
    public function createPlan(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $data['plan'] = $request->subscription;
        $plan_exists = Plan::where('plan', $request->plan)->first();
        $plan = new SubscriptionPlan();
        if ($plan_exists) {
            // dd($plan_exists->subscription_id);

            $data['id'] = $plan_exists->subscription_id;
            $plan->update_plan($data);
        } else {
            // dd(2);
            $plan->create($data);
        }
    }

    /**
     * @return \PayPal\Api\PlanList
     */
    public  function listPlan()
    {
        // dd('ded');
        $plan = new SubscriptionPlan();
        return $plan->listPlan();
    }

    public function showPlan($id)
    {
        $plan = new SubscriptionPlan();
        return $plan->planDetails($id);
    }

    public function activatePlan($id)
    {
        $plan = new SubscriptionPlan();
        $plan->activate($id);
    }

    public function deactivatePlan($id)
    {
        $plan = new SubscriptionPlan();
        $plan->deactivate($id);
    }

    public function CreateAgreement($id)
    {
        // dd($id);
        $agreement = new PaypalAgreement;
        return $agreement->create($id);
    }

    public function executeAgreement($status)
    {
        Log::debug($status);
        if ($status == 'true') {
            $agreement = new PaypalAgreement;
            $agreement->execute(request('token'));
            Log::debug($agreement);
            return 'done';
        }
    }

    public function paypal()
    {
        $subscriptions = PoSubscription::all();

        return view('paypal', compact('subscriptions'));
    }


    public function webhook(Request $request)
    {
        $data = [];
        Mail::send('emails.welcome', $data, function ($message) {
            $message->from('us@example.com', 'Laravel');
            $message->to('foo@example.com');
        });
        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug($request->all());
        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug('**********************************');

        $logs = new ModelsLog;
        $logs->logs = $request->all();
        $logs->save();

        // $data = ($request->all());



        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug($request->event_type);
        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug('**********************************');
        Log::debug('**********************************');


        if($request->event_type == 'BILLING.SUBSCRIPTION.RENEWED' || $request->event_type == 'BILLING.SUBSCRIPTION.CREATED') {
            $subscriber = new Subscription();
            $subscriber->subscribe($request->all());
        } elseif($request->event_type == 'BILLING.SUBSCRIPTION.EXPIRED') {
            $subscriber = new Subscription();
            $subscriber->expired();
        }elseif($request->event_type == 'PAYMENT.SALE.COMPLETED') {
            $subscriber = new Subscription();
            $subscriber->renew($request->all());
            // $subscriber->expired();
        }
        return ;

    }
    public function webhook_index(Request $request)
    {
       return $model = ModelsLog::latest()->get('logs');
       $model = ModelsLog::latest()->first('logs');




        $data = (($model->logs));

        // return $data['resource']['subscriber']['email_address'];
        // dd($data);

        // $plan = Plan::where('plan_id', $data['resource']['plan_id'])->first();
        // $plan = Plan::first();

        // $email = $data['resource']['subscriber']['email_address'];

        $billing_agreement_id = (array_key_exists('billing_agreement_id', $data['resource'])) ? $data['resource']['billing_agreement_id'] : '2222222';


        $subscriber = Subscriber::where('agreement_id', $billing_agreement_id)->first();
        // $subscriber = Subscriber::first();
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

        return $subscription;



    }

    public function agreement_details()
    {
        $agreement = new PaypalAgreement;
        return $agreement->agreement_details();
    }

    public function upgrade()
    {
        $agreement = new PaypalAgreement;
        return $agreement->upgrade();
    }
}
