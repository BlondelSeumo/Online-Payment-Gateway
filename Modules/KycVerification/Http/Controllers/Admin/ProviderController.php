<?php

namespace Modules\KycVerification\Http\Controllers\Admin;

use App\Http\Helpers\Common;
use App\Http\Controllers\Controller;
use Modules\KycVerification\Entities\KycProvider;
use Modules\KycVerification\Datatable\ProvidersDatatable;
use Modules\KycVerification\Http\Requests\Admin\ProviderRequest;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param ProvidersDatatable $dataTable
     * @return \Illuminate\View\View
     */
    public function index(ProvidersDatatable $dataTable)
    {
        $data = [
            'menu' => 'kyc_verification',
            'sub_menu' => 'kyc_providers',
        ];
        return $dataTable->render('kycverification::admin.providers.index', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param KycProvider $provider
     * @return \Illuminate\View\View
     */
    public function edit(KycProvider $provider)
    {
        $data = [
            'menu' => 'kyc_verification',
            'sub_menu' => 'kyc_providers',
            'provider' => $provider
        ];

        return view('kycverification::admin.providers.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param ProviderRequest $request
     * @param KycProvider $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProviderRequest $request, KycProvider $provider)
    {
        (new KycProvider())->updateProvider($request, $provider);
        (new Common())->one_time_message('success', __('The :x has been successfully saved.', ['x' => __('provider')]));
        return redirect()->route('admin.kyc.providers.index');
    }
}
