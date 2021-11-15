<?php

namespace App\Models\Manufacturing;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function ingredients()
    {
        return $this->hasMany(ProductionIngredient::class);
    }
}
