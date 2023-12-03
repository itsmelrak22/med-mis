<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index()
    {
        $salesOrders = SalesOrder::all();
        return response()->json($salesOrders);
    }

    public function show($id)
    {
        $salesOrder = SalesOrder::findOrFail($id);
        return response()->json($salesOrder);
    }

    public function store(Request $request)
    {
        $salesOrder = SalesOrder::create($request->all());
        return response()->json($salesOrder, 201);
    }

    public function update(Request $request, $id)
    {
        $salesOrder = SalesOrder::findOrFail($id);
        $salesOrder->update($request->all());
        return response()->json($salesOrder, 200);
    }

    public function destroy($id)
    {
        $salesOrder = SalesOrder::findOrFail($id);
        $salesOrder->delete();
        return response()->json(null, 204);
    }
}