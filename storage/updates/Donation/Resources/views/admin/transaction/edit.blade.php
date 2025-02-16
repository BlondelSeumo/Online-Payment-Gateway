@extends('admin.layouts.master')
@section('title', __('Edit Transaction'))

@section('page_content')

    <div class="box box-default">
        <div class="box-body">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="top-bar-title padding-bottom pull-left">{{ __('Transaction Details') }}</div>
                </div>
                <!-- Transaction Status -->
                <div>
                    @if ($transaction->status)
                        <p class="text-left mb-0 f-18">{{ __('Status') }} :
                            @php
                                $transactionTypes = config('donation.transaction_types');
                                if (in_array($transaction->transaction_type_id, $transactionTypes)) {
                                    echo getStatusText($transaction->status);
                                }
                            @endphp
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <section class="min-vh-100">
        <div class="my-30">
            <div class="row f-14">
                <!-- Page title start -->
                <div class="col-md-8">
                    <div class="box">
                        <div class="box-body">
                            <div class="panel">
                                <div>
                                    <div class="p-4 rounded">
                                        <!-- Donor -->
                                        <div class="form-group row">
                                            <label
                                                class="control-label col-sm-3 fw-bold text-sm-end">{{ __('Donor') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">
                                                    {{ $transaction->transaction_type_id == Donation_Sent ? getColumnValue($transaction->user) : getColumnValue($transaction->end_user) }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Campaigner -->
                                        <div class="form-group row">
                                            <label
                                                class="control-label col-sm-3 fw-bold text-sm-end">{{ __('Campaigner') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">
                                                    {{ $transaction->transaction_type_id == Donation_Sent ? getColumnValue($transaction->end_user) : getColumnValue($transaction->user) }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Campaign -->
                                        <div class="form-group row">
                                            <label
                                                class="control-label col-sm-3 fw-bold text-sm-end">{{ __('Campaign') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">
                                                    {{ $transaction->donationPayment?->donation?->title }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Transaction ID -->
                                        <div class="form-group row">
                                            <label class="control-label col-sm-3 fw-bold text-sm-end">{{ __('Transaction ID') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">
                                                    {{ getColumnValue($transaction, 'uuid') }}</p>
                                            </div>
                                        </div>

                                        <!-- Type -->
                                        @if ($transaction->transaction_type_id)
                                            <div class="form-group row">
                                                <label class="control-label col-sm-3 fw-bold text-sm-end">{{ __('Type') }}</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static">
                                                        {{ str_replace('_', ' ', $transaction->transaction_type?->name) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Currency -->
                                        <div class="form-group row">
                                            <label
                                                class="control-label col-sm-3 fw-bold text-sm-end">{{ __('Currency') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">
                                                    {{ getColumnValue($transaction->currency, 'code') }}</p>
                                            </div>
                                        </div>

                                        <!-- Payment Method -->
                                        @if (isset($transaction->payment_method_id))
                                            <div class="form-group row">
                                                <label
                                                    class="control-label col-sm-3 fw-bold text-sm-end">{{ __('Payment Method') }}</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static">
                                                        {{ $transaction?->payment_method?->id == Mts ? settings('name') : getColumnValue($transaction->payment_method, 'name', '') }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($transaction->payment_status))
                                            <div class="form-group row">
                                                <label class="control-label col-sm-3 fw-bold text-sm-end">{{ __('Payment Status') }}</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static">{!! getStatusText($transaction->payment_status) !!}</p>
                                                </div>
                                            </div>
                                        @endif
                                        <!-- Created at date -->
                                        <div class="form-group row">
                                            <label
                                                class="control-label col-sm-3 fw-bold text-sm-end">{{ __('Date') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">
                                                    {{ dateFormat(getColumnValue($transaction, 'created_at')) }}</p>
                                            </div>
                                        </div>

                                        <!-- Investment Status -->
                                        <div class="form-group row">
                                            <label
                                                class="control-label col-sm-3 fw-bold text-sm-end">{{ __('Status') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">
                                                    {{ getColumnValue($transaction->donationPayment, 'status', '') }}</p>
                                            </div>
                                        </div>

                                        <!-- Back Button -->
                                        <div class="row">
                                            <div class="col-md-6 offset-md-3">
                                                <a id="cancel_anchor" class="btn btn-theme-danger me-1 f-14"
                                                    href="{{ url(config('adminPrefix') . '/transactions') }}">{{ __('Back') }}</a>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amount Section -->
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-body">
                            <div class="panel">
                                <div>
                                    <div class="pt-4 rounded">
                                        @if ($transaction->subtotal)
                                            <div class="form-group row">
                                                <label
                                                    class="control-label col-sm-6 fw-bold text-sm-end">{{ __('Amount') }}</label>
                                                <div class="col-sm-6">
                                                    {{ moneyFormat(optional($transaction->currency)->symbol, formatNumber($transaction->subtotal, $transaction->currency_id)) }}
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group row total-deposit-feesTotal-space">

                                            <label
                                                class="control-label col-sm-6 d-flex fw-bold justify-content-end">{{ __('Fees') }}
                                                <span>
                                                    <small class="transactions-edit-fee">
                                                        @if (isset($transaction))
                                                            ({{ formatNumber($transaction->percentage, $transaction->currency_id) }}%
                                                            +
                                                            {{ formatNumber($transaction->charge_fixed, $transaction->currency_id) }})
                                                        @else
                                                            (0% + 0)
                                                        @endif
                                                    </small>
                                                </span>
                                            </label>
                                            @php
                                                $totalFees = $transaction->charge_percentage + $transaction->charge_fixed;
                                            @endphp

                                            <div class="col-sm-6">
                                                <p class="form-control-static">
                                                    {{ moneyFormat(optional($transaction->currency)->symbol, formatNumber($totalFees, $transaction?->currency_id)) }}
                                                </p>

                                            </div>
                                        </div>

                                        <hr class="increase-hr-height">

                                        @if ($transaction->total)
                                            <div class="form-group row total-deposit-space">
                                                <label
                                                    class="control-label col-sm-6 fw-bold text-sm-end">{{ __('Total') }}</label>
                                                <div class="col-sm-6">
                                                    <p class="form-control-static">
                                                        {{ moneyFormat(optional($transaction->currency)->symbol, str_replace('-', '', formatNumber($transaction->total, $transaction->currency_id))) }}
                                                    </p>
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
        </div>
    </section>
@endsection
