<?php

namespace App\Models\Communication\Email;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SendEmail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'email_send';

    protected $guarded = [];
}
