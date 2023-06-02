<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Organization;
use App\Models\RtiInformation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth:api');
    }*/
    //
    public function getPending(){
        // TODO get org id from logged in user
        $org_id = Auth::user()->org_id;
        $org = Organization::find($org_id);
        $details = $org->rtiInformations()
            ->where([
                    ['active','=',1]
                ]
            )->get();
        $app_ids = [];
        foreach($details as $info){
            if(!in_array($info->application_id,$app_ids)){
                $app_ids[] = $info->application_id;
            }
        }
        $applications=[];
        foreach ($app_ids as $id)
            $applications[]=Application::find($id);
        $applications = Application::where([['active','1']])->get();
        $org_id = Auth::user()->org_id;
        $org = Organization::find($org_id);
        $for_reply = true;

        return view('application_list',compact(['applications','for_reply','app_ids','org','details']));
    }

    public function create(Request $request, String $appId){
        $application = Application::find($appId);
        $informations = $application->rti_informations;
        //return $information;
        return view('reply.submit',compact(['application','informations']));
    }
    public function store(Request $request,String $appId){
        $reply_status = '';
        switch ($request->reply_status){
            case 'DRAFT':
                $reply_status='P';
                break;
            case 'SUBMIT(complete)':
                $reply_status='C';
                break;
            case 'SUBMIT(interim)':
                $reply_status='I';
                break;
        }
        foreach ($request->reply as $id=>$reply){
            $info = RtiInformation::find($id);
            if($info->application_id == $appId){
                $info->reply = $reply['reply'];
                $info->reply_remarks = $reply['reply_remarks'];
                $info->reply_at = Carbon::now();
                $info->reply_from = $request->getClientIp();

                $info->reply_status = $reply_status;
                $info->save();
            }
        }
        return redirect()->route('reply.create',['id'=>$appId]);
    }
}
