<?php

namespace App\Imports;

use App\Models\BoqItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class BoqImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    protected int $projectId;
    protected int $sortOrder = 0;

    public function __construct(int $projectId)
    {
        $this->projectId = $projectId;
    }

    public function model(array $row): ?BoqItem
    {
        if (empty($row['description'])) return null;

        $quantity = (float) ($row['quantity'] ?? 0);
        $rate     = (float) ($row['rate'] ?? 0);

        return new BoqItem([
            'project_id'  => $this->projectId,
            'category'    => $row['category'] ?? 'General',
            'description' => $row['description'],
            'unit'        => $row['unit'] ?? 'No.',
            'quantity'    => $quantity,
            'rate'        => $rate,
            'amount'      => $quantity * $rate,
            'sort_order'  => ++$this->sortOrder,
        ]);
    }
}
