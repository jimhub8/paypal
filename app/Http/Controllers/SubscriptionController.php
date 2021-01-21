<?php

namespace App\Http\Controllers;

use App\Models\Log as ModelsLog;
use App\Models\PoSubscription;
use App\Paypal\PaypalAgreement;
use App\Paypal\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = PoSubscription::all();

        return view('subscription', compact('subscriptions'));
        $data = PoSubscription::first();
        return PoSubscription::all();
        dd($data['paypal_data']->payment_definitions[0]->id);
    }
    public function pricing()
    {
        $subscriptions = PoSubscription::all();

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
        $plan_exists = PoSubscription::where('subscription', $request->subscription)->first();
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
        return ;

    }
    public function webhook_index(Request $request)
    {
        return ModelsLog::latest()->get();
    }
}
