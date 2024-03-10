<?php

namespace App\Models\Communication\Sms;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SmsBody extends Model
{
    use SoftDeletes;

    protected $guarded = [];
}
