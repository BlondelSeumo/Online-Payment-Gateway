@extends('admin.layouts.master')

@section('title', __('Token Sent Transactions'))

@section('head_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/daterangepicker/daterangepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/DataTables/DataTables/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/DataTables/Responsive/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/libraries/jquery-ui/jquery-ui.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('Modules/TatumIo/Resources/assets/admin/css/crypto_transaction.min.css') }}">
@endsection

@section('page_content')
    <div class="box">
        <div class="box-body pb-20">
            <form class="form-horizontal" action="{{ route('admin.token_sent_transaction.index') }}" method="GET">

                <input id="startfrom" type="hidden" name="from" value="{{ isset($from) ? $from : '' }}">
                <input id="endto" type="hidden" name="to" value="{{ isset($to) ? $to : '' }}">
                <input id="user_id" type="hidden" name="user_id" value="{{ isset($user) ? $user : '' }}">

                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <div class="d-flex flex-wrap">
                                <!-- Date and time range -->
                                <div class="pr-25">
                                    <label class="f-14 fw-bold mb-1">{{ __('Date Range') }}</label><br>
                                    <button type="button" class="btn btn-default f-14" id="daterange-btn" >
                                        <span id="drp">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <i class="fa fa-caret-down"></i>
                                    </button>
                                </div>

                                <!-- Currency -->
                                <div class="pr-25">
                                    <label class="f-14 fw-bold mb-1" for="currency">{{ __('Token') }}</label><br>
                                    <select class="form-control select2" name="currency" id="currency">
                                        <option value="all" {{ ($currency =='all') ? 'selected' : '' }} >{{ __('All') }}</option>
                                        @foreach($cryptoSentTransactionsCurrencies as $transaction)
                                            <option value="{{ $transaction->currency_id }}" {{ ($transaction->currency_id == $currency) ? 'selected' : '' }}>
                                                {{ $transaction->currency?->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="pr-25">
                                    <label class="f-14 fw-bold mb-1" for="status">{{ __('Status') }}</label><br>
                                    <select class="form-control select2" name="status" id="status">
                                        <option value="all" {{ ($status =='all') ? 'selected' : '' }} >{{ __('All') }}</option>
                                        @foreach($cryptoSentTransactionsStatus as $t)
                                            <option value="{{ $t->status }}" {{ ($t->status == $status) ? 'selected' : '' }}>{{ $t->status }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="pr-25">
                                    <label class="f-14 fw-bold mb-1">{{ __('User') }}</label><br>
                                    <input id="user_input" type="text" name="user" placeholder="{{ __('Enter Name') }}" class="form-control f-14" 
                                    value="{{ !empty($getName) ? getColumnValue($getName) : null }}">
                                    <span class="f-12" id="error-user"></span>
                                </div>
                            </div>

                            <div>
                                <div class="input-group mt-3">
                                   <button type="submit" name="btn" class="btn btn-theme f-14" id="btn">{{ __('Filter') }}</button>
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
            <p class="panel-title text-bold ml-5 mb-0 f-14">{{ __('All Token Sent Transactions') }}</p>
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
@endsection

@push('extra_body_scripts')
    <script src="{{ asset('public/dist/plugins/daterangepicker/daterangepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/dist/plugins/DataTables/DataTables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/dist/plugins/DataTables/Responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/dist/libraries/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>

    {!! $dataTable->scripts() !!}

    <script type="text/javascript">
        'use strict'
        var userSeachRoute = '{{ route("admin.token_sent_transaction.search_user") }}';
        var sessionDateFormateType = '{{ Session::get("date_format_type") }}'
        let dateRangePickerText = '{{ __("Pick a date range") }}';
        var startDate = "{!! $from !!}";
        var endDate = "{!! $to !!}";
        var csvUrl = ADMIN_URL + "/token-sent-transactions/csv";
        var pdfUrl = ADMIN_URL + "/token-sent-transactions/pdf";
    </script>
    <script src="{{ asset('Modules/TatumIo/Resources/assets/admin/js/token_sent_transaction.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/admin/customs/js/daterange-select.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/admin/customs/js/csv-pdf.min.js') }}" type="text/javascript"></script>


@endpush

