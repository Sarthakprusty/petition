<?php

namespace App\Http\Controllers;

use App\Models\Grievance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrievanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Grievance::all()->where('active', 1)->toArray();

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'grievances_desc'=>'sometimes|nullable',
        ]);

        $grievances = new Grievance;
        $grievances->grievances_desc = $request->grievances_desc;

        $grievances->created_at = Carbon::now()->toDateTimeLocalString();
        $grievances->last_updated_by = Auth::user()->user_id;
        $grievances->last_updated_from = $request->ip();


        if ($grievances->save())
            return response($grievances, 201);
        else
            return response(array("code" => 400, "msg" => "Bad request"), 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Grievance $grievances)
    {
        return $grievances;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grievance $grievances)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grievance $grievances,Request $request)
    {
        $grievances->active = 0;
        $grievances->deleted_at = Carbon::now()->toDateTimeLocalString();
        $grievances->last_updated_by = Auth::user()->employee_id;
        $grievances->last_updated_from = $request->ip();
        $grievances->save();
        return response(array($grievances,"msg" => "as per your request your details has been deleted"),202);
    }
}
