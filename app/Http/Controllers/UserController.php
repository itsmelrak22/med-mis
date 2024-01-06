<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{

    public function index(){
        return User::all();
    }

    public function getAllAdmins(){
        return User::GET_ADMIN_USERS();
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        try {
            $is_admin = isset($request->is_admin) && strtolower($request->is_admin) == 'true' ? 1 : 0;
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->is_admin = $is_admin;
            $user->password = bcrypt('0000');
            $user->save();

        } catch (\Exception $e) {
            //throw $th;
            \DB::rollback();
            return $e->getMessage();
        }

        \DB::commit();

    }
    public function show($id)
    {
        return User::find($id);
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //
    }
    public function destroy($id)
    {
        //
    }
    public function change(Request $request, User $user){
        $user->password = bcrypt($request->password);
        $user->is_password_already_reset = 1;
        $user->save();
    }
}
