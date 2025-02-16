<?php

namespace Modules\Donation\Http\Requests;

use App\Rules\CheckValidFile;
use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules =  [
            'title'                   => 'required|string|max:150|min:10',
            'description'             => 'required|string',
            'goal_amount'             => 'required|numeric|min:1',
            'currency_id'             => 'required|integer',
            'donation_type'           => 'required|string|in:any_amount,fixed_amount,suggested_amount',
            'fixed_amount'            => 'required_if:donation_type,==,fixed_amount',
            'first_suggested_amount'  => 'required_if:donation_type,==,suggested_amount',
            'second_suggested_amount' => 'required_if:donation_type,==,suggested_amount',
            'third_suggested_amount'  => 'required_if:donation_type,==,suggested_amount',
            'banner_image'            => 'nullable|dimensions:min_width=365,min_height=200', [new CheckValidFile(getFileExtensions(3), true)],
            'end_date'                => 'required|date|after:yesterday',
        ];

        if (preference('donation_fee_applicable') == 'yes') {
            $rules['fee_bearer'] = 'required|string|max:3|in:Yes,No';
        }
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function fieldNames()
    {
        $rulesText = [
            'title'         => __("Title"),
            'currency_id'    => __("Currency"),
            'donation_type' => __("Campaign Type"),
            'goal_amount' => __("Goal Amount"),
            'fixed_amount' => __("Fixed Amount"),
            'first_suggested_amount' => __("First Suggested Amount"),
            'second_suggested_amount' => __("Second Suggested Amount"),
            'third_suggested_amount' => __("Third Suggested Amount"),
            'banner_image' => __("Banner Image"),
            'end_date' => __("Deadline"),
            'description' => __("Description"),
        ];
        if (preference('donation_fee_applicable') == 'yes') {
            $rulesText['fee_bearer'] = __("Will the donor cover the fee?");
        }
        return $rulesText;
    }

    public function messages()
{
    return [
        'end_date.after' => __('The end date must be set to a future date'),
    ];
}
}
