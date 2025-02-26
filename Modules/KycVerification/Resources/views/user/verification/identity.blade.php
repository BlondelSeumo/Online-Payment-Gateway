@extends('user.layouts.app')

@section('content')
    <div class="bg-white pxy-62 exchange pt-62 shadow" id="identity-verify">
        <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">{{ __('Verifications') }}</p>
        <div class="row">
            <div class="col-12">
                <nav>
                    <div class="nav-tab-parent d-flex justify-content-center mt-4">
                        <div class="d-flex p-2 border-1p rounded-pill gap-1 bg-white nav-tab-child">
                            <a href="{{ route('user.kyc.verifications.initiate') }}" class="tablink-edit text-gray-100 tabactive">{{ __('Identity Verification') }}</a>
                            <a href="{{ route('user.kyc.verifications.address') }}" class="tablink-edit text-gray-100">{{ __('Address Verification') }}</a>
                            @if ($two_step_verification != 'disabled')
                                <a href="{{ route('user.setting.twoFa') }}" class="tablink-edit text-gray-100">{{ __('TwoFa') }}</a>
                            @endif
                        </div>
                    </div>
                </nav>
                @include('user.common.alert')
                <div class="mt-28 label-top">
                    <form method="post" action="{{ route('user.kyc.verifications.process') }}" enctype="multipart/form-data" id="identity-verify-form">
                        @csrf
                        <input type="hidden" value="identity" name="verification_type">
                        <input type="hidden" value="{{ !empty($verification->file_id) ? $verification->file_id : '' }}" name="existing_file_id"  />
                        <input type="hidden" value="{{ $provider->id }}" name="provider_id"/>

                        <div class="mt-28 param-ref">
                            <label class="gilroy-medium text-gray-100 mb-2 f-15" for="identity-type">{{ __('Identity Type') }} <span class="text-danger">*</span> </label>
                            @if(!empty($verification->status))
                                <span class="gilroy-medium {{ getColor(ucfirst($verification->status)) }} f-15">{{ ' (' . $verification->status . ')' }}</span>
                            @endif
                            <div class="avoid-blink">
                                <select class="select2 sl_common_bx" data-minimum-results-for-search="Infinity" name="identity_type" id="identity-type" required data-value-missing="{{ __('This field is required.') }}">
                                    <option value="national_id" {{ !empty($verification->identity_type) && $verification->identity_type == 'national_id' ? 'selected' : '' }}>{{ __('National ID') }}</option>
                                    <option value="driving_license" {{ !empty($verification->identity_type) && $verification->identity_type == 'driving_license' ? 'selected' : '' }}>{{ __('Driving License') }}</option>
                                    <option value="passport" {{ !empty($verification->identity_type) && $verification->identity_type == 'passport' ? 'selected' : '' }}>{{ __('Passport') }}</option>
                                </select>
                                @error('identity_type')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="label-top mt-20">
                            <label class="gilroy-medium text-gray-100 f-15 mb-2" for="identity-number">{{ __('Identity Number') }} <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control input-form-control apply-bg l-s2" name="identity_number" id="identity-number" value="{{ !empty($verification->identity_number) ? $verification->identity_number : '' }}" required data-value-missing="{{ __('This field is required.') }}">

                            @error('identity_number')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="attach-file attach-print amount-label">
                            <label class="gilroy-medium text-B87 f-15 mb-2 mt-24 r-mt-amount r-mt-6" for="verification-file">{{ __('Attach Identity Proof') }} <span class="text-danger">*</span> </label>
                            <input type="file" class="form-control upload-filed" name="verification_file" id="verification-file" required data-value-missing="{{ __('This field is required.') }}">
                            @error('verification_file')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <p class="mb-0 f-11 gilroy-regular text-B87 mt-10">{{ __('Upload your documents (Max: :x mb)', ['x' => preference('file_size')]) }}</p>

                        @if (!empty($verification->file))
                        <div class="proof-btn-div d-flex justify-content-start mt-3">
                            <a href="{{ route('user.kyc.verifications.proof.download', ['identity', $verification->file?->filename]) }}" class='btn f-10 leading-12 proof-btn p-0 border-DF bg-FFF text-dark'><span>{{ $verification->file?->originalname }}</span>
                                {!! svgIcons('download_icon') !!}
                            </a>
                        </div>
                        @endif
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary px-4 py-2 mt-3" id="identity-verify-submit-btn">
                                <div class="spinner spinner-border text-white spinner-border-sm mx-2 d-none" role="status">
                                    <span class="visually-hidden"></span>
                                </div>
                                <span id="identity-verify-submit-btn-txt">{{ __('Verify Identity') }}</span>
                            </button>
                        </div>
                    </form>
                    <!-- 2nd step end-->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('public/dist/plugins/html5-validation/validation.min.js') }}"></script>
    <script>
        'use strict';
        var submitButtonText = "{{ __('Submitting...') }}";
    </script>
    <script src="{{ asset('Modules/KycVerification/Resources/assets/js/user/manual-verify.min.js') }}"></script>
@endpush
