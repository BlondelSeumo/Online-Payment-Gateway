<?php

namespace Modules\Donation\Database\Seeders;

use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emailTemplates = [
            // Donation Received Notification -- email
            [
                'name' => 'Notify User on Donation Received',
                'alias' => 'notify-user-on-donation-received',
                'subject' => 'Donation Received Notification',
                'body' => 'Hi {user},
                <br><br>Your campaign "<b>{donation_title}</b>" has been received donation {amount} {currency_code}.
                <br><br>Regards,
                <br><b>{soft_name}</b>  ',
                'language_id' => 1,
                'lang' => 'en',
                'type' => 'email',
                'group' => 'Donation',
                'status' => 'Active',
            ],
            ['name' => 'Notify User on Donation Received', 'alias' => 'notify-user-on-donation-received', 'subject' => '', 'body' => '', 'language_id' => 2, 'lang' => 'ar', 'type' => 'email', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Received', 'alias' => 'notify-user-on-donation-received', 'subject' => '', 'body' => '', 'language_id' => 3, 'lang' => 'fr', 'type' => 'email', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Received', 'alias' => 'notify-user-on-donation-received', 'subject' => '', 'body' => '', 'language_id' => 4, 'lang' => 'pt', 'type' => 'email', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Received', 'alias' => 'notify-user-on-donation-received', 'subject' => '', 'body' => '', 'language_id' => 5, 'lang' => 'ru', 'type' => 'email', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Received', 'alias' => 'notify-user-on-donation-received', 'subject' => '', 'body' => '', 'language_id' => 6, 'lang' => 'es', 'type' => 'email', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Received', 'alias' => 'notify-user-on-donation-received', 'subject' => '', 'body' => '', 'language_id' => 7, 'lang' => 'tr', 'type' => 'email', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Received', 'alias' => 'notify-user-on-donation-received', 'subject' => '', 'body' => '', 'language_id' => 8, 'lang' => 'ch', 'type' => 'email', 'group' => 'Donation', 'status' => 'Active'],

            // Donation Received Notification -- SMS
            [
                'name' => 'Notify User on Donation Received',
                'alias' => 'notify-user-on-donation-received',
                'subject' => 'Donation Received Notification',
                'body' => 'Hi {user}, Your campaign "<b>{donation_title}</b>" has been received donation {amount} {currency_code}.Regards,{soft_name}',
                'language_id' => 1,
                'lang' => 'en',
                'type' => 'sms',
                'group' => 'Donation',
                'status' => 'Active',
            ],
            ['name' => 'Notify User on Donation Received', 'alias' => 'notify-user-on-donation-received', 'subject' => '', 'body' => '', 'language_id' => 2, 'lang' => 'ar', 'type' => 'sms', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Received', 'alias' => 'notify-user-on-donation-received', 'subject' => '', 'body' => '', 'language_id' => 3, 'lang' => 'fr', 'type' => 'sms', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Received', 'alias' => 'notify-user-on-donation-received', 'subject' => '', 'body' => '', 'language_id' => 4, 'lang' => 'pt', 'type' => 'sms', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Received', 'alias' => 'notify-user-on-donation-received', 'subject' => '', 'body' => '', 'language_id' => 5, 'lang' => 'ru', 'type' => 'sms', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Received', 'alias' => 'notify-user-on-donation-received', 'subject' => '', 'body' => '', 'language_id' => 6, 'lang' => 'es', 'type' => 'sms', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Received', 'alias' => 'notify-user-on-donation-received', 'subject' => '', 'body' => '', 'language_id' => 7, 'lang' => 'tr', 'type' => 'sms', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Received', 'alias' => 'notify-user-on-donation-received', 'subject' => '', 'body' => '', 'language_id' => 8, 'lang' => 'ch', 'type' => 'sms', 'group' => 'Donation', 'status' => 'Active'],
            // Donation Send Confirmation Notification -- email
            [
                'name' => 'Notify User on Donation Send Confirmation',
                'alias' => 'notify-user-on-donation-send-confirmation',
                'subject' => 'Donation Send Confirmation Notification',
                'body' => 'Hi {user},
                <br><br>You has been successfully donated {amount} {currency_code}. For the campaign "<b>{donation_title}</b>".
                <br><br>Regards,
                <br><b>{soft_name}</b>  ',
                'language_id' => 1,
                'lang' => 'en',
                'type' => 'email',
                'group' => 'Donation',
                'status' => 'Active',
            ],
            ['name' => 'Notify User on Donation Send Confirmation', 'alias' => 'notify-user-on-donation-send-confirmation', 'subject' => '', 'body' => '', 'language_id' => 2, 'lang' => 'ar', 'type' => 'email', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Send Confirmation', 'alias' => 'notify-user-on-donation-send-confirmation', 'subject' => '', 'body' => '', 'language_id' => 3, 'lang' => 'fr', 'type' => 'email', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Send Confirmation', 'alias' => 'notify-user-on-donation-send-confirmation', 'subject' => '', 'body' => '', 'language_id' => 4, 'lang' => 'pt', 'type' => 'email', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Send Confirmation', 'alias' => 'notify-user-on-donation-send-confirmation', 'subject' => '', 'body' => '', 'language_id' => 5, 'lang' => 'ru', 'type' => 'email', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Send Confirmation', 'alias' => 'notify-user-on-donation-send-confirmation', 'subject' => '', 'body' => '', 'language_id' => 6, 'lang' => 'es', 'type' => 'email', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Send Confirmation', 'alias' => 'notify-user-on-donation-send-confirmation', 'subject' => '', 'body' => '', 'language_id' => 7, 'lang' => 'tr', 'type' => 'email', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Send Confirmation', 'alias' => 'notify-user-on-donation-send-confirmation', 'subject' => '', 'body' => '', 'language_id' => 8, 'lang' => 'ch', 'type' => 'email', 'group' => 'Donation', 'status' => 'Active'],

            // Donation Send Confirmation Notification -- SMS
            [
                'name' => 'Notify User on Donation Send Confirmation',
                'alias' => 'notify-user-on-donation-send-confirmation',
                'subject' => 'Donation Send Confirmation Notification',
                'body' => 'Hi {user}, You has been successfully donated {amount} {currency_code}. For the campaign "<b>{donation_title}</b>".Regards,{soft_name}',
                'language_id' => 1,
                'lang' => 'en',
                'type' => 'sms',
                'group' => 'Donation',
                'status' => 'Active',
            ],
            ['name' => 'Notify User on Donation Send Confirmation', 'alias' => 'notify-user-on-donation-send-confirmation', 'subject' => '', 'body' => '', 'language_id' => 2, 'lang' => 'ar', 'type' => 'sms', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Send Confirmation', 'alias' => 'notify-user-on-donation-send-confirmation', 'subject' => '', 'body' => '', 'language_id' => 3, 'lang' => 'fr', 'type' => 'sms', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Send Confirmation', 'alias' => 'notify-user-on-donation-send-confirmation', 'subject' => '', 'body' => '', 'language_id' => 4, 'lang' => 'pt', 'type' => 'sms', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Send Confirmation', 'alias' => 'notify-user-on-donation-send-confirmation', 'subject' => '', 'body' => '', 'language_id' => 5, 'lang' => 'ru', 'type' => 'sms', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Send Confirmation', 'alias' => 'notify-user-on-donation-send-confirmation', 'subject' => '', 'body' => '', 'language_id' => 6, 'lang' => 'es', 'type' => 'sms', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Send Confirmation', 'alias' => 'notify-user-on-donation-send-confirmation', 'subject' => '', 'body' => '', 'language_id' => 7, 'lang' => 'tr', 'type' => 'sms', 'group' => 'Donation', 'status' => 'Active'],
            ['name' => 'Notify User on Donation Send Confirmation', 'alias' => 'notify-user-on-donation-send-confirmation', 'subject' => '', 'body' => '', 'language_id' => 8, 'lang' => 'ch', 'type' => 'sms', 'group' => 'Donation', 'status' => 'Active']
            
        ];

        \App\Models\EmailTemplate::insert($emailTemplates);
    }
}
