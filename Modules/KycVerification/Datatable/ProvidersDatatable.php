<?php

namespace Modules\KycVerification\Datatable;

use App\Http\Helpers\Common;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Services\DataTable;
use Modules\KycVerification\Entities\KycProvider;

class ProvidersDatatable extends DataTable
{
    public function ajax(): JsonResponse
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('name', function ($provider) {
                return $provider->name;
            })
            ->addColumn('action', function ($provider) {
                return (Common::has_permission(auth('admin')->id(), 'edit_kyc_provider')) ? '<a href="' . route('admin.kyc.providers.edit', $provider->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function query()
    {
        $query = KycProvider::select('id', 'name');
        return $this->applyScopes($query);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn([
                'data' => 'id',
                'name' => 'kyc_providers.id',
                'title' => __('ID'),
                'searchable' => false,
                'visible' => false
            ])
            ->addColumn([
                'data' => 'name',
                'name' => 'kyc_providers.name',
                'title' => __('Provider')
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
