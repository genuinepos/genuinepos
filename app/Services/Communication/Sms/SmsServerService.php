<?php

namespace App\Services\Communication\Sms;


use App\Models\Communication\Sms\SmsServer;
use Yajra\DataTables\Facades\DataTables;

class SmsServerService
{
    public function index($request)
    {

        $data = SmsServer::query()->orderBy('id', 'desc');

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? "Active" : "Inactive";
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="#" class="edit-btn btn btn-success btn-sm text-white" title="Edit" data-id="' . $row->id . '"><span class="fas fa-edit"></span></a>';
                    $deleteBtn = '<a onclick="return confirm("are you sure")" href="#" class="delete-btn btn btn-danger btn-sm text-white ms-2" title="Delete" data-id="' . $row->id . '"><span class="fas fa-trash"></span></a>';
                    return $editBtn . $deleteBtn;
                })
                ->make(true);
        }

        return view('communication.Sms.index', compact('data'));
    }

    public function store($data)
    {
        $validatedData = validator($data, [
            'server_name' => 'required',
            'host' => 'required',
            'port' => 'required|numeric',
            'user_name' => 'required',
            'password' => 'required',
            'encryption' => 'required',
            'address' => 'required|Sms',
            'name' => 'required',
            'status' => 'required',
        ])->validate();

        $SmsServer = SmsServer::create($validatedData);

        if ($SmsServer) {
            return response()->json(['status' => 'success', 'message' => 'Sms server added successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to add Sms server'], 500);
        }

    }

    public function edit($id)
    {
        $data = SmsServer::findOrFail($id);
        return $data;
    }

    public function update($id, $data)
    {
        $validatedData = validator($data, [
            'server_name' => 'required',
            'host' => 'required',
            'port' => 'required|numeric',
            'user_name' => 'required',
            'password' => 'required',
            'encryption' => 'required',
            'address' => 'required|Sms',
            'name' => 'required',
            'status' => 'required',
        ])->validate();

        $SmsServer = SmsServer::findOrFail($id);

        $updated = $SmsServer->update($validatedData);

        if ($SmsServer) {
            return response()->json(['status' => 'success', 'message' => 'Sms server updated successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Sms server'], 500);
        }

    }

    public function destroy($id)
    {
        $SmsServer = SmsServer::findOrFail($id);

        $SmsServer->delete();

        if ($SmsServer) {
            return response()->json(['status' => 'success', 'message' => 'Sms server deleted successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update deleted server'], 500);
        }

    }

}
