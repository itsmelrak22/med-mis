<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Supplier::GET_ALL_BY_DESC();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
                $supplier              = new Supplier();
                $supplier->name        = $request->name;
                $supplier->info        = $request->info;
                $supplier->address     = $request->address;
                $supplier->phone       = $request->phone;
                $supplier->created_by  = $request->auth_id;
                $supplier->updated_by  = $request->auth_id;
                $supplier->save();
            DB::commit();

            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        try {
            DB::beginTransaction();
                $supplier->name        = $request->name;
                $supplier->info        = $request->info;
                $supplier->address     = $request->address;
                $supplier->phone       = $request->phone;
                $supplier->updated_by  = $request->auth_id;
                $supplier->updated_at  = new \DateTime;
                $supplier->save();
            DB::commit();

            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        Supplier::destroy($supplier->id);
        
    }
}
