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
            // $table->integer('user_id');
            // $table->string('type')->nullable();
            // $table->string('amount')->nullable();
            // $table->string('days')->nullable();
            // $table->string('remain')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('subscription_id');
            $table->string('status');
            $table->string('plan')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            // $table->index(['user_id', 'stripe_status']);
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
