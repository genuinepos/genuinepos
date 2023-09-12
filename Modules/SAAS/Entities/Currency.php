<?php

namespace Modules\SAAS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'exchange_rate', 'symbol'];
}
