<?php

namespace Modules\Donation\Entities;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class DonationPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id', 'payer_id', 'donation_id', 'payment_method_id', 'uuid', 'charge_percentage', 'charge_fixed', 'amount', 'status'
    ];

    public function transaction()
    {
        return $this->hasOne(\App\Models\Transaction::class, 'transaction_reference_id', 'id');
    }

    public function payer()
    {
        return $this->belongsTo(DonationPayerDetail::class, 'payer_id');
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class, 'donation_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(\App\Models\PaymentMethod::class, 'payment_method_id');
    }

    public function currency()
    {
        return $this->belongsTo(\App\Models\Currency::class, 'currency_id');
    }

    /**
     * [get users firstname and lastname for filtering]
     * @param  [integer] $user      [id]
     * @return [string]  [firstname and lastname]
     */
    public function getDonationPaymentsPayersName($user)
    {

        return $this->with(['payer:id,first_name,last_name'])->where('payer_id', $user)->first();
    }

    /**
     * [ajax response for search results]
     * @param  [string] $search   [query string]
     * @return [string] [distinct firstname and lastname]
     */
    public function getDonationPaymentsPayersResponse($search)
    {

        return $this->with('payer:id,first_name,last_name')->whereHas('payer', function($query) use ($search) {
            $query->where('first_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $search . '%');
        })
        ->distinct('payer_id')
        ->select('payer_id')
        ->get();
    }

    /**
     * [Donations Filtering Results]
     * @param  [null/date] $from     [start date]
     * @param  [null/date] $to       [end date]
     * @param  [string]    $status   [Status]
     * @param  [string]    $paymentMethod [Payment Methods]
     * @param  [string]    $currency [Currency]
     * @param  [null/id]   $user     [User ID]
     * @return [query]     [All Query Results]
     */
    public function getDonationPaymentsList($from, $to, $status, $currency, $paymentMethod, $user, $donationIds = null)
    {
        $conditions = [];

        if (empty($from) || empty($to)) {
            $dateRange = null;
        } else {
            $dateRange = 'Available';
        }

        if (!empty($status) && $status != 'all') {
            $conditions['status'] = $status;
        }

        if (!empty($paymentMethod) && $paymentMethod != 'all') {
            $conditions['payment_method_id'] = $paymentMethod;
        }

        if (!empty($currency) && $currency != 'all') {
            $conditions['currency_id'] = $currency;
        }
        if (!empty($user)) {
            $conditions['payer_id'] = $user;
        }

        $relations = [
            'payer:id,first_name,last_name,email',
            'currency:id,code,symbol',
            'paymentMethod:id,name'
        ];

        $donations = $this->with($relations)
            ->where($conditions)
            ->latest('donation_payments.id');
        
        if (!empty($donationIds)) {
            $donations->whereIn('donation_id', $donationIds);
        }

        if (!empty($dateRange)) {
            return $donations->where(function ($query) use ($from, $to) {
                $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
            })->select('donation_payments.id', 'donation_payments.created_at','donation_payments.payer_id', 'donation_payments.amount', 'donation_payments.currency_id', 'donation_payments.charge_percentage', 'donation_payments.charge_fixed', 'donation_payments.payment_method_id', 'donation_payments.status', 'donation_payments.uuid', 'donation_payments.total');
        } else {
            return $donations->select('donation_payments.id', 'donation_payments.created_at','donation_payments.payer_id', 'donation_payments.amount', 'donation_payments.currency_id', 'donation_payments.charge_percentage', 'donation_payments.charge_fixed', 'donation_payments.payment_method_id', 'donation_payments.status', 'donation_payments.uuid', 'donation_payments.total');
        }
    }

    public static function createNewDonationPayment($data)
    {
        $feeBearer = feeBearer($data['donation_id']);
        if (in_array($data['paymentMethodName'], ['Coinbase', 'Coinpayments', 'Payeer'])) {
            $paymentStatus = 'Pending';
        } else {
            $paymentStatus = 'Success';
        }
        try {
            $donationPayment                    = new self();
            $donationPayment->currency_id       = $data['currency_id'];
            $donationPayment->payer_id          = $data['payer_id'];

            $donationPayment->payment_method_id = $data['payment_method'];
            $donationPayment->donation_id       = $data['donation_id'];
            $donationPayment->uuid              = $data['uuid'];
            $donationPayment->charge_percentage = isset($feeBearer) ? ($feeBearer == 'donor' ? $data['percentage'] : 0) : 0;
            $donationPayment->charge_fixed      = isset($feeBearer) ? ($feeBearer == 'donor' ? $data['feesFixed'] : 0) : 0;
            $donationPayment->amount            = $data['amount'];
            $donationPayment->total             = $data['total'];
            $donationPayment->status            = $paymentStatus;
            $donationPayment->save();

            if (!in_array($data['paymentMethodName'], ['Coinbase', 'Coinpayments', 'Payeer'])) {
                $donation = Donation::where(['id' => $data['donation_id']])->first();
                $donation->raised_amount += $data['amount'];
                $donation->save();
            }
            if (!empty(auth()->check())) {
                $data['sender_id'] = auth()->user()->id;
            }
            
            $payerDetail = DonationPayerDetail::find($data['payer_id']);
            
            if ($payerDetail && $data['payment_method'] == Mts) {
                
                $payerDetail->first_name = auth()->user()->first_name;
                $payerDetail->last_name = auth()->user()->last_name;
                $payerDetail->email = auth()->user()->email;
                $payerDetail->save();
            }
            if ($data['sender_id']) {
                $userTransaction = new \App\Models\Transaction;
                $userTransaction->currency_id = $data['currency_id'];
                $userTransaction->user_id = $data['sender_id'];
                $userTransaction->end_user_id = $data['creator_id'];
                $userTransaction->payment_method_id = $data['payment_method'];
                $userTransaction->uuid = $data['uuid'];
                $userTransaction->transaction_reference_id = $donationPayment->id;
                $userTransaction->transaction_type_id = Donation_Sent;
                $userTransaction->user_type = $data['sender_id'] ? 'registered' : 'unregistered';
                $userTransaction->subtotal = $data['amount'];
                $userTransaction->percentage = isset($feeBearer) ? ($feeBearer == 'donor' ? $data['percentage'] : 0) : 0;
                $userTransaction->charge_percentage = isset($feeBearer) ? ($feeBearer == 'donor' ? $data['percentage'] : 0) : 0; //data feesPercentage
                $userTransaction->charge_fixed = isset($feeBearer) ? ($feeBearer == 'donor' ? $data['feesFixed'] : 0) : 0;
                $userTransaction->total = '-' . (isset($feeBearer) ? ($feeBearer == 'donor' ? $data['total'] : $data['amount']) : $data['amount']);
                $userTransaction->status = $paymentStatus;
                $userTransaction->save();
            }

            $creatorTransaction = new \App\Models\Transaction;
            $creatorTransaction->currency_id = $data['currency_id'];
            $creatorTransaction->user_id = $data['creator_id'];
            $creatorTransaction->end_user_id = $data['sender_id'];
            $creatorTransaction->payment_method_id = $data['payment_method'];
            $creatorTransaction->uuid = $data['uuid'];
            $creatorTransaction->transaction_reference_id = $donationPayment->id;
            $creatorTransaction->transaction_type_id = Donation_Received;
            $creatorTransaction->user_type = $data['sender_id'] ? 'registered' : 'unregistered';
            $creatorTransaction->subtotal = isset($feeBearer) ? ($feeBearer == 'donor' ? $data['amount'] : $data['amount'] - $data['totalFees']) : $data['amount'];
            $creatorTransaction->percentage = isset($feeBearer) ? ($feeBearer == 'creator' ? $data['percentage'] : 0) : 0;
            $creatorTransaction->charge_percentage = isset($feeBearer) ? ($feeBearer == 'creator' ? $data['percentage'] : 0) : 0; //data feesPercentage
            $creatorTransaction->charge_fixed = isset($feeBearer) ? ($feeBearer == 'creator' ? $data['feesFixed'] : 0) : 0;
            $creatorTransaction->total = $data['amount'];
            $creatorTransaction->status = $paymentStatus;
            $creatorTransaction->save();

            if ($data['payment_method'] == Mts) {
                $senderWallet = \App\Models\Wallet::where(['currency_id' => $data['currency_id'], 'user_id' => auth()->user()->id])->first();
                $senderWallet->balance = (double) $senderWallet->balance - $data['totalAmount'];
                $senderWallet->save();
            }
            if (!in_array($data['paymentMethodName'], ['Coinbase', 'Coinpayments', 'Payeer'])) {
                $balance = isset($feeBearer) ? ($feeBearer == 'donor' ? $data['amount'] : $data['amount'] - $data['totalFees']) : $data['amount'];
                $wallet  = \App\Models\Wallet::where(['currency_id' => $data['currency_id'], 'user_id' => $data['creator_id']])->first();

                if (empty($wallet)) {
                    $wallet = (new \App\Models\Wallet)->createWallet($data['creator_id'], $data['currency_id']);
                    $wallet->balance = $balance;
                } else {
                    $wallet->balance = (double) $wallet->balance + $balance;
                }
                
                $wallet->save();
            }
            return $donationPayment->id;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}

