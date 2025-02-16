@extends('donation::layouts.app')
@section('styles')
  <link rel="stylesheet" href="{{ asset('Modules/Donation/Resources/assets/css/donation-style.min.css') }}">
@endsection
@section('content')

<div class="section-payment">
    <div class="transaction-details-module">
        @if($paymentData && $donation)
            <div class="donation-transaction-details">
                <h2>{{ __('Donation') }}</h2>
                <span>{{ __('for') }}</span>
                <h5>{{ $donation['title'] }}</h5>
            </div>
            <div class="donation-transaction-details">
                <div class="d-flex align-items-center justify-content-between border-donation">
                    <h3>{{ __('Sending Amount') }}</h3>
                    <span>
                        @php
                            $amount = isset($paymentData['amount']) ? $paymentData['amount'] : 0;
                        @endphp
                        {{ moneyFormat(optional($donation->currency)->symbol, formatNumber($amount, optional($donation->currency)->id)) }}
                    </span>
                </div>
                @if (feeBearer($donation->id) == 'donor')
                    <div class="d-flex align-items-center justify-content-between border-donation">
                        <h3>{{ __('Fee') }}</h3>
                        <span id="donationFeesAmount">
                        </span>
                    </div>
                @endif
            </div>
            <div class="transaction-total d-flex justify-content-between">
                <h3>{{ __('Total') }} ({{ optional($donation->currency)->code }})</h3>
                <span id="donationTotalAmount">{{ moneyFormat(optional($donation->currency)->symbol, formatNumber($amount, optional($donation->currency)->id)) }}</span>
            </div>

            <form action="{{ route('donations.gateway') }}" method="post" id="paymentMethodForm">
                {{ csrf_field() }}
                <input name="amount" value="{{ $amount }}" type="hidden">
                <input name="currency_symbol" value="{{ optional($donation->currency)->symbol}}" type="hidden">
                <input name="currency" value="{{ optional($donation->currency)->code}}" type="hidden">
                <input name="currency_id" value="{{ optional($donation->currency)->id}}" type="hidden">
                <input name="order_no" value="{{ isset($paymentInfo['order']) ? $paymentInfo['order'] : '' }}" type="hidden">
                <input name="donation_id" value="{{ isset($donation->id) ? $donation->id : '' }}" type="hidden">
                @if (!empty($paymentMethods))
                <div class="transaction-payment-method">
                    <p>{{ __('Accepted payment methods') }}</p>
                    <div class="d-flex flex-wrap gap-18 mt-2 radio-hide">
                        @foreach($paymentMethods as $value)
                                <input type="radio" name="method" value="{{ $value['name'] }}" id="{{ $value['id'] }}" {{ old('method') == $value['id'] || $loop->index == 0 ? 'checked' : '' }}>
                                <label for="{{ $value['id'] }}" id="{{ $value['name'] }}" class="gateway d-inline-flex flex-column justify-content-center align-items-center {{ old('method') == $value['id'] ? 'gateway-selected' : '' }}">
                                    {!! ($value['name'] == 'Mts') ? getSystemLogo() : '<img src="' . asset('public/dist/images/gateways/payments/'.strtolower($value['name']).'.png') . '" alt="' . $value['name'] . '">' !!}
                                </label>
                        @endforeach
                    </div>
                </div>
                <div class="d-grid">
                    <button class="btn btn-lg btn-primary" type="submit" id="paymentMethodSubmitBtn">
                        <div class="spinner spinner-border text-white spinner-border-sm mx-2 d-none">
                            <span class="visually-hidden"></span>
                        </div>
                        <span id="paymentMethodSubmitBtnText" class="px-1">{{ __('Continue') }}</span>
                    </button>
                </div>
                @else
                <div class="transaction-payment-method">
                    <p class="text-center">{{ __('No payment methods are currently activated for campaign donations. Please contact the administrator for assistance.') }}</p>
                </div>
                @endif
            </form>
            <div class="d-flex justify-content-center align-items-center mt-2 back-direction">
                <button  class="text-gray gilroy-medium d-inline-flex align-items-center position-relative back-btn bg-transparent border-0" id="goBackButton">
                    {!! svgIcons('left_angle') !!}
                    <span class="ms-1 back-btn ns depositConfirmBackBtnText">{{ __('Back') }}</span>
                </button>
            </div>
            @else
            <div class="transaction-payment-method text-center">
                <p class="text-danger fw-bold">{{ __('Campaign not found') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('js')

    <script>
        'use strict';
        let paymentMethodSubmitBtnText = "{{ __('Continuing...') }}";
        let pretext = "{{ __('Continue') }}";
        var feesLimitUrl = "{{ route('donations.fees_limit') }}";
    </script>

    <script src="{{ asset('Modules/Donation/Resources/assets/js/methods.min.js') }}"></script>
@endsection
