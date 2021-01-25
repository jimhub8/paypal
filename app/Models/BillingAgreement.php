<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingAgreement extends Model
{
    use HasFactory;


    public function billing($logs)
    {
        $billing = new BillingAgreement();
        $billing->billing_id = $logs->id;
        $billing->status = $logs->state;
        $billing->description = $logs->description;
        $billing->start_date = $logs->start_date;
        $billing->payer_name = $logs->payer->payer_info->first_name . ' ' . $logs->payer->payer_info->last_name;
        $billing->payer_email = $logs->payer->payer_info->email;
        $billing->payer_id = $logs->payer->payer_info->payer_id;
        $billing->plan_frequence = $logs->plan->payment_definitions[0]->frequency;
        $billing->plan_amount = $logs->plan->payment_definitions[0]->amount->value;
        $billing->save();
    }
}
