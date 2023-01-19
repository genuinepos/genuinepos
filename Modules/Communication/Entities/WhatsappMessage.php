<?php

namespace Modules\Communication\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    use HasFactory;
    protected $table = 'whatsapp_messages';


}
