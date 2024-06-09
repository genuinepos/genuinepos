<?php

namespace App\Services\Services;

use App\Utils\FileUploader;
use App\Models\Services\JobCard;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class JobCardService
{
    function addJobCard(object $request, object $codeGenerator, ?string $jobCardNoPrefix = null): object
    {
        $jobNo = $codeGenerator->generateMonthWise(table: 'service_job_cards', column: 'job_no', prefix: $jobCardNoPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $addJobCard = new JobCard();
        $addJobCard->branch_id = auth()->user()->branch_id;
        $addJobCard->job_no = $jobNo;
        $addJobCard->customer_account_id = $request->customer_account_id;
        $addJobCard->service_type = $request->service_type;
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

        $addJobCard->technical_comment = $request->technical_comment;
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

            $dir = public_path('uploads/' . tenant('id') . '/' . 'services/documents/');

            $addJobCard->document = FileUploader::upload($request->file('document'), $dir);
        }

        $addJobCard->save();

        return $addJobCard;
    }

    public function singleJobCard(int $id, array $with = null): ?object
    {
        $query = JobCard::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
