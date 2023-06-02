<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Role::all()->where('active', 1);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_desc'=>'sometimes|nullable',
        ]);
        $role = new Role;
        $role->role_desc = $request->role_desc;

        $role->created_at = Carbon::now()->toDateTimeLocalString();
        $role->last_updated_by = Auth::user()->user_id;
        $role->last_updated_from = $request->ip();

        if ($role->save())
            return response($role, 201);
        else
            return response(array("code" => 400, "msg" => "Bad request"), 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return $role;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    public function destroy(Role $role,Request $request)
    {
        $role->active = 0;
        $role->deleted_at = Carbon::now()->toDateTimeLocalString();
        $role->last_updated_by = Auth::user()->user_id;
        $role->last_updated_from = $request->ip();
        $role->save();
        return response(array($role,"msg" => "as per your request your details has been deleted"),202);
    }

    public function mapUser(Request $request, string $role_id)
    {
        $role = Role::find($role_id);
        $users = $request->users;
        foreach ($users as $user_id) {
            $role->users()->attach($user_id);
        }
        return $role->users;
    }

    /** Lists of users of their role */
    public function getUser(string $role_id)
    {
        $role = Role::find($role_id);
        return $role->users;
    }
}
