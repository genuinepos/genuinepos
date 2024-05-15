<?php

namespace Modules\SAAS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SAAS\Entities\EmailSettings;
use Modules\SAAS\Http\Requests\EmailSettingsStoreRequest;
use Yajra\DataTables\Facades\DataTables;

class EmailSettingsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */

    public function index(Request $request)
    {

        // $this->authorize('users_index');

        $email = EmailSettings::query()->orderBy('created_at', 'desc');

        if ($request->ajax()) {
            return DataTables::of($email)
                ->addIndexColumn()
                ->addColumn('mail_active', function ($row) {
                    return $row->mail_active == 1 ? "Active" : "InActive";
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('saas.email-settings.edit', $row->id) . '" class="px-2 edit-btn btn btn-primary btn-sm text-white" title="Edit"><span class="fas fa-edit pe-1"></span>Edit</a>';
                    $html .= '<a href="' . route('saas.email-settings.destroy', $row->id) . '" class="px-2 trash-btn btn btn-danger btn-sm text-white ms-2" id="trashUser" title="Trash"><span class="fas fa-trash pe-1"></span>Trash</a>';
                    $html .= '</div>';
                    return $html;
                })
                ->make(true);
        }

        return view('saas::settings.email.index', compact('email'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */

    public function create()
    {

        //$this->authorize('users_create');

        return view('saas::settings.email.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */

    public function store(EmailSettingsStoreRequest $request)
    {

        EmailSettings::create($request->all());

        return redirect()->route('saas.email-settings.index')->with('success', 'Email Settings has been created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */

    public function edit($id)
    {
        $settings = EmailSettings::findOrfail($id);

        return view('saas::settings.email.edit', compact('settings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */

    public function update(EmailSettingsStoreRequest $request, $id)
    {
        // $this->authorize('users_update');
        $settings = EmailSettings::findOrfail($id);

        $settings->update($request->all());

        return redirect()->route('saas.email-settings.index')->with('success', 'Email Settings has been updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */

    public function destroy($id)
    {

        // $this->authorize('users_destroy');
        $settings = EmailSettings::findOrfail($id);

        $settings->delete();

        return redirect()->route('saas.email-settings.index')->with('success', 'Email Settings has been deleted successfully');
    }
}
