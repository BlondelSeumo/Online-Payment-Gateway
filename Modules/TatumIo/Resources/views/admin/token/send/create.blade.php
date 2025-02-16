@extends('admin.layouts.master')

@section('title', __('Token Send'))

@section('head_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('Modules/TatumIo/Resources/assets/admin/css/crypto_sent_receive.min.css') }}">
@endsection

@section('page_content')
    <div class="row">
        <div class="col-md-2">
            <button type="button" class="btn btn-theme active mt-15 f-14">{{ __('Token Send') }}</button>
        </div>
        <div class="col-md-6"></div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="box box-info" id="crypto-send-create">
                <form action="{{ route('admin.tatum.token_send.confirm') }}" class="form-horizontal" id="admin-crypto-send-form" method="POST">
                    <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">

                    <div class="box-body">

                        <!-- Network -->
                        <div class="form-group row align-items-center" id="token-div">
                            <label class="col-sm-3 control-label f-14 fw-bold text-sm-end" for="user">{{ __('Token') }}</label>
                            <div class="col-sm-6">
                                <input class="form-control f-14 token" data-type="{{ $tokenDetails->symbol }}" name="token" type="text" value="{{ $tokenDetails->symbol }}" id="token" readonly>
                            </div>
                        </div>

                        <!-- Network -->
                        <div class="form-group row align-items-center" id="network-div">
                            <label class="col-sm-3 control-label f-14 fw-bold text-sm-end" for="user">{{ __('Network') }}</label>
                            <div class="col-sm-6">
                                <input class="form-control f-14 network" data-type="{{ $currency->type }}" name="network" type="text" value="{{ $network }}" id="network" readonly>
                            </div>
                        </div>

                        <!-- User -->
                        <div class="form-group row align-items-center" id="user-div">
                            <label class="col-sm-3 control-label f-14 fw-bold text-sm-end" for="user">{{ __('User') }}</label>
                            <div class="col-sm-6">
                                <select class="form-control f-14 sl_common_bx select2" name="user_id" id="user_id" required data-value-missing="{{ __("This field is required.") }}">
                                    <option value="">{{ __('Please select a user') }}</option>
                                    @foreach ($users as $key => $user)
                                        <option value='{{ $user->id }}'>{{ getColumnValue($user) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('extra_body_scripts')
    <script src="{{ asset('public/dist/plugins/debounce/jquery.ba-throttle-debounce.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('public/dist/libraries/sweetalert/sweetalert-unpkg.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('public/dist/plugins/html5-validation/validation.min.js') }}"  type="text/javascript" ></script>

    @include('common.restrict_number_to_pref_decimal')
    @include('common.restrict_character_decimal_point')

    <script type="text/javascript">
        'use strict';
        var addressBalanceUrl = '{{ route("admin.tatum.token.address_balance") }}';
        var validateBalanceUrl = '{{ route("admin.tatum.token.validate_balance") }}';
        var backButtonUrl = '{{ route("admin.tatumio.token") }}';
        var confirmationCryptoSentUrl = '{{ route("admin.tatum.crypto_send.confirm") }}';
        var network = '{{ $network }}';
        var cryptoToken = '{{ $tokenDetails->symbol }}';
        var tokenAddress = '{{ $tokenDetails->address }}';
        var tokenDecimals = '{{ $tokenDetails->decimals }}';
        var pleaseWait = '{{ __("Please Wait") }}';
        var loading = '{{ __("Loading...") }}';
        var errorText = '{{ __("Error!") }}'
        var merchantCryptoAddress = '{{ __("Merchant Address") }}';
        var merchantCryptoBalance = '{{ __("Merchant Balance") }}';
        var merchantTokenAddress = '{{ __("Token Address") }}';
        var merchantTokenBalance = '{{ __("Token Balance") }}';
        var merchantTokenDecimals = '{{ __("Token Decimals") }}';
        var userCryptoAddress = '{{ __("User Address") }}';
        var cryptoSentAmount = '{{ __("Amount") }}';
        var cryptoTransactionText = '{{ __("Crypto transactions might take few moments to complete.") }}';
        var minWithdrawan = '{{ __("The amount withdrawn/sent must at least be :x") }}';
        var minNetworkFee = '{{ __("Please keep at least :x for network fees.") }}';
        var minAmount = '{{ __("The minimum amount must be :x") }}';
        var networkFeeText = '{{ __("Natework fee will deduct from native wallet :x") }}';
        var send = '{{ __("Send") }}';
        var sending = '{{ __("Sending...") }}';
        var backButton = '{{ __("Back") }}';
        var nextButton = '{{ __("Next") }}';
        var requiredField = '{{ __("This field is required.") }}';
        var low = '{{ __("Low") }}';
        var medium = '{{ __("Medium") }}';
        var high = '{{ __("High") }}';
        var tatumIoMinLimit = "{{ $minTatumIoLimit }}";
    </script>

    <script src="{{ asset('Modules/TatumIo/Resources/assets/admin/js/token_send.min.js') }}"  type="text/javascript"></script>
@endpush
