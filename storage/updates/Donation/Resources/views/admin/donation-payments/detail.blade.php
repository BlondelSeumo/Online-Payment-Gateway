@extends('admin.layouts.master')

@section('title', __('Campaign Payment Details'))

@section('page_content')

    <div class="box box-default">
        <div class="box-body">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="top-bar-title padding-bottom pull-left">{{ __('Campaign Payment Details') }}</div>
                </div>
                <div>
                    @if ($payment->status)
                        <p class="text-left mb-0 f-18">{{ __('Status') }} : {!! getStatusText($payment->status) !!}</p>
                    @endif
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
                                    <!-- Donor -->
                                    @if ($payment->payer_id)
                                        <div class="form-group row">
                                            <label class="control-label col-sm-3 fw-bold text-end" for="user">{{ __('Donor') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">
                                                    @php
                                                        echo getColumnValue($payment->payer);
                                                    @endphp
                                                </p>
                                            </div>
                                        </div>
                                        <!-- Donor Email -->
                                        <div class="form-group row">
                                            <label class="control-label col-sm-3 fw-bold text-end" for="user">{{ __('Donor Email') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">
                                                    {{ getColumnValue($payment->payer, 'email', '') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Creator -->
                                    @if (optional($payment->donation)->creator_id)
                                        <div class="form-group row">
                                            <label class="control-label col-sm-3 fw-bold text-end" for="user">{{ __('Creator') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">
                                                    <?php
                                                        $creator = getColumnValue(optional($payment->donation)->creator);
                                                        $creatorWithLink = (Common::has_permission(\Auth::guard('admin')->user()->id, 'edit_user')) ? '<a href="' . url(Config::get('adminPrefix').'/users/edit/' . optional(optional($payment->donation)->creator)->id) . '">'.$creator.'</a>' : $creator;
                                                        echo $creatorWithLink;
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Title -->
                                    @if (optional($payment->donation)->title)
                                        <div class="form-group row">
                                            <label class="control-label col-sm-3 fw-bold text-end" for="donation_uuid">{{ __('Campaign Title') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ optional($payment->donation)->title }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Transaction ID -->
                                    @if ($payment->uuid)
                                        <div class="form-group row">
                                            <label class="control-label col-sm-3 fw-bold text-end" for="donation_uuid">{{ __('Transaction ID') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ $payment->uuid }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Currency -->

                                    @if ($payment->currency)
                                        <div class="form-group row">
                                            <label class="control-label col-sm-3 fw-bold text-end" for="currency">{{ __('Currency') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ optional($payment->currency)->code }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Payment Method -->
                                    @if ($payment->paymentMethod)
                                        <div class="form-group row">
                                            <label class="control-label col-sm-3 fw-bold text-end" for="payment_method">{{ __('Payment Method') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ (optional($payment->paymentMethod)->name == "Mts") ? settings('name') : getColumnValue($payment->paymentMethod, 'name') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Date -->
                                    @if ($payment->created_at)
                                        <div class="form-group row">
                                            <label class="control-label col-sm-3 fw-bold text-end" for="created_at">{{ __('Date') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ dateFormat($payment->created_at) }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Status -->
                                    @if ($payment->status)
                                        <div class="form-group row">
                                        <label class="control-label col-sm-3 fw-bold text-end" for="status">{{ __('Status') }}</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static">{{ $payment->status == 'blocked' ? __('Cancel') : $payment->status }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-12 offset-md-3">
                                            <a class="btn btn-theme-danger pull-left" href="{{ route('admin.donation-payment.index') }}" id="users_cancel">{{ __('Back') }}</a>
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
                                    <!-- Amount -->
                                    @if ($payment->amount)
                                        <div class="form-group row">
                                            <label class="control-label col-sm-6 fw-bold text-end" for="amount">{{ __('Amount') }}</label>
                                            <div class="col-sm-6">
                                            <p class="form-control-static">{{  moneyFormat(optional($payment->currency)->symbol, formatNumber($payment->amount, $payment->currency_id)) }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Fees -->
                                    <div class="form-group row ">
                                        <label class="control-label col-sm-6 fw-bold text-end" for="feesTotal">{{ __('Fees') }}
                                        </label>
                                        @php
                                            $feesTotal = $payment->charge_percentage + $payment->charge_fixed;
                                        @endphp
                                        <div class="col-sm-6">
                                            <p class="form-control-static">{{  moneyFormat(optional($payment->currency)->symbol, formatNumber($feesTotal, $payment->currency_id)) }}</p>
                                        </div>
                                    </div>

                                    <hr class="increase-hr-height">

                                    <!-- Total -->

                                    @if (isset($payment->total))
                                        <div class="form-group row total-donation-space">
                                            <label class="control-label col-sm-6 fw-bold text-end" for="total">{{ __('Total') }}</label>
                                            <div class="col-sm-6">
                                            <p class="form-control-static">{{  moneyFormat(optional($payment->currency)->symbol, formatNumber($payment->total, $payment->currency_id)) }}</p>
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
