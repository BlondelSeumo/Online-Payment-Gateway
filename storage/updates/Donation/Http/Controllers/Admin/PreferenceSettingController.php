<?php

namespace Modules\Donation\Http\Controllers\Admin;

use Cache;
use App\Http\Helpers\Common;
use App\Http\Controllers\Controller;
use Modules\Donation\Services\PreferenceService;
use Modules\Donation\Http\Requests\PreferenceRequest;

class PreferenceSettingController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!m_g_c_v('RE9OQVRJT05fU0VDUkVU') && m_aic_c_v('RE9OQVRJT05fU0VDUkVU')) {
            return view('addons::install', ['module' => 'RE9OQVRJT05fU0VDUkVU']);
        }
        
        $preferences = \App\Models\Preference::where('category', 'donation')->get();
        $preferenceData = [];

        foreach ($preferences as $row) {
            $preferenceData[$row->category][$row->field] = $row->value;
        }

        $data = [
            'menu' => 'donation',
            'sub_menu' => 'donation_preference',
            'preferenceData' => $preferenceData
        ];

        return view('donation::admin.preference', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PreferenceRequest $request, PreferenceService $service)
    {
        $service->updatePreference($request);
        Cache::forget(config('cache.prefix') . '-preferences');
        (new Common)->one_time_message('success', __('The :x has been successfully saved.', ['x' => __('preference')]));
        return back();
    }
}
