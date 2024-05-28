<?php

namespace App\Services\Communication\Sms;


use App\Models\Communication\Sms\SmsBody;

use Yajra\DataTables\Facades\DataTables;

class SmsBodyService
{
    public function index($request)
    {
        $data = SmsBody::query()->orderBy('id', 'desc');

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_important', function ($row) {
                    return $row->is_important == 1 ? "Important" : "UnImportant";
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="#" class="edit-btn btn btn-success btn-sm text-white" title="Edit" data-id="' . $row->id . '"><span class="fas fa-edit"></span></a>';
                    $deleteBtn = '<a onclick="return confirm("are you sure")" href="#" class="delete-btn btn btn-danger btn-sm text-white ms-2" title="Delete" data-id="' . $row->id . '"><span class="fas fa-trash"></span></a>';
                    return $editBtn . $deleteBtn;

                })
                ->make(true);
        }

        return view('communication.sms.body.index', compact('data'));

    }

    public function store($data)
    {
        $validatedData = validator($data, [
            'format' => 'required',
            'subject' => 'required',
            'body' => 'required',
            'is_important' => 'required',
        ])->validate();

        $SmsBody = SmsBody::create($validatedData);

        if ($SmsBody) {
            return ['status' => 'success', 'message' => 'Sms body added successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to add Sms body'];
        }
    }

    public function edit($id)
    {
        $data = SmsBody::findOrFail($id);
        return $data;
    }

    public function update($id, $data)
    {
        $validatedData = validator($data, [
            'format' => 'required',
            'subject' => 'required',
            'body' => 'required',
            'is_important' => 'required',
        ])->validate();

        $SmsBody = SmsBody::findOrFail($id);

        $updated = $SmsBody->update($validatedData);

        if ($updated) {
            return ['status' => 'success', 'message' => 'Sms body updated successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to update Sms body'];
        }
    }

    public function destroy($id)
    {
        $SmsBody = SmsBody::findOrFail($id);

        $deleted = $SmsBody->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Sms body deleted successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to delete Sms body'];
        }
    }

}
