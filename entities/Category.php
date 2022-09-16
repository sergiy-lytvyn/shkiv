<?php

namespace App\Entities;
use App;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Product;

class Category extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'options' => App\Custom\Select::class,
    ];

    /**
     * @return mixed
     */
    public function getNameAttribute()
    {
        $locale = App::getLocale();
        $column = "name_" . $locale;
        return $this->{$column};
    }

    public function getSlugAttribute()
    {
        $locale = App::getLocale();
        $column = "slug_" . $locale;
        return $this->{$column};
    }

    public function setSlugAttribute()
    {
        $locale = App::getLocale();
        $column = "slug_" . $locale;
        return $this->attributes[$column];
    }

    public function getContentAttribute()
    {
        $locale = App::getLocale();
        $column = "content_" . $locale;
        return $this->{$column};
    }

    public function getDescriptionAttribute()
    {
        $locale = App::getLocale();
        $column = "description_" . $locale;
        return $this->{$column};
    }

    public function getSeoTitleAttribute()
    {
        $locale = App::getLocale();
        $column = "seo_title_" . $locale;
        return $this->{$column};
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function children() {
        return $this->hasMany(Category::class, 'parent_id' );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id' );
    }


    /**
     * @param $query
     * @return mixed
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


}

