<?php

/**
 * @package MtsProcessor
 * @author tehcvillage <support@techvill.org>
 * @contributor Ashraful Rasel <[ashraful.techvill@gmail.com]>
 * @created 01-08-2023
 */


namespace App\Services\Gateways\Mts;

use App\Models\Wallet;
use App\Services\Gateways\Gateway\Exceptions\{
    PaymentFailedException
};
use App\Services\Gateways\Gateway\PaymentProcessor;
use Exception;
use Illuminate\Support\Facades\Auth;

/**
 * @method array pay()
 */
class MtsProcessor extends PaymentProcessor
{
    protected $data;

    protected $uniqid;


    /**
     * Boot stripe payment processor
     *
     * @param array $data
     *
     * @return void
     */
    protected function boot($data)
    {
        $this->data = $data;

        $this->paymentCurrency();

        $this->uniqid = $this->data['uuid'];

    }


    /**
     * Confirm payment for stripe
     *
     * @param array $data
     *
     * @return mixed
     *
     * @throws PaymentFailedException
     */
    public function pay(array $data): array
    {
        try {
            $this->boot($data);

            $this->validateInitiatePaymentRequest($data);

            isTransactionExist($data['uuid']);

            $this->walletCheck();

            return [
                "type" => $this->gateway(),
                'redirect_url' => $data['redirect_url'] . '?params=' . $data['params'] . '&user=' . auth()->id() ?? $data['user_id'],
                'redirect' => true,
                'user' => auth()->id() ?? $data['user_id']
            ];

        } catch (Exception $th) {

            throw new PaymentFailedException($th->getMessage(), ["response" => $response ?? null]);
        }
    }

    /**
     * Method initiateGateway
     *
     * @param $data $data
     *
     * @return void
     */
    public function initiateGateway($data)
    {
        return view(('gateways.' . $this->gateway()), $data);
    }

    public function paymentView()
    {
        return 'gateways.'.$this->gateway();
    }


    /**
     * Get gateway alias name
     *
     * @return string
     */
    public function gateway(): string
    {
        return "mts";
    }


    /**
     * Validate initialization request
     *
     * @param array $data
     *
     * @return array
     */
    private function validateInitiatePaymentRequest($data)
    {
        $rules = [
            'amount' => 'required',
            'currency_id' => 'required',
            'payment_method_id' => 'required', 'exists:payment_methods,id',
            'redirect_url' => 'required',
            'transaction_type' =>'required',
            'payment_type' => 'required',
        ];
        return $this->validateData($data, $rules);
    }

 
    public function walletCheck()
    {
        $senderWallet = Wallet::where(['user_id' => auth()->user()->id ?? $this->data['user_id'], 'currency_id' => $this->data['currency_id']])->first(['id', 'balance']);
        //Check User has the wallet or not
        if (!$senderWallet) {
            auth()->logout();
            throw new Exception(__('User does not have the wallet - :x. Please exchange to wallet - :y', ['x' => $this->currency, 'y' => $this->currency]));
        }

        //Check user balance
        if ($senderWallet->balance < $this->data['amount']) {
            throw new Exception(__("User does not have sufficient balance!"));
        }

    }



}





