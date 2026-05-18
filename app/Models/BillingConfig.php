<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingConfig extends Model
{
    protected $fillable = [
        'project_id','loa_number','tendered_value','bid_type','bid_pct',
        'sd_pct','it_tds_pct','labour_cess_pct','gst_tds_pct','configured_at',
    ];

    protected $casts = [
        'tendered_value'  => 'decimal:2',
        'bid_pct'         => 'decimal:2',
        'sd_pct'          => 'decimal:2',
        'it_tds_pct'      => 'decimal:2',
        'labour_cess_pct' => 'decimal:2',
        'gst_tds_pct'     => 'decimal:2',
        'configured_at'   => 'datetime',
    ];

    public function project() { return $this->belongsTo(Project::class); }
}
