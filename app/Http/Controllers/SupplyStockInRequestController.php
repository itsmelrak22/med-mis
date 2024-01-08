<?php

namespace App\Http\Controllers;

use App\Models\SupplyStockInRequest;
use App\Models\SupplyStockInRequestDetail;
use App\Models\Supply;
use Illuminate\Http\Request;

class SupplyStockInRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SupplyStockInRequest::select(
                'supply_stock_in_requests.id as supply_stock_in_request_id',
                'supply_stock_in_request_details.id as supply_stock_in_request_detail_id',
                'supplies.id as supply_id',
                'supply_stock_in_requests.status',
                'supply_stock_in_requests.created_at as requested_date',
                'supply_stock_in_requests.created_at',
                'supply_stock_in_requests.updated_at',
                'users.name as requested_by',
                'supply_stock_in_request_details.quantity',
                'supply_stock_in_request_details.quantity',
                'supplies.name',
                'suppliers.name as supplier'
            )
            ->leftJoin('users', 'users.id', 'supply_stock_in_requests.created_by')
            ->leftJoin('supply_stock_in_request_details', 'supply_stock_in_requests.id', 'supply_stock_in_request_details.supply_stock_in_request_id')
            ->leftJoin('supplies', 'supply_stock_in_request_details.supply_id', 'supplies.id')
            ->leftJoin('suppliers', 'supplies.supplier_id', 'suppliers.id')
            ->orderBy('supply_stock_in_requests.created_at', 'DESC')
            ->get();
    }

    public function pending()
    {
        return SupplyStockInRequest::select(
            'supply_stock_in_requests.id as supply_stock_in_request_id',
            'supply_stock_in_request_details.id as supply_stock_in_request_detail_id',
            'supplies.id as supply_id',
            'supply_stock_in_requests.status',
            'supply_stock_in_requests.created_at as requested_date',
            'supply_stock_in_requests.created_at',
            'supply_stock_in_requests.updated_at',
            'users.name as requested_by',
            'supply_stock_in_request_details.quantity',
            'supplies.name',
            'suppliers.name as supplier'
        )
        ->leftJoin('users', 'users.id', 'supply_stock_in_requests.created_by')
        ->leftJoin('supply_stock_in_request_details', 'supply_stock_in_requests.id', 'supply_stock_in_request_details.supply_stock_in_request_id')
        ->leftJoin('supplies', 'supply_stock_in_request_details.supply_id', 'supplies.id')
        ->leftJoin('suppliers', 'supplies.supplier_id', 'suppliers.id')
        ->where('supply_stock_in_requests.status', 'PENDING')
            ->orderBy('supply_stock_in_requests.created_at', 'DESC')
            ->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupplyStockInRequest  $supplyStockInRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupplyStockInRequest $supplyStockInRequest)
    {
         try {
            \DB::beginTransaction();
                $supplyStockInRequest->status            =   $request->status; // PENDING, APPROVED, CANCELLED;
                $supplyStockInRequest->updated_by        =   $request->auth_id;
                $supplyStockInRequest->save();


                if( $request->status == 'APPROVED' ){
                   
                    $supply_stock_in_request_detail   =   SupplyStockInRequestDetail::findOrFail($request->supply_stock_in_request_detail_id);

                    $supply              = Supply::findOrFail($request->supply_id);
                    $supply->status      = "ACTIVE";
                    $supply->quantity    = $supply->quantity + $supply_stock_in_request_detail->quantity;
                    $supply->updated_by  = $request->auth_id;
                    $supply->updated_at = new \DateTime;
                    $supply->save();
                    $supply_id = $supply->id;
                }


        } catch (\Exception $e) {
            \DB::rollBack();
            return $e->getMessage();
        }

        \DB::commit();
        return 'success';

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupplyStockInRequest  $supplyStockInRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupplyStockInRequest $supplyStockInRequest)
    {
        //
    }
}
