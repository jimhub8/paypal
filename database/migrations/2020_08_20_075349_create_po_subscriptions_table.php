<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('subscription')->nullable();
            $table->decimal('subscription_amount')->default(0);
            $table->text('subscription_description')->nullable();
            $table->text('features')->nullable();
            $table->string('subscription_id')->nullable();
            $table->text('paypal_data')->nullable();
            $table->status('status')->default('Created');
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
        Schema::dropIfExists('po_subscriptions');
    }
}
