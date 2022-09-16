<?php

namespace App\Entities;

use App;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use \SleepingOwl\Admin\Traits\OrderableModel;
   

   public function getHeightAttribute($value)
    {
        return (int)$value;
    }

    public function getWeightAttribute($value)
    {
        return (int)$value;
    }

    public function getTitleAttribute()
    {
        $locale = App::getLocale();
        $column = "title_" . $locale;
        return $this->{$column};
    }

    public function getSlugAttribute()
    {
        $locale = App::getLocale();
        $column = "slug_" . $locale;
        return $this->{$column};
    }

    public function getContentAttribute()
    {
        $locale = App::getLocale();
        $column = "text_description_" . $locale;
        return $this->{$column};
    }

    public function getDopDescrAttribute()
    {
        $locale = App::getLocale();
        $column = "dop_description_" . $locale;
        return $this->{$column};
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    /*
     * First image
     */
    public function firstImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function productAttributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')
            ->withPivot('value');
    }
    

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}

