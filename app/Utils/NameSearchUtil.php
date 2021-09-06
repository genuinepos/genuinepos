<?php
namespace App\Utils;
use App\Models\Product;

class NameSearchUtil
{
    public function nameSearching($keyword)
    {
        $namedProducts = '';
        $namedProducts = Product::with(['product_variants', 'tax', 'unit'])
            ->where('name', 'LIKE',  $keyword . '%')
            ->where('status', 1)->orderBy('id', 'desc')
            ->get();

        if ($namedProducts && count($namedProducts) > 0) {
            return response()->json(['namedProducts' => $namedProducts]);
        } else {
            return response()->json(['NotFoundMsg' => 'Not Found.']);
        }
    }  
}