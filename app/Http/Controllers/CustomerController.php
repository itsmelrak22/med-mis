<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => ['required', 'email', Rule::unique('customers')->whereNull('deleted_at')],
                'phone' => 'required',
                'address' => 'required',
                'auth_id' => 'required',
            ]);
    
            $customer = new Customer;
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->address = $request->address;
            $customer->created_by = $request->auth_id;
            $customer->updated_by = $request->auth_id;
            $customer->save();
    
            return response()->json($customer, 201);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json(['error' => 'Email already exists'], 409);
            }
    
            return response()->json(['error' => 'An error occurred while processing your request'], 500);
        }
    }
    
    

    public function update(Request $request, Customer $customer)
    {
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->updated_by = $request->auth_id;
        $customer->save();
        return response()->json($customer, 200);
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->forceDelete();
        return response()->json(null, 204);
    }
}
