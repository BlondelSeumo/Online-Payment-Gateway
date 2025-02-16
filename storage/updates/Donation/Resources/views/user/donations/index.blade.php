@extends('user.layouts.app')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/libraries/sweetalert2/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/dist/plugins/daterangepicker/daterangepicker.min.css') }}">
@endpush



@section('content')
    @include('user.common.alert')
    <div class="text-center">
        <p class="mb-0 gilroy-Semibold f-26 text-dark theme-tran r-f-20 text-uppercase text-wrap text-break">{{ __('campaign list') }}
        </p>
        <p class="mb-0 gilroy-medium text-gray-100 f-16 r-f-12 merchant-title  leading-26 tran-title text-wrap text-break">
            {{ __('List of all your created campaigns in one place') }}</p>
    </div>
    <div class="event-content_header my-event_filter-area">
        <form method="get" class="my-event_select-container">
            <div class="param-ref filter-ref customized-select">
                <select class="select2 f-14" id="filter-ref-1" data-minimum-results-for-search="Infinity" name="type">
                    
                    <option value="all" {{ ($type == 'all') ? 'selected' : '' }}> {{ __('All Type') }} </option>
                    <option value="any_amount" {{ ($type == 'any_amount') ? 'selected' : '' }} > {{ __('Any amount') }} </option>
                    <option value="fixed_amount" {{ ($type == 'fixed_amount') ? 'selected' : '' }} > {{ __('Fixed amount') }}</option>
                    <option value="suggested_amount" {{ ($type == 'suggested_amount') ? 'selected' : '' }} >{{ __("Suggest 3 amounts, plus 'other'") }} </option>
                </select>
            </div>
            <div class="param-ref filter-ref customized-select">
                <select class="select2 f-14" id="filter-ref-2" data-minimum-results-for-search="Infinity" name="currency">
                    <option value="all" {{ ($currency == 'all') ? 'selected' : '' }} >{{ __('All currency') }}
                    </option>
                    @foreach($currencies as $wallet)
                        <option value="{{ $wallet->currency_id }}" {{ ($wallet->currency_id == $currency) ? 'selected' : '' }} >{{ optional($wallet->currency)->code }} </option>
                    @endforeach
                </select>
            </div>
            <div class="param-ref filter-ref customized-select">
                <select class="select2 f-14" id="filter-ref-3" data-minimum-results-for-search="Infinity" name="status">
                    <option value="all" {{ ($status == 'all') ? 'selected' : '' }} >{{ __('All Status') }}
                    </option>
                    <option value="active" {{ ($status == 'active') ? 'selected' : '' }} >{{ __('Active') }}
                    <option value="expired" {{ ($status == 'expired') ? 'selected' : '' }} >{{ __('Expired') }}
                    </option>
                </select>
            </div>
            <div class="param-ref filter-ref customized-select">
                <button class="green-btn text-center cursor-pointer add-new-merchant bg-primary d-flex justify-content-center align-items-center btn">
                    <span class="mb-0 f-14 leading-17 gilroy-medium text-white">{{ __('Filter') }}</span>
                </button>
            </div>
        </form>

        <div class="d-flex justify-content-center">
            <a href="{{ route('user.donation.create') }}" class="green-btn text-center cursor-pointer add-new-merchant bg-primary d-flex justify-content-center align-items-center">
                <span class="mb-0 f-14 leading-17 gilroy-medium text-white">+ {{ __('New Campaign') }}</span>
            </a>
        </div>
    </div>
    <div class="mt-24">
        <!-- without overlay start -->
        <div class="row event_card-grid mb-28">
            @forelse ($donations as $donation)
                <div class="col-md-6 col-xl-4">
                    <div class="my-event_card bg-white card h-100 border-0">
                        <div class="card-img_wrap">
                            <div class="card-img_top justify-content-end">
                                <div class="card-action_icon-wrap">
                                    <a href="{{ route('user.donation.edit', $donation->slug) }}" class="card-action_icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M10.5313 1.8624C11.3439 1.04977 12.6615 1.04977 13.4741 1.8624C14.2867 2.67504 14.2867 3.99258 13.4741 4.80521L5.09893 13.1804C5.08573 13.1936 5.07265 13.2067 5.05968 13.2197C4.86805 13.4117 4.69909 13.5809 4.4954 13.7058C4.31646 13.8154 4.12137 13.8962 3.9173 13.9452C3.685 14.001 3.44584 14.0008 3.17458 14.0005C3.15621 14.0005 3.1377 14.0005 3.11903 14.0005H2.00267C1.63448 14.0005 1.33601 13.702 1.33601 13.3338V12.2175C1.33601 12.1988 1.33599 12.1803 1.33597 12.1619C1.33572 11.8907 1.3355 11.6515 1.39127 11.4192C1.44026 11.2151 1.52107 11.02 1.63073 10.8411C1.75555 10.6374 1.92482 10.4684 2.11681 10.2768C2.12981 10.2638 2.14291 10.2508 2.15611 10.2376L10.5313 1.8624ZM12.5313 2.80521C12.2394 2.51328 11.766 2.51328 11.4741 2.80521L3.09892 11.1804C2.84587 11.4334 2.79922 11.4861 2.76758 11.5378C2.73103 11.5974 2.70409 11.6624 2.68776 11.7305C2.67362 11.7893 2.66934 11.8596 2.66934 12.2175V12.6671H3.11903C3.47689 12.6671 3.54716 12.6629 3.60604 12.6487C3.67406 12.6324 3.73909 12.6055 3.79874 12.5689C3.85037 12.5373 3.90308 12.4906 4.15612 12.2376L12.5313 3.86241C12.8232 3.57047 12.8232 3.09715 12.5313 2.80521ZM7.33599 13.3338C7.33599 12.9656 7.63447 12.6671 8.00266 12.6671H14.0027C14.3708 12.6671 14.6693 12.9656 14.6693 13.3338C14.6693 13.702 14.3708 14.0005 14.0027 14.0005H8.00266C7.63447 14.0005 7.33599 13.702 7.33599 13.3338Z"
                                                fill="currentColor" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('user.donation.detail', $donation->slug) }}" class="card-action_icon">
                                        {!! svgIcons('eye_open_icon') !!}
                                    </a>
                                    <button class="card-action_icon delete-donation" data-id="{{ $donation->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M5.33594 1.99967C5.33594 1.63148 5.63441 1.33301 6.0026 1.33301H10.0026C10.3708 1.33301 10.6693 1.63148 10.6693 1.99967C10.6693 2.36786 10.3708 2.66634 10.0026 2.66634H6.0026C5.63441 2.66634 5.33594 2.36786 5.33594 1.99967ZM3.33077 3.33301H2.0026C1.63441 3.33301 1.33594 3.63148 1.33594 3.99967C1.33594 4.36786 1.63441 4.66634 2.0026 4.66634H2.71224L3.14003 11.0833C3.1736 11.5869 3.20138 12.0037 3.25119 12.3429C3.30304 12.6961 3.3853 13.0189 3.55666 13.3197C3.82342 13.7879 4.2258 14.1644 4.71076 14.3994C5.02229 14.5504 5.3498 14.611 5.70567 14.6392C6.04746 14.6664 6.46516 14.6664 6.9699 14.6663H9.03531C9.54005 14.6664 9.95775 14.6664 10.2995 14.6392C10.6554 14.611 10.9829 14.5504 11.2945 14.3994C11.7794 14.1644 12.1818 13.7879 12.4485 13.3197C12.6199 13.0189 12.7022 12.6961 12.754 12.3429C12.8038 12.0037 12.8316 11.5869 12.8652 11.0832L13.293 4.66634H14.0026C14.3708 4.66634 14.6693 4.36786 14.6693 3.99967C14.6693 3.63148 14.3708 3.33301 14.0026 3.33301H12.6744C12.6705 3.33297 12.6667 3.33297 12.6628 3.33301H3.34243C3.33855 3.33297 3.33466 3.33297 3.33077 3.33301ZM11.9567 4.66634H4.04853L4.46865 10.9682C4.50443 11.5049 4.52917 11.8686 4.57037 12.1492C4.6104 12.4218 4.6593 12.5616 4.71519 12.6597C4.84857 12.8938 5.04975 13.082 5.29223 13.1995C5.39383 13.2488 5.53651 13.2883 5.81118 13.3101C6.09396 13.3325 6.45851 13.333 6.99637 13.333H9.00883C9.5467 13.333 9.91125 13.3325 10.194 13.3101C10.4687 13.2883 10.6114 13.2488 10.713 13.1995C10.9555 13.082 11.1566 12.8938 11.29 12.6597C11.3459 12.5616 11.3948 12.4218 11.4348 12.1492C11.476 11.8686 11.5008 11.5049 11.5366 10.9682L11.9567 4.66634ZM6.66927 6.33301C7.03746 6.33301 7.33594 6.63148 7.33594 6.99967V10.333C7.33594 10.7012 7.03746 10.9997 6.66927 10.9997C6.30108 10.9997 6.0026 10.7012 6.0026 10.333V6.99967C6.0026 6.63148 6.30108 6.33301 6.66927 6.33301ZM9.33594 6.33301C9.70413 6.33301 10.0026 6.63148 10.0026 6.99967V10.333C10.0026 10.7012 9.70413 10.9997 9.33594 10.9997C8.96775 10.9997 8.66927 10.7012 8.66927 10.333V6.99967C8.66927 6.63148 8.96775 6.33301 9.33594 6.33301Z"
                                                fill="currentColor" />
                                        </svg>
                                    </button>

                                    <form action="{{ route('user.donation.delete', $donation->id) }}"
                                        method="post" class="d-destroy"
                                        id="delete-form-{{ $donation->id }}">
                                        @csrf
                                    </form>
                                </div>
                            </div>

                            <input type="hidden" value="{{ route('donations.details', $donation->slug) }}" name="donation_link" id="donation-link-{{ $donation->id }}">

                            @if($donation->display_brand_image == 'Yes')
                                <img src="{{ asset('Modules/Donation/public/uploads/'.optional($donation->file)->filename) }}" class="card-img-top"
                                alt="donation-1">
                            @else
                            
                            <img src="{{ asset('Modules/Donation/Resources/assets/image/empty-img.png') }}"class="card-img-top" alt="empty">
                            @endif
                        </div>
                        <div class="card-body pb-0">
                            <div class="mb-12 d-flex gap-2 align-items-center justify-content-between btn-active">
                                <p class="mb-0 date-badge gilroy-regular text-wrap text-break">{{ __('Created') }}: <span class="gilroy-medium text-primary"> {{ date('d M Y', strtotime($donation->created_at)) }}</span></p>
                                <button class="donation-copy_btn copy-link" id="{{ $donation->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                                        fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11.4253 2.70046C11.0141 2.66686 10.4859 2.66634 9.72813 2.66634H4.99479C4.6266 2.66634 4.32813 2.36786 4.32813 1.99967C4.32813 1.63148 4.6266 1.33301 4.99479 1.33301L9.75666 1.33301C10.4792 1.333 11.062 1.333 11.5339 1.37155C12.0198 1.41125 12.4466 1.49513 12.8414 1.69632C13.4686 2.0159 13.9786 2.52583 14.2981 3.15304C14.4993 3.5479 14.5832 3.97469 14.6229 4.46059C14.6615 4.93251 14.6615 5.51527 14.6615 6.23779V10.9997C14.6615 11.3679 14.363 11.6663 13.9948 11.6663C13.6266 11.6663 13.3281 11.3679 13.3281 10.9997V6.26634C13.3281 5.5086 13.3276 4.9804 13.294 4.56917C13.261 4.16572 13.1996 3.93393 13.1101 3.75836C12.9184 3.38204 12.6124 3.07608 12.2361 2.88433C12.0605 2.79487 11.8287 2.73342 11.4253 2.70046ZM4.10243 3.66634H9.55382C9.90525 3.66633 10.2084 3.66632 10.4581 3.68672C10.7217 3.70826 10.9839 3.7558 11.2361 3.88433C11.6124 4.07608 11.9184 4.38204 12.1101 4.75836C12.2387 5.01061 12.2862 5.27276 12.3077 5.53632C12.3281 5.78604 12.3281 6.08922 12.3281 6.44066V11.892C12.3281 12.2435 12.3281 12.5466 12.3077 12.7964C12.2862 13.0599 12.2387 13.3221 12.1101 13.5743C11.9184 13.9506 11.6124 14.2566 11.2361 14.4484C10.9839 14.5769 10.7217 14.6244 10.4581 14.646C10.2084 14.6664 9.90526 14.6664 9.55384 14.6663H4.10241C3.75099 14.6664 3.44782 14.6664 3.19811 14.646C2.93454 14.6244 2.6724 14.5769 2.42015 14.4484C2.04382 14.2566 1.73786 13.9506 1.54611 13.5743C1.41758 13.3221 1.37004 13.0599 1.34851 12.7964C1.3281 12.5466 1.32811 12.2435 1.32813 11.892V6.44064C1.32811 6.08921 1.3281 5.78604 1.34851 5.53632C1.37004 5.27276 1.41758 5.01061 1.54611 4.75836C1.73786 4.38204 2.04382 4.07608 2.42015 3.88433C2.6724 3.7558 2.93454 3.70826 3.19811 3.68672C3.44782 3.66632 3.751 3.66633 4.10243 3.66634ZM3.30668 5.01563C3.12557 5.03042 3.05843 5.05554 3.02547 5.07234C2.90002 5.13625 2.79804 5.23824 2.73412 5.36368C2.71733 5.39664 2.69221 5.46379 2.67741 5.6449C2.66198 5.83379 2.66146 6.08197 2.66146 6.46634V11.8663C2.66146 12.2507 2.66198 12.4989 2.67741 12.6878C2.69221 12.8689 2.71733 12.936 2.73412 12.969C2.79804 13.0944 2.90002 13.1964 3.02547 13.2603C3.05843 13.2771 3.12557 13.3023 3.30668 13.3171C3.49557 13.3325 3.74376 13.333 4.12813 13.333H9.52813C9.91249 13.333 10.1607 13.3325 10.3496 13.3171C10.5307 13.3023 10.5978 13.2771 10.6308 13.2603C10.7562 13.1964 10.8582 13.0944 10.9221 12.969C10.9389 12.936 10.964 12.8689 10.9788 12.6878C10.9943 12.4989 10.9948 12.2507 10.9948 11.8663V6.46634C10.9948 6.08197 10.9943 5.83379 10.9788 5.6449C10.964 5.46379 10.9389 5.39664 10.9221 5.36368C10.8582 5.23824 10.7562 5.13625 10.6308 5.07234C10.5978 5.05554 10.5307 5.03042 10.3496 5.01563C10.1607 5.00019 9.91249 4.99967 9.52813 4.99967H4.12813C3.74376 4.99967 3.49557 5.00019 3.30668 5.01563Z"
                                            fill="currentColor" />
                                    </svg>
                                </button>
                            </div>
                            <div class="card-body-inner">
                                <h5 class="card-title my-event_card-title title-mb-10 text-dark gilroy-Bold text-wrap text-break line-clamp-single">
                                    {{ $donation->title }}</h5>
                                <p class="donation-card_desc line-clamp-double">{{ str()->limit($donation->description, 130) }}</p>
                            </div>
                            <div class="linear-progress_bar">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: {{ ($donation->raised_amount * 100) / $donation->goal_amount }}%;" aria-valuenow="25"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="donation-card_price-container d-flex justify-content-between align-items-center gap-1">
                                <div class="d-flex gap-2 align-items-center raised">
                                    <h3
                                        class="donation_card-price text-primary gilroy-Semibold text-wrap text-break align-self-start mb-0">
                                        {{ moneyFormat(optional($donation->currency)->symbol, formatNumber($donation->raised_amount, $donation->currency_id)) }}</h3>
                                    <p class="mb-0 mt-2p f-14 leading-22 gilroy-regular align-self-start text-gray-100">{{ __('raised
                                        of') }} <mark class="marks text-dark bg-white gilroy-Semibold">{{ moneyFormat(optional($donation->currency)->symbol, formatNumber($donation->goal_amount, $donation->currency_id)) }}</mark></p>
                                </div>
                                <p class="mb-0 mt-2p f-14 leading-22 gilroy-regular align-self-start text-gray-100">{{ ($donation->raised_amount * 100) / $donation->goal_amount }}%</p>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 pt-0 my-event_card-footer">
                            <div class="my-event_card-footer_small d-flex justify-content-between">
                                <div class="d-flex gap-2 align-items-center days-left align-self-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="10" viewBox="0 0 12 12"
                                        fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M1.5 1C1.5 0.723858 1.72386 0.5 2 0.5H10C10.2761 0.5 10.5 0.723858 10.5 1C10.5 1.27614 10.2761 1.5 10 1.5H9.5V2.3759C9.5 2.39537 9.50001 2.41458 9.50002 2.43355C9.50021 2.79072 9.50035 3.06046 9.43132 3.31704C9.37047 3.54321 9.27037 3.75694 9.13557 3.94848C8.98266 4.16577 8.77534 4.33835 8.50083 4.56686C8.48625 4.57899 8.47149 4.59128 8.45653 4.60374L6.78102 6L8.45654 7.39626C8.47149 7.40872 8.48626 7.42101 8.50083 7.43315C8.77534 7.66166 8.98266 7.83423 9.13557 8.05152C9.27037 8.24306 9.37047 8.45678 9.43132 8.68296C9.50035 8.93954 9.50021 9.20928 9.50002 9.56645C9.50001 9.58542 9.5 9.60463 9.5 9.6241V10.5H10C10.2761 10.5 10.5 10.7239 10.5 11C10.5 11.2761 10.2761 11.5 10 11.5H2C1.72386 11.5 1.5 11.2761 1.5 11C1.5 10.7239 1.72386 10.5 2 10.5H2.5V9.6241C2.5 9.60463 2.49999 9.58542 2.49998 9.56645C2.49979 9.20928 2.49965 8.93954 2.56868 8.68296C2.62953 8.45679 2.72963 8.24306 2.86443 8.05152C3.01734 7.83423 3.22466 7.66166 3.49917 7.43315C3.51374 7.42101 3.52851 7.40872 3.54347 7.39626L5.21898 6L3.54346 4.60374C3.52851 4.59128 3.51374 4.57899 3.49917 4.56685C3.22466 4.33834 3.01734 4.16577 2.86443 3.94848C2.72963 3.75694 2.62953 3.54321 2.56868 3.31704C2.49965 3.06046 2.49979 2.79072 2.49998 2.43355C2.49999 2.41458 2.5 2.39537 2.5 2.3759V1.5H2C1.72386 1.5 1.5 1.27614 1.5 1ZM3.5 1.5V2.3759C3.5 2.81616 3.50402 2.94455 3.53434 3.05724C3.56476 3.17033 3.61482 3.27719 3.68221 3.37296C3.74938 3.46839 3.84544 3.55368 4.18365 3.83552L6 5.34915L7.81635 3.83552C8.15456 3.55368 8.25062 3.46839 8.31779 3.37296C8.38518 3.27719 8.43524 3.17033 8.46566 3.05724C8.49598 2.94455 8.5 2.81616 8.5 2.3759V1.5H3.5ZM6 6.65085L4.18365 8.16448C3.84544 8.44632 3.74938 8.53161 3.68221 8.62704C3.61481 8.72281 3.56476 8.82967 3.53434 8.94276C3.50402 9.05545 3.5 9.18384 3.5 9.6241V10.5H8.5V9.6241C8.5 9.18384 8.49598 9.05545 8.46566 8.94276C8.43524 8.82967 8.38518 8.72281 8.31779 8.62704C8.25062 8.53161 8.15456 8.44632 7.81635 8.16448L6 6.65085Z"
                                            fill="currentColor" />
                                    </svg>
                                    <p class="mb-0 gilroy-medium f-12 text-gray-100 text-wrap text-break ">

                                        @php
                                            $daysLeft = round((strtotime($donation->end_date) - strtotime(date("Y-m-d"))) / (24 * 60 * 60));
                                        @endphp

                                        @if($daysLeft > 0)
                                            {{ __(':x days left', ['x' => $daysLeft]) }}
                                        @else
                                            {{ __('Expired') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="ticket-sold align-self-start">
                                    <p class="gilroy-medium f-12 text-gray-100 mb-0 text-wrap text-break">{{ __(':x donation', ['x' => $donation->donation_payment_count]) }}</p>
                                </div>
                            </div>
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
    </div>
    <div class="mt-4">
        <nav class="pagi-nav f-13 gilroy-regular d-flex justify-content-between align-items-center" aria-label="...">
            {{ $donations->links('vendor.pagination.bootstrap-5') }}
        </nav>
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
    <script src="{{ asset('public/dist/plugins/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('public/dist/plugins/daterangepicker/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('Modules/Donation/Resources/assets/js/user/donation-list.min.js') }}" type="text/javascript"></script>
@endpush
