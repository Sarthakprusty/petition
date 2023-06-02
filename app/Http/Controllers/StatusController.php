<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Status::all()->where('active', 1)->toArray();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Status_desc'=>'sometimes|nullable',
        ]);
        $status = new Status;
        $status->Status_desc = $request->Status_desc;

        $status->created_at = Carbon::now()->toDateTimeLocalString();
        $status->last_updated_by = Auth::user()->employee_id;
        $status->last_updated_from = $request->ip();

        if ($status->save())
            return response($status, 201);
        else
            return response(array("code" => 400, "msg" => "Bad request"), 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Status $status)
    {
        return $status;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Status $status)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status,Request $request)
    {
        $status->active = 0;
        $status->deleted_at = Carbon::now()->toDateTimeLocalString();
        $status->last_updated_by = Auth::user()->employee_id;
        $status->last_updated_from = $request->ip();
        $status->save();
        return response(array($status,"msg" => "as per your request your details has been deleted"),202);
    }


    public function mapUser(Request $request, string $status_id)
    {
        $status = Status::find($status_id);
        $users = $request->users;
        foreach ($users as $user_id) {
            $status->users()->attach($user_id);
        }
        return $status->users;
    }
}
