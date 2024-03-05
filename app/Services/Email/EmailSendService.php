<?php

namespace App\Services\Email;

use App\Models\Communication\SendEmail;

class EmailSendService
{
    public function index($request)
    {

        $data = SendEmail::query()->orderBy('id', 'desc');

        if ($request->ajax()) {
            $data = $data->where('status', $request->status)->get();
            return response()->json(['data' => $data]);
        }

        return view('communication.email.send.index', compact('data'));

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
        $emailBody = SendEmail::findOrFail($id);

        $deleted = $emailBody->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Email deleted successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to delete email'];
        }
    }

}
