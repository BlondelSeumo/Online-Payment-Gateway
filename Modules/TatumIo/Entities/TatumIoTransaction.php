<?php

namespace Modules\TatumIo\Entities;

use App\Models\Currency;

class TatumIoTransaction
{
    private $tatumIoRelations = ['cryptoAssetApiLog:id,object_id,payload,confirmations'];
    private $relations = [];

    public function __construct(private array $transactionRelations = []) 
    {
        $this->relations = array_merge($this->tatumIoRelations, $this->transactionRelations);
    }

    public function getTransactionDetails($id)
    {
        $data['menu'] = 'transaction';
        $data['sub_menu'] = 'transactions';

        $data['transaction'] = $this->getTransaction($id);

        if (!empty($data['transaction']->cryptoAssetApiLog)) {
            $getCryptoDetails = getCryptoPayloadConfirmationsDetails($data['transaction']->transaction_type_id, $data['transaction']->cryptoAssetApiLog?->payload, $data['transaction']->cryptoAssetApiLog?->confirmations);
            if (count($getCryptoDetails) > 0) {
                if (isset($getCryptoDetails['senderAddress'])) {
                    $data['senderAddress'] = $getCryptoDetails['senderAddress'];
                }
                if (isset($getCryptoDetails['receiverAddress'])) {
                    $data['receiverAddress'] = $getCryptoDetails['receiverAddress'];
                }

                if (isset($getCryptoDetails['network'])) {
                    $data['network'] = Currency::where('code', $getCryptoDetails['network'])->first();
                }

                $data['network_fee'] = isset($getCryptoDetails['network_fee']) ? $getCryptoDetails['network_fee'] : 0 ;
                $data['txId'] = $getCryptoDetails['txId'];
                $data['confirmations'] = $getCryptoDetails['confirmations'];
            }
        }

        return $data;
    }

    public function getTransaction($id)
    {
        return \App\Models\Transaction::with($this->relations)->find($id);
    }
}