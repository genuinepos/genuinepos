<?php

namespace App\Models\Contacts;

use App\Enums\ContactType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $casts = [
        'type' => ContactTypeType::class
    ];
}
