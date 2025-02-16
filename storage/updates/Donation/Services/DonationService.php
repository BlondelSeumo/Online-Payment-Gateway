<?php

namespace Modules\Donation\Services;

use App\Http\Controllers\Users\EmailController;
use App\Http\Helpers\Common;
use App\Http\Resources\V2\FeesResource;
use App\Models\{
    EmailTemplate,
    FeesLimit,
    PaymentMethod,
    Wallet
};
use Exception;
use Modules\Donation\Entities\{
    Donation,
    DonationPayerDetail
};

class DonationService
{
    public function getCampaigns()
    {
        $type = isset(request()->type) ? request()->type : 'all';
        $user = isset(request()->user) && request()->user == 'all' ? 'all' : auth()->id();
        $currency = isset(request()->currency) ? request()->currency : 'all';
        $limit = isset(request()->limit) ? request()->limit : 12;
        $offset = isset(request()->offset) ? request()->offset : 0;
        $status = isset(request()->status) ? request()->status : 'all';
        $order = isset(request()->order) ? request()->order : 'desc';

        $query = Donation::with('file:id,filename', 'currency:id,symbol')->withCount('donationPayment');

        if ($user !== 'all') {
            $query->where('creator_id', $user);
        }

        if ($status == 'active') {
            $query->where('end_date', '>=', date('Y-m-d'));
        }

        if ($status == 'expired') {
            $query->where('end_date', '<', date('Y-m-d'));
        }

        if ($type !== 'all') {
            $query->where('donation_type', $type);
        }

        if ($currency !== 'all') {
            $query->where('currency_id', $currency);
        }

        $donation = $query->orderBy('id', $order);

        return [
            'totalRecords' => $donation->count(),
            'campaigns' => $query->offset($offset)->limit($limit),
        ];
    }

    /**
     * Method getDonation
     *
     * @param $id $id [explicite description]
     *
     * @return object
     */
    public function getDonation($id)
    {
        $donation = Donation::where('id', $id)->first(['id', 'title', 'currency_id', 'creator_id', 'end_date', 'goal_amount', 'raised_amount', 'donation_type', 'fixed_amount']);

        if (empty($donation)) {
            throw new Exception(__('Did not found the campaign'));
        }

        if (auth()->user()) {
            if (auth()->user()->id == $donation->creator_id) {
                throw new Exception(__('You cannot make payment to your own campaign'));
            }

            if (auth()->user()->status == "Suspended") {
                throw new Exception(__('You are suspended to do any kind of transaction'));
            }
        }

        if ($donation->end_date < date("Y-m-d")) {
            throw new Exception(__('The campaign has already been expired'));
        }

        if ($donation->goal_amount < (request()->amount + $donation->raised_amount)) {
            throw new Exception(__('The amount exceeded goal amount of the campaign'));
        }
        
        if ($donation->donation_type == 'fixed_amount' && request()->amount != $donation->fixed_amount) {
            throw new Exception(__('The donation amount must align with the specified payment amount'));
        }

        return $donation;
    }

    public function payerInfoSet()
    {
        if (empty(request()->first_name) || empty(request()->last_name) || empty(request()->email)) {
            return null;
        }

        $payer = new DonationPayerDetail();
        $payer->donation_id = request()->donation_id;
        $payer->first_name = request()->first_name;
        $payer->last_name = request()->last_name;
        $payer->email = request()->email;
        $payer->save();

        return $payer->id;
    }

    public function sendMailToCreator(array $data)
    {
        $emailTemplate = EmailTemplate::where(['temp_id' => 49, 'lang' => getLanguageDefault()->short_name, 'type' => 'email'])->first();
        if (empty($emailTemplate)) {
            return false;
        }
        $message = $emailTemplate->body;
        $message = str_replace(
            [
                '{user}', '{donation_title}', '{amount}', '{currency_code}', '{soft_name}',
            ],
            [
                $data['creator_name'],
                $data['donation_title'],
                $data['amount'],
                $data['currencyCode'],
                settings('name'),
            ],
            $message
        );

        if (checkAppMailEnvironment()) {
            try {
                $email = new EmailController;
                $email->sendEmail($data['creator_email'], $emailTemplate->subject, $message);
                return true;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
        return false;
    }

    /**
     * Method getDonationFeesLimit
     *
     * @param $currencyId $currencyId [explicite description]
     * @param $paymentMethodId $paymentMethodId [explicite description]
     * @param $amount $amount [explicite description]
     *
     * @return object
     */
    public function getDonationFeesLimit($currencyId, $paymentMethodId, $amount)
    {
        $feesLimit = FeesLimit::where([
            'currency_id' => $currencyId,
            'transaction_type_id' => Donation_Sent,
            'payment_method_id' => $paymentMethodId,
            'has_transaction' => 'Yes'
        ])->first(['charge_percentage', 'charge_fixed', 'min_limit', 'max_limit']);

        if(empty($feesLimit)) {
            throw new Exception(__('Fees Limit Not Active'));
        }

        $chargePercentageFees = $amount * ($feesLimit->charge_percentage / 100);

        $feesLimit->totalAmount = $amount + $chargePercentageFees + $feesLimit->charge_fixed;
        $feesLimit->totalFeesLimit = $chargePercentageFees + $feesLimit->charge_fixed;
        $feesLimit->chargePercentageFees = $chargePercentageFees;

        return $feesLimit;
    }

    /**
     * Method checkWallet
     *
     * @param $request $request [explicit description]
     *
     * @return object
     */
    public function checkWallet()
    {
        $wallet = Wallet::where([
            'user_id' => auth()->id(),
            'currency_id' => request()->currency_id,
        ])->first();

        if (empty($wallet)) {
            throw new Exception(__('You do not have wallet to donate'));
        }

        if ($wallet->balance < request()->total) {
            throw new Exception(__('Insufficient balance found in your wallet'));
        }

        return $wallet;
    }

    public function setDonationPaymentData($request, $feesLimit, $donation)
    {
        return  [
            "currency_id"              => $request->currency_id,
            'creator_id'               => $donation->creator_id,
            'user_id'                  => auth()->id(),
            'sender_id'                => auth()->id() ?? null,
            'payer_id'                 => $this->payerInfoSet(),
            'transaction_type'         => Donation_Sent,
            'payment_type'             => 'donation',
            'currencyCode'             => optional($donation->currency)->code,
            "donation_id"              => $request->donation_id,
            "donation_get_payeer_info" => $request->donation_get_payeer_info,
            'amount'                   => $request->amount,
            'donation_id'              => $donation->id,
            'donation_title'           => $donation->title,
            'creator_email'            => optional($donation->creator)->email,
            'creator_name'             => getColumnValue($donation->creator),
            'uuid'                     => unique_code()
        ];
    }

    public function validateDepositable($currencyId, $amount, $donationId, $paymentMethodName)
    {
        $helper  = new Common();
        $paymentMethod = PaymentMethod::where('name', $paymentMethodName)->first();
        $paymentMethodAlias = strtolower(preg_replace("/\s+/", "", $paymentMethod->name));

        $feesDetails = $helper->transactionFees($currencyId, $amount, Donation_Sent, $paymentMethod->id);
        $helper->amountIsInLimit($feesDetails, $amount);

        $feeBearer = feeBearer($donationId);
        $feesArray = [
            'paymentMethodName' => $paymentMethod->name,
            'paymentMethodAlias' => $paymentMethodAlias,
            'min' => $feesDetails->min_limit,
            'max' => $feesDetails->max_limit,
            'payment_method' => $paymentMethod->id,
            'amount' => $feesDetails->amount,
            'totalAmount' => isset($feeBearer) ? ($feeBearer == 'donor' ? $feesDetails->total_amount : $feesDetails->amount) : $feesDetails->amount,
            'total' => isset($feeBearer) ? ($feeBearer == 'donor' ? $feesDetails->total_amount : $feesDetails->amount) : $feesDetails->amount,
            'percentage' => isset($feeBearer) ? $feesDetails->fees_percentage: 0
        ];

        return array_merge((new FeesResource($feesDetails))->toArray(request()), $feesArray) ;

    }
}