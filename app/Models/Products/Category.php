<?php

namespace App\Models\Products;

use App\Models\BaseModel;

class Category extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id')->where('number_of_sale', '>', 0);
    }
}
