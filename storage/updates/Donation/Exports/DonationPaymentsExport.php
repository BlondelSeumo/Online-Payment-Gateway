<?php

namespace Modules\Donation\Exports;

use Modules\Donation\Entities\DonationPayment;
use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles};

class DonationPaymentsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function query()
    {
        $status = isset(request()->status) ? request()->status : null;
        $user = isset(request()->user_id) ? request()->user_id : null;
        $currency = isset(request()->currency) ? request()->currency : null;
        $to = !empty(request()->endto) ? setDateForDb(request()->endto) : null;
        $paymentMethod = isset(request()->payment_method) ? request()->payment_method : null;
        $from = !empty(request()->startfrom) ? setDateForDb(request()->startfrom) : null;

        return (new DonationPayment())->getDonationPaymentsList($from, $to, $status, $currency, $paymentMethod, $user, null);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Payer',
            'Amount',
            'Fees',
            'Total',
            'Currency',
            'Payment Method',
            'Status',
        ];
    }

    public function map($payment): array
    {
        return [
            dateFormat($payment->created_at),
            getColumnValue($payment->payer),
            formatNumber($payment->amount, $payment->currency_id),
            $payment->charge_percentage == 0 && $payment->charge_fixed == 0 ? '-' : formatNumber($payment->charge_percentage + $payment->charge_fixed, $payment->currency_id),
            "+" . formatNumber($payment->total , $payment->currency_id),
            optional($payment->currency)->code,
            (optional($payment->paymentMethod)->name == 'Mts' ? settings('name') : getColumnValue($payment->paymentMethod, 'name')),
            ($payment->status == 'blocked') ? __('Cancelled') : $payment->status
        ];
    }

    public function styles($transfer)
    {
        $transfer->getStyle('A:B')->getAlignment()->setHorizontal('center');
        $transfer->getStyle('C:D')->getAlignment()->setHorizontal('center');
        $transfer->getStyle('E:F')->getAlignment()->setHorizontal('center');
        $transfer->getStyle('G:H')->getAlignment()->setHorizontal('center');
        $transfer->getStyle('1')->getFont()->setBold(true);
    }
}
