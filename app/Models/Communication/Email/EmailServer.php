<?php

namespace App\Models\Communication\Email;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailServer extends Model
{
    use HasFactory;

    protected $table = 'email_servers';

    protected $guarded = [];
}
