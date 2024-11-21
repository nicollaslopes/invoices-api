<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\InvoiceResource;
use App\Models\Invoice;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return InvoiceResource::collection(Invoice::with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required|max:1',
            'paid' => 'required|numeric|between:0,1',
            'payment_date' => 'nullable',
            'value' => 'required|numeric|between:1,9999.99'
        ]);

        if ($validator->fails()) {
            return $this->error('Invalid data', 422, $validator->errors());
        }

        $createdFields = Invoice::create($validator->validated());

        if ($createdFields) {
            return $this->success('Invoice created!', 200, new InvoiceResource($createdFields));
        }

        return $this->error('Invalid data', 422, $validator->errors());

    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required|max:1|in:' . implode(',', ['B', 'C', 'P']),
            'paid' => 'required|numeric|between:0,1',
            'value' => 'required|numeric|',
            'payment_date' => 'nullable|date_format:Y-m-d H:i:s'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation failed', 422, $validator->errors());
        }

        $fieldsUpdate = $validator->validated();

        $objectUpdate = Invoice::find($id)->update([
            'user_id' => $fieldsUpdate['user_id'],
            'type' => $fieldsUpdate['type'],
            'paid' => $fieldsUpdate['paid'],
            'value' => $fieldsUpdate['value'],
            'payment_date' => $fieldsUpdate['paid'] ? $fieldsUpdate['payment_date'] : null
        ]);

        if ($objectUpdate) {
            return $this->success('Invoice updated!', 200, $request->all());
        }

        return $this->error('Invoice not updated', 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $deletedObject = $invoice->delete();

        if ($deletedObject) {
            return $this->success('Invoice deleted', 200);
        }

        return $this->error('Invoice not deleted', 400);
    }
}
