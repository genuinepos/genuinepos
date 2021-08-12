<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpanseCategory;

class ExpanseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
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

        $codePrefix = ''; 
        if (!$request->code) {
            $name = explode(' ', $request->name);
            for ($i= 0; $i < count($name); $i++) {
                $prefix = str_split($name[$i])[0];
                $codePrefix .= $prefix;
            }
        }
        
        ExpanseCategory::insert([
            'name' => $request->name,
            'code' => $request->code ? $request->code : $codePrefix,
        ]);

        return response()->json('Expanse category created successfully');
    }

    // Update expanse category
    public function update(Request $request)
    {
        //return $request->all();
        $this->validate($request, [
            'name' => 'required',
        ]);

        $codePrefix = ''; 
        if (!$request->code) {
            $name = explode(' ', $request->name);
            for ($i= 0; $i < count($name); $i++) {
                $prefix = str_split($name[$i])[0];
                $codePrefix .= $prefix;
            }
        }

        $updateCategory = ExpanseCategory::where('id', $request->id)->first();
        $updateCategory->update([
            'name' => $request->name,
            'code' => $request->code ? $request->code : $codePrefix,
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
