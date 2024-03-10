<?php

namespace App\Http\Controllers\communication\email;

use App\Http\Controllers\Controller;
use App\Models\Communication\Email\EmailServer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmailServerController extends Controller
{

    public function index(Request $request)
    {

        $data = EmailServer::query()->orderBy('id', 'desc');

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

        return view('communication.email.index', compact('data'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'server_name' => 'required',
            'host' => 'required',
            'port' => 'required|numeric',
            'user_name' => 'required',
            'password' => 'required',
            'encryption' => 'required',
            'address' => 'required|email',
            'name' => 'required',
            'status' => 'required',
        ]);

        if ($request->status == 1) {
            EmailServer::where('status', 1)->update([
                'status' => 0,
            ]);
        }

        $emailServer = EmailServer::create($validatedData);

        if ($emailServer) {
            return response()->json(['status' => 'success', 'message' => 'Email server added successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to add email server'], 500);
        }
    }

    public function edit($id)
    {
        $data = EmailServer::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'server_name' => 'required',
            'host' => 'required',
            'port' => 'required|numeric',
            'user_name' => 'required',
            'password' => 'required',
            'encryption' => 'required',
            'address' => 'required|email',
            'name' => 'required',
            'status' => 'required',
        ]);

        //if request staus is active than previous all settings data will be inactive
        if ($request->status == 1) {
            EmailServer::where('status', 1)->update([
                'status' => 0,
            ]);
        }

        $emailServer = EmailServer::findOrFail($id);

        $emailServer->update($validatedData);

        if ($emailServer) {
            return response()->json(['status' => 'success', 'message' => 'Email server updated successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update email server'], 500);
        }
    }

    public function destroy($id)
    {
        $emailServer = EmailServer::findOrFail($id);

        $emailServer->delete();

        if ($emailServer) {
            return response()->json(['status' => 'success', 'message' => 'Email server deleted successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update deleted server'], 500);
        }

    }

}
