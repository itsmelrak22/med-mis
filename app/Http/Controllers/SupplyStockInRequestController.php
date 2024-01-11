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
        return SupplyStockInRequest::getAllData();
    }

    public function pending()
    {
        return SupplyStockInRequest::getPending();
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
