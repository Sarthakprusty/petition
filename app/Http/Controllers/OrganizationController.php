<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Rules\Orgtype;
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
            'org_desc'=>'nullable',
            'org_type'=>['nullable', new Orgtype],
            'org_head'=>'nullable',
            'org_head_hi'=>'nullable',
            'org_address'=>'nullable',
            'mail' => 'nullable',
        ]);
        $organization = new Organization;
        $organization->org_desc = $request->org_desc;
        $organization->org_type = $request->org_type;
        $organization->org_head = $request->org_head;
        $organization->org_head_hi = $request->org_head_hi;
        $organization->org_address = $request->org_address;
        $organization->mail = $request->mail;

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
    public function getOrgByIntOrExt(String $char)
    {
        if ($char == 'M')
            $id = 'M';
        else if ($char == 'I')
            $id = 'I';
        else if ($char == 'S')
            $id = 'S';
        else
            return response(array("code" => 400, "msg" => "value did not arrive"), 400);

        $org = Organization::where('org_type', $id)->get();
        if ($org)
            return $org;
        else
            return response(array("code" => 400, "msg" => "org not found"), 400);

    }
}
