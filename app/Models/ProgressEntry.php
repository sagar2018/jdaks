<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgressEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id','boq_item_id','date','quantity',
        'chainage_from','chainage_to','status','notes','created_by',
    ];

    protected $casts = [
        'date'         => 'date',
        'quantity'     => 'decimal:3',
        'chainage_from'=> 'decimal:3',
        'chainage_to'  => 'decimal:3',
    ];

    public function project()   { return $this->belongsTo(Project::class); }
    public function boqItem()   { return $this->belongsTo(BoqItem::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
