<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\SupplyStockInRequest;
use App\Models\SalesOrderRequest;


class PDFController extends Controller
{
    //


    public function generateOrderSlip($id)
    {
        $data =  SalesOrderRequest::select(
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
        ->where('sales_order_requests.id', $id)
        ->first();

        if($data){
            $pdf = PDF::loadView('order_slip_pdf', compact('data')) ->setPaper('a4', 'portrait');
            return response()->make($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="order_slip_pdf.pdf"'
            ]);
        }else{
            $pdf = PDF::loadView('no_data', compact('data')) ->setPaper('a4', 'portrait');
            return response()->make($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="no_data.pdf"'
            ]);
        }

      
    }
    public function generateStockInRequestAll()
    {

        $data = SupplyStockInRequest::getAllData();
        $pdf = PDF::loadView('stock_in_request_pdf', compact('data')) ->setPaper('a4', 'landscape');
        return response()->make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="stock_in_request_pdf.pdf"'
        ]);
    }
    public function generateStockInApproved()
    {

        $data = SupplyStockInRequest::getApproved();
        $pdf = PDF::loadView('stock_in_request_pdf_approved_pdf', compact('data')) ->setPaper('a4', 'landscape');
        return response()->make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="stock_in_request_pdf_approved_pdf.pdf"'
        ]);
    }
    public function generateStockInPending()
    {

        $data = SupplyStockInRequest::getPending();
        $pdf = PDF::loadView('stock_in_request_pdf_pending_pdf', compact('data')) ->setPaper('a4', 'landscape');
        return response()->make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="stock_in_request_pdf_pending_pdf.pdf"'
        ]);
    }
    public function generateStockInReturnToSeller()
    {

        $data = SupplyStockInRequest::getReturnToSeller();
        $pdf = PDF::loadView('stock_in_request_pdf_return_to_seller_pdf', compact('data')) ->setPaper('a4', 'landscape');
        return response()->make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="stock_in_request_pdf_return_to_seller_pdf.pdf"'
        ]);
    }
    public function generateStockInCancelled()
    {

        $data = SupplyStockInRequest::getReturnToCancelled();
        $pdf = PDF::loadView('stock_in_cancelled_pdf', compact('data')) ->setPaper('a4', 'landscape');
        return response()->make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="stock_in_cancelled_pdf.pdf"'
        ]);
    }

}
