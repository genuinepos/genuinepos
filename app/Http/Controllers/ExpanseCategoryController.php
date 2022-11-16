<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpanseCategory;
use App\Utils\Util;
use Illuminate\Support\Facades\DB;

class ExpanseCategoryController extends Controller
{
    
    public function __construct()
    {
        
    }

    // Category main page/index page
    public function index()
    {
        return view('expanses.categories.index');
    }

    // Get all category by ajax
    public function allCategory()
    {
        $categories = ExpanseCategory::orderBy('id', 'DESC')->get();
        return view('expanses.categories.ajax_view.category_list', compact('categories'));
    }

    // Store expanse category
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $lastExpenseCategory = DB::table('expanse_categories')->orderBy('id', 'desc')->first();
        $code = 0;
        if ($lastExpenseCategory) {
            $code = ++$lastExpenseCategory->id;
        }else {
            $code = 1;
        }

        ExpanseCategory::insert([
            'name' => $request->name,
            'code' => $request->code ? $request->code : $code,
        ]);

        return response()->json('Expanse category created successfully');
    }

    // Update expanse category
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $updateCategory = ExpanseCategory::where('id', $request->id)->first();

        $updateCategory->update([
            'name' => $request->name,
        ]);

        return response()->json('Expanse category updated successfully');
    }

    public function delete(Request $request, $categoryId)
    {
        //return $categoryId;
        $deleteCategory = ExpanseCategory::find($categoryId);

        if (!is_null($deleteCategory)) {

            $deleteCategory->delete();
        }
        return response()->json('Expanse category deleted successfully');
    }
}