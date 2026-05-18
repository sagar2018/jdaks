<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DsrItem extends Model
{
    protected $fillable = [
        'project_id','boq_item_id',
        'material_json','labour_json','machinery_json','deduction_pct_json','updated_by',
    ];

    protected $casts = [
        'material_json'      => 'array',
        'labour_json'        => 'array',
        'machinery_json'     => 'array',
        'deduction_pct_json' => 'array',
    ];

    public function project() { return $this->belongsTo(Project::class); }
    public function boqItem() { return $this->belongsTo(BoqItem::class); }

    public function getTotalCostAttribute(): float
    {
        $total = 0;
        foreach (['material_json', 'labour_json', 'machinery_json'] as $col) {
            foreach ($this->$col ?? [] as $row) {
                $total += (float)($row['qty'] ?? 0) * (float)($row['rate'] ?? 0);
            }
        }
        return $total;
    }
}
