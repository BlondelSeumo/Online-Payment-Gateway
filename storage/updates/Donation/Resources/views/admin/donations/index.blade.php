@extends('admin.layouts.master')

@section('title', __('Campaigns'))
@section('head_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/DataTables/DataTables/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/DataTables/Responsive/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/libraries/jquery-ui/jquery-ui.min.css')}}">
@endsection

@section('page_content')
    <div class="box">
        <div class="box-body pb-20">
            <form class="form-horizontal" method="GET">
                <input type="hidden" name="user_id" id="user_id" value="{{ isset($user) ? $user : '' }}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <div class="d-flex flex-wrap">

                                <!-- Currency -->
                                <div class="pr-25">
                                    <label class="f-14 fw-bold mb-1" for="currency">{{ __('Currency') }}</label><br>
                                    <select class="form-control select2 f-14" name="currency" id="currency">
                                        <option value="all" {{ ($currency =='all') ? 'selected' : '' }} >All</option>
                                        @foreach($donationCurrencies as $donation)
                                            <option value="{{ $donation->currency_id }}" {{ ($donation->currency_id == $currency) ? 'selected' : '' }}>
                                                {{ optional($donation->currency)->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Status -->
                                <div class="pr-25">
                                    <label class="f-14 fw-bold mb-1" for="type">{{ __('Campaign Type') }}</label><br>
                                    <select class="form-control select2 f-14" name="type" id="type">
                                        <option value="all" {{ ($type =='all') ? 'selected' : '' }} >{{ __('All') }}</option>
                                        @foreach($donationTypes as $donation)
                                            <option value="{{ $donation->donation_type }}" {{ ($donation->donation_type == $type) ? 'selected' : '' }}>
                                                @if($donation->donation_type == 'any_amount')
                                                    {{ __('Any Amount') }}
                                                @elseif($donation->donation_type == 'fixed_amount')
                                                    {{ __('Fixed Amount') }}
                                                @else
                                                    {{ __("Suggest 3  Amount, plus 'other'") }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- User -->
                                <div class="pr-25">
                                    <label class="f-14 fw-bold mb-1" for="user">{{ __('User') }}</label><br>
                                    <input id="user-input" type="text" name="user" placeholder="Enter Name" class="form-control f-14" value="{{ !empty($getName) ? getColumnValue($getName->creator) : null }}">
                                    <span id="error-user"></span>
                                    
                                </div>
                            </div>
                            <div>
                                <br>
                                <div class="input-group">
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
            <h3 class="panel-title text-bold ml-5 f-14">{{ __('All Campaigns') }}</h3>
        </div>
        <div class="col-md-4">
            <div class="btn-group pull-right">
                <a href="" class="btn btn-sm btn-default btn-flat f-14" id="csv">{{ __('CSV') }}</a>&nbsp;&nbsp;
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
        var userSearchUrl = "{{ route('admin.donation.users.search') }}";
        var userErrorText = "{{ __('User Does Not Exist.') }}";
    </script>
    <script src="{{ asset('public/dist/plugins/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('public/dist/plugins/daterangepicker/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('public/dist/plugins/DataTables/DataTables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/dist/plugins/DataTables/Responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/dist/libraries/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>

    <script src="{{ asset('Modules/Donation/Resources/assets/js/admin/donation.min.js') }}" type="text/javascript"></script>

    {!! $dataTable->scripts() !!}


@endpush
