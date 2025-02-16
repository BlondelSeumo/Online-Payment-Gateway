<?php

namespace Modules\Donation\Http\Controllers\User;

use Exception;
use App\Http\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Models\{Currency, File, Wallet};
use Modules\Donation\Services\DonationService;
use Modules\Donation\Http\Requests\CampaignRequest;
use Modules\Donation\Entities\{Donation, DonationPayment};

class DonationController extends Controller
{
    public function index(DonationService $service)
    {
        $type = isset(request()->type) ? request()->type : 'all';
        $currency = isset(request()->currency) ? request()->currency : 'all';
        $status = isset(request()->status) ? request()->status : 'all';

        $data = [
            'menu' => 'donations',
            'sub_menu' => 'donations',
            'currency' => $currency,
            'status' => $status,
            'type' => $type,
            'donations' => $service->getCampaigns()['campaigns']->paginate(12),
            'currencies' => Donation::with('currency:id,code')->groupBy('currency_id')->get(['currency_id'])
        ];

        return view('donation::user.donations.index', $data);
    }

    public function create()
    {
        if (!m_g_c_v('RE9OQVRJT05fU0VDUkVU') && m_aic_c_v('RE9OQVRJT05fU0VDUkVU')) {
            return view('vendor.installer.errors.user');
        }
        $data = [
            'preference' => preference('decimal_format_amount'),
            'wallet' => Wallet::where([
                'user_id' => auth()->id(),
                'is_default' => 'Yes']
            )->first(['currency_id']),
            'currencies' =>  Wallet::where('user_id', auth()->id())
            ->whereHas('currency', function ($query) {
                $query->whereStatus('Active');
            })
            ->with(['currency:id,type,code'])
            ->get()
            ->pluck('currency')
        ];

        return view('donation::user.donations.create', $data);
    }

    public function store(CampaignRequest $request)
    {
        if (!m_g_c_v('RE9OQVRJT05fU0VDUkVU') && m_aic_c_v('RE9OQVRJT05fU0VDUkVU')) {
            return view('vendor.installer.errors.user');
        }
        try {
            $checkSuggestedAmount = $this->suggestedAmountValidation();

            
            if ($checkSuggestedAmount['status']) {
                return back()->withErrors($checkSuggestedAmount['message'])->withInput();
            }
    
            (new Donation)->createNewDonation($request);
            (new Common)->one_time_message('success', __('The :x has been successfully saved.', ['x' => __('campaign')]));
    
            return redirect()->route('user.donation.index');

        } catch (Exception $exception) {
            (new Common)->one_time_message('error', $exception->getMessage());
            return redirect()->route('user.donation.index');
        }
    }

    public function edit(Donation $donation)
    {
        $data = [
            'donation' => $donation,
            'preference' => preference('decimal_format_amount'),
            'file' => File::where('id', $donation->file_id)->first(['filename', 'id']),
            'donationPaymentCount' => DonationPayment::where('donation_id', $donation->id)->count(),
            'currencies' => Wallet::where('user_id', auth()->id())
            ->whereHas('currency', function ($query) {
                $query->whereStatus('Active');
            })
            ->with(['currency:id,type,code'])
            ->get()
            ->pluck('currency') 
        ];

        return view('donation::user.donations.edit', $data);
    }

    public function update(CampaignRequest $request, Donation $donation)
    {
        if (!m_g_c_v('RE9OQVRJT05fU0VDUkVU') && m_aic_c_v('RE9OQVRJT05fU0VDUkVU')) {
            return view('vendor.installer.errors.user');
        }
        
        if ($donation->creator_id != auth()->id()) {
            (new Common)->one_time_message('error', __('You are not authorized to update this campaign.'));
            return redirect()->route('user.donation.index');
        }
        
        $donationPaymentCount = DonationPayment::where('donation_id', $donation->id)->count();
        if ($donationPaymentCount > 0) {
            return back()->withErrors(__("Update failed. Transactions have already been made for this campaign"))->withInput();
        }

        try {
            $checkSuggestedAmount = $this->suggestedAmountValidation();

            if ($checkSuggestedAmount['status']) {
                return back()->withErrors($checkSuggestedAmount['message'])->withInput();
            }

            if ($donationPaymentCount > 0 && $donation->currency_id != $request->currency_id) {
                return back()->withErrors(__('Currency can not be changed as this campaign has payment.'))->withInput();
            }

            (new Donation)->updateDonation($request, $donation);
            (new Common)->one_time_message('success', __('The :x has been successfully updated.', ['x' => __('campaign')]));
            return redirect()->route('user.donation.index');

        } catch (Exception $exception) {
            (new Common)->one_time_message('error', $exception->getMessage());
            return redirect()->route('user.donation.index');
        }
    }

    public function delete(Donation $donation)
    {
        if ($donation->creator_id != auth()->id()) {
            (new Common)->one_time_message('error', __('You are not authorized to delete this campaign.'));
            return redirect()->route('user.donation.index');
        }

        $paymentCount = DonationPayment::where('donation_id', $donation->id)->count();
        
        if ($paymentCount > 0) {
            (new Common)->one_time_message('error', __('This campaign has donation. It cannot be deleted.'));
            return redirect()->route('user.donation.index');
        }

        (new Donation)->deleteDonation($donation);
        (new Common)->one_time_message('success', __('The :x has been successfully deleted.', ['x' => __('campaign')]));
        return redirect()->route('user.donation.index');
    }

    public function detail(Donation $donation)
    {
        $data = [
            'socialShareUrl' => route('donations.details', $donation->slug),
            'donation' => $donation->load(['currency:id,code,symbol', 'file:id,filename']),
            'payments' => DonationPayment::with(['currency:id,code,symbol', 'paymentMethod:id,name', 'payer:id,first_name,last_name'])->where('donation_id', $donation->id)->latest()->get()
        ];
        return view('donation::user.donations.detail', $data);
    }

    private function suggestedAmountValidation()
    {
        if (request()->donation_type != 'suggested_amount') {
            return [
                'status' => false
            ];
        }

        if (
            request()->first_suggested_amount == 0 ||
            request()->third_suggested_amount == 0 ||
            request()->second_suggested_amount == 0
        ) {
            return [
                'status' => true,
                'message' =>__('Any suggest amount can not be zero')
            ];
        }

        if (
            request()->first_suggested_amount == request()->second_suggested_amount || request()->first_suggested_amount == request()->third_suggested_amount || request()->second_suggested_amount == request()->third_suggested_amount
        ) {
            return [
                'status' => true,
                'message' =>__('Any two suggest amount can not be same')
            ];
        }
        if (request()->donation_type == 'suggested_amount') {
            $suggestedAmount = request()->first_suggested_amount + request()->second_suggested_amount + request()->third_suggested_amount;
            if ($suggestedAmount != request()->goal_amount) {
                return [
                    'status' => true,
                    'message' =>__('The total of the suggested amounts must equal to the goal amount')
                ];
            }
        }

        return [
            'status' => false
        ];
        
    }
}