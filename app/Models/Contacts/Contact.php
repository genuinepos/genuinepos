<?php

namespace App\Models\Contacts;

use App\Enums\ContactType;
use App\Models\CustomerGroup;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contacts\ContactOpeningBalance;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;
    protected $casts = [
        'type' => ContactType::class
    ];

    public function openingBalances()
    {
        return $this->hasMany(ContactOpeningBalance::class, 'contact_id');
    }

    public function openingBalance()
    {
        return $this->hasOne(ContactOpeningBalance::class, 'contact_id')->where('branch_id', auth()->user()->branch_id);
    }

    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class, 'contact_id');
    }
}
