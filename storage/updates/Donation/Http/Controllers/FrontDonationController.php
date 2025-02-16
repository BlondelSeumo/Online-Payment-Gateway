<?php

namespace Modules\Donation\Http\Controllers;

use App\Http\Helpers\Common;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Donation\Entities\Donation;
use Modules\Donation\Entities\DonationPayment;
use Modules\Donation\Http\Requests\PaymentMethodRequest;
use Modules\Donation\Http\Requests\PaymentRequest;
use Modules\Donation\Services\DonationService;

class FrontDonationController extends Controller
{

    protected $doanationService;
    protected $helper;
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->doanationService = new DonationService();
        $this->helper = new Common();
    }

    /**
     * Method home
     *
     * @return view
     */
    public function home(Request $request) {
 
        $donations = Donation::with([
            'currency:id,code,symbol', 'file:id,filename', 'creator:id,first_name,last_name,picture', 'donationPayments'
        ])->whereColumn('goal_amount', '!=', 'raised_amount')->whereDate('end_date', '>', Carbon::today())->orderBy('id', 'desc')
        ->select('id', 'slug', 'creator_id', 'title', 'display_brand_image', 'goal_amount', 'raised_amount', 'end_date', 'file_id', 'currency_id', 'description')->paginate(6);
        $data = [
            'donationCount' => checkDemoEnvironment() ? config('donation.demo_payment_count') : formatCount(DonationPayment::count()),
            'menu'       => 'Donation',
            'pageInfo'   => 'donation',
            'donations'   => $donations
        ];

        if (request()->ajax()) {
            return response()->json([
                'donations' => view('donation::front.donation-list-ajax', compact('donations'))->render(),
                'next_page' => $donations->currentPage() + 1,
                'last_page' => $donations->lastPage()
            ]);

        }
        return view('donation::front.home', $data);
    }

    /**
     * Donation details
     *
     * @param $slug
     *
     * @return view
     */
    public function details($slug)
    {
        $donation = Donation::with(['currency:id,code,symbol,type','file:id,filename'])->whereColumn('goal_amount', '!=', 'raised_amount')->whereDate('end_date', '>', Carbon::today())->where('slug', $slug)->first();

        if (empty($donation)) {
            return abort(404);
        }
        $data = [
            'menu'       => 'Donation',
            'pageInfo'   => 'donation',
            'donation'   => $donation,
            'socialShareUrl' => route('donations.details', $donation->slug)
        ];
        return view('donation::front.details', $data);
    }

    public function paymentForm(PaymentMethodRequest $request)
    {
        try {
            $data['donation'] = $donation =  $this->doanationService->getDonation($request->donation_id);
            $data['paymentMethods'] = getDonationActivePaymentMethod($donation->currency_id);
            $data['paymentData'] = $paymentData = $this->doanationService->setDonationPaymentData($request, null, $donation);
            setPaymentData($paymentData);
            return view('donation::payment.methods', $data);
        } catch (Exception $e) {
            $this->helper->one_time_message('error', __($e->getMessage()));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Method depositGateway
     *
     * @param Request $request
     *
     * Set payment data
     *
     * Generate Payment url, redirect to payment page
     *
     */
    public function donationGateway(PaymentRequest $request)
    {
        try {
            $data = getPaymentData();
            $transInfo  = $this->doanationService->validateDepositable(
                $request->currency_id,
                $request->amount,
                $request->donation_id,
                $request->method
            );
            $transData = array_merge($transInfo, $data);
            
            // These are the mandatory field for dynamic gateway payment.
            $paymentData = [
                'method'      => $transInfo['payment_method'],
                'redirectUrl' => route('donation.payment'),
                'cancel_url'  => url('payment/fail'),
                'success_url' => route('donation.success'),
                'gateway'     => $transInfo['paymentMethodAlias'],
            ];
            $data = array_merge($transData, $paymentData);
            setPaymentData($data);
            return redirect(gatewayPaymentUrl($data));

        } catch (Exception $e) {
            $this->helper->one_time_message('error', __($e->getMessage()));
            return redirect()->route('donations.home');
        }

    }

    public function getCampaignFeesLimit(Request $request)
    {
        try {
            $paymentMethod = PaymentMethod::where('name', $request->method)->first();
            $feesDetails = $this->helper->transactionFees($request->currencyId, $request->amount, Donation_Sent, $paymentMethod->id);
            $feeBearer = feeBearer($request->donationId);

            $totalAmount = isset($feeBearer) ? ($feeBearer == 'donor' ? $feesDetails->total_amount : $feesDetails->amount) : $feesDetails->amount;
            $totalFees = isset($feeBearer) ? ($feeBearer == 'donor' ? $feesDetails->total_fees : 0) : 0;

            $data = [
                'totalAmount' => moneyFormat($request->currencySymbol, formatNumber($totalAmount, $request->currencyId)),
                'totalFees' => moneyFormat($request->currencySymbol, formatNumber($totalFees, $request->currencyId)),
            ];
            $data['status'] = '200';
        } catch (Exception $e) {
            $data = [
                'message' => __($e->getMessage()),
                'status' => '401'
            ];
        }
        return response()->json(['success' => $data]);
    }
}
