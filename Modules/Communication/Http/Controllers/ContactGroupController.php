<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Communication\Entities\CommunicationContactGroup;
use Yajra\DataTables\Facades\DataTables;

class ContactGroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = CommunicationContactGroup::all();
        if ($request->ajax()) {
            return DataTables::of($groups)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="javascript:;" class="action-btn c-edit" id="edit_group" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="'.route('communication.contacts.group.destroy', $row->id).'" class="action-btn c-delete" id="delete_group" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('communication.contacts.group.edit', $row->id);
                    },
                ])
                ->rawColumns(['action'])
                ->smart(true)
                ->make(true);
        }

        return view('communication::contacts.type.index', [
            'groups' => $groups,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $groups = new CommunicationContactGroup();
        $groups->name = $request->name;
        $groups->save();

        return response()->json('Group name created successfully');
    }

    public function edit(Request $request)
    {
        $groups = CommunicationContactGroup::find($request->id);

        return view('communication::contacts.type.ajax_view_unit.edit_modal_body', compact('groups'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $groups = CommunicationContactGroup::find($request->id);
        $groups->name = $request->name;
        $groups->save();

        return response()->json('Group updated successfully');
    }

    public function destroy(Request $request)
    {

        $groups = CommunicationContactGroup::find($request->id);

        $groups->delete();

        return response()->json('Group delete successfully');
    }
}
