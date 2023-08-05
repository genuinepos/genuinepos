<?php

namespace Modules\Communication\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationContactGroup extends Model
{
    use HasFactory;
    protected $table = 'communication_contact_groups';
}
