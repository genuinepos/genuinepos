<?php

namespace App\Models\Advertisement;

use App\Models\Branches\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advertisement extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function attachments()
    {
        return $this->hasMany(AdvertiseAttachment::class, 'advertisement_id', 'id');
    }
}
