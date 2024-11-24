<?php

namespace App\Http\Services;

use App\Http\Resources\v1\InvoiceResource;
use App\Http\Services\FilterOperator;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceService extends FilterOperator
{
    protected array $allowedOperatorsFields = [
        'value' => ['gt', 'eq', 'lt', 'gte', 'lte', 'ne'],
        'type' => ['eq', 'ne', 'in'],
        'paid' => ['eq', 'ne'],
        'payment_date' => ['gt', 'eq', 'lt', 'gte', 'lte', 'ne'],
    ];

    public static function invoiceFilter(Request $request)
    {

        $queryFilter = (new InvoiceService())->filter($request);

        if (empty($queryFilter)) {
            return InvoiceResource::collection(Invoice::with('user')->get());
        }

        $data = Invoice::with('user');

        if (!empty($queryFilter['whereIn'])) {
            foreach ($queryFilter['whereIn'] as $value) {
                $data->whereIn($value[0], $value[1]);
            }
        }

        $resource = $data->where($queryFilter['where'])->get();

        return InvoiceResource::collection($resource);
    }
}