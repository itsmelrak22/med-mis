<?php

namespace App\Http\Controllers;

use App\Models\SalesOrderRequest;
use App\Models\SalesOrderRequestDetail;
use App\Models\Supply;
use Illuminate\Http\Request;

class SalesOrderRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return SalesOrderRequest::select(
            'sales_order_requests.id as sales_order_requests_id',
            'sales_order_request_details.id as sales_order_request_details_id', 
            'supplies.id as supply_id',
            'sales_order_requests.status',
            'sales_order_requests.created_at as requested_date',
            'sales_order_requests.created_at',
            'sales_order_requests.updated_at',
            'users.name as requested_by',
            'customers.name as customer_name',
            'sales_order_request_details.quantity',
            'supplies.name',
            'supplies.unit',
            'supplies.unit_price',
            'suppliers.name as supplier'
        )
        ->leftJoin('users', 'users.id', 'sales_order_requests.created_by')
        ->leftJoin('sales_order_request_details', 'sales_order_requests.id', 'sales_order_request_details.sales_order_request_id')
        ->leftJoin('customers', 'customers.id', 'sales_order_request_details.customer_id')
        ->leftJoin('supplies', 'sales_order_request_details.supply_id', 'supplies.id')
        ->leftJoin('suppliers', 'supplies.supplier_id', 'suppliers.id')
        ->orderBy('sales_order_requests.created_at', 'DESC')
        ->get();
    }

    public function pending()
    {
        return SalesOrderRequest::select(
            'sales_order_requests.id as sales_order_requests_id',
            'sales_order_request_details.id as sales_order_request_details_id',
            'supplies.id as supply_id',
            'sales_order_requests.status',
            'sales_order_requests.created_at as requested_date',
            'sales_order_requests.created_at',
            'sales_order_requests.updated_at',
            'users.name as requested_by',
            'customers.name as customer_name',
            'sales_order_request_details.quantity',
            'supplies.name',
            'supplies.unit',
            'supplies.unit_price',
            'suppliers.name as supplier'
        )
        ->leftJoin('users', 'users.id', 'sales_order_requests.created_by')
        ->leftJoin('sales_order_request_details', 'sales_order_requests.id', 'sales_order_request_details.sales_order_request_id')
        ->leftJoin('customers', 'customers.id', 'sales_order_request_details.customer_id')
        ->leftJoin('supplies', 'sales_order_request_details.supply_id', 'supplies.id')
        ->leftJoin('suppliers', 'supplies.supplier_id', 'suppliers.id')
        ->orderBy('sales_order_requests.created_at', 'DESC')
        ->where('sales_order_requests.status', 'PENDING')
        ->get();
    }


    public function store(Request $request)
    {


        try {
            \DB::beginTransaction();

                $sales_order_requests                    =   new SalesOrderRequest();
                $sales_order_requests->status            =   "PENDING"; // PENDING, APPROVED, CANCELLED;
                $sales_order_requests->created_by        =   $request->auth_id;
                $sales_order_requests->updated_by        =   $request->auth_id;
                $sales_order_requests->save();
                $sales_order_requests_id = $sales_order_requests->id;

                $sales_order_request_detail                                 =   new SalesOrderRequestDetail();
                $sales_order_request_detail->sales_order_request_id         =   $sales_order_requests_id;
                $sales_order_request_detail->customer_id                    =   $request->customer_id;
                $sales_order_request_detail->supply_id                      =   $request->supply_id;
                $sales_order_request_detail->quantity                       =   $request->quantity;
                $sales_order_request_detail->save();



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
     * @param  \App\Models\SalesOrderRequest  $salesOrderRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalesOrderRequest $salesOrderRequest)
    {
        try {
            \DB::beginTransaction();
                if( isset($request->is_editted) ){
                    if( $salesOrderRequest->status == 'APPROVED' && $request->status == 'RETURN TO SELLER'){
                        $supply_stock_in_request_detail   =   SalesOrderRequestDetail::findOrFail($request->sales_order_request_details_id);
                        $supply              = Supply::findOrFail($request->supply_id);
                        $supply->quantity    = $supply->quantity + $supply_stock_in_request_detail->quantity;
                        $supply->updated_by  = $request->auth_id;
                        $supply->updated_at = new \DateTime;
                        $supply->save();
                        $supply_id = $supply->id;
                    }
                }

                $salesOrderRequest->status            =   $request->status; // PENDING, APPROVED, CANCELLED;
                $salesOrderRequest->updated_by        =   $request->auth_id;
                $salesOrderRequest->save();

              


                if( $request->status == 'APPROVED' ){
                   
                    $supply_stock_in_request_detail   =   SalesOrderRequestDetail::findOrFail($request->sales_order_request_details_id);
                    $supply              = Supply::findOrFail($request->supply_id);

                    if($supply->quantity < $supply_stock_in_request_detail->quantity){
                        return 'out_of_stock';
                    }

                    $supply->quantity    = $supply->quantity - $supply_stock_in_request_detail->quantity;
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
     * @param  \App\Models\SalesOrderRequest  $salesOrderRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesOrderRequest $salesOrderRequest)
    {
        //
    }
}
