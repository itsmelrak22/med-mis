<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\SupplyStockInRequest;
use App\Models\SupplyStockInRequestDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Supply::GET_ALL_ACTIVE_BY_DESC();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // return $request;
        try {
            \DB::beginTransaction();
                $supply                     = new Supply;
                $supply->serial_number      = $request->serial_number;
                $supply->name               = $request->name;
                $supply->supplier_id        = $request->supplier_id;
                $supply->status             = "INACTIVE";
                $supply->updated_by         = $request->auth_id;
                $supply->updated_at         = new \DateTime;
                $supply->save();
                $supply_id = $supply->id;

                $supply_stock_in_request                    =   new SupplyStockInRequest();
                $supply_stock_in_request->status            =   "PENDING"; // PENDING, APPROVED, CANCELLED;
                $supply_stock_in_request->created_by        =   $request->auth_id;
                $supply_stock_in_request->updated_by        =   $request->auth_id;
                $supply_stock_in_request->save();
                $supply_stock_in_request_id = $supply_stock_in_request->id;

                $supply_stock_in_request_detail                                 =   new SupplyStockInRequestDetail();
                $supply_stock_in_request_detail->supply_stock_in_request_id     =   $supply_stock_in_request_id;
                $supply_stock_in_request_detail->supply_id                      =   $supply_id;
                $supply_stock_in_request_detail->quantity                       =   $request->quantity;
                $supply_stock_in_request_detail->save();



        } catch (\Exception $e) {
            \DB::rollBack();
            return $e->getMessage();
        }

        \DB::commit();
        return 'success';

    }
    public function store_exist(Request $request, Supply $supply)
    {
        try {
            \DB::beginTransaction();
                $supply_id = $supply->id;

                $supply_stock_in_request                    =   new SupplyStockInRequest();
                $supply_stock_in_request->status            =   "PENDING"; // PENDING, APPROVED, CANCELLED;
                $supply_stock_in_request->created_by        =   $request->auth_id;
                $supply_stock_in_request->updated_by        =   $request->auth_id;
                $supply_stock_in_request->save();
                $supply_stock_in_request_id = $supply_stock_in_request->id;

                $supply_stock_in_request_detail                                 =   new SupplyStockInRequestDetail();
                $supply_stock_in_request_detail->supply_stock_in_request_id     =   $supply_stock_in_request_id;
                $supply_stock_in_request_detail->supply_id                      =   $supply_id;
                $supply_stock_in_request_detail->quantity                       =   $request->quantity;
                $supply_stock_in_request_detail->save();



        } catch (\Exception $e) {
            \DB::rollBack();
            return $e->getMessage();
        }

        \DB::commit();
        return 'success';

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supply  $supply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supply $supply)
    {
        $supply->name        = $request->name;
        $supply->supplier_id = $request->supplier_id;
        $supply->status      = $request->status;
        $supply->updated_by  = $request->auth_id;
        $supply->updated_at = new \DateTime;
        $supply->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supply  $supply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supply $supply)
    {
        Supply::destroy($supply->id);
    }
}
