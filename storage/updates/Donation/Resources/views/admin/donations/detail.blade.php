@extends('admin.layouts.master')

@section('title', __('Campaign Detail'))

@section('page_content')

<div class="box box-default">
	<div class="box-body">
		<div class="d-flex justify-content-between">
			<div>
				<div class="top-bar-title padding-bottom pull-left">{{ __('Campaign Detail') }}</div>
			</div>
		</div>
	</div>
</div>

<section class="min-vh-100">
    <div class="my-30">
        <div class="row f-14">
            <div class="col-md-8">
                <div class="box">
                    <div class="box-body">
                        <div class="panel">
                            <div class="p-4 rounded">
                                <!-- Creator -->
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 fw-bold text-end" for="inputEmail3">
                                        {{ __('Creator') }}
                                    </label>
                                    <div class="col-sm-6">
                                        <p class="form-control-static">
                                            <?php $creator = getColumnValue($donation->creator);?>
                                            {!! (Common::has_permission(auth()->guard('admin')->user()->id, 'edit_user')) ? '<a href="' . url(Config::get('adminPrefix').'/users/edit/' . $donation->creator?->id) . '">' . $creator . '</a>' : $creator !!}
                                        </p>
                                    </div>
                                </div>

                                <!-- Title -->
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 fw-bold text-end" for="inputEmail3">
                                        {{ __('Campaign Title') }}
                                    </label>
                                    <div class="col-sm-6">
                                        <p class="form-control-static">{{ $donation->title }}</p>
                                    </div>
                                </div>

                                <!-- Title -->
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 fw-bold text-end" for="inputEmail3">
                                        {{ __('Currency') }}
                                    </label>
                                    <div class="col-sm-6">
                                        <p class="form-control-static">{{ optional($donation->currency)->code }}</p>
                                    </div>
                                </div>

                                <!-- Campaign type -->
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 fw-bold text-end" for="donation_type">
                                        {{ __('Campaign type') }}
                                    </label>
                                    <div class="col-sm-6">
                                        <p class="form-control-static">
                                            {{ ucwords(str_replace('_', ' ', $donation->donation_type)) }}
                                        </p>
                                    </div>
                                </div>

                                @if ($file != null && file_exists('Modules/Donation/public/uploads/' . $file->filename))
                                    <!-- Banner image -->
                                    <div class="form-group row">
                                        <label class="control-label col-sm-3 fw-bold text-end" for="status">{{ __('Banner image') }}</label>
                                        <div class="col-sm-6">
                                            <a href="{{ url('Modules/Donation/public/uploads/' . $file->filename) }}" target="_blank" >
                                                {{ $file->filename }}
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <!-- Display banner image -->
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 fw-bold text-end" for="status">{{ __('Display banner image') }}</label>
                                    <div class="col-sm-6">
                                        <p class="form-control-static">{{ ucfirst($donation->display_brand_image) }}</p>
                                    </div>
                                </div>
                                <!-- End date -->
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 fw-bold text-end" for="status">{{ __('End date') }}</label>
                                    <div class="col-sm-6">
                                        <p class="form-control-static">{{ date('d-M-Y', strtotime($donation->end_date)) }}</p>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 fw-bold text-end" for="status">{{ __('Description') }}</label>
                                    <div class="col-sm-6">
                                        <p class="form-control-static">{{ $donation->description }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 offset-md-3">
                                        <a class="btn btn-theme-danger me-1 f-14" href="{{ route('admin.donation.index') }}">{{ __('Back') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box">
                    <div class="box-body">
                        <div class="panel">
                            <div class="p-4 rounded">
                                <!-- Goal amount -->
                                <div class="form-group row">
                                    <label class="control-label col-sm-6 fw-bold text-end" for="inputEmail3">
                                        {{ __('Goal amount') }}
                                    </label>
                                    <div class="col-sm-6">
                                        <p class="form-control-static">{{ moneyFormat(optional($donation->currency)->symbol, formatNumber($donation->goal_amount, $donation->currency_id)) }}</p>
                                    </div>
                                </div>
                                <!-- Raised amount -->
                                <div class="form-group row">
                                    <label class="control-label col-sm-6 fw-bold text-end" for="inputEmail3">
                                        {{ __('Raised amount') }}
                                    </label>
                                    <div class="col-sm-6">
                                        <p class="form-control-static">{{ moneyFormat(optional($donation->currency)->symbol, formatNumber($donation->raised_amount, $donation->currency_id)) }}</p>
                                    </div>
                                </div>

                                <!-- Fixed amount -->
                                @if($donation->donation_type == 'fixed_amount')
                                    <div class="form-group row">
                                        <label class="control-label col-sm-6 fw-bold text-end" for="inputEmail3">
                                            {{ __('Fixed amount') }}
                                        </label>
                                        <div class="col-sm-6">
                                            <p class="form-control-static">{{ moneyFormat(optional($donation->currency)->symbol, formatNumber($donation->fixed_amount, $donation->currency_id)) }} </p>

                                        </div>
                                    </div>
                                @endif

                                @if($donation->donation_type == 'suggested_amount')
                                    <!-- First suggested amount -->
                                    <div class="form-group row">
                                        <label class="control-label col-sm-6 fw-bold text-end" for="inputEmail3">
                                            {{ __('First suggested amount') }}
                                        </label>
                                        <div class="col-sm-6">
                                            <p class="form-control-static">{{ moneyFormat(optional($donation->currency)->symbol, formatNumber($donation->first_suggested_amount, $donation->currency_id)) }}</p>
                                        </div>
                                    </div>

                                    <!-- Second suggested amount -->
                                    <div class="form-group row">
                                        <label class="control-label col-sm-6 fw-bold text-end" for="inputEmail3">
                                            {{ __('Second suggested amount') }}
                                        </label>
                                        <div class="col-sm-6">
                                            <p class="form-control-static">{{ moneyFormat(optional($donation->currency)->symbol, formatNumber($donation->second_suggested_amount, $donation->currency_id)) }} </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Third suggested amount -->
                                    <div class="form-group row" >
                                        <label class="control-label col-sm-6 fw-bold text-end" for="inputEmail3">
                                            {{ __('Third suggested amount') }}
                                        </label>
                                        <div class="col-sm-6">
                                            <p class="form-control-static"> {{ moneyFormat(optional($donation->currency)->symbol, formatNumber($donation->third_suggested_amount, $donation->currency_id)) }} </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


