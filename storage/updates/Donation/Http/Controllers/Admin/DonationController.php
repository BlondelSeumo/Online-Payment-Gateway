<?php

namespace Modules\Donation\Http\Controllers\Admin;

use Excel;
use App\Models\File;
use App\Http\Helpers\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Donation\Exports\DonationsExport;
use Modules\Donation\DataTables\DonationsDataTable;
use Modules\Donation\Entities\{Donation, DonationPayment};

class DonationController extends Controller
{
    public function index(DonationsDataTable $dataTable)
    {
        if (!m_g_c_v('RE9OQVRJT05fU0VDUkVU') && m_aic_c_v('RE9OQVRJT05fU0VDUkVU')) {
            return view('addons::install', ['module' => 'RE9OQVRJT05fU0VDUkVU']);
        }

        $user = isset(request()->user_id) ? request()->user_id : null;

        $data = [
            'user' => $user,
            'menu' => 'donation',
            'sub_menu' => 'donations',
            'type' => isset(request()->type) ? request()->type : 'all',
            'getName' => (new Donation)->getDonationUsersName($user),
            'currency' => isset(request()->currency) ? request()->currency : 'all',
            'donationTypes' => (new Donation)->groupBy('donation_type')->get(['donation_type']),
            'donationCurrencies' => (new Donation)->with('currency:id,code')->groupBy('currency_id')->get(['currency_id']),
        ];

        return $dataTable->render('donation::admin.donations.index', $data);
    }

    public function userSearch(Request $request)
    {
        $search = $request->search;
        $user = (new Donation)->getDonationsUsersResponse($search);

        $res = [
            'status' => 'fail',
        ];
        
        if (count($user) > 0) {
            $res = [
                'status' => 'success',
                'data'   => $user,
            ];
        }
        return json_encode($res);
    }

    public function csv()
    {
        return Excel::download(new DonationsExport(), 'campaigns_'. time() .'.xls');
    }

    public function pdf()
    {
        $type = isset(request()->type) ? request()->type : null;
        $currency = isset(request()->currency) ? request()->currency : null;
        $user = isset(request()->user_id) ? request()->user_id : null;
        $data['donations'] = (new Donation())->getDonationsList($type, $currency, $user)->latest()->get();
        generatePDF('donation::admin.donations.pdf', 'campaigns_', $data);
    }

    public function detail(Donation $donation)
    {
        $donation = $donation->load(['currency:id,code,symbol', 'creator:id,first_name,last_name']);

        $data = [
            'menu' => 'donation',
            'donation' => $donation,
            'sub_menu' => 'donations',
            'preference' => preference('decimal_format_amount'),
            'file' => File::where('id', $donation->file_id)->first(['filename', 'id']),
        ];
        return view('donation::admin.donations.detail', $data);
    }

    public function delete(Donation $donation)
    {
        $paymentCount = DonationPayment::where('donation_id', $donation->id)->count();
        
        if ($paymentCount > 0) {
            (new Common)->one_time_message('error', __('This campaign has donation. It cannot be deleted.'));
            return back();
        }

        (new Donation)->deleteDonation($donation);
        (new Common)->one_time_message('success', __('The :x has been successfully deleted.', ['x' => __('campaign')]));
        return back();
    }
}