<?php

namespace App\Http\Controllers;
use App;
use App\Entities\Review;
use Illuminate\Http\Request;
use App\Entities\Product;
use App\Entities\Attribute;
use App\Entities\AttributeValue;
use App\Libraries\Mobile_Detect;

class ProductController extends Controller
{
    protected $locale;
    protected $mobile;

    public function __construct()
    {
        $this->locale = App::getLocale();
    }

    public function index($slug)
    {
        $slug = trim($slug);
        $detect = new Mobile_Detect;
        $mobile = $detect->isMobile();
        $lng = App::getLocale();

        $product = Product::active()->where('slug_uk', '=', $slug)
            ->orWhere('slug_ru', '=', $slug)
            ->orWhere('slug_en', '=', $slug)
            ->orWhere('slug_pl', '=', $slug)
            ->first();
        $reviews =  Review::where('active', 1)->get();
        

        $parent_cat = '';
        if(!is_null($product->category->parent_id)){
            $parent_cat = App\Entities\Category::findOrFail($product->category->parent_id);
        }

        $attributes = Attribute::all();
        $values_id = $product->attributes->pluck('value');
        $values = AttributeValue::whereIn('id', $values_id)->get()->toArray();

        return view('product', compact('product','reviews', 'parent_cat',
            'attributes','values', 'mobile', 'lng'));
    }

  
}

