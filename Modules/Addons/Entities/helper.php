<?php

use App\Http\Helpers\Common;

if (!function_exists('addonThumbnail')) {
    function addonThumbnail($name) {
        $path = join(DIRECTORY_SEPARATOR, ['Modules', $name, 'Resources', 'assets', 'thumbnail.png']);

        if (file_exists($path)) {
            return url($path);
        }

        return url(join(DIRECTORY_SEPARATOR, ['Modules', 'Addons', 'Resources', 'assets', 'thumbnail.png']));
    }
}

/**
 * get active modules transaction types for a specific payment method
 * @param  string $paymentMethod
 * @return array
 */


if (!function_exists('addonPaymentMethods')) {
    function addonPaymentMethods($method)
    {
        $transactionTypes = [];
        $modules = [];

        $addons = \Modules\Addons\Entities\Addon::all();
        foreach ($addons as $addon) {
            if ($addon->get('core') || !$addon->isEnabled() || config($addon->get('alias') . '.' . 'payment_methods') == null) {
                continue;
            }
            $name = (count(config($addon->get('alias') . '.' . 'payment_methods')) > 1) ?  $addon->getName() : '';
            $transactionTypes[] = [
                'name' => $name,
                'types' => config($addon->get('alias') . '.' . 'payment_methods')
            ];
        }

        foreach ($transactionTypes as $type) {
            $types = [];
            foreach ($type['types'] as $key => $value) {
                if(in_array($method, $value)) array_push($types, $key);
            }
            $modules[] = [
                'name' => $type['name'],
                'type' => $types
            ];
        }
        return $modules;
    }  
}

if (!function_exists('moduleExistChecker')) {
    function moduleExistChecker($currency)
    {
        $addons = array_filter(Module::all(), function($addon) { return !$addon->get('core'); }) ;
        
        if (empty($addons)) {
            return $addons;
        }
            
        $addonArray = [];
        
        foreach ($addons as $value) {
            switch ($value) {
                case 'CryptoExchange':
                    $directionExist = \Modules\CryptoExchange\Entities\ExchangeDirection::where('from_currency_id', $currency->id)->orWhere('to_currency_id', $currency->id)->exists();
                    if ($directionExist) {
                        $moduleArray = [
                            'status' => true,
                            'text' => __('crypto direction')
                        ];
                    }
                    break;

                case 'Investment':
                    $planExist = \Modules\Investment\Entities\InvestmentPlan::where('currency_id', $currency->id)->exists();
                    if ($planExist) {
                        $moduleArray = [
                            'status' => true,
                            'text' => __('investment plan')
                        ];
                    }
                    break;

                case 'Referral':
                    $planExist = \Modules\Referral\Entities\AwardLevel::where(['currency_id' => $currency->id, 'status' => 'Active'])->exists();
                    if ($planExist) {
                        $moduleArray = [
                            'status' => true,
                            'text' => __('award level')
                        ];
                    }
                    break;
                
                default:
                    break;
            }

            if ($moduleArray['status'] ?? false) {
                $addonArray[] = $moduleArray['text'];
            }
        }

        return $addonArray;
    }
}

if (!function_exists('getTransactionListUser')) {
    /**
     * Get available modules transaction sender/receiver user name
     * @param object $transaction
     * @param string $type [values either sender/receiver]
     * @param bool $link [determine name with link or name only]
     * @return string
     */
    function getTransactionListUser(object $transaction, string $type = 'sender', bool $link = true)
    {
        $modules = getAllModules();

        foreach ($modules as $module) {
            if (!empty(config($module->get('alias') . '.transaction_list'))) {
                $transactionTypes = config($module->get('alias') . '.transaction_list.' . $type);

                if (!empty($transactionTypes)) {
                    foreach ($transactionTypes as $key => $transactionType) {
                        switch ($transaction->transaction_type_id) {
                            case $key:
                                $user = $transaction->{config($module->get('alias') . '.transaction_list.' . $type .  '.' .$transaction->transaction_type_id)};

                                if (!empty($user)) {
                                    $userWithLink = (Common::has_permission(auth('admin')->user()->id, 'edit_user')) ? '<a href="' . url(config('adminPrefix') . '/users/edit/' . $user->id) . '">' . getColumnValue($user) . '</a>' : getColumnValue($user);

                                    if (isset($transaction->agent_id) && !is_null($transaction->agent_id) && $transaction->transaction_type_id == Deposit ) {
                                        $userWithLink = (Common::has_permission(auth('admin')->user()->id, 'edit_user')) ? '<a href="' . url(config('adminPrefix') . '/agent/agents/' . $transaction->agent_id) .'/edit'.'">' . getColumnValue($user) . '</a>' : getColumnValue($user);
                                    }

                                    return $link ?  $userWithLink : getColumnValue($user);
                                }
                            break;
                        }
                    }
                } 
            }
        }

    }
}


if (!function_exists('getModuleTransactionTypeFeesLimit')) {
    
    function getModuleTransactionTypeFeesLimit($transactionType, $currencyId, $currencyType, $moduleAlias = null)
    {
        $condition = ($currencyType == 'fiat') ? config($moduleAlias . '.' . 'payment_methods.web.fiat') : config($moduleAlias . '.' . 'payment_methods.web.crypto');
        $transactionTypeName = \App\Models\TransactionType::where('id', $transactionType)->value('name');
        if (!empty(config($moduleAlias . '.fees_limit_settings')) && in_array($transactionType, config($moduleAlias . '.transaction_types'))) {
            foreach (config($moduleAlias . '.' . 'fees_limit_settings') as $key => $moduleTransactionType) {

                if(strtolower($transactionTypeName) != $moduleTransactionType['transaction_type']) continue;

                if ($moduleTransactionType['payment_method'] == 'Single') {
                    return \App\Models\FeesLimit::where(['transaction_type_id' => $transactionType, 'currency_id' => $currencyId])->first();
                }
                $paymentMethods = config($moduleAlias . '.' . 'payment_methods')[strtolower($transactionTypeName)]; 
                $key = array_search('Wallet', $paymentMethods);

                if ($key !== false) {
                    $paymentMethods[$key] = 'Mts';
                }
                return App\Models\PaymentMethod::with([
                    'fees_limit' => function ($query) use ($transactionType, $currencyId)
                        {
                            $query->where(['transaction_type_id' => $transactionType, 'currency_id' => $currencyId]);
                        }
                    ])
                    ->whereIn('name', $paymentMethods) 
                    ->whereIn('id', $condition)
                    ->where('status', 'Active')
                    ->get(['id', 'name']);
            }
        }

        return null;
    }
}

if (!function_exists("m_ast_c_v")) { function m_ast_c_v($mns) { return m_ins_ckr($mns); } } if (!function_exists("m_aic_c_v")) { function m_aic_c_v($mns) { return m_aie_c_v($mns); } } if (!function_exists("m_aie_c_v")) { function m_aie_c_v($mns) { return m_ais_c_v($mns); } } if (!function_exists("m_ais_c_v")) { function m_ais_c_v($mns) { return m_ast_c_v($mns); } } if (!function_exists("m_g_c_v")) { function m_g_c_v($mns) { return cache(g_m_s_k($mns)); } } if (!function_exists("g_m_s_k")) { function g_m_s_k($mns) { return base64_decode($mns); } } if (!function_exists('m_g_e_v')) { function m_g_e_v($mns) { return env(g_m_s_k($mns)); } } if (!function_exists("m_uid_c_v")) { function m_uid_c_v($mns) { return m_aie_c_v($mns); } } if (!function_exists("m_aipa_c_v")) { function m_aipa_c_v($mns) { return m_uid_c_v($mns); } }