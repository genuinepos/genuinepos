<?php

namespace App\Models\Communication\Email;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EmailBody extends Model
{
    use SoftDeletes;

    protected $guarded = [];
}
