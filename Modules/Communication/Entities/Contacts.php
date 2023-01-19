<?php

namespace Modules\Communication\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;
    protected $table = 'contacts';

    public function group()
    {
        return $this->belongsTo(ContactGroup::class, 'group_id');
    }
}
