<?php

namespace Modules\Donation\Exports;

use Modules\Donation\Entities\Donation;
use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles};

class DonationsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function query()
    {
        $type = isset(request()->type) ? request()->type : 'all';
        $user = isset(request()->user_id) ? request()->user_id : null;
        $currency  = isset(request()->currency) ? request()->currency : 'all';
        return  (new Donation())->getDonationsList($type, $currency, $user);
    }

    public function headings(): array
    {
        return [
            'Date',
            'User',
            'Campaign Title',
            'Goal Amount',
            'Raised Amount',
            'Currency',
            'Campaign Type',
            'Fee Bearer',
        ];
    }

    public function map($donation): array
    {
        return [
            dateFormat($donation->end_date),
            getColumnValue($donation->creator),
            $donation->title,
            formatNumber($donation->goal_amount, $donation->currency_id),
            formatNumber($donation->raised_amount, $donation->currency_id),
            optional($donation->currency)->code,
            ucwords(str_replace('_', ' ', $donation->donation_type)),
            ucfirst($donation->fee_bearer),
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
