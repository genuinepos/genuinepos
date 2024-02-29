<?php

namespace Modules\SAAS\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SAAS\Database\factories\EmailVerificationFactory;

class EmailVerification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): EmailVerificationFactory
    {
        //return EmailVerificationFactory::new();
    }
}
