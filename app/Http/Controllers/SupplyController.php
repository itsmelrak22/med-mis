<?php

namespace App\Http\Controllers;

use App\Models\Supply;
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
        return Supply::GET_ALL_BY_DESC();
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
                $supply              = new Supply();
                $supply->name        = $request->name;
                $supply->supplier_id = $request->supplier_id;
                $supply->status      = $request->status;
                $supply->created_by  = $request->auth_id;
                $supply->updated_by  = $request->auth_id;
                $supply->save();
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
