@extends('user.layouts.app')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/libraries/sweetalert2/sweetalert2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('Modules/Donation/Resources/assets/css/social-share.min.css')}}">
@endpush

@section('content')
    <div>
        <div class="text-center disput-parent-content">
            <p class="mb-0 gilroy-Semibold f-26 text-dark theme-tran r-f-20">{{ __('CAMPAIGN DETAILS') }}</p>
            <p class="mb-0 gilroy-medium text-gray-100 f-16 r-f-12 mt-2 donate-details tran-title leading-26">{{ __('Everything you need to know about the campaign') }}</p>
        </div>
        <div class="row mt-24 donate-details">
            <div class="col-md-5 col-lg-4">
                <div class="sticky-mode">
                    <div class="d-flex align-items-center back-direction donateback">
                        <a href="{{ route('user.donation.index') }}"
                            class="text-gray-100 f-16 leading-20 gilroy-medium d-inline-flex align-items-center position-relative back-btn">
                            <svg class="position-relative nscaleX-1" width="12" height="12" viewBox="0 0 12 12"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.47075 10.4709C8.7311 10.2105 8.7311 9.78842 8.47075 9.52807L4.94216 5.99947L8.47075 2.47087C8.7311 2.21053 8.7311 1.78842 8.47075 1.52807C8.2104 1.26772 7.78829 1.26772 7.52794 1.52807L3.52795 5.52807C3.2676 5.78842 3.2676 6.21053 3.52795 6.47088L7.52794 10.4709C7.78829 10.7312 8.2104 10.7312 8.47075 10.4709Z"
                                    fill="currentColor"></path>
                            </svg>
                            <span class="ms-1 back-btn">{{ __('Back') }}</span>
                        </a>
                    </div>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item mt-12 details-info-left-box details-info bg-white">
                            <div id="collapseOne" class="accordion-collapse collapse show mt-10n" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="donation-dis-details-body">
                                    <div class="donation-dis-details-body mt-24">
                                        <div class="d-flex dispute-id justify-content-between">
                                            <div class="d-flex created">
                                                <p class="mb-0 text-gray-100 f-13 gilroy-medium mr-6p w-brk">{{ __('Created on') }}</p>
                                                <p class="mb-0 text-dark f-13 gilroy-medium w-brk"> {{ dateFormat($donation->created_at) }}</p>
                                            </div>
                                            <div class="d-flex justify-content-center align-items-center mt-n3p">
                                                @php
                                                    $daysLeft = round((strtotime($donation->end_date) - strtotime(date("Y-m-d"))) / (24 * 60 * 60));
                                                @endphp

                                                <span class="text-{{ $daysLeft > 0 ? 'success' : 'danger' }} f-13 gilroy-medium w-brk">{{ $daysLeft > 0 ? __('Running') : __('Expired') }}</span>
                                            </div>
                                        </div>
                                        <p class="mb-0 f-28 leading-34 gilroy-Semibold text-primary donation-dollar w-brk">
                                            {{ optional($donation->currency)->symbol . formatNumber($donation->raised_amount, $donation->currency_id) }}</p>

                                        <div class="d-flex justify-content-between donation-raised-parent">
                                            <div class="d-flex gap-2 align-items-center donation-raised w-75">
                                                <p
                                                    class="mb-0 mt-1 f-18 leading-24 gilroy-regular align-self-start text-gray-100 w-brk">
                                                    {{ __('raised of') }} <mark
                                                        class="text-dark bg-white gilroy-Semibold w-brk">{{ optional($donation->currency)->symbol . formatNumber($donation->goal_amount, $donation->currency_id) }}</mark></p>
                                            </div>
                                            <p
                                                class="mb-0 mt-2p f-14 leading-22 gilroy-regular align-self-start text-gray-100 w-brk">
                                                {{ ($donation->raised_amount * 100) / $donation->goal_amount }}%</p>
                                        </div>
                                        <div class="linear-progress_bar donation-details">
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: {{ ($donation->raised_amount * 100) / $donation->goal_amount }}%;"
                                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="number-of-donation">
                                            <p class="mb-0 f-14 leading-20 gilroy-medium text-gray-100 w-brk">{{ __('Number of Donations') }}</p>
                                            <p class="mb-0 f-14 leading-20 gilroy-medium text-primary">{{ count($payments) }}</p>
                                        </div>
                                        <div class="deadline-box">
                                            <div class="deadline">
                                                <p class="mb-0 f-14 leading-20 gilroy-medium text-gray-100 w-brk">Deadline
                                                </p>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <p class="mb-0 text-dark f-13 gilroy-medium w-brk w-75">{{ dateFormat($donation->end_date) }}</p>
                                                <p class="mb-0 f-13 leading-16 gilroy-medium text-danger w-brk ">@if($daysLeft > 0)
                                                    {{ __(':x days left', ['x' => $daysLeft]) }}
                                                @else
                                                    {{ __('Expired') }}
                                                @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="status-footer d-flex gap-10">
                                            <div class="btn-div w-100">
                                                <a href="{{ route('user.donation.edit', $donation->slug) }}" class="donation-details-edit w-100">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M10.5313 1.8624C11.3439 1.04977 12.6615 1.04977 13.4741 1.8624C14.2867 2.67504 14.2867 3.99258 13.4741 4.80521L5.09893 13.1804C5.08573 13.1936 5.07265 13.2067 5.05968 13.2197C4.86805 13.4117 4.69909 13.5809 4.4954 13.7058C4.31646 13.8154 4.12137 13.8962 3.9173 13.9452C3.685 14.001 3.44584 14.0008 3.17458 14.0005C3.15621 14.0005 3.1377 14.0005 3.11903 14.0005H2.00267C1.63448 14.0005 1.33601 13.702 1.33601 13.3338V12.2175C1.33601 12.1988 1.33599 12.1803 1.33597 12.1619C1.33572 11.8907 1.3355 11.6515 1.39127 11.4192C1.44026 11.2151 1.52107 11.02 1.63073 10.8411C1.75555 10.6374 1.92482 10.4684 2.11681 10.2768C2.12981 10.2638 2.14291 10.2508 2.15611 10.2376L10.5313 1.8624ZM12.5313 2.80521C12.2394 2.51328 11.766 2.51328 11.4741 2.80521L3.09892 11.1804C2.84587 11.4334 2.79922 11.4861 2.76758 11.5378C2.73103 11.5974 2.70409 11.6624 2.68776 11.7305C2.67362 11.7893 2.66934 11.8596 2.66934 12.2175V12.6671H3.11903C3.47689 12.6671 3.54716 12.6629 3.60604 12.6487C3.67406 12.6324 3.73909 12.6055 3.79874 12.5689C3.85037 12.5373 3.90308 12.4906 4.15612 12.2376L12.5313 3.86241C12.8232 3.57047 12.8232 3.09715 12.5313 2.80521ZM7.33599 13.3338C7.33599 12.9656 7.63447 12.6671 8.00266 12.6671H14.0027C14.3708 12.6671 14.6693 12.9656 14.6693 13.3338C14.6693 13.702 14.3708 14.0005 14.0027 14.0005H8.00266C7.63447 14.0005 7.33599 13.702 7.33599 13.3338Z"
                                                            fill="white" />
                                                    </svg>
                                                    <p class="mb-0 text-light f-13 leading-14 gilroy-medium w-brk">{{ __('Edit Campaign') }}</p>
                                                </a>
                                            </div>
                                            <button class="d-delete delete-donation">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M6.66406 2.50033C6.66406 2.04009 7.03716 1.66699 7.4974 1.66699H12.4974C12.9576 1.66699 13.3307 2.04009 13.3307 2.50033C13.3307 2.96056 12.9576 3.33366 12.4974 3.33366H7.4974C7.03716 3.33366 6.66406 2.96056 6.66406 2.50033ZM4.1576 4.16699H2.4974C2.03716 4.16699 1.66406 4.54009 1.66406 5.00033C1.66406 5.46056 2.03716 5.83366 2.4974 5.83366H3.38443L3.91918 13.8549C3.96114 14.4844 3.99586 15.0054 4.05812 15.4294C4.12294 15.8709 4.22577 16.2743 4.43997 16.6503C4.77342 17.2356 5.27639 17.7062 5.88259 18C6.272 18.1887 6.68139 18.2645 7.12622 18.2998C7.55347 18.3337 8.07559 18.3337 8.70651 18.3337H11.2883C11.9192 18.3337 12.4413 18.3337 12.8686 18.2998C13.3134 18.2645 13.7228 18.1887 14.1122 18C14.7184 17.7062 15.2214 17.2356 15.5548 16.6503C15.769 16.2743 15.8718 15.8709 15.9367 15.4294C15.9989 15.0053 16.0337 14.4843 16.0756 13.8547L16.6104 5.83366H17.4974C17.9576 5.83366 18.3307 5.46056 18.3307 5.00033C18.3307 4.54009 17.9576 4.16699 17.4974 4.16699H15.8372C15.8323 4.16695 15.8275 4.16695 15.8226 4.16699H4.17218C4.16733 4.16695 4.16247 4.16695 4.1576 4.16699ZM14.94 5.83366H5.0548L5.57995 13.711C5.62468 14.3818 5.6556 14.8365 5.70711 15.1873C5.75714 15.528 5.81827 15.7027 5.88812 15.8253C6.05485 16.118 6.30633 16.3533 6.60943 16.5001C6.73643 16.5617 6.91478 16.6111 7.25811 16.6383C7.61158 16.6664 8.06727 16.667 8.73961 16.667H11.2552C11.9275 16.667 12.3832 16.6664 12.7367 16.6383C13.08 16.6111 13.2584 16.5617 13.3854 16.5001C13.6885 16.3533 13.9399 16.118 14.1067 15.8253C14.1765 15.7027 14.2376 15.528 14.2877 15.1873C14.3392 14.8365 14.3701 14.3818 14.4148 13.711L14.94 5.83366ZM8.33073 7.91699C8.79097 7.91699 9.16406 8.29009 9.16406 8.75033V12.917C9.16406 13.3772 8.79097 13.7503 8.33073 13.7503C7.87049 13.7503 7.4974 13.3772 7.4974 12.917V8.75033C7.4974 8.29009 7.87049 7.91699 8.33073 7.91699ZM11.6641 7.91699C12.1243 7.91699 12.4974 8.29009 12.4974 8.75033V12.917C12.4974 13.3772 12.1243 13.7503 11.6641 13.7503C11.2038 13.7503 10.8307 13.3772 10.8307 12.917V8.75033C10.8307 8.29009 11.2038 7.91699 11.6641 7.91699Z"
                                                        fill="#6A6B87" />
                                                </svg>
                                            </button>
                                            <form action="{{ route('user.donation.delete', $donation->id) }}"
                                                method="post" class="d-destroy"
                                                id="donation-delete-form">
                                                @csrf
                                            </form>
                                        </div>
                                        <button type="button" class="btn btn-primary w-100 mt-3 p-3 text-light f-13 leading-14 gilroy-medium w-brk f-14" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            {{ __('Share') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-lg-8 dis-mb-top sm-dismb-top">
                <div class="details-info-right-box donation-info bg-white mt-43 border-radius-8p">
                    <p class="mb-20 f-22 leading-32 gilroy-Semibold text-dark text-wrap">{{ $donation->title }}</p>
                    <div class="camp-details-image">
                        <img src="{{ asset('Modules/Donation/public/uploads/'.optional($donation->file)->filename) }}"
                            alt="{{ __('Campaign Image') }}" class="img-fluid w-100 h-290p">
                    </div>
                    <div class="tab-content" id="nav-tabContent">
                        <nav>
                            <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                <button class="nav-link active gilroy-medium f-18 px-0 pt-0 me-4" id="nav-home-tab"
                                    data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab"
                                    aria-controls="nav-home" aria-selected="true">{{ __('Description') }}</button>
                                <button class="nav-link gilroy-medium f-18 px-0 pt-0 me-4" id="donar-info-tab"
                                    data-bs-toggle="tab" data-bs-target="#donar-info" type="button" role="tab"
                                    aria-controls="donar-info" aria-selected="false">{{ __('Donations') }}</button>
                            </div>
                        </nav>
                        <div class="description tab-pane fade active show" id="nav-home" role="tabpanel"
                        aria-labelledby="nav-home-tab">
                            {{ $donation->description }}
                        </div>
                        <div class="tab-pane fade" id="donar-info" role="tabpanel" aria-labelledby="donar-info">
                            <ul class="list-unstyled" id="listDonations">
                                @forelse ($payments as $payment)
                                    <li class="d-flex justify-content-start align-items-center py-2 gap-3">
                                        <img src="{{ asset('public/dist/images/default-avatar.png') }}" width="60" class="rounded-circle mr-3 border">
                                        <div class="media-body">
                                            <h6 class="mt-0 mb-1 gilroy-medium f-17"> {{ getColumnValue($payment->payer) }} <span class="text-info-200 gilroy-normal f-14 ms-1">{{ __('donated') }}</span> <span class="text-success gilroy-medium f-17">{{ optional($payment->currency)->symbol . formatNumber($payment->amount, $payment->currency_id) }}</span> </h6>
                                            <small class="btn-block timeAgo text-info-200 gilroy-normal f-14">{{ $payment->created_at->diffForHumans() }}</small>
                                        </div>
                                    </li>
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
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade share-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Share on') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><svg class="-mt-17p color-89" xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='currentColor'><path d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/></svg></button>
                    </div>
                    <div class="modal-body px-4">
                        <div class="">
                            @include('donation::social-share')
                        </div>
                        <div class="d-flex mt-5 gap-2 copy-section">
                            <input type="text" class="image-share-text-box donation_url" readonly value="{{ $socialShareUrl }}">
                            <button id="copyBtn" class="btn btn-primary  copys-btn !mt-0" fdprocessedid="avog8i"
                                data-feedback="Copied"><span class="copy-link text-white gilroy-medium f-13">{{ __('Copy') }}</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

      
@endsection

@push('js')

    <script>
        'use strict';
        var copiedText = "{{ __('Copied') }}";
        var confirmText = "{{ __('Are you sure?') }}";
        var deleteButtonText = '{{ __("Yes, delete it.") }}';
        var cancelButtonText = '{{ __("No, cancel please.") }}';
        var safeDonationText = '{{ __("Your campaign is safe.") }}';
    </script>

    <script src="{{ asset('public/dist/libraries/sweetalert2/sweetalert2.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('Modules/Donation/Resources/assets/js/social-share.min.js') }}"></script>
    <script src="{{ asset('Modules/Donation/Resources/assets/js/user/donation-detail.min.js') }}" type="text/javascript"></script>
@endpush
