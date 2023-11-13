<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supply extends Model
{
    use HasFactory, SoftDeletes;

    public static function GET_ALL_BY_DESC(){
        return self::select('supplies.*', 'suppliers.name as supplier')
                        ->leftJoin('suppliers', 'supplies.supplier_id', 'suppliers.id')
                        ->orderBy('created_at', 'DESC')
                        ->get();
    }
}
