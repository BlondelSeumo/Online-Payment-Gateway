@extends('admin.layouts.master')
@section('title', __('Disputes'))

@section('head_style')
    <!-- Bootstrap daterangepicker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/daterangepicker/daterangepicker.min.css')}}">

    <!-- dataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/DataTables/DataTables/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/DataTables/Responsive/css/responsive.dataTables.min.css') }}">

    <!-- jquery-ui -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/libraries/jquery-ui/jquery-ui.min.css')}}">
@endsection

@section('page_content')
    <div class="box">
        <div class="box-body pb-20">
            <form class="form-horizontal" action="{{ url(config('adminPrefix').'/disputes') }}" method="GET" id='transaction_form'>

                <input id="startfrom" type="hidden" name="from" value="{{ isset($from) ? $from : '' }}">

                <input id="endto" type="hidden" name="to" value="{{ isset($to) ? $to : '' }}">

                <input id="user_id" type="hidden" name="user_id" value="{{ isset($user) ? $user : '' }}">

                <input id="transaction_id" type="hidden" name="transaction_id" value="{{ isset($transaction) ? $transaction : '' }}">

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

                                <div class="pr-25">
                                    <label class="f-14 fw-bold mb-1" for="status">{{ __('Status') }}</label><br>
                                    <select class="form-control select2" name="status" id="status">
                                        <option value="all" {{ ($status =='all') ? 'selected' : '' }} >{{ __('All') }}</option>
                                        @foreach($dispute_status as $dispute)
                                        <option value="{{ $dispute->status }}" {{ ($dispute->status == $status) ? 'selected' : '' }}>
                                            {{
                                                (
                                                    ($dispute->status == 'Reject') ? "Rejected" :
                                                    (
                                                        ($dispute->status == 'Solve') ? "Solved" : $dispute->status
                                                    )
                                                )
                                            }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="pr-25">
                                    <label class="f-14 fw-bold mb-1" for="user_input">{{ __('User') }}</label><br>
                                    <input id="user_input" type="text" name="user" placeholder="{{ __('Enter Name') }}" class="form-control f-14" value="{{ !empty($getName) ? getColumnValue($getName) : null }}">
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

    <div class="box box-default">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="top-bar-title padding-bottom">{{ __('Disputes') }}</div>
                </div>
            </div>
        </div>
    </div>

    @if (count($summary) != 0)
        <div class="box">
            <div class="box-body">
                <div class="row">
                    @foreach($summary as $data)
                        <div class="col-md-2">
                            <div class="panel panel-primary">
                                <div class="panel-body text-center">
                                    <span class="h5">{{ $data->total_status }}</span><br>
                                    <span class="f-14">
                                        @php
                                            echo getStatusText($data->status);
                                        @endphp
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12 f-14">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <div class="table-responsive">
                                {!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive', 'width' => '100%', 'cellspacing' => '0']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.layouts.partials.message_boxes')
@endsection

@push('extra_body_scripts')

<!-- Bootstrap daterangepicker -->
<script src="{{ asset('public/dist/plugins/daterangepicker/daterangepicker.min.js') }}" type="text/javascript"></script>

<!-- jquery.dataTables js -->
<script src="{{ asset('public/dist/plugins/DataTables/DataTables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/dist/plugins/DataTables/Responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>

<!-- jquery-ui -->
<script src="{{ asset('public/dist/libraries/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    'use strict';
    var sessionDateFormateType = "{{Session::get('date_format_type')}}";
    let dateRangePickerText = '{{ __("Pick a date range") }}';
    var startDate = "{!! $from !!}";
    var endDate   = "{!! $to !!}";
</script>

<script src="{{ asset('public/admin/customs/js/daterange-select.min.js') }}" type="text/javascript"></script>


{!! $dataTable->scripts() !!}

<script type="text/javascript">
    $(".select2").select2({});

    $(document).ready(function()
    {
        $("#user_input").on('keyup keypress', function(e)
        {
            if (e.type=="keyup" || e.type=="keypress")
            {
                var user_input = $('form').find("input[type='text']").val();
                if(user_input.length === 0)
                {
                    $('#user_id').val('');
                    $('#error-user').html('');
                    $('form').find("button[type='submit']").prop('disabled',false);
                }
            }
        });

        $('#user_input').autocomplete(
        {
            source:function(req,res)
            {
                if (req.term.length > 0)
                {
                    $.ajax({
                        url:'{{url(config('adminPrefix').'/disputes_user_search')}}',
                        dataType:'json',
                        type:'get',
                        data:{
                            search:req.term
                        },
                        success:function (response)
                        {
                            $('form').find("button[type='submit']").prop('disabled',true);

                            if(response.status == 'success')
                            {
                                res($.map(response.data, function (item)
                                {
                                    return {
                                            id : item.id,
                                            first_name : item.first_name,
                                            last_name : item.last_name,
                                            value: item.first_name + ' ' + item.last_name
                                        }
                                    }
                                ));
                            }
                            else if(response.status == 'fail')
                            {
                                $('#error-user').addClass('text-danger').html('User Does Not Exist!');
                            }
                        }
                    })
                }
                else
                {
                    $('#user_id').val('');
                }
            },
            select: function (event, ui)
            {
                var e = ui.item;

                $('#error-user').html('');

                $('#user_id').val(e.id);

                $('form').find("button[type='submit']").prop('disabled',false);
            },
            minLength: 0,
            autoFocus: true
        });
    });
</script>

@endpush
