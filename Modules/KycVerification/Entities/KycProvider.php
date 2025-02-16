<?php

namespace Modules\KycVerification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KycProvider extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'alias'];

    /**
     * Update provider name
     * @param \illuminate\Http\Request $request
     * @param KycProvider $provider
     * @return void
     */
    public function updateProvider($request, $provider)
    {
        $provider->name = $request->name;
        $provider->save();
    }
}
