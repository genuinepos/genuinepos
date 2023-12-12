<?php

namespace App\Services\Contacts;

use App\Enums\CustomerGroupPriceCalculationType;
use App\Models\Contacts\CustomerGroup;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerGroupService
{
    public function customerGroupsTable($request)
    {
        $generalSettings = config('generalSettings');
        $customerGroups = '';
        $query = DB::table('customer_groups')
            ->leftJoin('branches', 'customer_groups.branch_id', 'branches.id')
            ->leftJoin('price_groups', 'customer_groups.price_group_id', 'price_groups.id');

        $this->filteredQuery(request: $request, query: $query);

        $customerGroups = $query->select(
            'customer_groups.*',
            'price_groups.name as price_group_name',
            'branches.name as branch_name',
            'branches.branch_code',
        )->orderBy('name', 'asc')->get();

        return DataTables::of($customerGroups)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';
                $html .= '<a href="'.route('contacts.customers.groups.edit', [$row->id]).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                $html .= '<a href="'.route('contacts.customers.groups.delete', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('price_calculation_type', function ($row) {

                return CustomerGroupPriceCalculationType::tryFrom($row->price_calculation_type)->name;
            })
            ->editColumn('calculation_percentage', function ($row) {

                if ($row->calculation_percentage != 0) {
                    return \App\Utils\Converter::format_in_bdt($row->calculation_percentage).'%';
                }
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->branch_name) {

                        return $row->branch_name;
                    }
                } else {

                    return $generalSettings['business__business_name'];
                }
            })
            ->rawColumns(['action', 'price_calculation_type', 'calculation_percentage', 'branch'])
            ->make(true);
    }

    public function addCustomerGroup(object $request): void
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $addCustomerGroup = new CustomerGroup();
        $addCustomerGroup->branch_id = $ownBranchIdOrParentBranchId;
        $addCustomerGroup->name = $request->name;
        $addCustomerGroup->price_calculation_type = $request->price_calculation_type;
        if ($request->price_calculation_type == CustomerGroupPriceCalculationType::Percentage->value) {

            $addCustomerGroup->calculation_percentage = $request->calculation_percentage;
        } elseif ($request->price_calculation_type == CustomerGroupPriceCalculationType::SellingPriceGroup->value) {

            $addCustomerGroup->price_group_id = $request->price_group_id;
        }

        $addCustomerGroup->save();
    }

    public function updateCustomerGroup(int $id, object $request): void
    {
        $updateCustomerGroup = $this->singleCustomerGroup(id: $id);

        $updateCustomerGroup->price_group_id = null;
        $updateCustomerGroup->calculation_percentage = 0;
        $updateCustomerGroup->name = $request->name;
        $updateCustomerGroup->price_calculation_type = $request->price_calculation_type;
        if ($request->price_calculation_type == CustomerGroupPriceCalculationType::Percentage->value) {

            $updateCustomerGroup->calculation_percentage = $request->calculation_percentage;
        } elseif ($request->price_calculation_type == CustomerGroupPriceCalculationType::SellingPriceGroup->value) {

            $updateCustomerGroup->price_group_id = $request->price_group_id;
        }

        $updateCustomerGroup->save();
    }

    public function deleteCustomerGroup(int $id): void
    {
        $deleteCustomerGroup = $this->singleCustomerGroup(id: $id);

        if (! is_null($deleteCustomerGroup)) {

            $deleteCustomerGroup->delete();
        }
    }

    public function singleCustomerGroup(int $id, array $with = null): ?object
    {
        $query = CustomerGroup::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function customerGroups(array $with = null): ?object
    {
        $query = CustomerGroup::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    private function filteredQuery(object $request, object $query)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('customer_groups.branch_id', null);
            } else {

                $query->where('customer_groups.branch_id', $request->branch_id);
            }
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

            $query->where('customer_groups.branch_id', $ownBranchIdOrParentBranchId);
        }

        return $query;
    }
}
