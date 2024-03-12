<?php

namespace App\Services\Communication\Email;


use App\Models\Communication\Email\EmailBody;

use Yajra\DataTables\Facades\DataTables;

// Import Log facade for debugging

class EmailMenualService
{
    public function index()
    {
        return view('communication.email.menual.index');

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
