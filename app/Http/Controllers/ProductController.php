<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function getProductIdsByBrand(Request $request)
    {
        $mainBrands = empty($request->query('mainBrands')) ? [] : explode(',', $request->query('mainBrands'));
        $childBrands = empty($request->query('childBrands')) ? [] : explode(',', $request->query('childBrands'));
        $childArray = [];

        if (count($childBrands) > 0) {
            foreach ($childBrands as $cb) {
                $mapping_results = DB::table('brands_products')->whereRaw('`Brand` LIKE ?', ['%' . $cb . '%'])->get();

                if (empty($mapping_results)) {
                    return response()->json(['message' => 'No mapping found for the given brand'], 404);
                }

                foreach ($mapping_results as $result) {
                    $pid = $result->productIds;
                    array_push($childArray, $pid);
                }   
            }
            return array_values($childArray);
        }

        foreach ($mainBrands as $mb) {
            $mapping_results = DB::table('brands_mapping')->whereRaw('`Category` LIKE ?', ['%' . $mb . '%'])->get();

            if (empty($mapping_results)) {
                return response()->json(['message' => 'No mapping found for the given brand'], 404);
            }

            foreach ($mapping_results as $result) {
                $brandName = $result->Brands;

                $mapping_results2 = DB::table('brands_products')->whereRaw('`Brand` LIKE ?', ['%' . $brandName . '%'])->get();

                if (empty($mapping_results2)) {
                    return response()->json(['message' => 'No mapping found for the given brand'], 404);
                }

                foreach ($mapping_results2 as $result2) {
                    $pid = $result2->productIds;
                    array_push($childArray, $pid);
                }
            }   
        }

        return array_values($childArray);
    }

    public function getProductIdsByColor(Request $request)
    {
        $colors = empty($request->query('colors')) ? [] : explode(',', $request->query('colors'));
        $sizes = empty($request->query('sizes')) ? [] : explode(',', $request->query('sizes'));
        $collection_id = $request->query('collection_id');
        $colorsArray = [];
        $sizesArray = [];
        $finalIdsArray = [];

        $allSizeProductIds = [];
        $allColorProductIds = [];

        if (count($sizes) > 0) {
            foreach ($sizes as $sz) {
                // $mapping_results = DB::table('mappings')->where('Keys', 'LIKE', '%' . $sz . '%')->get();
                $mapping_results = DB::table('mappings')->whereRaw('`Keys` LIKE ?', ['%' . $sz . '%'])->get();

                if (empty($mapping_results)) {
                    return response()->json(['message' => 'No mapping found for the given size'], 404);
                }

                $values = json_decode(str_replace("'", '"', $mapping_results[0]->Values));
                $sizesArray = array_merge($sizesArray, $values);
            }

            $size_product_ids = [];

            foreach ($sizesArray as $value) {
                $product_color_results = DB::table('product_colors')->where('Colors', 'LIKE', '%' . $value . '%')->get();
                foreach ($product_color_results as $product) {
                    $size_product_ids[$product->ID] = $product->ID;
                }
            }

            if (count($size_product_ids)) {
                $allSizeProductIds = array_values($size_product_ids);
            }
        }

        for ($g = 0; $g < count($colors); $g++) {
            $color = $colors[$g];
            $mapping_results = DB::table('mappings')->where('Keys', 'LIKE', '%' . $color . '%')->get();

            if (empty($mapping_results)) {
                return response()->json(['message' => 'No mapping found for the given color'], 404);
            }

            $values = json_decode(str_replace("'", '"', $mapping_results[0]->Values));
            $colorsArray = array_merge($colorsArray, $values);
        }

        $color_product_ids = [];

        foreach ($colorsArray as $value) {
            $product_color_results = DB::table('product_colors')->where('Colors', 'LIKE', '%' . $value . '%')->get();

            foreach ($product_color_results as $product) {
                $color_product_ids[$product->ID] = $product->ID;
            }
        }

        if (count($color_product_ids)) {
            $allColorProductIds = array_values($color_product_ids);
        }

        if (count($sizes) > 0) {
            if (count($allColorProductIds)) {
                $finalIdsArray = array_intersect($allSizeProductIds, $allColorProductIds);
            }
            $finalIdsArray = $allSizeProductIds;
        } else if (count($colors) > 0) {
            $finalIdsArray = $allColorProductIds;
        }

        $product_ids = $finalIdsArray;

        if (empty($product_ids)) {
            return response()->json(['message' => 'No products found for the given color'], 404);
        }

        $allProductIds = array_values($product_ids);

        $resultsOfQuery = DB::table('product_collection')->where('Collections', 'LIKE', '%' . $collection_id . '%')->get();
        $ids = array_map(function ($obj) {
            return $obj->ID;
        }, $resultsOfQuery->toArray());

        return array_values(array_intersect($allProductIds, $ids));
    }
}