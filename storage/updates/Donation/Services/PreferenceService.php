<?php

namespace Modules\Donation\Services;

class PreferenceService
{
    public function updatePreference(object $request)
    {
        \App\Models\Preference::where([
            'category' => 'donation',
            'field' => 'donation_available_for'
        ])->update(['value' => $request->donation_available_for]);

        \App\Models\Preference::where([
            'category' => 'donation',
            'field' => 'donation_fee_applicable'
        ])->update(['value' => $request->donation_fee_applicable]);
    }
}
