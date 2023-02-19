<?php

namespace App\Http\Controllers;

use App;
use DB;
use Cart;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


    public function index(Request $request, $slug)
    {
        $category =  Category::where('slug_uk', '=', $slug)
            ->orWhere('slug_ru', '=', $slug)
            ->orWhere('slug_en', '=', $slug)
            ->orWhere('slug_pl', '=', $slug)
            ->first();

        $children = Category::with(['products' => function($q){
            $q->where('status', 1);
            $q->orderBy(DB::raw("CONVERT(price, DECIMAL(4,2))"), 'asc');
        }])->where('parent_id', $category->id)
            ->get();


        $values = AttributeValue::get()->toArray();


        return view('catalog', compact('category', 'children', 'values'));
    }

    public function childrenCategory($slug, $slug_sub, Request $request)
    {
        $lng = App::getLocale();

        $category = Category::with(['products' => function($q) use ($lng){
            $q->where('status', 1);
            $q->orderBy('price', 'asc');
        }])->where('slug_uk', '=', $slug_sub)
            ->orWhere('slug_ru', '=', $slug_sub)
            ->orWhere('slug_en', '=', $slug_sub)
            ->orWhere('slug_pl', '=', $slug_sub)
            ->first();

        $products = Product::with('attributes')
            ->where('category_id', $category->id)
            ->where('status', 1)
            ->orderBy(DB::raw("CONVERT(price, DECIMAL(4,2))"), 'asc')
            ->get();

        $values = AttributeValue::get()->toArray();

        return view('category', compact('category', 'values', 'products'));
    }

   
}
