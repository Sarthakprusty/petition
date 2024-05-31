<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\State;
use App\Rules\Orgtype;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\Reason;

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
            'org_desc'=>'required',
            'org_type'=>['required', new Orgtype],
            'org_head'=>'required',
            'org_head_hi'=>'required',
            'org_address'=>'required',
            'mail' => 'nullable',
            'org_desc_hin'=>'required',
            'v_code'=>'nullable',
            'org_address_hin'=>'required',
            'state_id'=>'nullable',
            'pincode'=>'nullable',
            'phone_no' => 'nullable',
        ]);

        $organization = new Organization;
        if(isset($request->id) && $request->id){
            $organization = Organization::find($request->id);
            $organization->updated_at = Carbon::now()->toDateTimeLocalString();
        }

        $organization->org_desc = $request->org_desc;
        $organization->org_type = $request->org_type;
        $organization->org_head = $request->org_head;
        $organization->org_head_hi = $request->org_head_hi;
        $organization->org_address = $request->org_address;
        $organization->mail = $request->mail;
        $organization->org_desc_hin = $request->org_desc_hin;
        $organization->v_code = $request->v_code;
        $organization->org_address_hin = $request->org_address_hin;
        $organization->state_id = $request->state_id;
        $organization->pincode = $request->pincode;
        $organization->phone_no = $request->phone_no;

        if(!isset($request->id) && !$request->id) {
            $organization->created_at = Carbon::now()->toDateTimeLocalString();
        }
        $organization->last_updated_by = Auth::user()->id;
        $organization->last_updated_from = $request->ip();


        if ($organization->save())
            return redirect(route('applications.dashboard'));
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


    public function changeorganization(Request $request){
        $states= State::all();
        $organizations= Organization::find($request->org_id);
        // if ($request->orgvalue && $request->orgvalue != '') {
        //     if ($request->orgvalue == 'names' && $request->orgDescMin && $request->orgDescMin != '') {
        //         $organizations = Organization::where('id', $request->orgDescMin)->first();
        //     } elseif ($request->orgvalue == 'types' && $request->orgDescStat && $request->orgDescStat != '') {
        //         $organizations = Organization::where('id', $request->orgDescStat)->first();
        //     }else
        //         return response(array("code" => 400, "msg" => "Bad request"), 400);
        // } else
        //         return response(array("code" => 400, "msg" => "Bad request"), 400);

        return view('orglist', compact(  'organizations','states'));
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

    public function ministries(Request $request){
        // $states=State::orderBy('state_name','asc')->get();
        $organization_ministry = Organization::orderBy('org_desc','asc')->where('org_type','M')->get();
        $organizations_state = Organization::orderBy('org_desc','asc')->where('org_type','S')->get();
    
        return view('show_organization', compact('organization_ministry','organizations_state'));
    }

    public function Noaction(){
        $No_action_report = Application::orderBy('letter_date','desc')->where('action_org','N') ->join('reasons as reason', 'reason.id', '=', 'applications.reason_id')->get();

        // dd($No_action_report);
        return view('Noaction_report', compact('No_action_report'));
    }
}
