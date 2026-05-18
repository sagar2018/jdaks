<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PvIndex extends Model
{
    protected $fillable = [
        'project_id','month','base_month',
        'labour_idx','cement_idx','steel_idx','bitumen_idx',
        'pol_idx','other_idx','plant_idx','created_by',
    ];

    protected $casts = [
        'labour_idx'  => 'decimal:4',
        'cement_idx'  => 'decimal:4',
        'steel_idx'   => 'decimal:4',
        'bitumen_idx' => 'decimal:4',
        'pol_idx'     => 'decimal:4',
        'other_idx'   => 'decimal:4',
        'plant_idx'   => 'decimal:4',
    ];

    public function project() { return $this->belongsTo(Project::class); }
}
