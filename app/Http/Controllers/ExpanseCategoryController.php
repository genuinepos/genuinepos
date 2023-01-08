<?php

namespace App\Http\Controllers;

use App\Utils\Util;
use Illuminate\Http\Request;
use App\Models\ExpanseCategory;
use App\Utils\InvoiceVoucherRefIdUtil;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExpanseCategoryController extends Controller
{
    protected $invoiceVoucherRefIdUtil;
    public function __construct(InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil)
    {
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
    }

    // Category main page/index page
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $expenseCategories = DB::table('expanse_categories')->orderBy('name', 'asc')->get();

            return DataTables::of($expenseCategories)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('expenses.categories.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('expenses.categories.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                    $html .= '</div>';

                    return $html;
                })->rawColumns(['action'])->smart(true)->make(true);
        }

        return view('expanses.categories.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:expanse_categories,name',
            'code' => 'nullable|unique:expanse_categories,code',
        ]);

        $addCategory = ExpanseCategory::create([
            'name' => $request->name,
            'code' => $request->code ? $request->code : str_pad($this->invoiceVoucherRefIdUtil->getLastId('hrm_leaves'), 4, "0", STR_PAD_LEFT),
        ]);

        return $addCategory;
    }

    public function edit($id)
    {
        $expenseCategory = DB::table('expanse_categories')->where('id', $id)->first();
        return view('expanses.categories.ajax_view.edit', compact('expenseCategory'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:expanse_categories,name,'.$id,
        ]);

        $updateCategory = ExpanseCategory::where('id', $id)->update([
            'name' => $request->name,
        ]);

        return response()->json('Expense category updated successfully.');
    }

    public function delete(Request $request, $id)
    {
        $deleteCategory = ExpanseCategory::with('expenseDescriptions')->where('id', $id)->first();

        if (count($deleteCategory->expenseDescriptions) > 0) {

            return response()->json(['errorMsg' => 'Expense category can\'t be deleted. This Category associated with expense.']);
        }

        if (!is_null($deleteCategory)) {

            $deleteCategory->delete();
        }

        DB::statement('ALTER TABLE expanse_categories AUTO_INCREMENT = 1');

        return response()->json('Expense category deleted successfully.');
    }
}
