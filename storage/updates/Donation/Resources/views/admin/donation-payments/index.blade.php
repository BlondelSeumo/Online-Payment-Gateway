@extends('admin.layouts.master')

@section('title', __('Campaigns Payments'))

@section('head_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/daterangepicker/daterangepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/DataTables/DataTables/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/DataTables/Responsive/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/libraries/jquery-ui/jquery-ui.min.css')}}">
@endsection

@section('page_content')
    <div class="box">
        <div class="box-body pb-20">
            <form class="form-horizontal" method="GET">
                <input id="startfrom" type="hidden" name="from" value="{{ isset($from) ? $from : '' }}">
                <input id="endto" type="hidden" name="to" value="{{ isset($to) ? $to : '' }}">
                <input id="user_id" type="hidden" name="user_id" value="{{ isset($user) ? $user : '' }}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <div class="d-flex flex-wrap">

                                <!-- Date and time range -->
                                <div class="pr-25">
                                    <label class="f-14 fw-bold mb-1" for="daterange-btn">{{ __('Date Range') }}</label><br>
                                    <button type="button" class="btn btn-default f-14" id="daterange-btn" >
                                        <span id="drp">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <i class="fa fa-caret-down"></i>
                                    </button>
                                </div>

                                <!-- Currency -->
                                <div class="pr-25">
                                    <label class="f-14 fw-bold mb-1" for="currency">{{ __('Currency') }}</label><br>
                                    <select class="form-control select2 f-14" name="currency" id="currency">
                                        <option value="all" {{ ($currency =='all') ? 'selected' : '' }} >{{ __('All') }}</option>
                                        @foreach($donationCurrencies as $donationCurrency)
                                            <option value="{{ $donationCurrency->currency_id }}" {{ ($donationCurrency->currency_id == $currency) ? 'selected' : '' }}>
                                                {{ optional($donationCurrency->currency)->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Status -->
                                <div class="pr-25">
                                    <label class="f-14 fw-bold mb-1" for="status">{{ __('Status') }}</label><br>
                                    <select class="form-control select2 f-14" name="status" id="status">
                                        <option value="all" {{ ($status =='all') ? 'selected' : '' }} >{{ __('All') }}</option>
                                        @foreach($donationStatuses as $donationStatus)
                                            <option value="{{ $donationStatus->status }}" {{ ($donationStatus->status == $status) ? 'selected' : '' }}>
                                                @if($donationStatus->status == 'blocked')
                                                    {{ __('Cancelled') }}
                                                @elseif($donationStatus->status == 'success')
                                                    {{ __('Success') }}
                                                @else
                                                    {{ $donationStatus->status }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Payment Method -->
                                <div class="pr-25">
                                    <label class="f-14 fw-bold mb-1" for="status">{{ __('Payment Method') }}</label><br>
                                    <select class="form-control select2 f-14" name="payment_method" id="payment_methods">
                                        <option value="all" {{ ($paymentMethod =='all') ? 'selected' : '' }} >{{ __('All') }}</option>
                                        @foreach($donationPaymentMethods as $donationPaymentMethod)
                                            <option value="{{ $donationPaymentMethod->payment_method_id }}" {{ ($donationPaymentMethod->payment_method_id == $paymentMethod) ? 'selected' : '' }}>
                                                {{ (optional($donationPaymentMethod->paymentMethod)->name == "Mts") ? settings('name') : getColumnValue($donationPaymentMethod->paymentMethod, 'name') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- User -->
                                <div class="pr-25">
                                    <label class="f-14 fw-bold mb-1" for="user">{{ __('User') }}</label><br>
                                    <input id="user_input" type="text" name="user" placeholder="Enter Name" class="form-control f-14" value="{{ !empty($getName) ? getColumnValue($getName->payer) : null }}">
                                    <span id="error-user"></span>
                                </div>
                            </div>
                            <div>
                                <br>
                                <div class="input-group" >
                                    <button type="submit" name="btn" class="btn btn-theme" id="btn">{{ __('Filter') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <h3 class="panel-title text-bold ml-5 f-14">{{ __('All Campaign Payments') }}</h3>
        </div>
        <div class="col-md-4">
            <div class="btn-group pull-right">
                <a href="" class="btn btn-sm btn-default btn-flat f-14" id="csv">{{ __('CSV') }}</a>
                <a href="" class="btn btn-sm btn-default btn-flat f-14" id="pdf">{{ __('PDF') }}</a>
            </div>
        </div>
    </div>


    <div class="box mt-20">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12 f-14">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <div class="table-responsive">
                            {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive transactions', 'width' => '100%', 'cellspacing' => '0']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('extra_body_scripts')

    <script type="text/javascript">
        'use strict';
        var sDate;
        var eDate;
        var sessionDate = "{{session('date_format_type')}}";
        var sessionDateFinal = sessionDate.toUpperCase();
        var startDate = "{!! $from !!}";
        var endDate   = "{!! $to !!}";
        var userErrorText = "{{ __('User Does Not Exist.') }}";
        var userSearchUrl = "{{ route('admin.donation-payment.users.search') }}";
        let dateRangePickerText = '{{ __('Pick a date range') }}';
    </script>
    <script src="{{ asset('public/dist/plugins/daterangepicker/daterangepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/dist/plugins/DataTables/DataTables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/dist/plugins/DataTables/Responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/dist/libraries/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('Modules/Donation/Resources/assets/js/admin/donation-payment.min.js') }}" type="text/javascript"></script>

    {!! $dataTable->scripts() !!}

@endpush
