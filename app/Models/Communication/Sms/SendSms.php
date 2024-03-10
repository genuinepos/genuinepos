<?php

namespace App\Models\Communication\Sms;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SendSms extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sms_send';

    protected $guarded = [];
}
