<?php

namespace App\Models\TransferStocks;

use App\Models\Branches\Branch;
use App\Models\Setups\Warehouse;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TransferStock extends Model
{
    public function transferStockProducts()
    {
        return $this->hasMany(TransferStockProduct::class, 'transfer_stock_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function senderBranch()
    {
        return $this->belongsTo(Branch::class, 'sender_branch_id');
    }

    public function receiverBranch()
    {
        return $this->belongsTo(Branch::class, 'receiver_branch_id');
    }

    public function senderWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'sender_warehouse_id');
    }

    public function receiverWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'receiver_warehouse_id');
    }

    public function sendBy()
    {
        return $this->belongsTo(User::class, 'send_by_id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by_id');
    }
}
