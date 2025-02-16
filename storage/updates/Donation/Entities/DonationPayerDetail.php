<?php

namespace Modules\Donation\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DonationPayerDetail extends Model
{
    use HasFactory;

    protected $fillable = ['donation_id', 'first_name', 'last_name', 'email'];
    
}
