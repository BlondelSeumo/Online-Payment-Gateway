<?php

namespace Modules\Donation\Http\Controllers;

use Exception;
use App\Http\Helpers\Common;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Donation\Http\Requests\PaymentPageRequest;
use Modules\Donation\Entities\DonationPayment;
use Modules\Donation\Services\{DonationMailService, DonationService};

class DonationPaymentController extends Controller
{
    protected $helper;
    protected $doanationService;
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->helper = new Common();
        $this->doanationService = new DonationService();
    }


    public function donationComplete(Request $request)
    {
        try {
            
            $data = getPaymentParam(request()->params);

            if (!empty(auth()->check()) && auth()->user()->id == $data['creator_id']) {
                throw new Exception(__('You cannot make payment to your own campaign'));
            }
            isGatewayValidMethod($data['paymentMethodName']);

            $donationPayment =  DonationPayment::createNewDonationPayment($data);
            (new DonationMailService)->send($data);
            (new DonationMailService)->sendToDoner($data);

            $data['transaction_id'] = $donationPayment;

            clearActionSession();

            if (isset(request()->execute) && (request()->execute == 'api')) {
                return  $data['transaction_id'];
            }

            setPaymentData($data);

            return redirect()->route('donation.success');

        } catch (Exception $e) {

            if (isset(request()->execute) && (request()->execute == 'api')) {
                return [
                    'status' => '401',
                    'message' => $e->getMessage()
                ];
            }

            $this->helper->one_time_message('error', __( $e->getMessage() ));
            return redirect('payment/fail');
        }

    }

    public function donationSuccess()
    {
        try {
            $data = getPaymentData();
            return view('donation::front.thank', $data);
        } catch (Exception $e) {
            $this->helper->one_time_message('error', $e->getMessage());
            return redirect(route('donations.home'));
        }

    }

    public function print(Transaction $transaction)
    {
        $donationPayment = DonationPayment::with('payer')->find($transaction->transaction_reference_id);
        $data = [
            'transaction' => $transaction->load([
                'payment_method:id,name',
                'currency:id,symbol,code',
                'transaction_type:id,name',
            ]),
            'donationPayment' => $donationPayment
            ];

        generatePDF('donation::front.donation-payment-invoice-pdf', 'donationPayment_', $data);
    }


  
}
