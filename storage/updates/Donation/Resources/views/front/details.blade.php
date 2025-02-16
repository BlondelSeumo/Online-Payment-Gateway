@extends('frontend.layouts.app')
@section('styles')
  <link rel="stylesheet" href="{{ asset('Modules/Donation/Resources/assets/css/donation-style.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('Modules/Donation/Resources/assets/css/social-share.min.css')}}">
@endsection
@section('content')
@include('user.common.alert')
<div class="main-content" id="donation-home-details">
    <!-- Hero section -->
    <div class="d-flex justify-content-center donation-details deve-datails-breadcrumb breadcrumb-distace">
      <nav class="customize-bcrm">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('donations.home') }}">{{ __('Campaign') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($donation->title, 40) }}</li>
        </ol>
      </nav>
    </div>
    <div class="d-flex justify-content-center">

      <form action="{{ route('donations.payment_form') }}" method="post" id="paymentMethodForm">
        <div class="content-left content-main deve-content">
          <div class="module-img">
            @if ($donation->display_brand_image == 'Yes')
              <img src="{{ asset('Modules/Donation/public/uploads/'.optional($donation->file)->filename) }}" alt="donation-img" class="img-fluid">
              @else
                <img src="{{ asset('Modules/Donation/Resources/assets/image/empty-img.png') }}" alt="" class="img-fluid imgradious">
              @endif
          </div>
          {{ csrf_field() }}
          <input type="hidden" name="currency_id" value="{{ $donation->currency_id }}">
          <input type="hidden" name="donation_id" value="{{ $donation->id }}">
          <input type="hidden" name="donation_type" value="{{ $donation->donation_type }}">
          <input type="hidden" name="donation_get_payeer_info" value="{{ $donation->get_payer_info }}">
          <div class="module-creator d-flex justify-content-between align-items-center gap-3">
            <div class="d-flex gap-2">
              <div class="module-creator-img">
                @if (optional($donation->creator)->picture != null && file_exists('public/uploads/user-profile/'. optional($donation->creator)->picture))
                  <img src="{{ asset('public/uploads/user-profile/'. optional($donation->creator)->picture) }}" alt="creator">
                @else
                  <img src="{{ asset('public/dist/images/default-avatar.png') }}" alt="creator">
                @endif
              </div>
              <div class="module-text w-75">
                <p class="title">{{ __('Created by') }}:</p>
                <p class="sub-title">{{ getColumnValue($donation->creator) }}</p>
              </div>
            </div>
            <button type="button" class="btn btn-primary px-3 py-2 text-light f-13 leading-14 gilroy-medium w-brk f-14 share-btn gap-3" data-bs-toggle="modal" data-bs-target="#example2Modal">
              <svg width="14" height="16" viewBox="0 0 14 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0513 11.8454C10.4521 11.4831 10.9763 11.2519 11.5621 11.2519C12.8031 11.2519 13.8128 12.2616 13.8128 13.5026C13.8128 14.7436 12.8031 15.7534 11.5621 15.7534C10.3211 15.7534 9.31132 14.7436 9.31132 13.5026C9.31132 13.333 9.33444 13.1634 9.37298 13.0016L3.88485 9.79503C3.46862 10.1804 2.92135 10.4194 2.31241 10.4194C1.03288 10.4194 0 9.38651 0 8.10697C0 6.82744 1.03288 5.79456 2.31241 5.79456C2.92135 5.79456 3.46862 6.03351 3.88485 6.41891L9.31903 3.2509C9.28049 3.07362 9.24965 2.89633 9.24965 2.71134C9.24965 1.4318 10.2825 0.398926 11.5621 0.398926C12.8416 0.398926 13.8745 1.4318 13.8745 2.71134C13.8745 3.99088 12.8416 5.02375 11.5621 5.02375C10.9531 5.02375 10.4059 4.7848 9.98963 4.3994L4.55545 7.56741C4.59399 7.74469 4.62483 7.92198 4.62483 8.10697C4.62483 8.29196 4.59399 8.46925 4.55545 8.64653L10.0513 11.8454ZM12.3327 2.71134C12.3327 2.2874 11.9858 1.94054 11.5619 1.94054C11.1379 1.94054 10.7911 2.2874 10.7911 2.71134C10.7911 3.13529 11.1379 3.48215 11.5619 3.48215C11.9858 3.48215 12.3327 3.13529 12.3327 2.71134ZM2.31175 8.87779C1.88781 8.87779 1.54095 8.53093 1.54095 8.10699C1.54095 7.68304 1.88781 7.33618 2.31175 7.33618C2.7357 7.33618 3.08256 7.68304 3.08256 8.10699C3.08256 8.53093 2.7357 8.87779 2.31175 8.87779ZM10.7911 13.518C10.7911 13.9419 11.1379 14.2888 11.5619 14.2888C11.9858 14.2888 12.3327 13.9419 12.3327 13.518C12.3327 13.0941 11.9858 12.7472 11.5619 12.7472C11.1379 12.7472 10.7911 13.0941 10.7911 13.518Z"></path>
            </svg> {{ __('Share') }}
          </button>
          </div>
          <div class="module-title">
            <h1>{{ $donation->title }}</h1>
          </div>
          <div class="about-campaign">
            <h2>{{ __('About campaign') }}</h2>
            <p>{{ $donation->description }}</p>
          </div>
          <div class="risingfoundation w-100">
            <div class="d-flex justify-content-between currency">
              <p><span class="dolar">{{ optional($donation->currency)->symbol }} {{ formatNumber($donation->raised_amount, optional($donation->currency)->id)}}</span><span class="dolar-text">{{ __('of') }} {{ optional($donation->currency)->symbol }} {{ formatNumber($donation->goal_amount, optional($donation->currency)->id)}} {{ __('raised') }}</span></p>
              <span class="dolar percent">{{ formatNumber(($donation->raised_amount * 100) / $donation->goal_amount, optional($donation->currency)->id) }}%</span>
            </div>
            <div class="progress">
              <div class="progress-bar" role="progressbar" style="width: {{ ($donation->raised_amount * 100) / $donation->goal_amount }}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="w-100 progress-desc">
              <div class="supporters d-flex align-items-center gap-2">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M1.14216 7.24096C0.410741 6.50306 0 5.50573 0 4.46589C0 3.42029 0.415064 2.4172 1.15441 1.67786C1.89375 0.93851 2.89685 0.523438 3.94245 0.523438C4.98806 0.523438 5.99115 0.93851 6.73049 1.67786L6.99999 1.94736L7.26951 1.67786C8.00885 0.93851 9.01122 0.523438 10.0568 0.523438C11.1031 0.523438 12.1055 0.93851 12.8449 1.67786C13.5842 2.4172 14 3.42029 14 4.46589C14 5.50573 13.5893 6.50306 12.8571 7.24096L7.50875 12.9042C7.37688 13.044 7.1924 13.1233 6.99999 13.1233C6.80759 13.1233 6.62312 13.044 6.49125 12.9042L1.14216 7.24096ZM6.99999 11.4039L11.8411 6.27822L11.8555 6.26381C12.3318 5.78677 12.5999 5.14038 12.5999 4.46589C12.5999 3.7914 12.3318 3.14502 11.8555 2.66797C11.3784 2.19093 10.7313 1.92286 10.0568 1.92286C9.38305 1.92286 8.73595 2.19093 8.25891 2.66797L7.49506 3.43254C7.22123 3.70565 6.77805 3.70565 6.50494 3.43254L5.74037 2.66797C5.26405 2.19093 4.61694 1.92286 3.94245 1.92286C3.26796 1.92286 2.62158 2.19093 2.14453 2.66797C1.66749 3.14502 1.40014 3.7914 1.40014 4.46589C1.40014 5.14038 1.66749 5.78677 2.14453 6.26381C2.14958 6.26886 2.1539 6.27318 2.15822 6.27822L6.99999 11.4039Z" fill="#CDCCD0"/>
                </svg>
                <span>{{ formatCount($donation->donationPayments->count()) }} {{ __('Supporters') }}</span> 
              </div>
              <div class="days">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M8.41378 12.7512C8.29833 12.78 8.18063 12.8055 8.06381 12.827C7.75841 12.8838 7.5565 13.1775 7.61299 13.4832C7.64089 13.6336 7.7263 13.7588 7.84225 13.8406C7.96176 13.9247 8.11385 13.9627 8.26884 13.9339C8.40797 13.9081 8.54818 13.8777 8.68577 13.8434C8.98735 13.7683 9.17108 13.4628 9.09584 13.1614C9.02079 12.8597 8.71554 12.6761 8.41378 12.7512Z" fill="currentColor"/>
                  <path d="M12.5754 5.21016C12.6149 5.32897 12.6901 5.42596 12.7852 5.49298C12.9262 5.59227 13.1106 5.62567 13.2866 5.56747C13.5817 5.46948 13.7416 5.15126 13.644 4.8563C13.5995 4.72184 13.5502 4.58696 13.4976 4.45562C13.3822 4.16702 13.0548 4.02651 12.766 4.14195C12.4776 4.25733 12.337 4.58479 12.4525 4.87349C12.4967 4.98387 12.5381 5.09718 12.5754 5.21016Z" fill="currentColor"/>
                  <path d="M10.2429 11.9488C10.1436 12.0144 10.0411 12.0778 9.93792 12.1372C9.6686 12.2926 9.57636 12.6368 9.73165 12.906C9.77381 12.9793 9.82997 13.0391 9.89464 13.0849C10.0683 13.207 10.304 13.2253 10.5004 13.1122C10.6231 13.0414 10.7451 12.9661 10.8634 12.8879C11.1226 12.7167 11.1939 12.3675 11.0226 12.1081C10.8513 11.8487 10.5022 11.7774 10.2429 11.9488Z" fill="currentColor"/>
                  <path d="M13.9943 6.77901C13.9821 6.46837 13.7204 6.22659 13.4097 6.23872C13.0994 6.25097 12.8574 6.5127 12.8696 6.82322C12.8742 6.94194 12.8755 7.06244 12.8727 7.18104C12.8683 7.37584 12.9636 7.54949 13.1117 7.65386C13.1999 7.71597 13.3069 7.75356 13.423 7.75621C13.7337 7.7631 13.9911 7.51671 13.998 7.20593C14.0011 7.06421 13.9999 6.92063 13.9943 6.77901Z" fill="currentColor"/>
                  <path d="M12.4839 10.4682C12.2348 10.2814 11.8824 10.3321 11.6958 10.5807C11.6244 10.676 11.5491 10.77 11.4721 10.8606C11.2707 11.0971 11.2992 11.4524 11.5357 11.6539C11.5492 11.6653 11.5628 11.6758 11.577 11.6857C11.8122 11.8515 12.139 11.8134 12.3291 11.5903C12.4211 11.4822 12.5108 11.3699 12.5962 11.2562C12.7828 11.0075 12.7323 10.6549 12.4839 10.4682Z" fill="currentColor"/>
                  <path d="M13.3111 8.43608C13.0145 8.34309 12.6987 8.50819 12.6057 8.80477C12.5701 8.91807 12.5307 9.03192 12.4882 9.14342C12.3947 9.38887 12.4843 9.65894 12.6895 9.80367C12.7272 9.83012 12.7686 9.85254 12.8137 9.86958C13.1041 9.98038 13.4292 9.83469 13.5399 9.54419C13.5904 9.41163 13.6373 9.27617 13.6797 9.14147C13.7726 8.84482 13.6076 8.52907 13.3111 8.43608Z" fill="currentColor"/>
                  <path d="M5.95869 12.8324C5.4555 12.742 4.97282 12.5882 4.51511 12.3736C4.50969 12.3708 4.50485 12.3676 4.49916 12.365C4.3913 12.3142 4.28362 12.2598 4.17925 12.203C4.17889 12.2026 4.17823 12.2023 4.17766 12.2021C3.98617 12.0966 3.79928 11.98 3.61772 11.8521C0.970238 9.98718 0.333677 6.316 2.19876 3.66855C2.60431 3.09308 3.0951 2.61298 3.64225 2.23247C3.64899 2.22777 3.65573 2.22311 3.66241 2.21838C5.59048 0.889889 8.2085 0.800357 10.2558 2.16551L9.81609 2.80083C9.69385 2.97767 9.76905 3.10654 9.98305 3.08727L11.8931 2.91628C12.1073 2.89702 12.2355 2.71166 12.1779 2.50473L11.665 0.656746C11.6075 0.449575 11.4605 0.424777 11.3382 0.601583L10.8974 1.23841C9.39498 0.229854 7.59294 -0.154724 5.80433 0.15543C5.62418 0.186608 5.44657 0.224768 5.27139 0.269188C5.27003 0.269429 5.26895 0.269579 5.26787 0.26982C5.26109 0.271475 5.25423 0.273672 5.24764 0.275508C3.70529 0.671583 2.35961 1.57114 1.39939 2.85461C1.39129 2.86421 1.38296 2.8736 1.37531 2.88405C1.34338 2.92705 1.31169 2.97105 1.28066 3.01505C1.22992 3.08715 1.17991 3.16107 1.13206 3.23498C1.12607 3.24389 1.12149 3.25295 1.11626 3.26194C0.323867 4.48983 -0.0583347 5.90939 0.00721129 7.3546C0.00736176 7.35935 0.00709091 7.36414 0.00721129 7.36901C0.0135612 7.51019 0.0247564 7.65332 0.0399542 7.79422C0.0407667 7.80331 0.0427831 7.81191 0.0443179 7.821C0.0600273 7.96269 0.0797392 8.1047 0.104447 8.24669C0.355556 9.69538 1.03894 10.999 2.06297 12.0133C2.06535 12.0157 2.06782 12.0183 2.07023 12.0208C2.07107 12.0217 2.072 12.0222 2.07281 12.023C2.34794 12.2944 2.6472 12.5454 2.96942 12.7723C3.81267 13.3665 4.75165 13.7593 5.76006 13.9403C6.06603 13.9952 6.35831 13.7916 6.4132 13.4857C6.46807 13.1797 6.26457 12.8872 5.95869 12.8324Z" fill="currentColor"/>
                  <path d="M6.65476 2.50391C6.40308 2.50391 6.19922 2.70795 6.19922 2.95927V7.49594L10.3484 9.64081C10.4151 9.67538 10.4866 9.69167 10.557 9.69167C10.7218 9.69167 10.881 9.60192 10.9619 9.44534C11.0773 9.22186 10.99 8.94731 10.7665 8.83189L7.10979 6.94141V2.95927C7.10976 2.70795 6.90614 2.50391 6.65476 2.50391Z" fill="currentColor"/>
                </svg>
                  <span>
                    @php
                      $duration = round((strtotime($donation->end_date) - strtotime(date("Y-m-d"))) / (24 * 60 * 60));
                      @endphp
                      @if($duration > 0)
                        {{ $duration }} {{ __('Days Left') }}
                      @else
                        {{ __('Expired') }}
                      @endif
                  </span>
              </div>
            </div>
          </div>
          <div class="risingfoundation">
            @if ($donation->donation_type == 'suggested_amount')
            <div class="raising-amount">
              @if (old('amount'))
                <button type = "button" class="amount action suggested-amount {{ (old('amount') ==  $donation->first_suggested_amount) ? 'active-color' : '' }}" id="first-suggestion" data-amount="{{ $donation->first_suggested_amount }}"><span>{{ optional($donation->currency)->symbol }} {{ formatNumber($donation->first_suggested_amount) }}</span></button>
              @else 
                <button type = "button" class="amount action suggested-amount active-color" id="first-suggestion" data-amount="{{ $donation->first_suggested_amount }}"><span>{{ optional($donation->currency)->symbol }} {{ formatNumber($donation->first_suggested_amount) }}</span></button>
              @endif

              <button type = "button" class="amount action suggested-amount {{ (old('amount') ==  $donation->second_suggested_amount) ? 'active-color' : '' }}" id="second-suggestion" data-amount="{{ $donation->second_suggested_amount }}"><span>{{ optional($donation->currency)->symbol }} {{ formatNumber($donation->second_suggested_amount) }}</span></button>

              <button type = "button" class="amount  action suggested-amount {{ (old('amount') ==  $donation->third_suggested_amount) ? 'active-color' : '' }}" id="third-suggestion" data-amount="{{ $donation->third_suggested_amount }}"><span>{{ optional($donation->currency)->symbol }} {{ formatNumber($donation->third_suggested_amount) }}</span></button>  

              @php
                  $isOthers = old('amount') && old('amount') !=  $donation->first_suggested_amount && old('amount') !=  $donation->second_suggested_amount && old('amount') !=  $donation->third_suggested_amount;
              @endphp
              <button type = "button" id="other-amount" class="amount action other-amount {{ ($isOthers) ? 'active-color' : '' }}"><span>{{ __('others') }}</span></button>
            </div>
            @endif
            <input type="hidden" id="currency_type" data-type="{{ optional($donation->currency)->type }}">
            <div class="form-group donation-amount mb-40 {{ $donation->donation_type == 'suggested_amount' && ($isOthers) ? 'd-block' : '' }}" id="amount-div">
              <input type="text" class="form-control input-form-control sl_common_bx" placeholder="{{ optional($donation->currency)->symbol }} 0.00" name="amount" id="amount" onkeypress="return isNumberOrDecimalPointKey(this, event);" oninput="restrictNumberToPrefdecimalOnInput(this)" value="@if($donation->donation_type == 'fixed_amount'){{ formatNumber($donation->fixed_amount) }}@elseif($donation->donation_type == 'suggested_amount'){{ ( old('amount') ?? $donation->first_suggested_amount) }}@else {{ old('amount')}} @endif" @if ($donation->donation_type == 'fixed_amount') {{ 'readonly' }} @endif required data-value-missing="{{ __('This field is required.') }}" >
              <div id="amount-error" class="error"></div>
  
              @if ($errors->has('amount'))
                  <div class="error">{{ $errors->first('amount') }}</div>
              @endif
            </div>
          <div class="payer-title">
            <h2>{{ __('Fill up your personal information') }}</h2>
          </div>
          <div class="deve-form">
              <div class="row">
                <div class="col-6">
                  <div class="form-group mb-3">
                    <input type="text" class="form-control input-form-control" name="first_name" id="first_name" placeholder="{{ __('First Name') }}" required @if((Auth::check()))  value="{{ Auth::user()->first_name }}" readonly @else value="{{ old('first_name')}}" @endif>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group mb-3">
                    <input type="text" class="form-control input-form-control" name="last_name" id="last_name" placeholder="{{ __('Last Name') }}" required @if((Auth::check()))  value="{{ Auth::user()->last_name }}" readonly @else value="{{ old('last_name')}}" @endif>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="form-group mb-3">
                    <input type="email" class="form-control input-form-control" name="email" id="email" placeholder="{{ __('Email Address') }}" required @if((Auth::check()))  value="{{ Auth::user()->email }}" readonly @else value="{{ old('email')}}" @endif>
                  </div>
                </div>
              </div>
              <div class="d-grid mt-1">
                <button type="submit" class="btn btn-lg btn-primary" id="paymentMethodSubmitBtn">
                  <div class="spinner spinner-border text-white spinner-border-sm mx-2 d-none">
                    <span class="visually-hidden"></span>
                  </div>
                  <span id="paymentMethodSubmitBtnText" class="px-1">{{ __('DONATE NOW') }}</span>
                  <svg class="position-relative ms-1 rtl-wrap-one nscaleX-1 arrow" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.11648 12.216C3.81274 11.9123 3.81274 11.4198 4.11648 11.1161L8.23317 6.99937L4.11648 2.88268C3.81274 2.57894 3.81274 2.08647 4.11648 1.78273C4.42022 1.47899 4.91268 1.47899 5.21642 1.78273L9.88309 6.4494C10.1868 6.75314 10.1868 7.2456 9.88309 7.54934L5.21642 12.216C4.91268 12.5198 4.42022 12.5198 4.11648 12.216Z" fill="currentColor"></path>
                  </svg>
                </button>
              </div>
          </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="modal fade donate-modal" id="example2Modal" tabindex="-1" aria-labelledby="example2ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="example2ModalLabel">{{ __('Share on') }}</h5>
          <button type="button" class="btn-close color-89" data-bs-dismiss="modal" aria-label="Close"><svg class="-mt-17p color-89" xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='currentColor'><path d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/></svg></button>
        </div>
        <div class="modal-body px-4">
            <div class="d-flex flex-wrap gap-3 mt-3 justify-content-center align-items-center">
                @include('donation::social-share')
            </div>
            <div class="d-flex mt-5 gap-2 copy-section">
              <input type="text" class="image-share-text-box" readonly value="{{ $socialShareUrl }}">
              <button id="copyBtn" class="btn btn-primary  copys-btn !mt-0" fdprocessedid="avog8i"
                  data-feedback="Copied"> <span class="copy-link text-white gilroy-medium f-13">{{ __('Copy') }}
                  </span></button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('js')
@include('common.restrict_number_to_pref_decimal')
@include('common.restrict_character_decimal_point')

<script>
  'use strict';
  let paymentMethodSubmitBtnText = "{{ __('Continuing...') }}";
  let pretext = "{{ __('DONATE NOW') }}";
  var donationType = "{{ $donation->donation_type }}";
</script>

<script src="{{ asset('public/dist/plugins/html5-validation/validation.min.js') }}"></script>
<script src="{{ asset('Modules/Donation/Resources/assets/js/home-detail.min.js') }}"></script>
<script src="{{ asset('Modules/Donation/Resources/assets/js/methods.min.js') }}"></script>
<script src="{{ asset('Modules/Donation/Resources/assets/js/social-share.min.js') }}"></script>
@endsection