@extends('frontend.layouts.app')
@section('styles')
  <link rel="stylesheet" href="{{ asset('Modules/Donation/Resources/assets/css/donation-style.min.css') }}">
@endsection
@section('content')
@include('user.common.alert')
<div class="main-content">
    <!-- Hero section -->
    <div class="donataion-list standards-hero-section hero-section-bg">
      <div class="px-240">
        <div class="d-flex flex-column align-items-start">
          <nav class="customize-bcrm">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __('Campaign') }}</li>
            </ol>
          </nav>
          <div class="btn-section">
            <button class="btn btn-dark btn-lg">
              <a href="{{ route('donations.home') }}" class="text-reset">
                  {{ __('Campaign') }}
              </a>
            </button>
          </div>
          <div class="merchant-text">
            <div class="donation">
              <h1>{{ __('Join US in creating a') }}</h1>
              <h2>{{ __('brighter future') }}</h2>
            </div>
            <p>{{ __('The smallest act of kindness is worth more than the grandest intention. So make your future world for the best') }}.</p>
          </div>
        </div>
      </div>
    </div> 
    <!-- Goal-and-aspiration start -->
    <div class="section section-goal">
      <div class="px-240">
        <div class="module-centered-title">
          <h4>{{ __('OUR STORY') }}</h4>
          <h2>{{ __('OUR GOAL AND ASPIRATIONS') }}</h2>
          <p class="small-border mb-0 bgd-blue m-auto"></p>
        </div>
        <div class="row align-items-center mt-1">
          <div class="col-md-7 col-12">
            <div class="aspiration-img-module position-relative">
              <div class="aspiration-img-one">
                <img src="{{ asset('Modules/Donation/Resources/assets/image/aspimg-1.jpg') }}" alt="aspiration" class="img-fluid">
              </div>
              <div class="aspiration-img-two">
                <img src="{{ asset('Modules/Donation/Resources/assets/image/aspimg-2.jpg') }}" alt="aspiration" class="img-fluid">
              </div>
            </div>
          </div>
          <div class="col-md-5 col-12">
            <div class="aspiration-desc">
              <h3>{{ __('We are here to help you!') }}</h3>
              <p>{{ __('Our trust and safety team is composed of professionals who have a global reach and more than 10 years of accumulated experience in managing various types of fundraisers across different regions of the world') }}.</p>
              <div class="d-flex flex-wrap ul-list-gap justify-content-around">
                <div class="list">
                  <ul>
                    <li>
                      <span>{{ __('100% Fundraising Success Rate') }}</span>
                    </li>
                    <li>
                      <span>{{ __('Supported By :x + Donors', ['x' => $donationCount]) }} </span>
                    </li>
                  </ul>
                </div>
                <div class="list">
                  <ul>
                    <li>
                      <span>{{ __('Accept all Popular Payments') }}</span>
                    </li>
                    <li>
                      <span>{{ __('Withdraw Fund Hassle Free') }}</span>
                    </li>
                  </ul>
                </div>
              </div>
              <a href="{{ url('login') }}" class="process-goto cursor-pointer position-relative d-flex justify-content-center align-items-center w-max">
                  <span>{{ __('Start a Campaign') }}</span>
                  <svg class="ml-5p position-relative" width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0 4.57143C0 4.25584 0.255837 4 0.571429 4L15.4286 4C15.7442 4 16 4.25584 16 4.57143C16 4.88702 15.7442 5.14286 15.4286 5.14286L0.571429 5.14286C0.255837 5.14286 0 4.88702 0 4.57143Z" fill="currentColor"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0243 0.167368C11.2475 -0.0557892 11.6093 -0.0557892 11.8324 0.167368L15.8324 4.16737C16.0556 4.39052 16.0556 4.75233 15.8324 4.97549L11.8324 8.97549C11.6093 9.19865 11.2475 9.19865 11.0243 8.97549C10.8011 8.75233 10.8011 8.39052 11.0243 8.16737L14.6202 4.57143L11.0243 0.97549C10.8011 0.752333 10.8011 0.390524 11.0243 0.167368Z" fill="currentColor"></path>
                  </svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Goal-and-aspiration end -->
    <!-- Campaign section start -->
    <div class="section-campaignlist">
      <div class="px-240">
        <div class="module-centered-title">
          <h4>{{ __('our campaign list') }}</h4>
          <h2>{{ __('Our different initiatives') }}</h2>
          <p class="small-border mb-0 bgd-blue m-auto"></p>
        </div>
        <div class="row gx-4 gy-4 doantion-container">
          @forelse ($donations as $donation)
          <a href="{{ route('donations.details', $donation->slug) }}" class="col-md-4 gxy-sm-0">
            <div class="donation-module  position-relative">
              <div class="donation-img">
                @if ($donation->display_brand_image == 'Yes')


                <img src="{{ asset('Modules/Donation/public/uploads/'.optional($donation->file)->filename) }}" alt="donation-img" class="img-fluid">
                @else
                  <img src="{{ asset('Modules/Donation/Resources/assets/image/empty-img.png') }}" alt="" class="img-fluid imgradious">
                @endif
              </div>
              <div class="donation-card risingfoundation mb-0">
                <div class="donation-creator d-flex gap-10 align-items-center">

                  @if (optional($donation->creator)->picture != null && file_exists('public/uploads/user-profile/'. optional($donation->creator)->picture))
                      <img src="{{ asset('public/uploads/user-profile/'. optional($donation->creator)->picture) }}" class="img-fluid  avatar-campaign">
                  @else
                      <img src="{{ asset('public/dist/images/default-avatar.png') }}" class="img-fluid  avatar-campaign">
                  @endif
                  <h4>{{ __('by') }} {{ getColumnValue($donation->creator) }}</h4>
                </div>
                <h2 class="line-clamp-double">{{ $donation->title }}</h2> 
                <div class="donation-support">
                  <div class="d-flex justify-content-between currency mb-2">
                  <p><span class="dolar">{{ optional($donation->currency)->symbol }} {{ formatNumber($donation->raised_amount, optional($donation->currency)->id)}}
                    </span><span class="dolar-text">{{ __('of') }} {{ optional($donation->currency)->symbol }} {{ formatNumber($donation->goal_amount, optional($donation->currency)->id)}} {{ __('raised') }}</span></p>
                  <span class="dolar per">{{ formatNumber(($donation->raised_amount * 100) / $donation->goal_amount, optional($donation->currency)->id) }}%</span>
                  </div>
                  <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: {{ ($donation->raised_amount * 100) / $donation->goal_amount }}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <div class="w-100 progress-desc mb-0">
                    <div class="supporters d-flex align-items-center gap-2">
                      <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.30533 8.27483C0.469419 7.43151 0 6.29171 0 5.10332C0 3.90834 0.474359 2.76196 1.31932 1.91699C2.16429 1.07202 3.31068 0.597656 4.50566 0.597656C5.70063 0.597656 6.84702 1.07202 7.69199 1.91699L7.99999 2.225L8.30801 1.91699C9.15298 1.07202 10.2985 0.597656 11.4935 0.597656C12.6893 0.597656 13.8349 1.07202 14.6798 1.91699C15.5248 2.76196 16 3.90834 16 5.10332C16 6.29171 15.5306 7.43151 14.6938 8.27483L8.58143 14.7471C8.43072 14.9069 8.21988 14.9975 7.99999 14.9975C7.78011 14.9975 7.56928 14.9069 7.41857 14.7471L1.30533 8.27483ZM7.99999 13.0325L13.5326 7.17455L13.5491 7.15809C14.0935 6.61289 14.3998 5.87416 14.3998 5.10332C14.3998 4.33247 14.0935 3.59375 13.5491 3.04855C13.0039 2.50336 12.2644 2.197 11.4935 2.197C10.7235 2.197 9.98394 2.50336 9.43875 3.04855L8.56579 3.92234C8.25283 4.23447 7.74634 4.23447 7.43422 3.92234L6.56042 3.04855C6.01605 2.50336 5.2765 2.197 4.50566 2.197C3.73481 2.197 2.99609 2.50336 2.45089 3.04855C1.9057 3.59375 1.60016 4.33247 1.60016 5.10332C1.60016 5.87416 1.9057 6.61289 2.45089 7.15809C2.45666 7.16385 2.4616 7.16879 2.46654 7.17455L7.99999 13.0325Z" fill="#6A6B87"/>
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
                          @if(round((strtotime($donation->end_date) - strtotime(date("Y-m-d"))) / (24 * 60 * 60)) > 0)
                            {{ round((strtotime($donation->end_date) - strtotime(date("Y-m-d"))) / (24 * 60 * 60)) }} {{ __('Days Left') }}
                          @else
                            {{ __('Expired') }}
                          @endif
                    </span>
                    </div>
                  </div>
                </div>
            </div>
            </div>
          </a>
          @empty
          <div class="notfound mt-16 p-4">
            <div class="d-flex flex-wrap justify-content-center align-items-center gap-26">
              <div class="image-notfound">
                <img src="{{ asset('public/dist/images/not-found-img.png') }}" class="img-not-found">
              </div>
              <div class="text-notfound aspiration-desc">
                <p class="mb-0 f-20 leading-25 gilroy-medium">{{ __('Sorry!') }} </p>
                <p class="mb-0 f-16 leading-24 gilroy-regular text-gray-100 mt-12">{{ __('There is no campaign available.') }}</p>
              </div>
            </div>
          </div> 
          @endforelse
        </div>
        <div class="loading" style="display: none;">
          <p>{{ __('Loading more Donations') }}...</p>
        </div>
      </div>
    </div> 
    <!-- Campaign section end -->
    <!--Steps section start -->
    <div class="section-steps">
      <div class="px-240">
        <div class="row">
          <div class="col-md-6">
            <div class="module-centered-title text-start step-gap">
              <h4>{{ __('How it works') }}</h4>
              <h2>{{ __('Three easy steps to kickstart your fundraising') }}</h2>
              <p class="small-border mb-0 mt-20 bgd-blue"></p>
            </div>
            <div class="step-box">
              <div class="step-content d-flex align-items-stretch gap-22">
                <div class="line-box d-flex">
                  <div class="content-vline">
                    <div class="content-steps">
                      <span>{{ __('Step 1') }}:</span>
                      <p>{{ __('Set up campaign') }}</p>
                    </div>
                  </div>
                </div>
                <div class="steps-content">
                <p>{{ __("It will only take a few minutes! Choose a campaign from the list or browse using your preferred campaign link") }}.</p>
                </div>
              </div>
            </div>
            <div class="step-box">
              <div class="step-content d-flex align-items-stretch gap-22">
                <div class="line-box d-flex">
                  <div class="content-vline share">
                    <div class="content-steps">
                      <span>{{ __('Step 2') }}:</span>
                      <p>{{ __('Share your Link') }} </p>
                    </div>
                  </div>
                </div>
                <div class="steps-content">
                <p>{{ __("Just provide a few details about yourself, including your name and contact information, as well as the amount you aim to raise for your campaign") }}.</p>
                </div>
              </div>
            </div>
            <div class="step-box">
              <div class="final-content step-content d-flex align-items-stretch gap-22">
                <div class="line-box d-flex">
                  <div class="content-vline finale-step notify">
                    <div class="content-steps">
                      <span>{{ __('Step 3') }}:</span>
                      <p>{{ __('Donate Money') }} </p>
                    </div>
                  </div>
                </div>
                <div class="steps-content">
                <p>{{ __("Please select your preferred payment method from the options available, and confirm your payment to complete the donation") }}.</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 img-col">
            <div class="volunteer">
              <div class="volunteer-img">
                <img src="{{ asset('Modules/Donation/Resources/assets/image/voluntier.svg') }}" alt="volunteer" class="img-fluid">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--Steps section end -->
    <!-- Partner section start -->
    <div class="section-partner">
      <div class="px-240">
        <div class="module-centered-title">
          <h4>{{ __('OUR PARTNERS') }}</h4>
          <h2>{{ __('our integration partners') }}</h2>
          <p class="small-border mb-0 bgd-blue m-auto"></p>
        </div>
        <div class="d-flex justify-content-center flex-wrap gap-28 gateway-panel">
          <div class="gateway-box">
            <img src="{{ asset('Modules/Donation/Resources/assets/image/d-paypal.svg') }}" alt="image" class="img-fluid gateway-light">
            <img src="{{ asset('Modules/Donation/Resources/assets/image/dark-paypal.svg') }}" alt="image" class="img-fluid gateway-dark">
          </div>
          <div class="gateway-box">
            <img src="{{ asset('Modules/Donation/Resources/assets/image/d-coinbase.svg') }}" alt="image" class="img-fluid gateway-light">
            <img src="{{ asset('Modules/Donation/Resources/assets/image/dark-coinbase.svg') }}" alt="image" class="img-fluid gateway-dark">
          </div>
          <div class="gateway-box">
            <img src="{{ asset('Modules/Donation/Resources/assets/image/payeer.svg') }}" alt="image" class="img-fluid gateway-light">
            <img src="{{ asset('Modules/Donation/Resources/assets/image/payeer-dark.svg') }}" alt="image" class="img-fluid gateway-dark">
          </div>
          <div class="gateway-box">
            <img src="{{ asset('Modules/Donation/Resources/assets/image/d-stripe.svg') }}" alt="image" class="img-fluid gateway-light">
            <img src="{{ asset('Modules/Donation/Resources/assets/image/dark-stripe.svg') }}" alt="image" class="img-fluid gateway-dark">
          </div>
          <div class="gateway-box">
            <img src="{{ asset('Modules/Donation/Resources/assets/image/d-coinpayments.svg') }}" alt="image" class="img-fluid gateway-light">
            <img src="{{ asset('Modules/Donation/Resources/assets/image/dark-coinpayments.svg') }}" alt="image" class="img-fluid gateway-dark">
          </div>
          <div class="gateway-box">
            <img src="{{ asset('Modules/Donation/Resources/assets/image/pay-u-money.svg') }}" alt="image" class="img-fluid gateway-light">
            <img src="{{ asset('Modules/Donation/Resources/assets/image/pay-u-money-dark.svg') }}" alt="image" class="img-fluid gateway-dark">
          </div>
        </div>
      </div>
    </div>
    <!-- Partner section end -->
  </div>
@endsection
@section('js')

<script>

var lastPage = {{ $donations->lastPage() }};
</script>
<script src="{{ asset('Modules/Donation/Resources/assets/js/home.min.js') }}"></script>
@endsection