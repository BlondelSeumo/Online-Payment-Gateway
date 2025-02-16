<?php

namespace Modules\Donation\Entities;


class DonationTransaction 
{
    private $donationRelation = ['donationPayment'];

    private $relations = [];

    public function __construct(private array $transactionRelations = [])
    {
        $this->relations = array_merge($this->donationRelation, $this->transactionRelations);
    }

    public function getTransactionDetails($id)
    {
        $data['menu'] = 'transaction';
        $data['sub_menu'] = 'transactions';

        $data['transaction'] = $this->getTransaction($id);

        return $data;
    }

    public function getTransaction($id)
    {
        return \App\Models\Transaction::with($this->relations)->find($id);
    }
}