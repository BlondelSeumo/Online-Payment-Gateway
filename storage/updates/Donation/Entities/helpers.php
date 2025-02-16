<?php

use App\Models\Transaction;
use Modules\Donation\Entities\Donation;
use Modules\Donation\Entities\DonationPayment;

if (!function_exists('formatCount')) {
    function formatCount($number, $precision = 1) {
        if ($number < 1000) {
            return $number;
        }

        $units = ['K', 'M', 'B', 'T'];
        $unitIndex = floor(log($number, 1000));
        $unit = $units[$unitIndex - 1];

        $formattedNumber = round($number / pow(1000, $unitIndex), $precision);

        return $formattedNumber . $unit;
    }
}

function getDonationActivePaymentMethod($currency_id)
{
    $condition =  config('donation.payment_methods.web.fiat');
    $feesLimits = \App\Models\FeesLimit::with([
        'currency' => function ($query) use ($condition) {
            $query->whereIn('id', $condition)->where('status', '=', 'Active');
        },
        'payment_method' => function ($q) {
            $q->where('status', 'Active');
        },
    ])
    ->where(['transaction_type_id' => Donation_Sent, 'has_transaction' => 'Yes', 'currency_id' => $currency_id])
    ->get(['payment_method_id']);

    $currencyPaymentMethods = \App\Models\CurrencyPaymentMethod::where('currency_id', $currency_id)
    ->where('activated_for', 'like', "%donation%")
    ->get(['method_id']);

    return currencyPaymentMethodFeesLimitCurrencies($feesLimits, $currencyPaymentMethods);

}

function currencyPaymentMethodFeesLimitCurrencies($feesLimits, $currencyPaymentMethods)
{
    $selectedCurrencies = [];
    foreach ($feesLimits as $feesLimit) {
        foreach ($currencyPaymentMethods as $currencyPaymentMethod) {
            if ($feesLimit->payment_method_id == $currencyPaymentMethod->method_id ) {
                $selectedCurrencies[$feesLimit->payment_method_id]['id']   = $feesLimit->payment_method_id;
                $selectedCurrencies[$feesLimit->payment_method_id]['name'] = optional($feesLimit->payment_method)->name;
            }
        }
    }
    return $selectedCurrencies;
}

if (!function_exists('feeBearer')) {
    function feeBearer($donation_id)
    {
        if (isset($donation_id) && preference('donation_fee_applicable') == 'yes') {
            return Donation::find($donation_id)->fee_bearer;
        }
    }
}

if (!function_exists('donationTransactionUpdate')) {
    function donationTransactionUpdate($transaction, $status)
    {
        $transactionData    = Transaction::find($transaction->id);
        $donationDecodeData = getPaymentParam($transactionData->uuid);

        if ($donationDecodeData) {
            Transaction::where('uuid', $donationDecodeData['uuid'])->update(['status' => $status]);
            DonationPayment::where('uuid', $donationDecodeData['uuid'])->update(['status' => $status]);
            
            $feeBearer = feeBearer($donationDecodeData['donation_id']);
            $donation = Donation::where(['id' => $donationDecodeData['donation_id']])->first();
            $balance = isset($feeBearer) ? ($feeBearer == 'donor' ? $donationDecodeData['amount'] : $donationDecodeData['amount'] - $donationDecodeData['totalFees']) : $donationDecodeData['amount'];
            $wallet  = \App\Models\Wallet::where(['currency_id' => $donationDecodeData['currency_id'], 'user_id' => $donationDecodeData['creator_id']])->first();

            if ($status == 'Success') {
                $donation->raised_amount += $donationDecodeData['amount'];
                $donation->save();
                if (empty($wallet)) {
                    $wallet = (new \App\Models\Wallet)->createWallet($donationDecodeData['creator_id'], $donationDecodeData['currency_id']);
                    $wallet->balance = $balance;
                } else {
                    $wallet->balance = (double) $wallet->balance + $balance;
                }
                $wallet->save();
            }
        }
    }

    
}

/**
 * Format number in decimal format according to preference without comma saperation
 * @param $num [Any number]
 * @param $currencyId [Id]
 * @return number
 */
function formatNumberWithoutComma($num = 0, $currencyId = NULL)
{
    $currencyType = 'fiat';
    if ($currencyId !== null) {
        $currencyType = \App\Models\Currency::where('id', $currencyId)->value('type');
    }

    $seperator = preference('thousand_separator', '.');
    $format =  ($currencyType == 'fiat') ? preference('decimal_format_amount', 2) : preference('decimal_format_amount_crypto', 8);

    if ($seperator == '.') {
        $num = trimExtraZeros(number_format($num, (int)$format, "", "."));
    } elseif ($seperator == ',') {
        $num = trimExtraZeros(number_format($num, (int)$format, ".", ""));
    }
    return $num;
}

if (!function_exists('getDonationTransactionInfo')) {
    /**
     * getDonationTransactionInfo
     *
     * @param  mixed $type
     * @return array
     */
    function getDonationTransactionInfo($type, $transaction = null): array
    {
        switch ($type) {
            case 'Donation_Sent':
                return [
                    'name' => 'Donation Sent',
                    'type' => 'Sender',
                    'user' => getColumnValue($transaction?->user),
                    'currency' => 'Currency',
                    'print' => 'user.donation-payment.print'
                ];
                break;
            case 'Donation_Received':
                return [
                    'name' => 'Donation Received',
                    'type' => 'Receiver',
                    'user' => getColumnValue($transaction?->end_user),
                    'currency' => 'Currency',
                    'print' => 'user.donation-payment.print'
                ];
                break;

            default:
                return [
                    'name' => 'Transaction',
                    'type' => 'Transaction By',
                    'user' => getColumnValue($transaction?->user),
                    'currency' => 'Currency',
                    'print' => 'user.transactions.print'
                ];
                break;
        }
    }
}