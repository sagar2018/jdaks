<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'project_id','material_id','txn_type','date',
        'quantity','rate','amount','notes','created_by',
    ];

    protected $casts = [
        'date'     => 'date',
        'quantity' => 'decimal:3',
        'rate'     => 'decimal:2',
        'amount'   => 'decimal:2',
    ];

    public function project()  { return $this->belongsTo(Project::class); }
    public function material() { return $this->belongsTo(InventoryMaterial::class, 'material_id'); }
}
