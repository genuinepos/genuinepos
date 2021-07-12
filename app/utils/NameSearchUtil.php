<?php
namespace App\Utils;
use App\Models\Product;

class NameSearchUtil
{
    public function nameSearching($keyword)
    {
        $namedProducts = '';
        $nameSearch = Product::with(['product_variants', 'tax', 'unit'])
            ->where('name', 'LIKE',  $keyword . '%')
            ->where('status', 1)->orderBy('id', 'desc')
            ->get();

        if (count($nameSearch) > 0) {
            $namedProducts = $nameSearch;
        }

        $priceSearch = Product::with(['product_variants', 'tax', 'unit'])
            ->where('product_price', 'like', "$keyword%")
            ->where('status', 1)
            ->get();

        if (count($priceSearch) > 0) {
            $namedProducts = $priceSearch;
        }

        if ($namedProducts && count($namedProducts) > 0) {
            return response()->json(['namedProducts' => $namedProducts]);
        } else {
            return response()->json(['NotFoundMsg' => 'Not Found.']);
        }
    }  
}