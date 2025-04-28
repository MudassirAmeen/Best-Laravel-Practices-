<?php

namespace App\Http\Resources;

use App\Http\Resources\BaseResource;
use App\Models\Sale\SaleModel;

class ExampleResource extends BaseResource
{
    protected static array $fields = [
        'iSaleID' => ['key' => 'sale_id', 'default' => null],
        'iEmployeeID' => ['key' => 'employee_id', 'default' => null],
        '...' 
    ];

    protected static array $relations = [
        "Real RelationShip Name In Model" => [
            'resource' => 'Any Resource class',
            'key' => 'fake name for that resource in response'
        ],
    ];

    protected function customFields(): array
    {
        return [
            'status' => $this->bStatus ?? 'N/A',
            'sync_status' => $this->sSyncStatus ?? 'N/A',
            'entry_source' => $this->sEntrySource ?? 'N/A',
            'action' => $this->sAction ?? 'N/A',
        ];
    }
}