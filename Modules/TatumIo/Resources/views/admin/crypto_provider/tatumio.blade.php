@php
updateTatumAssetCredentials();
$activeCryptoProviders = getProviderActiveStatus($providers);
@endphp

@extends('admin.layouts.master')

@section('title', __('Crypto Providers'))

@section('head_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/libraries/sweetalert/sweetalert.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('Modules/TatumIo/Resources/assets/admin/css/provider.min.css') }}">
@endsection

@section('page_content')
    <div class="box">
        <div class="panel-body ml-20">
            <ul class="nav nav-tabs cus f-14" role="tablist">
                @foreach ($providers as $cryptoProvider)
                    @if (isset($activeCryptoProviders[$cryptoProvider->alias]) && $activeCryptoProviders[$cryptoProvider->alias])
                        <li class="{{ $cryptoProvider->name == $selected_provider ? 'active' : 'nav-item' }}">
                            <a class="nav-link {{ $cryptoProvider->name == $selected_provider ? 'active' : '' }}" href="{{ route('admin.crypto_providers.list', $cryptoProvider->alias) }}">{{ $cryptoProvider->name }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>

    <!-- Provider header -->
    <div class="crypto-header">
        <div class="box-body box-body-navbar">
            <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex flex-row crypto-name-status justify-content-between flex-wrap">
                    <div class="top-bar-title padding-bottom pull-left">
                        {{ $provider->name }}
                        <strong class="{{ $provider->status == 'Active' ?'crypto-text-success' : 'crypto-text-danger' }}">
                            <small>
                            {{ !$provider->cryptoAssetSettings->isEmpty() ? '(' : '' }}
                                {{ json_decode($provider->subscription_details)->current_plan ?? '' }}
                            {{ !$provider->cryptoAssetSettings->isEmpty() ? ')' : '' }}
                            </small>
                        </strong>
                    </div>
                    @if (!$provider->cryptoAssetSettings->isEmpty())
                        <div class="crypto-card-2-logo-color-sample {{ $provider->status == 'Active' ? 'crypto-green' : 'crypto-red' }}"></div>
                    @endif
                </div>
                <div class="d-flex add-search">
                    <div id="container-search" class="container-search">
                        <input id="crypto-search-input" class="crypto-search-input" type="text" placeholder="Search">
                        <i id="crypto-search-icon" class="fa fa-search" aria-hidden="true"></i>
                    </div>

                    <!-- Provider status change switch -->
                    @if (!$provider->cryptoAssetSettings->isEmpty() && Common::has_permission(auth('admin')->user()->id, 'edit_crypto_provider'))
                        <label class="switch crypto-status-toggle">
                            <input type="checkbox" name="programming1" value="{{ $provider->name }}" class="provider-status" {{ $provider->status == 'Active' ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    @endif

                    <!-- Add Crypto Asset Button -->
                    @if (Common::has_permission(auth('admin')->user()->id, 'add_crypto_asset'))
                        <a href="{{ route('admin.tatumio_asset.create') }}" class="btn btn-theme pull-right f-14"><span class="fa fa-plus"></span>&nbsp;{{ __('Add New Asset') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Network list -->
    <div class="box">
        <div class="box-body box-body-customize mb-2">
            <!-- Main content -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <div>
                                <div class="row">
                                    @if (Common::has_permission(auth('admin')->user()->id, 'view_crypto_asset'))
                                        @foreach ($provider->cryptoAssetSettings as $cryptoAsset)
                                            @php
                                                $networkCredentials = json_decode($cryptoAsset->network_credentials);
                                            @endphp
                                            @if ($provider->name == 'TatumIo' && optional($cryptoAsset->currency)->type == 'crypto_asset' )
                                           <div class="mt-20 col-xxl-3 col-md-6 col-12">
                                                <div class="crypto-cards">
                                                    <div class="dropdown">
                                                        <span data-bs-toggle="dropdown">
                                                            <span class="crypto-dropdown">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="5" height="22"
                                                                    viewBox="0 0 5 22" fill="none">
                                                                    <circle cx="2.5" cy="2.5" r="2.5" fill="#C4C4C4" />
                                                                    <circle cx="2.5" cy="10.8333" r="2.5" fill="#C4C4C4" />
                                                                    <circle cx="2.5" cy="19.1667" r="2.5" fill="#C4C4C4" />
                                                                </svg>
                                                            </span>
                                                        </span>
                                                        <ul class="dropdown-menu pull-right crypto-dropdown-list xss f-14 p-0">
                                                            @if (Common::has_permission(auth('admin')->user()->id, 'edit_crypto_asset'))
                                                                <li class="px-2 py-1">
                                                                    <a class="px-2 py-1 d-block" href="{{ url(config('adminPrefix') . '/' . strtolower($provider->name) . '/edit/' . encrypt($cryptoAsset->network)) }}">{{ __('Edit') }}</a>
                                                                </li>
                                                            @endif
                                                            <li class="px-2 py-1">
                                                                <a href="javascript:void(0)" class="network px-2 py-1 d-block" data-network={{ encrypt($cryptoAsset->network) }}  data-status={{ $cryptoAsset->status == 'Active' ? 'Inactive' : 'Active' }}  >{{ $cryptoAsset->status == 'Active' ? __('Mark as Inactive') : __('Mark as Active')}}</a>
                                                            </li>
                                                            <li class="px-2 py-1">
                                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addressValidationModal" data-network={{ $cryptoAsset->network }} class="validate-network px-2 py-1 d-block">{{ __('Validate Address') }}</a>
                                                            </li>

                                                            <li class="px-2 py-1">
                                                                <a class="px-2 py-1 d-block" href="{{ url(config('adminPrefix') . '/' . strtolower($provider->name) . '/webhook/' . encrypt($cryptoAsset->network)) }}">{{ __('Webhook') }}</a>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                    <div class="crypto-card-header">
                                                        <span class="crypto-logo text-center">
                                                            @if (!empty(optional($cryptoAsset->currency)->logo) && fileExistCheck($cryptoAsset->currency->logo, 'currency'))
                                                                <img src="{{ image($cryptoAsset->currency->logo, 'currency') }}" alt="{{ __('Currency Logo') }}" class="img-w64">
                                                            @else
                                                                <img src='{{ image(null, 'currency') }}' class="img-w64">
                                                            @endif
                                                        </span>
                                                        <h3 class="crypto-name text-center"> {{ optional($cryptoAsset->currency)->name }} <span class="label label-{{ $cryptoAsset->status == 'Active' ? 'success' : 'danger' }}">{{ $cryptoAsset->status }}</span></h3>
                                                    </div>
                                                    <div class="crypto-information">
                                                        <div class="crypto-address">
                                                            <span class="address">{{ __('Merchant Address') }}</span>
                                                            <span class="address-name">{{ $networkCredentials->address }}</span>
                                                        </div>
                                                        <div class="account-bottom-border"></div>
                                                        <div class="crypto-information-aligns crypto-border">
                                                            <div class="crypto-balance">
                                                                <h3 class="text-light">{{ __('Acount Balance') }}</h3>
                                                                <p class="text-deep mb-0"> {{ optional($cryptoAsset->currency)->symbol }} {{ formatNumber($networkCredentials->balance, $cryptoAsset->currency_id) }}</p>
                                                            </div>

                                                            <div >
                                                                <h3 class="text-light text-end">{{ __('Network Code') }}</h3>
                                                                <p class="text-deep text-end mb-0">{{ optional($cryptoAsset->currency)->code }}</p>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="crypto-btn-container">
                                                        <a href="{{ route('admin.tatum.crypto_send.create', encrypt($cryptoAsset->network)) }}" class="crypto-send-btn">{{ __('Send') }}</a>
                                                        <a href="{{ route('admin.tatum.crypto_receive.create', encrypt($cryptoAsset->network)) }}" class="crypto-rec-btn">{{ __('Receive') }}</a>
                                                    </div>
                                                </div>
                                           </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Validate Crypto Address Modal-->
    <div class="modal fade" id="addressValidationModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header d-block">
                    <a class="close float-end f-18 fw-bold text-black-50 cursor-pointer" data-bs-dismiss="modal">×</a>
                    <p class="modal-title text-center mb-0 f-18"></p>
                </div>

                <form id="crypto-address-check-form" method="post">
                    @csrf
                    <input type="hidden" name="network" value="" id="address-validation-network">
                    <div class="modal-body">
                        <div id="validate-address-error">
                            <div class="alert-class">
                                <ul id="validate-address-error-message">
                                </ul>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-1"></div>
                                <div class="d-flex justify-content-center">
                                    <div class="form-group col-10">
                                        <label class="f-14 fw-bold mb-1">{{ __('Crypto Address') }}</label>
                                        <input type="text" class="form-control f-14" id="address" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-theme f-14" id="address-validate-button"><i class="fa fa-spinner fa-spin d-none"></i>
                                    <span id="address-validate-button-text">
                                        <strong>{{ __('Validate Address') }}</strong>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('extra_body_scripts')
    <script type="text/javascript" src="{{ asset('public/dist/libraries/sweetalert/sweetalert.min.js') }}"></script>
    <script>
        'use strict';
        var assetStatusChangeUrl = '{{ route("admin.tatumio_asset.status_change") }}';
        var validateAddressUrl = '{{ route("admin.tatumio_asset.validate_address") }}';
        var updateText = '{{ __("Updated") }}';
        var notFound = '{{ __("Not Found") }}';
        var wrongInput = '{{ __("Wrong Input") }}';
        var wentWrong = '{{ __("Something went wrong.") }}';
        var checking = '{{ __("Checking...") }}';
        var validateAddress = '{{ __("Validate Address") }}';
        var emptyAddress = '{{ __("Must specify an address to validate.") }}';
        var validateCryptoAddress= '{{ __("Validate :x crypto address") }}';
        var networkAddress = '{{ __(":x network address (eg - 3EvfKEKk13kXFJDaHXNfHbMbRXggNpojp5)") }}';
    </script>
    <script type="text/javascript" src="{{ asset('Modules/TatumIo/Resources/assets/admin/js/provider.min.js') }}"></script>
@endpush
