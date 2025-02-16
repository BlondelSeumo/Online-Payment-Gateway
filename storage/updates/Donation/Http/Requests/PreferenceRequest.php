<?php

namespace Modules\Donation\Http\Requests;

use App\Http\Requests\CustomFormRequest;

class PreferenceRequest extends CustomFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'donation_available_for' => ['required', 'string', 'max:10', 'in:merchant,user,both'],
            'donation_fee_applicable' => ['required', 'string', 'max:10', 'in:yes,no'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function attributes()
    {
        return [
            'donation_available_for' => __('Available For'),
            'donation_fee_applicable' => __('Fee Applicable')
        ];
    }
}
