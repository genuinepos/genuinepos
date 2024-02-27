<?php

namespace Modules\SAAS\Entities;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class EmailSettings extends Model
{

    use SoftDeletes;

    protected $table = 'email_settings';

    protected $guarded = [];
}
