@extends('user.layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('public/dist/plugins/daterangepicker/daterangepicker.min.css') }}">
@endpush

@section('content')
    <div class="text-center">
        <p class="mb-0 gilroy-Semibold f-26 text-dark theme-tran r-f-20">{{ __('CAMPAIGN PAYMENTS') }}</p>
        <p class="mb-0 gilroy-medium text-gray-100 f-16 r-f-12 l-sp64  mt-2 tran-title">{{ __('History of all your campaign transactions in one place') }}</p>
    </div>
    <div class="mt-22 mt-sm-4">
        <div class="d-flex justify-content-between align-items-center r-pb-8 pb-10">
            <p class="mb-0 text-gray-100 f-16 r-f-12 gilroy-medium dark-CDO">{{ __('All campaign payments') }}</p>
            <div class="d-flex align-items-center">
                <p class="mb-0 text-gray-100 f-16 r-f-12 gilroy-medium dark-CDO pt-5p">{{ __('Filter') }}</p>
                <a class="fil-btn ml-12">
                <img src="{{ asset('public/dist/images/filter-on.svg') }}" alt="{{ __('Filter') }}">
                <img src="{{ asset('public/dist/images/filter-cross.svg') }}" alt="{{ __('Cross') }}" class="cross-none">
                </a>
            </div>
        </div>

        <form action="" method="get">
            <div class="filter-panel">
                <div class="d-flex flex-wrap justify-content-between pb-26">
                    <div class="d-flex flex-wrap align-items-center pb-2 pb-xl-0">

                        <input id="startfrom" type="hidden" name="from" value="{{ isset($from) ? $from : '' }}">
                        <input id="endto" type="hidden" name="to" value="{{ isset($to) ? $to : '' }}">

                        <!-- DateRange Picker -->
                        <div class="me-2">
                            <div id="daterange-btn" class="param-ref filter-ref h-45 custom-daterangepicker">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8 1C8.55229 1 9 1.44772 9 2V3H15V2C15 1.44772 15.4477 1 16 1C16.5523 1 17 1.44772 17 2V3.00163C17.4755 3.00489 17.891 3.01471 18.2518 3.04419C18.8139 3.09012 19.3306 3.18868 19.816 3.43597C20.5686 3.81947 21.1805 4.43139 21.564 5.18404C21.8113 5.66937 21.9099 6.18608 21.9558 6.74817C22 7.28936 22 7.95372 22 8.75868V17.2413C22 18.0463 22 18.7106 21.9558 19.2518C21.9099 19.8139 21.8113 20.3306 21.564 20.816C21.1805 21.5686 20.5686 22.1805 19.816 22.564C19.3306 22.8113 18.8139 22.9099 18.2518 22.9558C17.7106 23 17.0463 23 16.2413 23H7.75868C6.95372 23 6.28936 23 5.74817 22.9558C5.18608 22.9099 4.66937 22.8113 4.18404 22.564C3.43139 22.1805 2.81947 21.5686 2.43597 20.816C2.18868 20.3306 2.09012 19.8139 2.04419 19.2518C1.99998 18.7106 1.99999 18.0463 2 17.2413V8.7587C1.99999 7.95373 1.99998 7.28937 2.04419 6.74817C2.09012 6.18608 2.18868 5.66937 2.43597 5.18404C2.81947 4.43139 3.43139 3.81947 4.18404 3.43597C4.66937 3.18868 5.18608 3.09012 5.74818 3.04419C6.10898 3.01471 6.52454 3.00489 7 3.00163V2C7 1.44772 7.44772 1 8 1ZM7 5.00176C6.55447 5.00489 6.20463 5.01356 5.91104 5.03755C5.47262 5.07337 5.24842 5.1383 5.09202 5.21799C4.7157 5.40973 4.40973 5.71569 4.21799 6.09202C4.1383 6.24842 4.07337 6.47262 4.03755 6.91104C4.00078 7.36113 4 7.94342 4 8.8V9H20V8.8C20 7.94342 19.9992 7.36113 19.9624 6.91104C19.9266 6.47262 19.8617 6.24842 19.782 6.09202C19.5903 5.7157 19.2843 5.40973 18.908 5.21799C18.7516 5.1383 18.5274 5.07337 18.089 5.03755C17.7954 5.01356 17.4455 5.00489 17 5.00176V6C17 6.55228 16.5523 7 16 7C15.4477 7 15 6.55228 15 6V5H9V6C9 6.55228 8.55229 7 8 7C7.44772 7 7 6.55228 7 6V5.00176ZM20 11H4V17.2C4 18.0566 4.00078 18.6389 4.03755 19.089C4.07337 19.5274 4.1383 19.7516 4.21799 19.908C4.40973 20.2843 4.7157 20.5903 5.09202 20.782C5.24842 20.8617 5.47262 20.9266 5.91104 20.9624C6.36113 20.9992 6.94342 21 7.8 21H16.2C17.0566 21 17.6389 20.9992 18.089 20.9624C18.5274 20.9266 18.7516 20.8617 18.908 20.782C19.2843 20.5903 19.5903 20.2843 19.782 19.908C19.8617 19.7516 19.9266 19.5274 19.9624 19.089C19.9992 18.6389 20 18.0566 20 17.2V11Z" fill="currentColor" />
                                </svg>
                                <p class="mb-0 gilroy-medium f-13 px-2">{{ __('Pick a date range') }}</p>
                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.40165 3.23453C1.6403 2.99588 2.02723 2.99588 2.26589 3.23453L5.50043 6.46908L8.73498 3.23453C8.97363 2.99588 9.36057 2.99588 9.59922 3.23453C9.83788 3.47319 9.83788 3.86012 9.59922 4.09877L5.93255 7.76544C5.6939 8.00409 5.30697 8.00409 5.06831 7.76544L1.40165 4.09877C1.16299 3.86012 1.16299 3.47319 1.40165 3.23453Z" fill="currentColor" />
                                </svg>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="me-2">
                            <div class="param-ref filter-ref w-135 h-45">
                                <select class="select2 f-13" data-minimum-results-for-search="Infinity" id="payment_method" name="payment_method">
                                    <option value="all" {{ ($paymentMethod =='all') ? 'selected' : '' }} >{{ __('All') }}</option>
                                    @foreach($paymentMethods as $payment)
                                        <option value="{{ $payment->id }}" {{ ($payment->id == $paymentMethod) ? 'selected' : '' }}>
                                            {{ ($payment->name == "Mts") ? settings('name') : $payment->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="me-2">
                            <div class="param-ref filter-ref w-135 h-45">
                                <select class="select2 f-13" data-minimum-results-for-search="Infinity" id="status" name="status">
                                    <option value="all" <?= ($status == 'all') ? 'selected' : '' ?>>{{ __('All Status') }}
                                    </option>
                                    <option value="Success" {{ ($status == 'Success') ? 'selected' : '' }}>{{ __('Success') }}</option>
                                    <option value="Pending" {{ ($status == 'Pending') ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="Cancelled" {{ ($status == 'Cancelled') ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Currency -->
                        <div class="me-2">
                            <div class="param-ref filter-ref w-135 h-45">
                                <select class="select2 f-13" data-minimum-results-for-search="Infinity" id="currency" name="currency">
                                    <option value="all" <?= ($currency == 'all') ? 'selected' : '' ?>>{{ __('All Currency') }}
                                    </option>
                                    @foreach($donationCurrencies as $donationCurrency)
                                        <option value="{{ optional($donationCurrency->currency)->id }}" {{ (optional($donationCurrency->currency)->id == $currency) ? 'selected' : '' }}>{{ optional($donationCurrency->currency)->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex align-items-center p-2">
                        <a href="{{ route('user.donation-payment.index') }}" class="reset-btn text-gray-100 f-14 gilroy-medium leading-17 tran-title">{{ __('Reset') }}</a>
                        <button type="submit" class="apply-filter f-14 gilroy-medium leading-17 b-none">{{ __('Apply Filter') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="transac-parent">
        @forelse ($payments as $key => $payment)

            <div class="modal fade modal-overly" id="transaction-Info-{{ $key }}" tabindex="-1" aria-hidden="true">
                <div class="transac modal-dialog modal-dialog-centered modal-lg res-dialog">
                    <div class="modal-content modal-transac transaction-modal">
                        <div class="modal-body modal-themeBody">
                            <div class="d-flex position-relative modal-res">
                                <button type="button" class="cursor-pointer close-btn" data-bs-dismiss="modal" aria-label="Close">
                                <svg class="position-absolute close-btn text-gray-100" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24408 5.24408C5.56951 4.91864 6.09715 4.91864 6.42259 5.24408L10 8.82149L13.5774 5.24408C13.9028 4.91864 14.4305 4.91864 14.7559 5.24408C15.0814 5.56951 15.0814 6.09715 14.7559 6.42259L11.1785 10L14.7559 13.5774C15.0814 13.9028 15.0814 14.4305 14.7559 14.7559C14.4305 15.0814 13.9028 15.0814 13.5774 14.7559L10 11.1785L6.42259 14.7559C6.09715 15.0814 5.56951 15.0814 5.24408 14.7559C4.91864 14.4305 4.91864 13.9028 5.24408 13.5774L8.82149 10L5.24408 6.42259C4.91864 6.09715 4.91864 5.56951 5.24408 5.24408Z" fill="currentColor"/>
                                </svg>
                                </button>
                                <div class="deposit-transac d-flex flex-column justify-content-center p-4 text-wrap">
                                <div class="d-flex justify-content-center text-primary align-items-center transac-img">
                                    <img src="{{ image(null,  $payment->paymentMethod?->name) }}" alt="notfound">
                                </div>
                                <p class="mb-0 mt-28 text-dark gilroy-medium f-15 r-f-12 r-mt-18 text-center">{{ __('Donation Amount') }}</p>
                                <p class="mb-0 text-dark gilroy-Semibold f-24 leading-29 r-f-26 text-center l-s2 mt-10"> <span class="f-22">{{ optional($payment->currency)->symbol }}</span>{{ formatNumber($payment->total, $payment->currency_id) }}</p>
                                <p class="mb-0 mt-18 text-gray-100 gilroy-medium f-13 leading-20 r-f-14 text-center">{{ dateFormat($payment->created_at) }}</p>
                                
                                </div>
                                <div class="ml-20 trans-details">
                                <p class="mb-0 mt-9 text-dark dark-5B f-20 gilroy-Semibold transac-title">{{ __('Transaction Details') }}</p>
                                <div class="row gx-sm-5">
                                    <div class="col-6">
                                        <p class="mb-0 mt-4 text-gray-100 gilroy-medium f-13 leading-20 r-f-9 r-mt-11">{{ __('Donor') }}</p>
                                        <p class="mb-0 mt-5p text-dark gilroy-medium f-15 leading-22 r-text">{{ getColumnValue($payment->payer) }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-0 mt-4 text-gray-100 gilroy-medium f-13 leading-20 r-f-9 r-mt-11">{{ __('Currency') }}</p>
                                        <p class="mb-0 mt-5p text-dark gilroy-medium f-15 leading-22 r-text">{{ $payment->currency?->code }}</p>
                                    </div>
                                </div>
                                <div class="row gx-sm-5">
                                    <div class="col-6">
                                        <p class="mb-0 mt-20 text-gray-100 gilroy-medium f-13 leading-20 r-f-9 r-mt-11">{{ __('Transaction ID') }}</p>
                                        <p class="mb-0 mt-5p text-dark gilroy-medium f-15 leading-22 r-text">{{ $payment->uuid }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-0 mt-20 text-gray-100 gilroy-medium f-13 leading-20 r-f-9 r-mt-11">{{ __('Transaction Fee') }}</p>
                                        <p class="mb-0 mt-5p text-dark gilroy-medium f-15 leading-22 r-text">{{ ($payment->charge_percentage == 0) && ($payment->charge_fixed == 0) ? '-' : optional($payment->currency)->symbol . formatNumber($payment->charge_percentage + $payment->charge_fixed, $payment->currency_id) }}</p>
                                    </div>
                                </div>
                                <div class="row gx-sm-5">
                                    <div class="col-6">
                                    <p class="mb-0 mt-20 text-gray-100 gilroy-medium f-13 leading-20 r-f-9 r-mt-11">{{ __('Payment Method') }}</p>
                                    <p class="mb-0 mt-5p text-dark gilroy-medium f-15 leading-22 r-text">{{ $payment->payment_method_id == Mts ? settings('name') : $payment->paymentMethod?->name }}</p>
                                    </div>
                                    <div class="col-6">
                                    <p class="mb-0 mt-20 text-gray-100 gilroy-medium f-13 leading-20 r-f-9 r-mt-11">{{ __('Status') }}</p>
                                    <p class="mb-0 mt-5p text-primary gilroy-medium f-15 leading-22 r-text">{{ $payment->status }}</p>
                                    </div>
                                </div>
                                <p class="hr-border w-100 mb-0"></p>
                                <div class="row gx-sm-5">
                                    <div class="col-6">
                                        <p class="mb-0 mt-4 text-gray-100 dark-B87 gilroy-medium f-13 leading-20 r-f-9 r-mt-11">{{ __('Donation Amount') }}</p>
                                        <p class="mb-0 mt-5p text-dark dark-CDO gilroy-medium f-15 leading-22 r-text">{{ optional($payment->currency)->symbol . formatNumber($payment->amount, $payment->currency_id) }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-0 mt-4 text-gray-100 dark-B87 gilroy-medium f-13 leading-20 r-f-9 r-mt-11">{{ __('Total Amount') }}</p>
                                        <p class="mb-0 mt-5p text-dark dark-CDO gilroy-medium f-15 leading-22 r-text">{{ optional($payment->currency)->symbol . formatNumber($payment->total, $payment->currency_id) }}</p>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between transac-child">
                <div class="d-flex w-50">
                    <div class="deposit-circle d-flex justify-content-center align-items-center">
                        <img src="{{ image(null,  $payment->paymentMethod?->name) }}" alt="image">
                    </div>
                    
                    <div class="ml-20 r-ml-8">
                        <p class="mb-0 text-dark f-16 gilroy-medium theme-tran">{{ getColumnValue($payment->payer) }} ({{ getColumnValue($payment->payer, 'email', '') }})</p>
                        <div class="d-flex flex-wrap">
                            <p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2">{{ $payment->payment_method_id == Mts ? settings('name') : $payment->paymentMethod?->name }}</p>
                            <p class="mb-0 text-gray-100 f-13 leading-17 gilroy-regular tran-title mt-2 d-flex justify-content-center align-items-center">
                            <svg class="mx-2 text-muted-100" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="2" cy="2" r="2" fill="currentColor" />
                            </svg>
                            {{ dateFormat($payment->created_at) }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <div>
                        <p class="mb-0 gilroy-medium text-gray-100 r-f-12 f-16 ph-20"> 
                            <svg class="mx-2" width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.89992 3.84617L7.02742 5.71867L5.88409 6.86784C5.65113 7.10045 5.33538 7.23109 5.00617 7.23109C4.67697 7.23109 4.36122 7.10045 4.12826 6.86784L1.10659 3.84617C0.709923 3.4495 0.995756 2.77284 1.54992 2.77284H8.45659C9.01659 2.77284 9.29659 3.4495 8.89992 3.84617Z" fill="#2AAA5E"/>
                            </svg> + {{ formatNumber($payment->amount, $payment->currency_id) }} <span class="text-dark">{{ $payment->currency?->code }}</span>
                        </p>
                                
                        <p class="text-{{ $payment->status == 'Success' ? 'success' : ($payment->status == 'Pending' ? 'primary' : 'danger') }} f-13 gilroy-regular text-end mt-6 mb-0 status-info rlt-txt">
                            {{ $payment->status }}
                        </p>
                    </div>
                    <div class="cursor-pointer transaction-arrow  ml-28 r-ml-12">
                        <a class="arrow-hovers" data-bs-toggle="modal" data-bs-target="#transaction-Info-{{ $key }}">
                          <svg class="nscaleX-1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                           <path fill-rule="evenodd" clip-rule="evenodd" d="M3.5312 1.52861C3.27085 1.78896 3.27085 2.21107 3.5312 2.47141L7.0598 6.00001L3.5312 9.52861C3.27085 9.78895 3.27085 10.2111 3.5312 10.4714C3.79155 10.7318 4.21366 10.7318 4.47401 10.4714L8.47401 6.47141C8.73436 6.21106 8.73436 5.78895 8.47401 5.52861L4.47401 1.52861C4.21366 1.26826 3.79155 1.26826 3.5312 1.52861Z" fill="currentColor" />
                          </svg>
                        </a>
                      </div>
                </div>
            </div>
        @empty
            <div class="notfound mt-16 bg-white p-4">
                <div class="d-flex flex-wrap justify-content-center align-items-center gap-26">
                    <div class="image-notfound">
                        <img src="{{ asset('public/dist/images/not-found.png') }}" class="img-fluid">
                    </div>
                    <div class="text-notfound">
                        <p class="mb-0 f-20 leading-25 gilroy-medium text-dark">{{ __('Sorry!') }} {{ __('No data found.') }}</p>
                        <p class="mb-0 f-16 leading-24 gilroy-regular text-gray-100 mt-12">{{ __('The requested data does not exist for this feature overview.') }}</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    <div class="mt-3">
        <nav class="pagi-nav f-13 gilroy-regular d-flex justify-content-between align-items-center"
            aria-label="...">
            {{ $payments->links('vendor.pagination.bootstrap-5') }}
        </nav>
    </div>
@endsection

@push('js')
<script>
    'use strict';
    let dateRangePickerText = '{{ __('Pick a date range') }}';
    var startDate = "{!! $from !!}";
    var endDate = "{!! $to !!}";
</script>
    <script src="{{ asset('public/dist/plugins/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('public/dist/plugins/daterangepicker/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('Modules/Donation/Resources/assets/js/user/donation-payment.min.js') }}" type="text/javascript"></script>
@endpush