<?php

namespace Modules\Donation\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\{Currency, PaymentMethod, Transaction, Wallet};
use Modules\Donation\Entities\{Donation, DonationPayment};

class PaymentController extends Controller
{
    public function index()
    {
        if (!m_g_c_v('RE9OQVRJT05fU0VDUkVU') && m_aic_c_v('RE9OQVRJT05fU0VDUkVU')) {
            return view('vendor.installer.errors.user');
        }
        $donationIds = Donation::where('creator_id', auth()->id())->pluck('id')->toArray();
        $donationIds = !empty($donationIds) ? $donationIds : [0];
        $status      = isset(request()->status) ? request()->status : 'all';
        $user        = isset(request()->user_id) ? request()->user_id : null;
        $currency    = isset(request()->currency) ? request()->currency : 'all';
        $to          = isset(request()->to) ? setDateForDb(request()->to) : null;
        $from        = isset(request()->from) ? setDateForDb(request()->from) : null;
        $paymentMethod = isset(request()->payment_method) ? request()->payment_method : 'all';

        $data = [
            'to' => $to,
            'paymentMethod' => $paymentMethod,
            'from' => $from,
            'user' => $user,
            'status' => $status,
            'currency' => $currency,
            'paymentMethods' => PaymentMethod::whereIn('id', [Mts, Stripe, Paypal, PayUmoney, Coinpayments, Payeer, Coinbase])->get(['id', 'name']),
            'donationCurrencies' => Donation::with('currency:id,code,type')->select('currency_id')->groupBy('currency_id')->get(),
            'payments' => (new DonationPayment())->getDonationPaymentsList($from, $to, $status, $currency, $paymentMethod, $user, $donationIds)->paginate(10)
        ];


        return view('donation::user.payment', $data);
    }

    public function print(Transaction $transaction)
    {
        $data = [
            'transaction' => $transaction->load([
                'payment_method:id,name',
                'currency:id,symbol,code',
                'transaction_type:id,name',
            ])
        ];

        generatePDF('donation::user.donation-payment-pdf', 'donationPayment_', $data);
    }
}