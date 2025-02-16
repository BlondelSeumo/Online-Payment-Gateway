<?php

namespace Modules\KycVerification\Http\Controllers\Admin;

use Cache;
use App\Http\Helpers\Common;
use App\Models\{Role, Setting};
use App\Http\Controllers\Controller;
use App\Facade\VerificationProviderManager;
use Modules\KycVerification\Entities\KycProvider;
use Modules\KycVerification\Contract\AutomatedContract;
use Modules\KycVerification\Http\Requests\Admin\SettingRequest;

class SettingController extends Controller
{
    /**
     * Show the form for creating a new resource.
     * @return Illuminate\View\View
     */
    public function create()
    {
        $data = [
            'menu' => 'kyc_verification',
            'sub_menu' => 'kyc_settings',
            'providers' => KycProvider::whereIn('alias', getActiveKycModulesProviderAlias())->get(['name', 'alias']),
            'result' => settings('kyc_verification'),
            'roles' => Role::select('id', 'display_name')->where('user_type', "User")->get()
        ];

        return view('kycverification::admin.setting', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param SettingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SettingRequest $request)
    {
        $response = $this->providerRequiredCredentialSettings($request->kyc_provider);

        if ($response['status']) {
            (new Common())->one_time_message('error', $response['message']);
            return redirect()->route('admin.kyc.settings.create');
        }

        (new Setting())->updateSettingsValue('kyc_provider', 'kyc_verification', $request->kyc_provider);
        (new Setting())->updateSettingsValue('kyc_mandatory', 'kyc_verification', $request->kyc_mandatory);
        (new Setting())->updateSettingsValue('kyc_required_for', 'kyc_verification', $request->kyc_required_for);

        Cache::forget(config('cache.prefix') . '-settings');

        (new Common())->one_time_message('success', __("The :x has been successfully saved.", ['x' => __('verification settings')]));
        return redirect()->route('admin.kyc.settings.create');
    }

    /**
     * Check if the provider requires credential settings.
     *
     * This method checks if the specified provider exists and whether it requires credentials for verification.
     * If the provider requires credentials and is not an instance of AutomatedContract, it returns an error message.
     *
     * @param string $providerAlias The alias of the provider.
     * @return array An array containing the status and message of the operation.
     */
    public function providerRequiredCredentialSettings($providerAlias)
    {
        // Attempt to find the provider using the VerificationProviderManager
        $provider = VerificationProviderManager::find($providerAlias);

        // If the provider is not found, return an error message
        if (empty($provider)) {
            return [
                'status' => true,
                'message' => __(":x is not found on the verification provider list.", ["x" => $providerAlias])
            ];
        }

        // Create an instance of the provider
        $providerInstance = app($provider);

        // Check if the provider requires credentials and is not an instance of AutomatedContract
        if (config("{$providerInstance->moduleAlias()}.credentials") && ! $providerInstance instanceof AutomatedContract) {
            return [
                'status' => true,
                'message' => __("Class :x must extend the :y class as it requires credentials for verification.", ['x' => $provider, 'y' => '\Modules\KycVerification\Contract\AutomatedContract'])
            ];
        }

        // Return a success status if no issues are found
        return [
            'status' => false,
        ];
    }
}
