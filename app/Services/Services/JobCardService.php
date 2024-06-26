<?php

namespace App\Services\Services;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Enums\ServiceType;
use App\Utils\FileUploader;
use App\Models\Services\JobCard;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class JobCardService
{
    public function jobCardsTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $jobCards = '';
        $query = DB::table('service_job_cards')
            ->leftJoin('accounts as customers', 'service_job_cards.customer_account_id', 'customers.id')
            ->leftJoin('sales', 'service_job_cards.sale_id', 'sales.id')
            ->leftJoin('brands', 'service_job_cards.brand_id', 'brands.id')
            ->leftJoin('service_devices', 'service_job_cards.device_id', 'service_devices.id')
            ->leftJoin('service_device_models', 'service_job_cards.device_model_id', 'service_device_models.id')
            ->leftJoin('service_status', 'service_job_cards.status_id', 'service_status.id')
            ->leftJoin('branches', 'service_job_cards.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('users as created_by', 'service_job_cards.created_by_id', 'created_by.id')
            ->whereNotNull('job_no');

        $this->filteredQuery(request: $request, query: $query);

        $jobCards = $query->select(
            'service_job_cards.id',
            'service_job_cards.branch_id',
            'service_job_cards.job_no',
            'service_job_cards.date_ts',
            'service_job_cards.delivery_date_ts',
            'service_job_cards.due_date_ts',
            'service_job_cards.total_cost',
            'service_job_cards.service_type',
            'service_job_cards.serial_no',

            'brands.name as brand_name',
            'service_devices.name as device_name',
            'service_device_models.name as device_model_name',
            'service_status.name as status_name',
            'service_status.color_code as status_color_code',

            'sales.id as sale_id',
            'sales.invoice_id',

            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',

            'customers.name as customer_name',

            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        )->orderBy('service_job_cards.date_ts', 'desc');

        return DataTables::of($jobCards)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="' . route('services.job.cards.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

                if (auth()->user()->can('job_cards_generate_pdf')) {

                    $html .= '<a class="dropdown-item" href="' . route('services.job.cards.generate.pdf', [$row->id]) . '" target="_blank">' . __('Generate Pdf') . '</a>';
                }

                if (auth()->user()->can('job_cards_generate_label')) {

                    $html .= '<a class="dropdown-item" href="' . route('services.job.cards.generate.label', [$row->id]) . '" id="generateLabel">' . __('Generate Label') . '</a>';
                }

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('job_cards_change_status')) {

                        $html .= '<a class="dropdown-item" href="' . route('services.job.cards.change.status.modal', $row->id) . '" id="changeStatus">' . __('Change Status') . '</a>';
                    }

                    if (auth()->user()->can('service_invoices_create') && !$row->sale_id) {

                        $html .= '<a href="' . route('sales.pos.create', [$row->id, \App\Enums\SaleScreenType::ServicePosSale->value]) . '" class="dropdown-item">' . __('Add Invoice') . '</a>';
                    }

                    if (auth()->user()->can('job_cards_edit')) {

                        $html .= '<a class="dropdown-item" href="' . route('services.job.cards.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                    }

                    if (auth()->user()->can('job_cards_delete')) {

                        $html .= '<a href="' . route('services.job.cards.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                    }
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('service_type', function ($row) use ($generalSettings) {

                return str(\App\Enums\ServiceType::tryFrom($row->service_type)->name)->headline();
            })

            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                return date($__date_format, strtotime($row->date_ts));
            })

            ->editColumn('delivery_date', function ($row) use ($generalSettings) {

                if ($row->delivery_date_ts) {

                    $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                    return date($__date_format, strtotime($row->delivery_date_ts));
                }
            })

            ->editColumn('due_date', function ($row) use ($generalSettings) {

                if ($row->due_date_ts) {

                    $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                    return date($__date_format, strtotime($row->due_date_ts));
                }
            })
            ->editColumn('job_no', function ($row) {

                $isSaleCompleted = '';
                if ($row->sale_id) {
                    $isSaleCompleted = '<strong><i class="fa-solid fa-check-double text-success"></i></strong>';
                }

                return '<a href="' . route('services.job.cards.show', [$row->id]) . '" id="details_btn">' . $row->job_no .$isSaleCompleted. '</a>';
            })

            ->editColumn('invoice_id', function ($row) {

                if ($row->sale_id) {

                    return '<a href="' . route('sales.show', [$row->sale_id]) . '" id="details_btn">' . $row->invoice_id . '</a>';
                }
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->branch_area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })
            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('total_cost', fn ($row) => '<span class="total_cost" data-value="' . $row->total_cost . '">' . \App\Utils\Converter::format_in_bdt($row->total_cost) . '</span>')

            ->editColumn('status', function ($row) {

                return '<span class="fw-bold" style="color:' . $row->status_color_code . ';">' . $row->status_name . '</span>';
            })

            ->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })

            ->rawColumns(['action', 'date', 'delivery_date', 'due_date', 'branch', 'job_no', 'invoice_id', 'branch', 'customer', 'total_cost', 'status', 'created_by'])
            ->make(true);
    }

    public function addJobCard(object $request, object $codeGenerator, ?string $jobCardNoPrefix = null): object
    {
        $jobNo = $codeGenerator->generateMonthWise(table: 'service_job_cards', column: 'job_no', prefix: $jobCardNoPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $addJobCard = new JobCard();
        $addJobCard->branch_id = auth()->user()->branch_id;
        $addJobCard->job_no = $jobNo;
        $addJobCard->customer_account_id = $request->customer_account_id;
        $addJobCard->service_type = $request->service_type;
        $addJobCard->address = $request->address;
        $addJobCard->brand_id = $request->brand_id;
        $addJobCard->device_id = $request->device_id;
        $addJobCard->device_model_id = $request->device_model_id;
        $addJobCard->service_checklist = isset($request->checklist) ? $request->checklist : null;
        $addJobCard->serial_no = $request->serial_no;
        $addJobCard->password = $request->password;

        if (isset($request->product_configuration)) {

            $productConfiguration = array_map(fn ($item) => $item['value'], json_decode($request->product_configuration, true));
            $__productConfiguration = implode(', ', $productConfiguration);

            $addJobCard->product_configuration = $__productConfiguration;
        }

        if (isset($request->problems_report)) {

            $problemsReport = array_map(fn ($item) => $item['value'], json_decode($request->problems_report, true));
            $__problemsReport = implode(', ', $problemsReport);

            $addJobCard->problems_report = $__problemsReport;
        }

        if (isset($request->product_condition)) {

            $productCondition = array_map(fn ($item) => $item['value'], json_decode($request->product_condition, true));
            $__productCondition = implode(', ', $productCondition);

            $addJobCard->product_condition = $__productCondition;
        }

        $addJobCard->technician_comment = $request->technician_comment;
        $addJobCard->status_id = $request->status_id;
        $addJobCard->send_notification = $request->send_notification;
        $addJobCard->custom_field_1 = $request->custom_field_1;
        $addJobCard->custom_field_2 = $request->custom_field_2;
        $addJobCard->custom_field_3 = $request->custom_field_3;
        $addJobCard->custom_field_4 = $request->custom_field_4;
        $addJobCard->custom_field_5 = $request->custom_field_5;
        $addJobCard->total_item = $request->total_item ? $request->total_item : 0;
        $addJobCard->total_qty = $request->total_qty ? $request->total_qty : 0;
        $addJobCard->total_cost = $request->total_cost ? $request->total_cost : 0;
        $addJobCard->date_ts = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addJobCard->delivery_date_ts = isset($request->delivery_date) ? date('Y-m-d H:i:s', strtotime($request->delivery_date . date(' H:i:s'))) : null;
        $addJobCard->due_date_ts = isset($request->due_date) ? date('Y-m-d H:i:s', strtotime($request->due_date . date(' H:i:s'))) : null;
        $addJobCard->created_by_id = auth()->user()->id;

        if ($request->hasFile('document')) {

            $dir = public_path('uploads/services/documents/');

            $addJobCard->document = FileUploader::upload($request->file('document'), $dir);
        }

        $addJobCard->save();

        return $addJobCard;
    }

    public function updateJobCard(int $id, object $request): object
    {
        $updateJobCard = $this->singleJobCard(id: $id);
        $updateJobCard->customer_account_id = $request->customer_account_id;
        $updateJobCard->service_type = $request->service_type;
        $updateJobCard->address = $request->address;
        $updateJobCard->brand_id = $request->brand_id;
        $updateJobCard->device_id = $request->device_id;
        $updateJobCard->device_model_id = $request->device_model_id;
        $updateJobCard->service_checklist = isset($request->checklist) ? $request->checklist : null;
        $updateJobCard->serial_no = $request->serial_no;
        $updateJobCard->password = $request->password;

        if (isset($request->product_configuration)) {

            $productConfiguration = array_map(fn ($item) => $item['value'], json_decode($request->product_configuration, true));
            $__productConfiguration = implode(', ', $productConfiguration);

            $updateJobCard->product_configuration = $__productConfiguration;
        }

        if (isset($request->problems_report)) {

            $problemsReport = array_map(fn ($item) => $item['value'], json_decode($request->problems_report, true));
            $__problemsReport = implode(', ', $problemsReport);

            $updateJobCard->problems_report = $__problemsReport;
        }

        if (isset($request->product_condition)) {

            $productCondition = array_map(fn ($item) => $item['value'], json_decode($request->product_condition, true));
            $__productCondition = implode(', ', $productCondition);

            $updateJobCard->product_condition = $__productCondition;
        }

        $updateJobCard->technician_comment = $request->technician_comment;
        $updateJobCard->status_id = $request->status_id;
        $updateJobCard->send_notification = $request->send_notification;
        $updateJobCard->custom_field_1 = $request->custom_field_1;
        $updateJobCard->custom_field_2 = $request->custom_field_2;
        $updateJobCard->custom_field_3 = $request->custom_field_3;
        $updateJobCard->custom_field_4 = $request->custom_field_4;
        $updateJobCard->custom_field_5 = $request->custom_field_5;
        $updateJobCard->total_item = $request->total_item ? $request->total_item : 0;
        $updateJobCard->total_qty = $request->total_qty ? $request->total_qty : 0;
        $updateJobCard->total_cost = $request->total_cost ? $request->total_cost : 0;
        $time = date(' H:i:s', strtotime($updateJobCard->date_ts));
        $updateJobCard->date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $time = isset($request->delivery_date) ? date(' H:i:s', strtotime($updateJobCard->delivery_date)) : date(' H:i:s');
        $updateJobCard->delivery_date_ts = isset($request->delivery_date) ? date('Y-m-d H:i:s', strtotime($request->delivery_date . $time)) : null;
        $time = isset($request->due_date_ts) ? date(' H:i:s', strtotime($updateJobCard->due_date_ts)) : date(' H:i:s');
        $updateJobCard->due_date_ts = isset($request->due_date) ? date('Y-m-d H:i:s', strtotime($request->due_date . $time)) : null;

        if ($request->hasFile('document')) {

            $dir = public_path('uploads/services/documents/');
            if (isset($updateJobCard->document) && file_exists($dir . $updateJobCard->document)) {

                unlink($dir . $updateJobCard->document);
            }

            $updateJobCard->document = FileUploader::upload($request->file('document'), $dir);
        }

        $updateJobCard->save();

        return $updateJobCard;
    }

    public function addOrUpdateJobCardBySale(object $request, int $saleId): void
    {
        $jobCardId = null;

        if (isset($request->job_card_id)) {

            $jobCardId = filter_var($request->job_card_id, FILTER_VALIDATE_INT);
        }

        $jobCard = null;
        if (isset($jobCardId)) {

            $jobCard = $this->singleJobCard(id: $jobCardId);
        }

        if (isset($jobCard)) {

            $this->updateJobCardBySale(request: $request, jobCard: $jobCard, saleId: $saleId);
        } else {

            $this->addJobCardBySale(request: $request, saleId: $saleId);
        }
    }

    private function addJobCardBySale(object $request, int $saleId): void
    {
        $addJobCard = new JobCard();
        $addJobCard->branch_id = auth()->user()->branch_id;
        $addJobCard->customer_account_id = $request->customer_account_id;
        $addJobCard->sale_id = $saleId;
        $addJobCard->brand_id = $request->brand_id;
        $addJobCard->device_id = $request->device_id;
        $addJobCard->device_model_id = $request->device_model_id;
        $addJobCard->service_checklist = isset($request->checklist) ? $request->checklist : null;
        $addJobCard->serial_no = $request->serial_no;
        $addJobCard->service_type = ServiceType::CarryIn->value;

        if (isset($request->problems_report)) {

            $problemsReport = array_map(fn ($item) => $item['value'], json_decode($request->problems_report, true));
            $__problemsReport = implode(', ', $problemsReport);

            $addJobCard->problems_report = $__problemsReport;
        }

        $addJobCard->status_id = $request->status_id;
        $addJobCard->date_ts = date('Y-m-d H:i:s');
        $addJobCard->delivery_date_ts = isset($request->delivery_date) ? date('Y-m-d H:i:s', strtotime($request->delivery_date . date(' H:i:s'))) : null;

        $addJobCard->completed_at_ts = isset($request->service_complete_date) ? date('Y-m-d H:i:s', strtotime($request->service_complete_date . date(' H:i:s'))) : null;

        $addJobCard->created_by_id = auth()->user()->id;

        $addJobCard->save();
    }

    private function updateJobCardBySale(object $request, object $jobCard, int $saleId): void
    {
        $jobCard->sale_id = $saleId;
        $jobCard->brand_id = $request->brand_id;
        $jobCard->device_id = $request->device_id;
        $jobCard->device_model_id = $request->device_model_id;
        $jobCard->service_checklist = isset($request->checklist) ? $request->checklist : null;
        $jobCard->serial_no = $request->serial_no;

        if (isset($request->problems_report)) {

            $problemsReport = array_map(fn ($item) => $item['value'], json_decode($request->problems_report, true));
            $__problemsReport = implode(', ', $problemsReport);

            $jobCard->problems_report = $__problemsReport;
        }

        $jobCard->status_id = $request->status_id;

        $time = date(' H:i:s', strtotime($jobCard->date_ts));

        $time = isset($request->delivery_date) ? date(' H:i:s', strtotime($jobCard->delivery_date)) : date(' H:i:s');
        $jobCard->delivery_date_ts = isset($request->delivery_date) ? date('Y-m-d H:i:s', strtotime($request->delivery_date . $time)) : null;

        $time = isset($request->service_complete_date) ? date(' H:i:s', strtotime($jobCard->completed_at_ts)) : date(' H:i:s');
        $jobCard->completed_at_ts = isset($request->service_complete_date) ? date('Y-m-d H:i:s', strtotime($request->service_complete_date . $time)) : null;

        $jobCard->save();
    }

    public function updateJobCardStatus(int $id, object $request): void
    {
        $updateJobCard = $this->singleJobCard(id: $id);

        if (isset($updateJobCard)) {

            $updateJobCard->status_id = $request->status_id;
            $updateJobCard->save();
        }
    }

    public function deleteJobCard(int $id): array
    {
        $deleteJobCard = $this->singleJobCard(id: $id, with: ['sale']);

        if (isset($deleteJobCard)) {

            if (isset($deleteJobCard->sale)) {

                return ['pass' => false, 'msg' => __('Job card can not be deleted. Invoice is added against this job card.')];
            }

            $deleteJobCard->delete();
        }

        return ['pass' => true];
    }

    public function singleJobCard(?int $id, array $with = null): ?object
    {
        $query = JobCard::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    private function filteredQuery(object $request, object $query)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('service_job_cards.branch_id', null);
            } else {

                $query->where('service_job_cards.branch_id', $request->branch_id);
            }
        }

        if ($request->brand_id) {

            $query->where('service_job_cards.brand_id', $request->brand_id);
        }

        if ($request->device_id) {

            $query->where('service_job_cards.device_id', $request->device_id);
        }

        if ($request->device_model_id) {

            $query->where('service_job_cards.device_model_id', $request->device_model_id);
        }

        if ($request->service_type) {

            $query->where('service_job_cards.service_type', $request->service_type);
        }

        if ($request->user_id) {

            $query->where('service_job_cards.created_by_id', $request->created_by_id);
        }

        if ($request->customer_account_id) {

            $query->where('service_job_cards.customer_account_id', $request->customer_account_id);
        }

        if ($request->status_id) {

            $query->where('service_job_cards.status_id', $request->status_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('service_job_cards.date_ts', $date_range); // Final
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('service_job_cards.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
