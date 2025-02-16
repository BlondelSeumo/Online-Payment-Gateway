<?php

namespace Modules\Donation\Http\Controllers\Admin;

use Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Donation\Entities\DonationPayment;
use Modules\Donation\Exports\DonationPaymentsExport;
use Modules\Donation\DataTables\DonationPaymentsDataTable;

class DonationPaymentController extends Controller
{
    public function index(DonationPaymentsDataTable $dataTable)
    {
        if (!m_g_c_v('RE9OQVRJT05fU0VDUkVU') && m_aic_c_v('RE9OQVRJT05fU0VDUkVU')) {
            return view('addons::install', ['module' => 'RE9OQVRJT05fU0VDUkVU']);
        }

        $user = isset(request()->user_id) ? request()->user_id : null;
        $data = [
            'user' => $user,
            'menu' => 'donation',
            'sub_menu' => 'donation_payments',
            'status' => isset(request()->status) ? request()->status : 'all',
            'currency' => isset(request()->currency) ? request()->currency : 'all',
            'donationStatuses' => (new DonationPayment)->groupBy('status')->get(['status']),
            'to' => isset(request()->to ) ? setDateForDb(request()->to) : null,
            'getName' => (new DonationPayment)->getDonationPaymentsPayersName($user),
            'from' => isset(request()->from) ? setDateForDb(request()->from) : null,
            'paymentMethod' => isset(request()->payment_method) ? request()->payment_method : 'all',
            'donationCurrencies'   => (new DonationPayment)->with('currency:id,code')->groupBy('currency_id')->get(['currency_id']),
            'donationPaymentMethods' => (new DonationPayment)->with('paymentMethod:id,name')->whereNotNull('payment_method_id')->groupBy('payment_method_id')->get(['payment_method_id']),
        ];

        return $dataTable->render('donation::admin.donation-payments.index', $data);
    }

    public function userSearch(Request $request)
    {
        $search = $request->search;
        $user = (new DonationPayment)->getDonationPaymentsPayersResponse($search);

        $res = [
            'status' => 'fail',
        ];
        
        if (count($user) > 0) {
            $res = [
                'status' => 'success',
                'data'   => $user,
            ];
        }
        return json_encode($res);
    }

    public function csv()
    {
        return Excel::download(new DonationPaymentsExport(), 'campaign_payments_'. time() .'.xls');
    }

    public function pdf()
    {
        $status   = isset(request()->status) ? request()->status : null;
        $user     = isset(request()->user_id) ? request()->user_id : null;
        $currency = isset(request()->currency) ? request()->currency : null;
        $to       = !empty(request()->endto) ? setDateForDb(request()->endto) : null;
        $paymentMethod       = isset(request()->payment_method) ? request()->payment_method : null;
        $from     = !empty(request()->startfrom) ? setDateForDb(request()->startfrom) : null;

        $data = [
            'date_range' => isset($from) && isset($to) ? $from . ' To ' . $to : 'N/A',
            'payments' => (new DonationPayment)->getDonationPaymentsList($from, $to, $status, $currency, $paymentMethod, $user)->get()
        ];

        generatePDF('donation::admin.donation-payments.pdf', 'campaign_payments_', $data);
    }

    public function detail(DonationPayment $payment)
    {
        $data = [
            'menu' => 'donation',
            'sub_menu' => 'donation_payments',
            'payment' => $payment->load(['payer:id,first_name,last_name,email', 'donation:id,title,creator_id']),
        ];

        return view('donation::admin.donation-payments.detail', $data);
    }
}