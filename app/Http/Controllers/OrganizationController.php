<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganizationRequest;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Organization::all()->where('active', 1)->toArray();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'grievances_desc'=>'sometimes|nullable',
        ]);
        $organization = new Organization;
        $organization->org_desc = $request->org_desc;

        $organization->created_at = Carbon::now()->toDateTimeLocalString();
        $organization->last_updated_by = Auth::user()->user_id;
        $organization->last_updated_from = $request->ip();


        if ($organization->save())
            return response($organization, 201);
        else
            return response(array("code" => 400, "msg" => "Bad request"), 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        return $organization;

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id,Organization $organization,Request $request)
    {
        $organization->active = 0;
        $organization->deleted_at = Carbon::now()->toDateTimeLocalString();
        $organization->last_updated_by = Auth::user()->employee_id;
        $organization->last_updated_from = $request->ip();
        $organization->save();
        return response(array($organization,"msg" => "as per your request your details has been deleted"),202);
    }
}
