<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplyStockInRequest extends Model
{
    use HasFactory, SoftDeletes;

    public static function getAllData(){
        return self::select(
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
            'supplies.unit_price',
            'supplies.unit',
            'suppliers.name as supplier'
        )
        ->leftJoin('users', 'users.id', 'supply_stock_in_requests.created_by')
        ->leftJoin('supply_stock_in_request_details', 'supply_stock_in_requests.id', 'supply_stock_in_request_details.supply_stock_in_request_id')
        ->leftJoin('supplies', 'supply_stock_in_request_details.supply_id', 'supplies.id')
        ->leftJoin('suppliers', 'supplies.supplier_id', 'suppliers.id')
        ->orderBy('supply_stock_in_requests.created_at', 'DESC')
        ->get();
    }

    public static function getApproved(){
        return self::select(
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
            'supplies.unit_price',
            'supplies.unit',
            'suppliers.name as supplier'
        )
        ->leftJoin('users', 'users.id', 'supply_stock_in_requests.created_by')
        ->leftJoin('supply_stock_in_request_details', 'supply_stock_in_requests.id', 'supply_stock_in_request_details.supply_stock_in_request_id')
        ->leftJoin('supplies', 'supply_stock_in_request_details.supply_id', 'supplies.id')
        ->leftJoin('suppliers', 'supplies.supplier_id', 'suppliers.id')
        ->where('supply_stock_in_requests.status', 'APPROVED')
        ->orderBy('supply_stock_in_requests.created_at', 'DESC')
        ->get();
    }

    public static function getPending(){
        return self::select(
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
            'supplies.unit_price',
            'supplies.unit',
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

    public static function getReturnToSeller(){
        return self::select(
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
            'supplies.unit_price',
            'supplies.unit',
            'suppliers.name as supplier'
        )
        ->leftJoin('users', 'users.id', 'supply_stock_in_requests.created_by')
        ->leftJoin('supply_stock_in_request_details', 'supply_stock_in_requests.id', 'supply_stock_in_request_details.supply_stock_in_request_id')
        ->leftJoin('supplies', 'supply_stock_in_request_details.supply_id', 'supplies.id')
        ->leftJoin('suppliers', 'supplies.supplier_id', 'suppliers.id')
        ->where('supply_stock_in_requests.status', 'RETURN TO SELLER')
        ->orderBy('supply_stock_in_requests.created_at', 'DESC')
        ->get();
    }

    public static function getReturnToCancelled(){
        return self::select(
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
            'supplies.unit_price',
            'supplies.unit',
            'suppliers.name as supplier'
        )
        ->leftJoin('users', 'users.id', 'supply_stock_in_requests.created_by')
        ->leftJoin('supply_stock_in_request_details', 'supply_stock_in_requests.id', 'supply_stock_in_request_details.supply_stock_in_request_id')
        ->leftJoin('supplies', 'supply_stock_in_request_details.supply_id', 'supplies.id')
        ->leftJoin('suppliers', 'supplies.supplier_id', 'suppliers.id')
        ->where('supply_stock_in_requests.status', 'CANCELLED')
        ->orderBy('supply_stock_in_requests.created_at', 'DESC')
        ->get();
    }

}
