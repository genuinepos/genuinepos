<?php

namespace App\Services\Email;

use App\Models\Communication\EmailBody;

use Yajra\DataTables\Facades\DataTables;

// Import Log facade for debugging

class EmailBodyService
{
    public function index($request)
    {
        $data = EmailBody::query()->orderBy('id', 'desc');

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

        return view('communication.email.body.index', compact('data'));

    }

    public function store($data)
    {
        $validatedData = validator($data, [
            'format' => 'required',
            'subject' => 'required',
            'body' => 'required',
            'is_important' => 'required',
        ])->validate();

        $emailBody = EmailBody::create($validatedData);

        if ($emailBody) {
            return ['status' => 'success', 'message' => 'Email body added successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to add email body'];
        }
    }

    public function edit($id)
    {
        $data = EmailBody::findOrFail($id);
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

        $emailBody = EmailBody::findOrFail($id);

        $updated = $emailBody->update($validatedData);

        if ($updated) {
            return ['status' => 'success', 'message' => 'Email body updated successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to update email body'];
        }
    }

    public function destroy($id)
    {
        $emailBody = EmailBody::findOrFail($id);

        $deleted = $emailBody->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Email body deleted successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to delete email body'];
        }
    }

}
