<?php

namespace App\Services\Setups;

class InvoiceLayoutService
{
    public function invoiceLayoutListTable($request)
    {
        $layouts = DB::table('invoice_layouts')->orderBy('id', 'DESC')->select('id', 'name', 'is_default', 'is_header_less')->get();

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('invoice_layout.branch_id', null);
            } else {

                $query->where('invoice_layout.branch_id', $request->branch_id);
            }
        }

        return DataTables::of($layouts)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {

                return $row->name.' '.($row->is_default == 1 ? '<span class="badge bg-primary">'.__('Default').'</span>' : '');
            })
            ->editColumn('is_header_less', function ($row) {
                return $row->is_header_less == 1 ? '<span class="badge bg-info">Yes</span>' : '<span class="badge bg-secondary">None</span>';
            })
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                $html .= '<a href="'.route('invoices.layouts.edit', [$row->id]).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';

                if ($row->is_default == 0) {

                    $html .= '<a href="'.route('invoices.layouts.delete', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';

                    $html .= '<a href="'.route('invoices.layouts.set.default', [$row->id]).'" class="bg-primary text-white rounded pe-1" id="set_default_btn">
                    '.__('Set As Default').'
                    </a>';
                }
                $html .= '</div>';

                return $html;
            })

            ->rawColumns(['action', 'name', 'is_header_less'])
            ->make(true);
    }
}
