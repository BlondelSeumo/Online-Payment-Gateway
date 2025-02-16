<?php

namespace Modules\Donation\Entities;

use Exception, Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = ['file_id', 'currency_id', 'creator_id', 'display_brand_image', 'title', 'slug', 'description', 'goal_amount', 'raised_amount', 'donation_type', 'fixed_amount', 'first_suggested_amount', 'second_suggested_amount', 'third_suggested_amount', 'get_payer_info', 'end_date'];

    public function currency()
    {
        return $this->belongsTo(\App\Models\Currency::class, 'currency_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'creator_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo(\App\Models\File::class, 'file_id');
    }

    public function donationPayment()
    {
        return $this->hasOne(DonationPayment::class, 'donation_id');
    }
    
    public function donationPayments()
    {
        return $this->hasMany(DonationPayment::class, 'donation_id', 'id');
    }

    public function getDonationsList($type, $currency, $user)
    {
        $conditions = [];

        if (!empty($type) && $type != 'all') {
            $conditions['donation_type'] = $type;
        }

        if (!empty($currency) && $currency != 'all') {
            $conditions['currency_id'] = $currency;
        }

        if (!empty($user)) {
            $conditions['creator_id'] = $user;
        }

        $donations = $this->with([
            'creator:id,first_name,last_name',
            'currency:id,code',
        ])->where($conditions);
        
        $donations->select('donations.id', 'donations.created_at', 'donations.creator_id', 'donations.title', 'donations.goal_amount', 'donations.raised_amount', 'donations.currency_id', 'donations.donation_type', 'donations.fee_bearer');
        
        return $donations;
    }

    public function getDonationUsersName($user)
    {
        return $this->with(['creator:id,first_name,last_name'])->where('creator_id', $user)->first();
    }

    public function getDonationsUsersResponse($search)
    {
        return $this->with('creator:id,first_name,last_name')->whereHas('creator', function($query) use ($search) {
            $query->where('first_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $search . '%');
        })
        ->distinct('creator_id')
        ->select('creator_id')
        ->get();
    }

    public function deleteDonation(Object $donation)
    {
        $file = \App\Models\File::where(['id' => $donation->file_id])->first();

        if ($file != null && file_exists('Modules/Donation/public/uploads/'. $file->filename)) {
            unlink('Modules/Donation/public/uploads/' . $file->filename);
            $file->delete();
        }
        
        $donation->delete();
    }

    public function createNewDonation($request)
    {
        $fileId = $this->insertBannerImageInFileTable($request);

        $this->title                   = $request->title;
        $this->slug                    = $this->slugChecking();
        $this->currency_id             = $request->currency_id;
        $this->donation_type           = $request->donation_type;
        $this->goal_amount             = $request->goal_amount;
        $this->fixed_amount            = $request->fixed_amount;
        $this->first_suggested_amount  = $request->first_suggested_amount;
        $this->second_suggested_amount = $request->second_suggested_amount;
        $this->third_suggested_amount  = $request->third_suggested_amount;
        $this->file_id                 = $fileId ;
        $this->display_brand_image     = 'Yes';
        $this->fee_bearer              = preference('donation_fee_applicable') == 'yes' ? ($request->fee_bearer == 'Yes' ? 'donor' : 'creator') : 'donor';
        $this->end_date                = date('Y-m-d', strtotime($request->end_date));
        $this->description             = strip_tags($request->description);
        $this->creator_id              = auth()->id();
        $this->save() ;
    }
    
    public function updateDonation($request, $donation)
    {
        $fileId = $this->insertBannerImageInFileTable($request);
        
        $donation->title                   = $request->title;
        $donation->slug                    = $this->slugChecking($donation->id);
        $donation->currency_id             = $request->currency_id;
        $donation->donation_type           = $request->donation_type;
        $donation->goal_amount             = $request->goal_amount;
        $donation->fixed_amount            = ($request->donation_type != 'any_amount' && $request->donation_type != 'suggested_amount') ? $request->fixed_amount : null;
        $donation->first_suggested_amount  = ($request->donation_type != 'any_amount' && $request->donation_type != 'fixed_amount') ? $request->first_suggested_amount : null;
        $donation->second_suggested_amount = ($request->donation_type != 'any_amount' && $request->donation_type != 'fixed_amount') ? $request->second_suggested_amount : null;
        $donation->third_suggested_amount  = ($request->donation_type != 'any_amount' && $request->donation_type != 'fixed_amount') ? $request->third_suggested_amount : null;
        $donation->display_brand_image     = 'Yes';
        $donation->fee_bearer              = preference('donation_fee_applicable') == 'yes' ? ($request->fee_bearer == 'Yes' ? 'donor' : 'creator') : 'donor';
        $donation->end_date                = date('Y-m-d', strtotime($request->end_date));
        $donation->description             = strip_tags($request->description);
        if($fileId != null) {
            $donation->file_id = $fileId;
        }
        $donation->save() ;
    }

    public function insertBannerImageInFileTable($request)
    {
        if ($request->hasFile('banner_image')) {
            $fileName     = $request->file('banner_image');
            $originalName = $fileName->getClientOriginalName();
            $uniqueName   = strtolower(time() . '.' . $fileName->getClientOriginalExtension());
            $fileExtension = strtolower($fileName->getClientOriginalExtension());


            if (!in_array($fileExtension, getFileExtensions(3))) {
                throw new Exception(__('The :x format is invalid.', ['x' => __('file')]));
            }

            $uploadPath = 'Modules/Donation/public/uploads';

            $resizedImage = Image::make($fileName->getRealPath());
            if ($resizedImage->width() > 710 || $resizedImage->height() > 400) {
                // Resize the image and save it
                $resizedImage->resize(710, 400)->save($uploadPath . '/' . $uniqueName);
            } else {
                $fileName->move($uploadPath, $uniqueName);
            }

            if (isset($request->existingBannerFileID)) {
                $existingFile = \App\Models\File::where(['id' => $request->existingBannerFileID])->first();
                
                if(file_exists('Modules/Donation/public/uploads/' . $existingFile->filename)) {
                    unlink('Modules/Donation/public/uploads/' . $existingFile->filename);
                }
                
                $existingFile->filename     = $uniqueName;
                $existingFile->originalname = $originalName;
                $existingFile->save();
                return $existingFile->id;
            }

            $file               = new \App\Models\File();
            $file->user_id      = auth()->id();
            $file->filename     = $uniqueName;
            $file->originalname = $originalName;
            $file->type         = $fileExtension;
            $file->save();
            return $file->id;
        }
    }

    private function slugChecking($donationId = null)
    {
        $slug = str()->slug(request()->title);

        $existingSlug = $this->whereSlug($slug)->count();

        if ($donationId != null) {
            $existingSlug = $this->whereSlug($slug)->where('id', '!=', $donationId)->count();
        }

        if ($existingSlug > 0) {
            $latestEntry = $this->latest()->first();
            $slug = $slug . '-' . $existingSlug + $latestEntry->id;
        }

        return $slug;
    }
}