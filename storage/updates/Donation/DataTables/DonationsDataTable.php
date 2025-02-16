<?php

namespace Modules\Donation\DataTables;

use Config, Common;
use Illuminate\Http\JsonResponse;
use Modules\Donation\Entities\Donation;
use Yajra\DataTables\Services\DataTable;

class DonationsDataTable extends DataTable
{
    public function ajax(): JsonResponse
    {
        return datatables()
            ->eloquent($this->query())
            ->editColumn('created_at', function ($donation) {
                return dateFormat($donation->created_at);
            })
            ->addColumn('creator_id', function ($donation) {
                $sender = getColumnValue($donation->creator);
                if ($sender <> '-') {
                    return Common::has_permission(auth()->guard('admin')->user()->id, 'edit_user') ? '<a href="' . url(Config::get('adminPrefix') . '/users/edit/' . $donation?->creator?->id) . '">' . $sender . '</a>' : $sender;
                }
                return $sender;
            })
            ->editColumn('title', function ($donation) {
                return $donation->title;
            })
            ->editColumn('goal_amount', function ($donation) {
                return formatNumber($donation->goal_amount, $donation->currency_id);
            })
            ->editColumn('raised_amount', function ($donation) {
                return formatNumber($donation->raised_amount, $donation->currency_id);
            })
            ->editColumn('currency_id', function ($donation) {
                return optional($donation->currency)->code;
            })
            ->editColumn('donation_type', function ($donation) {
                return ucwords(str_replace('_', ' ', $donation->donation_type));
            })
            ->editColumn('fee_bearer', function ($donation) {
                return ucfirst($donation->fee_bearer);
            })
            ->addColumn('action', function ($donation) {
                $edit = (Common::has_permission(auth()->guard('admin')->user()->id, 'edit_campaign')) ?
                '<a href="' . route('admin.donation.detail', $donation->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>&nbsp;' : '';

                $delete = (Common::has_permission(auth()->guard('admin')->user()->id, 'delete_campaign')) ? '<a href="' . route('admin.donation.delete', $donation->id) . '" class="btn btn-xs btn-danger delete-warning"><i class="fa fa-trash"></i></a>' : '';

                return $edit . $delete;
            })
            ->rawColumns(['creator_id', 'action'])
            ->make(true);
    }

    public function query()
    {
        $type     = isset(request()->type) ? request()->type : 'all';
        $currency = isset(request()->currency) ? request()->currency : 'all';
        $user     = isset(request()->user_id) ? request()->user_id : null;
        $query    = (new Donation())->getDonationsList($type, $currency, $user);
        return $this->applyScopes($query);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn([
                'data' => 'id',
                'name' => 'donations.id',
                'title' => __('ID'),
                'searchable' => false,
                'visible' => false
            ])
           ->addColumn([
                'data' => 'created_at', 
                'name' => 'donations.created_at', 
                'title' => __('Date')
            ])
            ->addColumn([
                'data' => 'creator_id', 
                'name' => 'creator.last_name', 
                'title' => __('User'),
                'visible' => false
            ])
            ->addColumn([
                'data' => 'creator_id', 
                'name' => 'creator.first_name', 
                'title' => __('User')
            ])
            ->addColumn([
                'data' => 'title', 
                'name' => 'donations.title', 
                'title' => __('Campaign Title')
            ])
            ->addColumn([
                'data' => 'goal_amount', 
                'name' => 'donations.goal_amount', 
                'title' => __('Goal Amount')
            ])
            ->addColumn([
                'data' => 'raised_amount', 
                'name' => 'donations.raised_amount', 
                'title' => __('Raised Amount')
            ])
            ->addColumn([
                'data' => 'currency_id', 
                'name' => 'currency.code', 
                'title' => __('Currency')
            ])
            ->addColumn([
                'data' => 'donation_type', 
                'name' => 'donations.donation_type', 
                'title' => __('Campaign Type')
            ])
            ->addColumn([
                'data' => 'fee_bearer', 
                'name' => 'donations.fee_bearer', 
                'title' => __('Fee Bearer')
            ])
            ->addColumn([
                'data' => 'action', 
                'name' => 'action', 
                'title' => __('Action'), 
                'orderable' => false, 
                'searchable' => false
            ])
            ->parameters(dataTableOptions());
    }
}
