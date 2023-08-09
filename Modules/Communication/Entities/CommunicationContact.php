<?php

namespace Modules\Communication\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationContact extends Model
{
    use HasFactory;

    protected $table = 'communication_contacts';

    public function group()
    {
        return $this->belongsTo(CommunicationContactGroup::class);
    }
}
