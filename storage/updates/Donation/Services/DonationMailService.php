<?php

/**
 * @package DepositViaAdminMailService
 * @author tehcvillage <support@techvill.org>
 * @contributor Abu Sufian Rubel <[sufian.techvill@gmail.com]>
 * @created 29-05-2023
 */

 namespace Modules\Donation\Services;

use Exception;
use App\Services\Mail\TechVillageMail;
use Modules\Donation\Entities\DonationPayerDetail;

class DonationMailService extends TechVillageMail
{
    /**
     * The array of status and message whether email sent or not.
     *
     * @var array
     */
    protected $mailResponse = [];

    public function __construct()
    {
        parent::__construct();
        $this->mailResponse = [
            'status'  => true,
            'message' => __('We have sent you the donation details. Please check your email.')
        ];
    }
    /**
     * Send forgot password code to deposit email
     * @param object $deposit
     * @return array $response
     */
    public function send($data)
    {
        try {
            $recipient =  $data['creator_email'];
            $response = $this->getEmailTemplate('notify-user-on-donation-received');

            if (!$response['status']) {
                return $response;
            }
        
            $data = [
                "{user}" => $data['creator_name'],
                "{donation_title}" => $data['donation_title'],
                "{amount}" => $data['amount'],
                "{currency_code}" => $data['currencyCode'],
                "{soft_name}" => settings('name'),
            ];

            $message = str_replace(array_keys($data), $data, $response['template']->body);
            $this->email->sendEmail($recipient, $response['template']->subject, $message);
        } catch (Exception $e) {
            $this->mailResponse = ['status' => false, 'message' => $e->getMessage()];
        }
        return $this->mailResponse;
    }

    public function sendToDoner($data)
    {
        $payerDetail = DonationPayerDetail::find($data['payer_id']);

        try {
            $recipient =  $payerDetail->email;
            $response = $this->getEmailTemplate('notify-user-on-donation-send-confirmation');

            if (!$response['status']) {
                return $response;
            }
        
            $data = [
                "{user}" => $payerDetail->first_name,
                "{donation_title}" => $data['donation_title'],
                "{amount}" => $data['amount'],
                "{currency_code}" => $data['currencyCode'],
                "{soft_name}" => settings('name'),
            ];

            $message = str_replace(array_keys($data), $data, $response['template']->body);
            $this->email->sendEmail($recipient, $response['template']->subject, $message);
        } catch (Exception $e) {
            $this->mailResponse = ['status' => false, 'message' => $e->getMessage()];
        }
        return $this->mailResponse;
    }

}