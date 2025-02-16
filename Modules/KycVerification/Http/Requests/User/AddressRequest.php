<?php

namespace Modules\KycVerification\Http\Requests\User;

use App\Rules\CheckValidFile;
use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            //the supported files extension for verification file will be ‘jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf' and by using 8 these value will be returned.
            'verification_file' => ['required', new CheckValidFile(getFileExtensions(8))],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {

        return [
            'verification_file' => __('Address File')
        ];
    }
}
