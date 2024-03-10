<?php

namespace App\Models\Communication\Sms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsServer extends Model
{
    use HasFactory;

    protected $table = 'sms_servers';

    protected $guarded = [];
}
