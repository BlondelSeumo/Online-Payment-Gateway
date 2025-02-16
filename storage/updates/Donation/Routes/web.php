<?php

use Illuminate\Support\Facades\Route;
use Modules\Donation\Http\Controllers\{
    Admin\DonationController,
    Admin\DonationPaymentController,
    Admin\PreferenceSettingController,
    User\PaymentController,
    User\DonationController as UserDonationController
};


Route::group(config('addons.route_group.authenticated.admin'), function () {
    
    //Preference Routes
    Route::controller(PreferenceSettingController::class)->as('admin.donation.')->group(function () {
        Route::get('donation-preferences', 'create')->name('preferences')->middleware(['permission:view_campaign_setting']);
        Route::post('donation-preferences/store', 'store')->name('preferences.store')->middleware(['permission:add_campaign_setting']);
    });

    //Donation Routes
    Route::controller(DonationController::class)->as('admin.donation.')->group(function () {
        Route::get('campaigns', 'index')->name('index')->middleware(['permission:view_campaign']);
        Route::get('campaigns/detail/{donation}', 'detail')->name('detail')->middleware(['permission:edit_campaign']);
        Route::get('campaigns/delete/{donation}', 'delete')->name('delete')->middleware(['permission:delete_campaign']);
        Route::get('campaigns/user-search', 'userSearch')->name('users.search');
        Route::get('campaigns/csv', 'csv')->name('csv');
        Route::get('campaigns/pdf', 'pdf')->name('pdf');
    });

    //Donation Routes
    Route::controller(DonationPaymentController::class)->as('admin.donation-payment.')->group(function () {
        Route::get('campaign-payments', 'index')->name('index')->middleware(['permission:view_campaign']);
        Route::get('campaign-payments/detail/{payment}', 'detail')->name('detail')->middleware(['permission:edit_campaign']);
        Route::get('campaign-payments/user-search', 'userSearch')->name('users.search');
        Route::get('campaign-payments/csv', 'csv')->name('csv');
        Route::get('campaign-payments/pdf', 'pdf')->name('pdf');
    });

});

Route::group(array_merge(config('addons.route_group.authenticated.user'), [
    'middleware' => array_merge(
        config('addons.route_group.authenticated.user.middleware'), 
        ['donation_permission']
    )
    ]), function () {
    //Donation Routes
    Route::controller(UserDonationController::class)->as('user.donation.')->prefix('user')->group(function () {
        Route::get('campaigns', 'index')->name('index');
        Route::get('campaigns/create', 'create')->name('create');
        Route::post('campaigns/store', 'store')->name('store');
        Route::get('campaigns/edit/{donation:slug}', 'edit')->name('edit');
        Route::post('campaigns/update/{donation}', 'update')->name('update');
        Route::get('campaigns/detail/{donation:slug}', 'detail')->name('detail');
        Route::post('campaigns/delete/{donation}', 'delete')->name('delete');
    });

    //Donation Routes
    Route::controller(PaymentController::class)->as('user.donation-payment.')->prefix('user')->group(function () {
        Route::get('campaign-payments', 'index')->name('index');
        Route::get('campaign-payment/print/{transaction}', 'print')->name('print');
    });
});

Route::get('campaigns', 'FrontDonationController@home')->name('donations.home');
Route::get('campaigns/{slug}', 'FrontDonationController@details')->name('donations.details');
Route::match(array('GET', 'POST'), 'campaigns/payment-form', 'FrontDonationController@paymentForm')->name('donations.payment_form');
Route::get('campaigns/method-form', 'FrontDonationController@showPaymentForm')->name('donations.show_payment_form');
Route::post('campaigns/gateway', 'FrontDonationController@donationGateway')->name('donations.gateway');
Route::post('campaign/getCampaignFeesLimit', 'FrontDonationController@getCampaignFeesLimit')->name('donations.fees_limit');

Route::get('campaign/payment', 'DonationPaymentController@donationComplete')->name('donation.payment');
Route::get('campaign/success', 'DonationPaymentController@donationSuccess')->name('donation.success');
Route::get('campaign-payment/print/{transaction:uuid}', 'DonationPaymentController@print')->name('donation.print');