<?php

if (!app()->runningInConsole()) {
    return [
        'name' => 'Donation',
        'item_id' => 'mn4haf2ir7a',
        'options' => [
            ['label' => __('Settings'), 'url' => url(config('paymoney.prefix') . '/donation-preferences')]
        ],
        'supported_versions' => '4.2.2',
        'payment_methods' => [
            'donation_sent' => ['Wallet', 'Stripe', 'Paypal', 'PayUmoney', 'Payeer', 'Coinbase', 'Coinpayments'],
            'web' => [
                'fiat' => [
                    Mts, Stripe, Paypal, PayUmoney, Coinpayments, Payeer, Coinbase
                ],
                'crypto' => [
                    Mts, Coinpayments, Coinbase
                ]
            ],
        ],
        'permission_group' => ['Campaign', 'Campaign Payment', 'Campaign Setting'],
        'transaction_types' => defined('Donation_Received') && defined('Donation_Sent') ? [Donation_Received, Donation_Sent] : [],
        'fees_limit_settings' => [
            [
                'transaction_type' => defined('Donation_Sent') ? 'donation_sent' : '', 'display_name' => __('Donation'), 'payment_method' => 'Multiple', 'max_amount_require' => false, 'min_amount_require' => false,
            ]
        ],
        'transaction_type_settings' => [
            'web' => [
                'sent' =>  defined('Donation_Sent') ? [Donation_Sent] : [],
                'received' => defined('Donation_Received') ? [Donation_Received] : [],
            ],
            'mobile' => [
                'sent' => [
                    'Donation_Sent' => defined('Donation_Sent') ? Donation_Sent : ''
                ],
                'received' => [
                    'Donation_Received' => defined('Donation_Received') ? Donation_Received : '',
                ]
            ],
            'payment_sent' => defined('Donation_Sent') ? [Donation_Sent] : [],
            'payment_received' => defined('Donation_Received') ? [Donation_Received] : [],
        ],
        'transaction_list' => [
            'sender' => defined('Donation_Received') && defined('Donation_Sent')
            ? [Donation_Received => 'end_user', Donation_Sent => 'user']
            : [],
            'receiver' => defined('Donation_Received') && defined('Donation_Sent')
            ? [Donation_Received => 'user', Donation_Sent => 'end_user'] : []
        ],
        'demo_payment_count' => '55000'
    ];
} else {
    return [
        'name' => 'Donation',
        'item_id' => 'mn4haf2ir7a',
        'options' => [
            ['label' => __('Settings'), 'url' => url(config('paymoney.prefix') . '/donation-preferences')]
        ],
        'supported_versions' => '4.2.2',
        'permission_group' => ['Campaign', 'Campaign Payment', 'Campaign Setting'],
        'payment_methods' => [
            'donation' => ['Wallet', 'Stripe', 'Paypal', 'PayUmoney', 'Payeer', 'Coinbase', 'Coinpayments']
        ],
    ];
}
