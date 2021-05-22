<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $table = 'hrm_holidays';

    protected $fillable = ['holiday_name','start_date','end_date','shop_name','notes'];

}
