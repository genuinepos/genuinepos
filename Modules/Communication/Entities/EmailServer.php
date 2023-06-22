<?php

namespace Modules\Communication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailServer extends Model
{
    use HasFactory;

    protected $fillable = ['server_name', 'host', 'port', 'user_name', 'password', 'encryption', 'address', 'name'];

    // protected static function newFactory()
    // {
    //     return \Modules\Communication\Database\factories\EmailServerFactory::new();
    // }
}
