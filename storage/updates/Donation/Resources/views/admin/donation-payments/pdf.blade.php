@extends('admin.pdf.app')

@section('title', __('Campaign Payment pdf'))

@section('content')
    <div class="mt-30">
        <table class="table">
            <tr class="table-header">
                <td>{{ __('Date') }}</td>
                <td>{{ __('User') }}</td>
                <td>{{ __('Amount') }}</td>
                <td>{{ __('Fees') }}</td>
                <td>{{ __('Total') }}</td>
                <td>{{ __('Currency') }}</td>
                <td>{{ __('Payment Method') }}</td>
                <td>{{{ __('Status') }}}</td>
            </tr>

            @foreach($payments as $payment)
                <tr class="table-r">
                    <td>{{ dateFormat($payment->created_at) }}</td>
                    <td>{{ getColumnValue($payment->payer) }}</td>
                    <td>{{ formatNumber($payment->amount, $payment->currency_id) }}</td>
                    <td>
                        {{ $payment->charge_percentage == 0 && $payment->charge_fixed == 0 ? '-' : formatNumber($payment->charge_percentage + $payment->charge_fixed , $payment->currency_id) }}
                    </td>
                    <td>{{ '+' . formatNumber($payment->total, $payment->currency_id) }}</td>
                    <td>{{ optional($payment->currency)->code }}</td>
                    <td>{{ (optional($payment->paymentMethod)->name == "Mts") ? settings('name') : getColumnValue($payment->paymentMethod, 'name') }}</td>
                    <td>{{ ($payment->status == 'blocked') ? __('Cancelled') : $payment->status }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
