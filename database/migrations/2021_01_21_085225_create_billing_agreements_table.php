<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_agreements', function (Blueprint $table) {
            $table->id();
            $table->string('billing_id')->nullable();
            $table->string('status')->nullable();
            $table->string('description')->nullable();
            $table->string('start_date')->nullable();
            $table->string('payer_name')->nullable();
            $table->string('payer_email')->nullable();
            $table->string('payer_id')->nullable();
            $table->decimal('plan_amount')->nullable();
            $table->string('plan_frequence')->nullable();
            $table->string('tenant')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billing_agreements');
    }
}
