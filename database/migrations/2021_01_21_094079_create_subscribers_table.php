<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('tenant_id')->nullable();
            $table->boolean('status')->nullable();
            $table->string('platform')->nullable();
            $table->string('plan')->nullable();


            $table->boolean('at_trial')->default(true);


            $table->string('plan_id')->nullable();
            $table->datetime('trial_ends')->nullable();
            $table->datetime('subscription_start')->nullable();
            $table->datetime('subscription_expire')->nullable();
            $table->integer('subscription_adddays')->nullable();
            $table->boolean('expired')->nullable();
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
        Schema::dropIfExists('subscribers');
    }
}
