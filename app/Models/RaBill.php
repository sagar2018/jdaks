<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RaBill extends Model
{
    use SoftDeletes;

    const STATUS_FLOW = ['draft', 'submitted', 'certified', 'approved', 'paid'];

    protected $fillable = [
        'project_id','bill_number','bill_date','gross_amount',
        'deductions_json','net_payable','status','notes',
        'submitted_at','certified_at','approved_at','paid_at','paid_amount',
        'created_by',
    ];

    protected $casts = [
        'bill_date'      => 'date',
        'gross_amount'   => 'decimal:2',
        'net_payable'    => 'decimal:2',
        'paid_amount'    => 'decimal:2',
        'deductions_json'=> 'array',
        'submitted_at'   => 'datetime',
        'certified_at'   => 'datetime',
        'approved_at'    => 'datetime',
        'paid_at'        => 'datetime',
    ];

    public function project()   { return $this->belongsTo(Project::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }

    public function getNextStatusAttribute(): ?string
    {
        $flow = self::STATUS_FLOW;
        $idx  = array_search($this->status, $flow);
        return ($idx !== false && isset($flow[$idx + 1])) ? $flow[$idx + 1] : null;
    }
}
