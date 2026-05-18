<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryMaterial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id','material_code','name','unit','reorder_qty','opening_stock',
    ];

    protected $casts = [
        'reorder_qty'  => 'decimal:3',
        'opening_stock'=> 'decimal:3',
    ];

    public function project()      { return $this->belongsTo(Project::class); }
    public function transactions() { return $this->hasMany(InventoryTransaction::class, 'material_id'); }

    public function getCurrentStockAttribute(): float
    {
        $in  = $this->transactions()->whereIn('txn_type', ['purchase'])->sum('quantity');
        $out = $this->transactions()->whereIn('txn_type', ['consumption', 'wastage'])->sum('quantity');
        return (float)($this->opening_stock ?? 0) + (float)$in - (float)$out;
    }

    public function getStockStatusAttribute(): string
    {
        $stock  = $this->current_stock;
        $reorder = (float)($this->reorder_qty ?? 0);
        if ($stock <= 0)            return 'out';
        if ($reorder > 0 && $stock <= $reorder) return 'low';
        return 'ok';
    }
}
