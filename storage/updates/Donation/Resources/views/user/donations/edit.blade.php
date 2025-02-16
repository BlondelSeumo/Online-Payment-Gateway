@extends('user.layouts.app')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/libraries/jquery-ui/jquery-ui.min.css') }}">
@endpush

@section('content')
    <!-- Main Content Start-->
    @include('user.common.alert')
    <div id="donation-create-container">
        <div class="text-center">
            <p class="mb-0 gilroy-Semibold f-26 text-dark theme-tran r-f-20 text-uppercase">{{ __('UPDATE CAMPAIGN') }}</p>
            <p class="mb-0 gilroy-medium text-gray-100 f-16 leading-26 r-f-12 merchant-title mt-2 tran-title">{{ __('Fill in the information needed to update campaign') }}</p>
        </div>
        <div class="new-merchant-top mt-24 mx-xl-5 mx-md-3">
            <div class="d-flex align-items-center back-direction mx-xl-5 mx-0">
                <a href="{{ route('user.donation.index') }}"
                    class="text-gray-100 f-16 leading-20 gilroy-medium d-inline-flex align-items-center position-relative back-btn">
                    <svg class="position-relative nscaleX-1" width="12" height="12" viewBox="0 0 12 12"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.47075 10.4709C8.7311 10.2105 8.7311 9.78842 8.47075 9.52807L4.94216 5.99947L8.47075 2.47087C8.7311 2.21053 8.7311 1.78842 8.47075 1.52807C8.2104 1.26772 7.78829 1.26772 7.52794 1.52807L3.52795 5.52807C3.2676 5.78842 3.2676 6.21053 3.52795 6.47088L7.52794 10.4709C7.78829 10.7312 8.2104 10.7312 8.47075 10.4709Z"
                            fill="currentColor"></path>
                    </svg>
                    <span class="ms-1 back-btn">{{ __('Back to list') }}</span>
                </a>
            </div>
            <div class="row dis-mb-top bg-white mx-xl-5 mt-3 mx-0">
                <form class="col-xl-12" method="post" action="{{ route('user.donation.update', $donation->id) }}" enctype="multipart/form-data" id="donation-form">
                    @csrf
                    <div class="donation-parent-form bg-white border-radius-8p mx-xxl-5">
                        <p class="mb-form f-24 leading-30 gilroy-Semibold text-dark text-center">{{ __('Campaign Form') }}</p>
                        <div class="row">
                            <div class="col-12">
                                <div class="label-top">
                                    <label class="gilroy-medium text-gray-100 mb-2 f-15 mt-32 r-mt-amount r-mt-6" for="title">{{ __('Campaign Title') }}<span class="f-16 text-warning">*</span></label>
                                    <input type="text" class="form-control donation input-form-control apply-bg" placeholder="{{ __('Campaign Title') }}" required data-value-missing="{{ __('This field is required.') }}" name="title" value="{{ old('title', $donation->title) }}" id="title">
                                </div>
                                <span class="error" id="title-error"></span>
                                @error('title')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="label-top">
                                    <label class="gilroy-medium text-gray-100 mb-2 f-15 mt-4 r-mt-amount" for="description" >{{ __('Campaign Description') }}<span class="f-16 text-warning">*</span></label>
                                    <textarea class="form-control l-s0 input-form-control h-100p" placeholder="{{ __('Campaign Description') }}" id="description" required data-value-missing="{{ __('This field is required.') }}" name="description">{{ old('description', $donation->description) }}</textarea>
                                </div>
                                @error('description')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mt-20 param-ref">
                                    <label class="gilroy-medium text-gray-100 mb-2 f-15" for="currency">{{ __('Currency') }}<span class="text-warning">*</span></label>

                                    @if ($donationPaymentCount > 0 )
                                        <input type="hidden" name="currency_id" value="{{ $donation->currency_id }}">
                                    @endif
                                    <select class="select2" data-minimum-results-for-search="Infinity" name="currency_id" id="currency" required data-value-missing="{{ __('This field is required.') }}" {{ $donationPaymentCount > 0 ? 'disabled' : '' }}>
                                        @foreach ($currencies as $currency)
                                            <option data-type="{{ $currency->type }}" value="{{ $currency->id }}"{{ old('currency_id') == $currency->id || $donation->currency_id == $currency->id ? 'selected' : '' }}>{{ $currency->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('currency_id')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <div class="mt-20 param-ref">
                                    <label class="gilroy-medium text-gray-100 mb-2 f-15" for="goal-amount">{{ __('Goal Amount') }}<span class="text-warning">*</span></label>
                                    <input type="text" class="form-control donation input-form-control apply-bg" id="goal-amount" onkeypress="return isNumberOrDecimalPointKey(this, event);" oninput="restrictNumberToPrefdecimalOnInput(this)" placeholder="{{ __('Enter goal amount') }}" required data-value-missing="{{ __('This field is required.') }}" value="{{ old('goal_amount', formatNumberWithoutComma($donation->goal_amount, $donation->currency_id)) }}" name="goal_amount">
                                </div>
                                @error('goal_amount')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6" id="campaign-type-div">
                                <div class="mt-20 param-ref">
                                    <label class="gilroy-medium text-gray-100 mb-2 f-15" for="campaign-type">{{ __('Campaign Type') }}<span class="text-warning">*</span></label>
                                    <select class="select2" data-minimum-results-for-search="Infinity" id="campaign-type" name="donation_type" required data-value-missing="{{ __('This field is required.') }}">
                                        <option value="any_amount" {{ (old('donation_type') == 'any_amount' || $donation->donation_type == 'any_amount') ? 'selected' : '' }}>{{ __('Any amount') }}</option>

                                        <option value="fixed_amount" {{ (old('donation_type') == 'fixed_amount' || $donation->donation_type == 'fixed_amount') ? 'selected' : '' }}>{{ __('Fixed amount') }}</option>

                                        <option value="suggested_amount" {{ (old('donation_type') == 'suggested_amount' || $donation->donation_type == 'suggested_amount') ? 'selected' : '' }}>{{ __("Suggest 3 amounts, plus 'other'") }}</option>
                                    </select>
                                </div>
                                @error('donation_type')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-6" id="fixed-amount-div">
                                <div class="mt-20 param-ref">
                                    <label class="gilroy-medium text-gray-100 mb-2 f-15" for="fixed-amount">{{ __('Fixed Amount') }}<span class="text-warning">*</span></label>
                                    <input type="text" class="form-control donation input-form-control apply-bg" id="fixed-amount" onkeypress="return isNumberOrDecimalPointKey(this, event);" oninput="restrictNumberToPrefdecimalOnInput(this)" placeholder="{{ __('Enter fixed amount') }}" data-value-missing="{{ __('This field is required.') }}" value="{{ old('fixed_amount', formatNumberWithoutComma($donation->fixed_amount, $donation->currency_id)) }}" name="fixed_amount">
                                </div>
                                @error('fixed_amount')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-flex gap-20" id="suggestion-div">
                                <div class="label-top" id="first-suggested-div">
                                    <label class="gilroy-medium text-gray-100 mb-2 f-15 mt-24 r-mt-amount r-mt-6" for="first-suggestion">{{ __('First') }}<span
                                            class="f-16 text-warning">*</span></label>
                                    <input type="text" class="form-control donation input-form-control apply-bg" placeholder="0.00" onkeypress="return isNumberOrDecimalPointKey(this, event);" oninput="restrictNumberToPrefdecimalOnInput(this)" data-value-missing="{{ __('This field is required.') }}" value="{{ old('first_suggested_amount', formatNumberWithoutComma($donation->first_suggested_amount, $donation->currency_id)) }}" name="first_suggested_amount" id="first-suggestion">
                                </div>
                                @error('first_suggested_amount')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                                <div class="label-top" id="second-suggested-div">
                                    <label class="gilroy-medium text-gray-100 mb-2 f-15 mt-24 r-mt-amount r-mt-6" for="second-suggestion">{{ __('Second') }}<span class="f-16 text-warning">*</span></label>
                                    <input type="text" class="form-control donation input-form-control apply-bg" placeholder="0.00" onkeypress="return isNumberOrDecimalPointKey(this, event);" oninput="restrictNumberToPrefdecimalOnInput(this)" data-value-missing="{{ __('This field is required.') }}" value="{{ old('second_suggested_amount', formatNumberWithoutComma($donation->second_suggested_amount, $donation->currency_id)) }}" name="second_suggested_amount" id="second-suggestion">
                                </div>
                                @error('second_suggested_amount')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                                <div class="label-top" id="third-suggested-div">
                                    <label class="gilroy-medium text-gray-100 mb-2 f-15 mt-24 r-mt-amount r-mt-6" for="third-suggestion">{{ __('Third') }}<span class="f-16 text-warning">*</span></label>
                                    <input type="text" class="form-control donation input-form-control apply-bg" placeholder="0.00" onkeypress="return isNumberOrDecimalPointKey(this, event);" oninput="restrictNumberToPrefdecimalOnInput(this)" data-value-missing="{{ __('This field is required.') }}" value="{{ old('third_suggested_amount', formatNumberWithoutComma($donation->third_suggested_amount, $donation->currency_id)) }}" name="third_suggested_amount" id="third-suggestion">
                                </div>
                                @error('third_suggested_amount')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <div class="mt-20 param-ref">
                                    <label class="gilroy-medium text-gray-100 mb-2 f-15" for="end-date">{{ __('End Date') }}<span class="text-warning">*</span></label>
                                    <input type="text" class="form-control datepicker donation input-form-control apply-bg cursor-pointer" id="end-date" placeholder="{{ __('End Date') }}" autocomplete="off" required data-value-missing="{{ __('This field is required.') }}" name="end_date" value="{{ date('d-m-Y', strtotime($donation->end_date)) }}">
                                </div>
                                @error('end_date')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            @if(preference('donation_fee_applicable') == 'yes')
                            <div class="col-6">
                                <div class="mt-20 param-ref">
                                    <label class="gilroy-medium text-gray-100 mb-2 f-15" for="fee-bearer">{{ __('Will the donor cover the fee?') }}<span class="text-warning">*</span></label>
                                    <select class="select2" data-minimum-results-for-search="Infinity" id="fee-bearer" name="fee_bearer" required data-value-missing="{{ __('This field is required.') }}">
                                        <option value="No" {{ (old('fee_bearer') == 'No' || $donation->fee_bearer == 'creator') ? 'selected' : '' }} >{{ __('No') }}</option>
                                        <option value="Yes" {{ (old('fee_bearer') == 'Yes' || $donation->fee_bearer == 'donor') ? 'selected' : '' }} >{{ __('Yes') }}</option>
                                    </select>
                                </div>
                                @error('fee_bearer')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif
                            <div class="col-12" id="image-div">
                                <div class="mb-2 mt-24 attach-file label-top">
                                    <label for="banner-image" class="form-label text-gray-100 gilroy-medium">{{ __('Banner image') }}</label>
                                    <input class="form-control upload-filed focus-bgcolor" type="file" id="banner-image" name="banner_image">
                                </div>
                                @if (!empty($file) && file_exists('Modules/Donation/public/uploads/' . $file->filename))
                                    <img src="{{ asset('Modules/Donation/public/uploads/' . $file->filename) }}" id="banner-image-preview" width="200" height="100">
                                    <input type="hidden" name="existingBannerFileID" value="{{ $file->id }}">
                                @else
                                    <img src="" id="banner-image-preview">
                                @endif

                                <p class="mb-resulation f-12 leading-15 gilroy-regular text-gray-100">{{ __('Recommended Image Resolution: 710 x 400 (:x)', ['x' => implode(', ', getFileExtensions(3))]) }}</p>

                                <p class="error" id="dimension-error"></p>
                                @error('banner_image')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg btn-primary mt-4" id="donation-submit-btn">
                                <div class="spinner spinner-border text-white spinner-border-sm mx-2 d-none">
                                    <span class="visually-hidden"></span>
                                </div>
                                <span id="donation-submit-btn-text">{{ __('Update Campaign') }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Main Content End-->

@endsection

@push('js')
    @include('common.restrict_number_to_pref_decimal')
    @include('common.restrict_character_decimal_point')

    <script src="{{ asset('public/dist/plugins/html5-validation/validation.min.js') }}"></script>
    <script src="{{ asset('public/dist/libraries/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        'use strict';
        var maxImageDimensionError = "{{ __('Image dimension can not be greater than 710px X 400px.') }}";
        var minImageDimensionError = "{{ __('Image dimension can not be less than 365px X 200px.') }}";
        var titleErrorText = "{{ __('Title can only have text and number') }}";
        var _URL = window.URL || window.webkitURL;
        var submitButtonText = "{{ __('Updating...') }}";
        var feeApplicable = "{{ preference('donation_fee_applicable') }}";
    </script>

    <script src="{{ asset('Modules/Donation/Resources/assets/js/user/donation.min.js') }}" type="text/javascript"></script>
@endpush