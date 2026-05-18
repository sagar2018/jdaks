<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','package','location','contractor',
        'chainage_start','chainage_end',
        'start_date','end_date','deadline','bid_date',
        'bid_type','bid_pct','bit_grade','dual_lane','notes',
        'created_by',
    ];

    protected $casts = [
        'start_date'    => 'date',
        'end_date'      => 'date',
        'deadline' => 'date',
        'bid_date'      => 'date',
        'dual_lane'     => 'boolean',
        'chainage_start'=> 'decimal:3',
        'chainage_end'  => 'decimal:3',
        'bid_pct'       => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────────────────
    public function users()               { return $this->belongsToMany(User::class, 'project_user')->withPivot(['assigned_by','assigned_at'])->withTimestamps(); }
    public function boqItems()            { return $this->hasMany(BoqItem::class); }
    public function progressEntries()     { return $this->hasMany(ProgressEntry::class); }
    public function billingConfig()       { return $this->hasOne(BillingConfig::class); }
    public function raBills()             { return $this->hasMany(RaBill::class); }
    public function dsrItems()            { return $this->hasMany(DsrItem::class); }
    public function inventoryMaterials()  { return $this->hasMany(InventoryMaterial::class); }
    public function pvIndices()           { return $this->hasMany(PvIndex::class); }
    public function expenses()            { return $this->hasMany(Expense::class); }

    // ── Computed Attributes ────────────────────────────────────
    public function getTotalBoqAmountAttribute(): float
    {
        return (float) $this->boqItems()->sum('amount');
    }

    public function getEarnedValueAttribute(): float
    {
        return (float) $this->progressEntries()
            ->join('boq_items', 'progress_entries.boq_item_id', '=', 'boq_items.id')
            ->selectRaw('SUM(progress_entries.quantity * boq_items.rate) as total')
            ->value('total') ?? 0;
    }

    public function getCompletionPctAttribute(): float
    {
        $total = $this->total_boq_amount;
        return $total > 0 ? round(($this->earned_value / $total) * 100, 2) : 0;
    }
}
