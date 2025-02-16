@extends('admin.layouts.master')

@section('title', __('Donation Preference Settings'))

@section('page_content')
    <!-- Main content -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ __('Donation Preference Settings') }}</h3>
                </div>
                <form action="{{ route('admin.donation.preferences.store') }}" method="post" id="donation-preference-setting-form" class="form-horizontal">
                    {!! csrf_field() !!}
                    <div class="box-body">
                        <!-- Available For -->
                        <div class="form-group row">
                            <label class="col-sm-4 control-label f-14 fw-bold text-sm-end">{{ __('Available For') }} :</label>
                            <div class="col-sm-6" >
                                <div class="form-check fw-bold f-14">
                                    <input class="form-check-input" type="radio" id="merchant" name="donation_available_for" {{ (isset($preferenceData['donation']['donation_available_for']) && $preferenceData['donation']['donation_available_for'] == 'merchant') ? 'checked':"" }} value="merchant">
                                    <label for="merchant">{{ __('Merchant') }}</label>
                                </div>

                                <div class="form-check fw-bold f-14">
                                    <input class="form-check-input" type="radio" id="user" name="donation_available_for" {{ (isset($preferenceData['donation']['donation_available_for']) && $preferenceData['donation']['donation_available_for'] == 'user') ? 'checked':"" }} value="user" >
                                    <label for="user">{{ __('User') }}</label>
                                </div>

                                <div class="form-check fw-bold f-14">
                                    <input class="form-check-input" type="radio" id="both" name="donation_available_for" {{ (isset($preferenceData['donation']['donation_available_for']) && $preferenceData['donation']['donation_available_for'] == 'both') ? 'checked':"" }} value="both" >
                                    <label for="both">{{ __('Both') }}</label>
                                </div>

                                <small class="form-text text-muted f-12">
                                    <strong>*{{ __('This value will determine, what type of user can create campaign.') }}</strong>
                                </small>
                                <br>
                                <span class="text-danger">{{ $errors->first('donation_available_for') }}</span>
                            </div>
                        </div>
                        <hr>
                        <!-- Fee Applicable -->
                        <div class="form-group row">
                            <label class="col-sm-4 control-label f-14 fw-bold text-sm-end">{{ __('Fee Applicable') }} :</label>
                            <div class="col-sm-6" >
                                <div class="form-check fw-bold f-14">
                                    <input class="form-check-input" type="radio" id="yes" name="donation_fee_applicable" {{ (isset($preferenceData['donation']['donation_fee_applicable']) && $preferenceData['donation']['donation_fee_applicable'] == 'yes') ? 'checked':"" }} value="yes">
                                    <label for="yes">{{ __('Yes') }}</label>
                                </div>
                                <div class="form-check fw-bold f-14">
                                    <input class="form-check-input" type="radio" id="no" name="donation_fee_applicable" {{ (isset($preferenceData['donation']['donation_fee_applicable']) && $preferenceData['donation']['donation_fee_applicable'] == 'no') ? 'checked':"" }} value="no" >
                                    <label for="no">{{ __('No') }}</label>
                                </div>
                                <small class="form-text text-muted f-12">
                                    <strong>*{{ __('If set to Yes, campaign creator will have an option to set the fee bearer.') }}</strong>
                                </small>
                                <br>
                                <span class="text-danger">{{ $errors->first('donation_fee_applicable') }}</span>
                            </div>
                        </div>

                        @if(Common::has_permission(\Auth::guard('admin')->user()->id, 'add_campaign_setting'))
                            <div class="box-footer">
                                <div class="col-md-10">
                                    <button type="submit" class="btn btn-theme pull-right f-14" id="preference-submit-btn">
                                    <i class="fa fa-spinner fa-spin d-none"></i> <span id="preference-submit-btn-txt">{{ __('Save Settings') }}</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.box -->
@endsection

@push('extra_body_scripts')
<script type="text/javascript">
    'use strict';
    var submitButtonText = "{{ __('Updating...') }}";
</script>
<script src="{{ asset('public/dist/plugins/html5-validation/validation.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('Modules/Donation/Resources/assets/js/admin/preference-setting.min.js') }}"></script>
@endpush
