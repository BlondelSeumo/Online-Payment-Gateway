@extends('admin.layouts.master')

@section('title', __('Users'))

@section('head_style')
<!-- dataTables -->
<link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/DataTables/DataTables/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/dist/plugins/DataTables/Responsive/css/responsive.dataTables.min.css') }}">
@endsection

@section('page_content')
    <div class="box box-default">
        <div class="box-body">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="top-bar-title padding-bottom pull-left">{{ __('Users') }}</div>
                </div>

                <div>
                    @if(Common::has_permission(auth('admin')->user()->id, 'add_user'))
                        <a href="{{url(config('adminPrefix').'/users/create')}}" class="btn btn-theme f-14"><span class="fa fa-plus"> &nbsp;</span>{{ __('Add User') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <div class="table-responsive">
                                {!! $dataTable->table(['class' => 'table table-striped table-hover f-14 dt-responsive', 'width' => '100%', 'cellspacing' => '0']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('extra_body_scripts')

<!-- jquery.dataTables js -->
<script src="{{ asset('public/dist/plugins/DataTables/DataTables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/dist/plugins/DataTables/Responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>

{!! $dataTable->scripts() !!}

<script type="text/javascript">
</script>
@endpush
