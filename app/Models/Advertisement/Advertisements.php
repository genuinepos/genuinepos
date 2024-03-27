<?php

namespace App\Models\Advertisement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisements extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function attachments(){
        return $this->hasMany(AdvertiseAttachment::class,'advertisement_id','id');
    }
}
