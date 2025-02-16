<?php

namespace Modules\Donation\DataTables;

use Common;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Services\DataTable;
use Modules\Donation\Entities\DonationPayment;

class DonationPaymentsDataTable extends DataTable
{
    public function ajax(): JsonResponse
    {
        return datatables()

            ->eloquent($this->query())

            ->editColumn('created_at', function ($payment) {
                return dateFormat($payment->created_at);
            })

            ->addColumn('payer_id', function ($payment) {
                return getColumnValue($payment->payer);
            })

            ->editColumn('amount', function ($payment) {
                return formatNumber($payment->amount, $payment->currency_id);
            })

            ->addColumn('fees', function ($payment) {
                return $payment->charge_percentage == 0 && $payment->charge_fixed == 0 ? '-' : formatNumber($payment->charge_percentage + $payment->charge_fixed, $payment->currency_id);
            })

            ->addColumn('total', function ($payment) {
                return '<td><span class="text-green">+' . formatNumber($payment->total, $payment->currency_id) . '</span></td>';
            })

            ->editColumn('currency_id', function ($payment) {
                return optional($payment->currency)->code;
            })

            ->editColumn('payment_method_id', function ($payment) {
                return optional($payment->paymentMethod)->name == "Mts" ? settings('name') : getColumnValue($payment->paymentMethod, 'name');
            })

            ->editColumn('status', function ($payment) {
                return getStatusLabel($payment->status);
            })

            ->addColumn('action', function ($payment) {
                return (Common::has_permission(auth()->guard('admin')->user()->id, 'edit_campaign_payment')) ?
                '<a href="' . route('admin.donation-payment.detail', $payment->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            })

            ->rawColumns(['total', 'status', 'action'])

            ->make(true);
    }

    public function query()
    {
        $status = isset(request()->status) ? request()->status : 'all';
        $currency = isset(request()->currency) ? request()->currency : 'all';
        $paymentMethod = isset(request()->payment_method) ? request()->payment_method : 'all';
        $user = isset(request()->user_id) ? request()->user_id : null;
        $from = isset(request()->from) ? setDateForDb(request()->from) : null;
        $to = isset(request()->to ) ? setDateForDb(request()->to) : null;
        $query = (new DonationPayment())->getDonationPaymentsList($from, $to, $status, $currency, $paymentMethod, $user);

        return $this->applyScopes($query);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn([
                'data' => 'id',
                'name' => 'donation_payments.id',
                'title' => __('ID'),
                'searchable' => false,
                'visible' => false
            ])
            ->addColumn([
                'data' => 'created_at', 
                'name' => 'donation_payments.created_at',
                'title' => __('Date')
            ])
            ->addColumn([
                'data' => 'uuid', 
                'name' => 'donation_payments.uuid',
                'title' => __('UUID'),
                'visible' => false
            ])
            ->addColumn([
                'data' => 'payer_id', 
                'name' => 'payer.last_name', 
                'title' => __('Payer'), 
                'visible' => false
            ])
            ->addColumn([
                'data' => 'payer_id', 
                'name' => 'payer.first_name', 
                'title' => __('Payer')
            ])
            ->addColumn([
                'data' => 'amount', 
                'name' => 'donation_payments.amount', 
                'title' => __('Amount')
            ])
            ->addColumn([
                'data' => 'fees',
                'name' => 'fees',
                'title' => __('Fees')
            ])
            ->addColumn([
                'data' => 'total',
                'name' => 'total',
                'title' => __('Total')
            ]) 
            ->addColumn([
                'data' => 'currency_id',
                'name' => 'currency.code',
                'title' => __('Currency')
            ])
            ->addColumn([
                'data' => 'payment_method_id',
                'name' => 'paymentMethod.name ', 
                'title' => __('Payment Method')
            ])
            ->addColumn([
                'data' => 'status',
                'name' => 'donation_payments.status', 
                'title' => __('Status')
            ])
            ->addColumn([
                'data' => 'action',
                'name' => 'action',
                'title' => __('Action'),
                'orderable' => false, 
                'searchable' => false
            ])
            ->parameters(dataTableOptions());
    }
}
