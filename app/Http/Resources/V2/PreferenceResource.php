<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class PreferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "money_format"                 => preference("money_format") ?? "before",
            "money_format"                 => preference("money_format") ?? "before",
            "file_size"                    => preference("file_size"),
            "decimal_format_amount"        => preference("money_format") ?? "before",
            "thousand_separator"           => preference("thousand_separator") ?? ",",
            "decimal_format_amount"        => preference("decimal_format_amount") ? preference("decimal_format_amount") : 2,
            "decimal_format_amount_crypto" => preference("decimal_format_amount_crypto") ? preference("decimal_format_amount_crypto") : 8,
            "crypto_exchange_transaction" => preference("transaction_type") ? preference("transaction_type") : null,
        ];
    }
}
