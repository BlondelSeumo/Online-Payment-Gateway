@extends('user.pdf.app')

@section('title', __('Donation pdf'))
    
@section('content')
    <!-- Transaction details start -->
    <table class="tabl-width">
        <tbody>
            <tr>
                <td class="px-30 pb-24">
                    <span class="text-sm">{{ __('Donor') }}</span>
                    <h2 class="text-lg">{{ getColumnValue($transaction->donationPayment?->payer) }}</h2>
                </td>
                <td class="px-30 pb-24 align-rigt">
                    <span class="text-sm">{{ __('Currency') }}</span>
                    <h2 class="text-lg">{{ optional($transaction->currency)->code }}</h2>
                </td>
            </tr>
            <tr>
                <td class="px-30 pb-24">
                    <span class="text-sm">{{ __('Payment Method Name') }}</span>
                    <h2 class="text-lg">
                        {{ 
                            $transaction->payment_method?->name == 'Mts' 
                                ? settings('name') : 
                                $transaction->payment_method?->name 
                        }}
                    </h2>
                </td>
                <td class="px-30 pb-24 align-rigt">
                    <span class="text-sm">{{ __('Transaction Type') }}</span>
                    <h2 class="text-lg">{{ str_replace('_', ' ', $transaction->transaction_type?->name) }}</h2>
                </td>
            </tr>
            <tr>
                <td class="px-30 pb-24">
                    <span class="text-sm">{{ __('Transaction ID') }}</span>
                    <h2 class="text-lg">{{ $transaction->uuid }}</h2>
                </td>
                <td class="px-30 pb-24 align-rigt">
                    <span class="text-sm">{{ __('Transaction Date') }}</span>
                    <h2 class="text-lg">{{ dateFormat($transaction->created_at) }}</h2>
                </td>
            </tr>
            <tr>
                <td class="px-30 pb-24 align-left">
                    <span class="text-sm">{{ __('Status') }}</span>
                    <h2 class="text-lg {{ getColor($transaction->status) }}">{{ $transaction->status }}</h2>
                </td>
                <td class="px-30 pb-24 align-rigt">
                    <span class="text-sm">{{ __('Donation Amount') }}</span>
                    <h2 class="text-lg">{{ moneyFormat(optional($transaction->currency)->symbol, formatNumber($transaction->subtotal, $transaction->currency_id)) }}</h2>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- Transaction details end -->

    <!-- Transaction amount start -->
    <table class="tabl-width">
        <tbody>

            <tr>
                <td class="px-desc">
                    <p class="desc-title">{{ __('Description') }}</p>
                </td>
            </tr>
            <tr>
                <td class="pb-10 pl-30 align-center">
                    <p class="text-md">{{ __('Sub Total') }}</p>
                </td>
                <td class="pb-10 pl-30 align-center">
                    <p class="text-md">{{ moneyFormat(optional($transaction->currency)->symbol, formatNumber($transaction->subtotal, $transaction->currency_id)) }}</p>
                </td>
            </tr>
            <tr>
                <td class="pb-10 pl-30 align-center">
                    <p class="text-md">{{ __('Fees') }}</p>
                </td>
                <td class="pb-10 pl-30 align-center">
                    <p class="text-md">{{ getmoneyFormatFee($transaction) }}</p>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="pt-0 pb-10 pl-80 pr-120">
                    <hr>
                </td>
            </tr>
            <tr>
                <td class="pb-10 pl-30 align-center">
                    <p class="text-md">{{ __('Total') }}</p>
                </td>
                <td class="pb-10 pl-30 align-center">
                    <p class="text-md">{{ moneyFormat(optional($transaction->currency)->symbol, formatNumber($transaction->total, $transaction->currency_id)) }}</p>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- Transaction amount end -->
@endsection