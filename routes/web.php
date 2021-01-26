<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return redirect()->away('https://google.com');

    return view('welcome');
});


Route::get('/webhook_index', 'SubscriptionController@webhook_index')->name('webhook_index');


Route::get('/paypal', 'SubscriptionController@paypal')->name('paypal');


Route::get('/execute-payment', 'PaymentController@execute');
Route::post('/create-payment', 'PaymentController@create')->name('create-payment');

Route::get('subscription', 'SubscriptionController@index');
Route::get('pricing', 'SubscriptionController@pricing');


Route::post('plan/create', 'SubscriptionController@createPlan')->name('create_plan');
Route::get('plan/list', 'SubscriptionController@listPlan');
Route::get('plan/{id}', 'SubscriptionController@showPlan');
Route::get('plan/{id}/activate', 'SubscriptionController@activatePlan');
Route::get('plan/{id}/deactivate', 'SubscriptionController@deactivatePlan');

Route::post('plan/{id}/agreement/create', 'SubscriptionController@createAgreement')->name('create-agreement');
Route::get('execute-agreement/{success}', 'SubscriptionController@executeAgreement');

Route::get('agreement_details', 'SubscriptionController@agreement_details');




// Upgrade & Downgrade
Route::get('upgrade', 'SubscriptionController@upgrade');
Route::get('subscription_transaction', 'TransactionController@subscription_transaction');

Route::get('token', 'TransactionController@token');
