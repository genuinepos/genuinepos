<?php

namespace App\Http\Controllers\communication\sms;

use App\Http\Controllers\Controller;
use App\Models\Communication\Sms\SmsServer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SmsServerController extends Controller
{

    public function index(Request $request)
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

        return view('communication.sms.index', compact('data'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'server_name' => 'required',
            'host' => 'required',
            'api_key' => 'required',
            'sender_id' => 'required',
            'status' => 'required',
        ]);

        if ($request->status == 1) {
            SmsServer::where('status', 1)->update([
                'status' => 0,
            ]);
        }

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
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'server_name' => 'required',
            'host' => 'required',
            'api_key' => 'required',
            'sender_id' => 'required',
            'status' => 'required',
        ]);

        if ($request->status == 1) {
            SmsServer::where('status', 1)->update([
                'status' => 0,
            ]);
        }

        $SmsServer = SmsServer::findOrFail($id);

        $SmsServer->update($validatedData);

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
