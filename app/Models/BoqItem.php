<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoqItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id','category','description','unit',
    ];

    protected $casts = [
        'quantity'   => 'decimal:3',
        'rate'       => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function project()         { return $this->belongsTo(Project::class); }
    public function progressEntries() { return $this->hasMany(ProgressEntry::class); }
    public function dsrItem()         { return $this->hasOne(DsrItem::class); }

    public function getDoneQuantityAttribute(): float
    {
        return (float) $this->progressEntries()->sum('quantity');
    }

    public function getCompletionPctAttribute(): float
    {
        $total = (float) $this->quantity;
        return $total > 0 ? min(round(($this->done_quantity / $total) * 100, 2), 100) : 0;
    }

    public function getStatusAttribute(): string
    {
        $pct = $this->completion_pct;
        if ($pct >= 100)  return 'completed';
        if ($pct > 0)     return 'in_progress';
        return 'not_started';
    }
}
