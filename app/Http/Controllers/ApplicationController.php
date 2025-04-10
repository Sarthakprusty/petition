<?php

namespace App\Http\Controllers;
use App\Events\CurlRequestEvent;
use App\Events\GeneratePdfEventAck;
use App\Events\GeneratePdfEventFwd;
use App\Jobs\ProcessApplicationJob;
use App\Models\Reason;
use App\Models\User;
use TCPDF;
use setasign\Fpdi\Tcpdf\Fpdi;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use App\Models\Application;
use App\Models\Grievance;
use App\Models\Organization;
use App\Models\SignAuthority;
use App\Models\State;
use App\Models\EmailLog;
use App\Rules\Acknowledgement;
use App\Rules\ActionOrg;
use App\Rules\Country;
use App\Rules\Gender;
use App\Rules\Language;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Dompdf\Dompdf;
use Barryvdh\Snappy\Facades\SnappyPdf;
use PDF;
use Dompdf\Options;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use App\Mail\SendPdfEmail;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use App\Models\Status;
use PhpParser\Node\Stmt\Echo_;
use Twilio\Http\CurlClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $states = State::all();
        $organizations = Organization::all();
        //getting application based on role
        $role_ids = auth()->user()->roles()->where('user_roles.active', 1)->pluck('role_id')->toArray();
        $org_id = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();
        $arr = [];
        $qr = [];
        $arr[] = ['active', 1];
        if (in_array(1, $role_ids)) {
            $arr[] = ['received_by', Auth::user()->id];
            $qr[] = 1;
            $qr[] = 0;
        }
        if (in_array(2, $role_ids)) {
            $qr[] = 2;
        }
        if (in_array(3, $role_ids)) {
            $qr[] = 3;
        }
        if (in_array(4, $role_ids)) {
            $qr[] = 0;//CR DRAFT  STATUS_ID =4,FORWARD =1
            $qr[] = 0.5;
        }
        //  print_r($qr);die;

        if (in_array(176, $org_id)) {
            $applications = Application::join('application_status as ast', 'ast.application_id', '=', 'applications.id')
                ->where('applications.active', 1)
                ->where('ast.active', 1)
                ->where('applications.created_by', auth()->user()->id)
                ->whereIn('ast.status_id', $qr)
                ->select('applications.*')
                ->orderBy('applications.created_at', 'desc')
                ->paginate(18)
                ->appends($request->except('page'));
        } else {
            $applications = Application::where($arr)
                ->whereIn('received_by', function ($query) use ($org_id) {
                    $query->select('users.id')
                        ->from('users')
                        ->join('user_organization', 'users.id', '=', 'user_organization.user_id')
                        ->whereIn('user_organization.org_id', $org_id);
                })
                ->whereHas('statuses', function ($query) use ($qr) {
                    $query->whereIn('status_id', $qr)
                        ->where('application_status.active', 1);
                })
                ->paginate(18)
                ->appends($request->except('page'));
        }

        //  echo "<pre>";print_r($applications);die;






        // $applications=Application::where('id', 35297
        // )->paginate(18);
        $this->applicationlistRequirements($applications);
        return view('application_list', compact('applications', 'states', 'organizations'));

    }
    public function sendByCR(Request $request)
    {
        $states = State::all();
        $organizations = Organization::all();
        $role_ids = auth()->user()->roles()->where('user_roles.active', 1)->pluck('role_id')->toArray();
        $org_id = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();
        $arr = [];
        $qr = [];
        $arr[] = ['active', 1];
        // if (in_array(1, $role_ids)) {
        //     $arr[] = ['created_by', Auth::user()->id];
        //     $qr[] = 1;
        //     $qr[] = 0;
        // }
        // print_r($qr);die;
        // if (in_array(2, $role_ids)) {
        //     $qr[] = 2;
        // }
        // if (in_array(3, $role_ids)) {
        //     $qr[] = 3;
        // }
        if (in_array(1, $role_ids)) {
            //$qr[] = 0;//CR DRAFT  STATUS_ID =4,FORWARD =1
            $qr[] = 0.5;
        }
        $applications = Application::join('application_status as ast', 'ast.application_id', '=', 'applications.id')
            ->where('applications.active', 1)
            ->where('ast.active', 1)
            ->whereIn('ast.status_id', $qr)
            ->whereIn('ast.forwarded_section', $org_id)
            ->select('applications.*')
            ->orderBy('applications.created_at', 'desc')
            ->paginate(18)
            ->appends($request->except('page'));


        // $applications = Application::join('application_status as ast', 'ast.application_id', '=', 'applications.id')
        //     ->where('applications.active', 1)
        //     ->where('ast.active', 1)
        //     ->whereIn('ast.status_id', $qr)
        //     ->select('applications.*')
        //     ->paginate(18)
        //     ->appends($request->except('page'));
        //   echo "<pre>";print_r($applications);die;



        // $applications=Application::where('id', 35297
        // )->paginate(18);
        //$applications->$sentByCr=true;
        $sentByCr = true;
        // $this->applicationlistRequirements($applications);
        return view('application_list_cr', compact('applications', 'states', 'organizations', 'sentByCr'));

    }

    private function arraysAreEqual($array1, $array2)
    {
        sort($array1);
        sort($array2);
        // dd('Entered arraysAreEqual method', $array1, $array2);
        return $array1 == $array2;
    }

    /**
     * Searching within resources.
     */
    public function search(Request $request)
    {
        $org_id = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();
        $qr = [0, 1, 2, 3, 4, 5];
        $arr = [];
        $arr[] = ['active', 1];

        if ($request->reg_no && $request->reg_no != '') {
            $arr[] = ['reg_no', 'like', '%' . $request->reg_no . '%'];
        }
        if ($request->state_id && $request->state_id != '') {
            $arr[] = ['state_id', '=', $request->state_id];
        }
        if ($request->letter_no && $request->letter_no != '') {
            $arr[] = ['letter_no', 'like', '%' . $request->letter_no . '%'];
        }
        if ($request->applicant_name && $request->applicant_name != '') {
            $arr[] = ['applicant_name', 'like', '%' . $request->applicant_name . '%'];
        }
        if ($request->app_date_from && $request->app_date_from != '') {
            $arr[] = ['created_at', '>=', $request->app_date_from];
        }
        if ($request->app_date_to && $request->app_date_to != '') {
            $arr[] = ['created_at', '<=', $request->app_date_to];
        }

        if ($request->orgTy && $request->orgTy != '') {
            if ($request->orgTy == 'name') {
                $arr[] = ['action_org', 'F'];
                if ($request->orgDescMi && $request->orgDescMi != '') {
                    $arr[] = ['department_org_id', $request->orgDescMi];
                }
            } elseif ($request->orgTy == 'type') {
                $arr[] = ['action_org', 'S'];
                if ($request->orgDescSt && $request->orgDescSt != '') {
                    $arr[] = ['department_org_id', $request->orgDescSt];
                }
            }
        }
        if ($request->organization && $request->organization != '') {
            $org_id = [];
            $org_id[] = $request->organization;
        }

        if ($request->status !== null && $request->status != '') {
            $qr = [$request->status];
        }

        if ($request->user_id && $request->user_id != '') {
            $arr[] = ['created_by', '=', $request->user_id];
        }

        $organizations = Organization::all();
        $states = State::all();

        $applications = Application::with('state')
            ->where($arr)
            ->whereIn('created_by', function ($query) use ($org_id) {
                $query->select('users.id')
                    ->from('users')
                    ->join('user_organization', 'users.id', '=', 'user_organization.user_id')
                    ->wherein('user_organization.org_id', $org_id);
            })
            ->whereHas('statuses', function ($query) use ($qr) {
                $query->wherein('status_id', $qr)
                    ->where('application_status.active', 1);
            })
            ->paginate(18) // Pagination with 10 items per page
            ->appends($request->except('page')); // Pagination added, with 10 items per page

        foreach ($applications as $application) {
            if (auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && ($application->created_by == auth()->user()->id) && ($application->statuses->isEmpty() || $application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(1) || $application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(0))) {
                $application->allowEdit = true;
            } else {
                $application->allowEdit = false;
            }

            if (auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && $application->statuses->first() && $application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(4) && $application->reply == '') {
                $application->allowFinalReply = true;
            } else {
                $application->allowFinalReply = false;
            }

            if (
                auth()->check() && auth()->user()->roles->pluck('id')->contains(2) && ($application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(3)) &&
                ($this->arraysAreEqual(auth()->user()->organizations()->wherePivot('active', 1)->pluck('org_id')->toArray(), $application->createdBy->organizations()->wherePivot('active', 1)->pluck('org_id')->toArray()))
            ) {
                $application->allowPullBack = true;
            } else if (auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && ($application->created_by == auth()->user()->id) && ($application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(2))) {
                $application->allowPullBack = true;
            } else {
                $application->allowPullBack = false;
            }

            if ($application->department_org && $application->department_org->org_desc) {
                $remark = $application->department_org->org_desc;
                $application->trimmedremark = strlen($remark) > 30 ? substr($remark, 0, 25) . '...' : $remark;
            } elseif ($application->reason && $application->reason->reason_desc) {
                $remark = $application->reason->reason_desc;
                $application->trimmedremark = strlen($remark) > 30 ? substr($remark, 0, 25) . '...' : $remark;
            }
        }

        // $notpaginate = true;
        return view('application_list', compact('applications', 'states', 'organizations'));
    }

    public function checkDiaryNo()
    {
        // Get the POST data
        $inputValue = $_POST['inputValue'] ?? null;
        $idEdit = $_POST['idEdit'] ?? null;

        // Start building the query
        $query = Application::where('letter_no', $inputValue);

        // If idEdit is provided, exclude that record
        if ($idEdit) {
            $query->where('id', '!=', $idEdit);
        }

        // Check if any records exist that match the query
        $exists = $query->exists();

        return response()->json($exists);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $org_id = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();
        $grievances = [];
        if (in_array(174, $org_id)) {
            $grievances = Grievance::where('section', 11)->get();
        } elseif (in_array(175, $org_id)) {
            $grievances = Grievance::where('section', 12)->get();
        }
        $organizationStates = Organization::where('org_type', 'S')->get();
        $organizationM = Organization::where('org_type', 'M')->get();
        $reasonM = Reason::where('action_code', 99)->get();
        $reasonN = Reason::where('action_code', 10)->get();
        $states = State::all();
        //$existed_letter_no = [];
        /*$application  = Application::where('active','1')->get();
        foreach($application as $appli){
            $existed_letter_no[] = $appli->letter_no;
        }*/

        $app = new Application();
        $allowOnlyForward = $this->Forwardbuttoncommon($app);
        if (($app->id == null || ($app->created_by == auth()->user()->id && auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && $app->statuses->first() && $app->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(0)))) {
            $allowDraft = true;
        } else {
            $allowDraft = false;
        }
        //  $appStatusRemark = $app->statuses()->wherePivot('active', 0)->pluck('pivot.remarks')->with('created by');
        $isCr = false;
        if (in_array(176, $org_id)) {
            $isCr = true;
        }


        return view('application', compact('app', 'organizationStates', 'states', 'grievances', 'reasonM', 'reasonN', 'organizationM', 'allowDraft', 'allowOnlyForward', 'isCr'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // echo "Here";die;
        $isCr = false;
        $org_id = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();
        //echo "<pre>"; print_r($org_id);die;
        $grievances = [];
        if (in_array(174, $org_id)) {
            $grievances = Grievance::where('section', 11)->get();
        } elseif (in_array(175, $org_id)) {
            $grievances = Grievance::where('section', 12)->get();
        } else if (in_array(176, $org_id)) {
            $isCr = true;
        }
        $app = Application::find($id);
        // echo "<pre>"; print_r($app);die;
        $states = State::all();
        $organizationStates = Organization::where('org_type', 'S')->get();
        $organizationM = Organization::where('org_type', 'M')->get();
        $reasonM = Reason::where('action_code', 99)->get();
        $reasonN = Reason::where('action_code', 10)->get();
        // $application  = Application::where('active','1')->where('id','<>',$id)->get();
        // $existed_letter_no = [];
        // foreach($application as $appli){
        //     $existed_letter_no[] = $appli->letter_no;
        // }
        // $statuses = $app->statuses()
        //     ->whereIn('application_status.active', [0, 1])
        //     ->whereNotNull('remarks')
        //     ->get();
        $statuses = $app->statuses()
            ->whereIn('application_status.active', [0, 1])
            ->whereNotNull('remarks')
            ->where('received_by', auth()->user()->id)
            ->get();
        // print_r($statuses);die;



        foreach ($statuses as $status)
            $status->user = User::findorfail($status->pivot->received_by);

        $allowOnlyForward = $this->Forwardbuttoncommon($app);

        if ($app->received_by == auth()->user()->id && auth()->check() && auth()->user()->roles->pluck('id')->intersect([1, 4])->isNotEmpty() && $app->statuses->first() && $app->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(0)) {
            $allowDraft = true;
            $allowOnlyForward = false;
        } else {
            $allowDraft = false;
        }

        return view('application', compact('app', 'organizationStates', 'states', 'grievances', 'reasonM', 'reasonN', 'organizationM', 'statuses', 'allowDraft', 'allowOnlyForward', 'isCr'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  //  echo "<pre>";print_r($request->all());die;
        $app = new Application();

        if (isset($request->id) && $request->id) {
            $app = Application::find($request->id);
        }
        $org_id = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();
        // if (isset($request->id) && $request->id) {
        //     $app = Application::find($request->id);
        //     if ($request->letter_no && $request->letter_no !== null && Application::where('letter_no', $request->letter_no)->where('id', '<>', $request->id)->exists()) {
        //         $letter_no_msg = "Letter no already exist!";
        //         session()->put('error', $letter_no_msg);
        //         return back()->withInput($request->input());
        //     }
        // }
        // if ($request->letter_no && $request->letter_no !== null && (!$request->id || $request->id == null) && Application::where('letter_no', $request->letter_no)->exists()) {
        //     $letter_no_msg = "Letter no already exist!";
        //     session()->put('error', $letter_no_msg);
        //     return back()->withInput($request->input());
        // }

        if ($request->language_of_letter != 'O') {
            if ($request->input('submit') == 'Forward') {
                //                $validatedData=$request->validate([
                // $request->validate([
                //     'reg_no' => 'nullable',
                //     'applicant_title' => 'nullable',
                //     'applicant_name' => 'required',
                //     'address' => 'required',
                //     'pincode' => ['nullable', 'digits:6'],
                //     'state_id' => 'nullable|numeric',
                //     'org_from' => 'nullable',
                //     'letter_date' => 'nullable|date_format:Y-m-d|before_or_equal:today',
                //     'gender' => ['nullable', new Gender],
                //     'language_of_letter' => ['nullable', new Language],
                //     'country' => ['required', new Country],
                //     'phone_no' => ['nullable', 'digits:11'],
                //     'mobile_no' => ['nullable', 'digits:10'],
                //     'email_id' => 'nullable|email',
                //     'letter_no' => 'required',
                //     'letter_subject' => 'required',
                //     'letter_body' => 'nullable',
                //     if($request->org_id=="176"){
                //     'acknowledgement' => ['nullable', new Acknowledgement],
                //     'grievance_category_id' => 'nullable|numeric',
                //     //                    'action_org' => ['required', 'string', 'size:1', 'in:N,F,M,S'],'department_org_id',reason_id
                //     'action_org' => ['required', new ActionOrg],
                //     'reason_id' => 'required_if:action_org,==,M,N|nullable|numeric',
                //     'department_org_id' => 'required_if:action_org,==,F,S|nullable|numeric',
                //     'remarks' => 'nullable',
                //     'reply' => 'nullable',
                //     }
                //     'file_path' => $app->file_path && $app->file_path != null ? 'nullable|file|mimes:pdf|max:20480' : 'required|file|mimes:pdf|max:20480',
                // ]);
                $rules = [
                    'reg_no' => 'nullable',
                    'applicant_title' => 'nullable',
                    'applicant_name' => 'required',
                    'address' => 'required',
                    'pincode' => ['nullable', 'digits:6'],
                    'state_id' => 'nullable|numeric',
                    'org_from' => 'nullable',
                    'letter_date' => 'nullable|date_format:Y-m-d|before_or_equal:today',
                    'gender' => ['nullable', new Gender],
                    'language_of_letter' => ['nullable', new Language],
                    'country' => ['required', new Country],
                    'phone_no' => ['nullable', 'digits:11'],
                    'mobile_no' => ['nullable', 'digits:10'],
                    'email_id' => 'nullable|email',
                    'letter_no' => 'required',
                    'letter_subject' => 'required',
                    'letter_body' => 'nullable',
                    'file_path' => $app->file_path && $app->file_path != null ? 'nullable|file|mimes:pdf|max:20480' : 'required|file|mimes:pdf|max:20480',

                ];

                // Add additional rules if org_id is 176

                if (!in_array(176, $org_id)) {
                    $rules = array_merge($rules, [
                        'acknowledgement' => ['nullable', new Acknowledgement],
                        'grievance_category_id' => 'nullable|numeric',
                        'action_org' => ['required', new ActionOrg],
                        'reason_id' => 'required_if:action_org,M,N|nullable|numeric',
                        'department_org_id' => 'required_if:action_org,F,S|nullable|numeric',
                        'remarks' => 'nullable',
                        'reply' => 'nullable',
                    ]);
                }
                if (in_array(176, $org_id)) {
                    $rules = array_merge($rules, [
                        'forwarded_to' => 'required',

                    ]);
                }


                // Validate the request
                $request->validate($rules);

                //                if(!$validatedData)
//                    return back()->with('error',$validatedData);
            }
        }


        $app->applicant_title = $request->applicant_title;
        $app->applicant_name = $request->applicant_name;
        $app->address = $request->address;
        $app->pincode = $request->pincode;
        $app->state_id = $request->state_id;
        $app->org_from = $request->org_from;
        $app->letter_date = $request->letter_date;
        $app->gender = $request->gender;
        $app->language_of_letter = $request->language_of_letter;
        $app->country = $request->country;
        $app->phone_no = $request->phone_no;
        if ($request->mobile_no)
            $app->mobile_no = '+91' . $request->mobile_no;
        $app->email_id = $request->email_id;
        $app->letter_no = $request->letter_no;
        $app->letter_subject = $request->letter_subject;
        $app->letter_body = $request->letter_body;
        $app->acknowledgement = $request->acknowledgement;
        if ($request->acknowledgement && $request->acknowledgement == "Y") {
            if (($request->email_id && $request->email_id !== null) && ($app->email_id && $app->email_id !== null))
                $app->ack_mail_sent = "R";
            else
                $app->ack_mail_sent = "NR";
            $app->ack_offline_post = "R";
        } else {
            $app->ack_mail_sent = "NR";
            $app->ack_offline_post = "NR";
        }
        $app->grievance_category_id = $request->grievance_category_id;
        $app->action_org = $request->action_org;
        $app->remarks = $request->remarks;
        if ($request->department_org_id && $request->reason_id == null) {
            $app->department_org_id = $request->department_org_id;
            $app->reason_id = null;
            $app->fwd_mail_sent = "R";
            $app->fwd_offline_post = "R";
        }
        if ($request->reason_id && $request->department_org_id == null) {
            $app->department_org_id = null;
            $app->reason_id = $request->reason_id;
            $app->fwd_mail_sent = "NR";
            $app->fwd_offline_post = "NR";
        }
        // if($request->org_id=="176"){
        //$app->forwarded_section = $request->org_id;
        // }


        //        if ($request->hasFile('file_path')) {
//            {
//                $uploadedFile = $request->file('file_path');
//                $path = $uploadedFile->getRealPath();
//                $allowedExtensions = ['pdf'];
//                $fileExtension = $uploadedFile->getClientOriginalExtension();
//                if (!in_array($fileExtension, $allowedExtensions))
//                    return response(array("code" => 400, "msg" => "PDF"), 400);
//                else{
//                    $pdf = new Pdf($path);
//                    $info = $pdf->getDataFields();
//                    if ($info['NumPages'] > 0) {
//                        $fileContents = file_get_contents($path);
//                        if (empty($fileContents)) {
//                            return response(array("code" => 400, "msg" => "CORRUPT"), 400);
//                        } else {
//                            $filename =  time() . $request->file('file_path')->getClientOriginalExtension();
//                            $storedPath = $uploadedFile->storeAs($app->id . '/fee_file', $filename, 'upload');
//                            $app->file_path = base64_encode($storedPath);
//                        }
//                    }
//                    else
//                        return response(array("code" => 400, "msg" => "PASSWORD"), 400);
//
//                }
//
//            }
//            catch (Exception $e) {
//                // Handle exceptions or display error messages as needed
//                echo 'Error: ' . $e->getMessage();
//                exit;
//            }
//        }
        if ($request->input('submit') == 'Forward') {
            if ($app->id == null || ($app->received_by == auth()->user()->id && auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && $app->statuses->first() && ($app->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(0) || $app->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(1)))) {

                //reg_no
                if ($app->reg_no && $app->reg_no !== null) {
                    //if reg no exist it means it is getting updated that's why update details are here.
                    $app->updated_at = Carbon::now()->toDateTimeLocalString();
                    $app->last_updated_by = Auth::user()->id;
                    $app->last_updated_from = $request->ip();
                    $app->save();

                } else {
                    if (in_array(175, $org_id) || in_array(174, $org_id)) {
                        $currentYear = Carbon::now()->format('Y');
                        $currentMonth = Carbon::now()->format('m');
                        $currentDay = Carbon::now()->format('d');

                        $givenString = Auth::user()->username;
                        $modifiedString = substr($givenString, 0, 2) . '/' . substr($givenString, 2);

                        if ($currentMonth >= 4) {
                            // Financial year starts from April of the current year
                            $startYear = $currentYear;
                            $endYear = Carbon::now()->addYear()->format('Y');
                        } else {
                            // Financial year starts from April of the previous year
                            $startYear = Carbon::now()->subYear()->format('Y');
                            $endYear = $currentYear;
                        }

                        $startDate = Carbon::createFromFormat('Y-m-d', $startYear . '-04-01')->startOfDay();
                        $endDate = Carbon::createFromFormat('Y-m-d', $endYear . '-03-31')->endOfDay();

                        $matchingRowCount = Application::whereBetween('created_at', [$startDate, $endDate])
                            ->whereNotNull('reg_no')
                            ->where('reg_no', 'LIKE', $modifiedString . '%')
                            ->count();

                        if ($matchingRowCount > 0)
                            $count = $matchingRowCount + 1;
                        elseif ($matchingRowCount == 0)
                            $count = 1;

                        if ($count <= 9999) {
                            $petitionNumber = sprintf('%04d', $count);
                        } else {
                            $petitionNumber = $count;
                        }

                        $month = sprintf('%02d', $currentMonth);
                        $day = sprintf('%02d', $currentDay);

                        $app->reg_no = $modifiedString . '/' . $currentYear . $month . $day . $petitionNumber;
                    }
                    //if reg no does not exist it means it is a new record that's why create details are here.
                    $app->created_at = Carbon::now()->toDateTimeLocalString();
                    $app->created_by = Auth::user()->id;
                    $app->received_by = Auth::user()->id;
                    $app->created_from = $request->ip();
                    $app->save();

                }

                //file save
                if ($request->hasFile('file_path')) {
                    $this->applicantFileCommon($app, $request);
                } else {
                    if ($app->file_path) {
                        $currentFilePath = base64_decode($app->file_path);
                        if (Storage::disk('upload')->exists($currentFilePath)) {
                            $fname = str_replace('/', '_', $app->reg_no);
                            $extension = pathinfo($currentFilePath, PATHINFO_EXTENSION);
                            $newFileName = $fname . '.' . $extension;
                            if ($currentFilePath !== $newFileName) {
                                $newFilePath = 'applications/' . $app->id . '/' . $newFileName;

                                // Move the existing file to the new file name
                                Storage::disk('upload')->move($currentFilePath, $newFilePath);

                                // Update the file_path attribute with the new file name
                                $app->file_path = base64_encode($newFilePath);
                                $app->save();
                            }
                        }
                    }
                }

                $applicationId = $app->id;
                $status = $app->statuses()->wherePivot('active', 1)->get();
                $app->statuses()->updateExistingPivot(
                    $status,
                    [
                        'active' => 0,
                        'updated_at' => carbon::now()->toDateTimeLocalString()
                    ]
                );
                if (in_array(176, $org_id)) {
                    $statusId = 0.5; // For "DH" status
                } else {
                    $statusId = 2; // For "SO" status
                }

                $forwarded_section = $request->forwarded_to ?? null;

                // Attach the status_id directly to the pivot table
                $app->statuses()->attach($statusId, [
                    'created_from' => $request->ip(),
                    'created_by' => Auth::user()->id,
                    'created_at' => Carbon::now()->toDateTimeLocalString(),
                    'forwarded_section' => $forwarded_section,
                ]);

                return $this->ReturnapplicationView($app);
            }
        } elseif ($request->input('submit') === 'Draft') {
            if ($app->id == null || ($app->received_by == auth()->user()->id && auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && $app->statuses->first() && $app->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(0))) {
                if ($app->id) {
                    $app->updated_at = Carbon::now()->toDateTimeLocalString();
                    $app->last_updated_by = Auth::user()->id;
                    $app->last_updated_from = $request->ip();
                } else {
                    $app->created_at = Carbon::now()->toDateTimeLocalString();
                    $app->created_by = Auth::user()->id;
                    $app->created_from = $request->ip();
                    $app->received_by = Auth::user()->id;
                }
                if ($app->save()) {
                    if ($request->hasFile('file_path')) {
                        $this->applicantFileCommon($app, $request);
                    }
                    $applicationId = $app->id;
                    $status = $app->statuses()->wherePivot('active', 1)->get();
                    $app->statuses()->updateExistingPivot(
                        $status,
                        [
                            'active' => 0,
                            'updated_at' => carbon::now()->toDateTimeLocalString()
                        ]
                    );
                    $statusId = 0;
                    $status = Status::find($statusId);
                    if ($status) {
                        $app->statuses()->attach($status, [
                            'created_from' => $request->ip(),
                            'created_by' => Auth::user()->id,
                            'created_at' => carbon::now()->toDateTimeLocalString()
                        ]);
                    }
                    return redirect(url(route('applications.index')))->with('success', 'Draft created successfully.');
                }
            }
        } elseif (($request->input('submit') == 'Submit') && (auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && $app->statuses->first() && $app->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(4) && $app->reply == '')) {
            $app = Application::find($request->id);
            $app->reply = $request->input('reply');
            $app->updated_at = Carbon::now()->toDateTimeLocalString();
            $app->last_updated_by = Auth::user()->id;
            $app->last_updated_from = $request->ip();
            $app->save();
            $applicationId = $app->id;
            $status = $app->statuses()->wherePivot('active', 1)->get();
            $app->statuses()->updateExistingPivot(
                $status,
                [
                    'active' => 0,
                    'updated_at' => carbon::now()->toDateTimeLocalString(),

                ]
            );
            $statusId = 5;
            $status = Status::find($statusId);
            if ($status) {
                $app->statuses()->attach($status, [
                    'created_from' => $request->ip(),
                    'created_by' => Auth::user()->id,
                    'created_at' => carbon::now()->toDateTimeLocalString()
                ]);
            }

            return $this->ReturnapplicationView($app);

        } else {
            return back()->withErrors([
                'username' => 'Sorry, could not save the data.',
            ])->withInput();
        }

        return back()->withErrors([
            'username' => 'Sorry, something got wrong',
        ])->withInput();

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $app = Application::find($id);

        if (!$app)
            abort(404);
        return $this->ReturnapplicationView($app);
    }

    /**
     * update/demote the status of application
     */
    public function updateStatus(Request $request, string $application_id)
    {
        // echo "<pre>";print_r($request->all());echo "<br>";
        // print_r($application_id);die;
        $action = $request->input('submit');
        $application = Application::findOrFail($application_id);
        if ($application->statuses->first() && $application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(2)) {
            $allowSO = true;
        } else {
            $allowSO = false;
        }
        if ($application->statuses->first() && $application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(3)) {
            $allowUS = true;
        } else {
            $allowUS = false;
        }


        $remarks = $request->input('remarks');
        $user = auth()->user();
        $role_ids = $user->roles()->pluck('role_id')->toArray();

        if ((in_array(2, $role_ids)) && $allowSO) {
            if ($action == 'Approve') {
                $status = $application->statuses()->wherePivot('active', 1)->get();
                $application->statuses()->updateExistingPivot(
                    $status,
                    [
                        'active' => 0,
                        'updated_at' => carbon::now()->toDateTimeLocalString()
                    ]
                );
                $status_id = 3;
                $status = Status::findOrFail($status_id);
                $application->statuses()->attach(
                    $status,
                    [
                        'remarks' => $remarks,
                        'created_from' => $request->ip(),
                        'created_by' => Auth::user()->id,
                        'created_at' => carbon::now()->toDateTimeLocalString()
                    ]
                );

                return redirect(url(route('applications.index')))->with('success', 'Status created successfully.');
            } elseif ($action == 'Return') {
                $request->validate([
                    'remarks' => 'required',
                ]);

                $status = $application->statuses()->wherePivot('active', 1)->get();
                $application->statuses()->updateExistingPivot(
                    $status,
                    [
                        'active' => 0,
                        'updated_at' => carbon::now()->toDateTimeLocalString()
                    ]
                );
                $status_id = 1;
                $status = Status::findOrFail($status_id);
                $application->statuses()->attach(
                    $status,
                    [
                        'remarks' => $remarks,
                        'created_from' => $request->ip(),
                        'created_by' => Auth::user()->id,
                        'created_at' => carbon::now()->toDateTimeLocalString()
                    ]
                );

                return redirect(url(route('applications.index')))->with('success', 'Status created successfully.');
            }
        }


        if ((in_array(3, $role_ids)) && ($allowUS)) {

            if (Auth::user()->authority && Auth::user()->authority->Sign_path && Auth::user()->authority->Sign_path != null) {
                $imagePath = Storage::disk('upload')->path(base64_decode(Auth::user()->authority->Sign_path));
                try {
                    $imageData = file_get_contents($imagePath);
                } catch (\Exception $e) {
                    Log::error('Failed to get signature:' . $e->getMessage());
                    return redirect(url(route('authority.create')));
                }
            } else {
                return redirect(url(route('authority.create')));
            }

            if ($action == 'Approve' && $imageData) {
                $status = $application->statuses()->wherePivot('active', 1)->get();
                $application->statuses()->updateExistingPivot(
                    $status,
                    [
                        'active' => 0,
                        'updated_at' => carbon::now()->toDateTimeLocalString()
                    ]
                );
                $status_id = 4;
                $status = Status::findOrFail($status_id);
                $application->statuses()->attach(
                    $status,
                    [

                        'remarks' => $remarks,
                        'created_from' => $request->ip(),
                        'created_by' => Auth::user()->id,
                        'created_at' => carbon::now()->toDateTimeLocalString()
                    ]
                );
                $application->authority_id = Auth::user()->sign_id;
                $application->save();


                if ($application->acknowledgement === 'Y') {
                    //                    CURL
                    $imagePath = Storage::disk('upload')->path(base64_decode(Auth::user()->authority->Sign_path));
                    $imageData = file_get_contents($imagePath);
                    $imageBase64 = base64_encode($imageData);
                    $logoPath = public_path('storage/logo.png');
                    $logoData = file_get_contents($logoPath);
                    $logoBase64 = base64_encode($logoData);
                    $html = view('acknowledgementletter', compact('application', 'imageBase64', 'logoBase64'))->render();
                    $postParameter = array(
                        'htmlSource' => $html
                    );
                    Log::info('post param:' . json_encode($postParameter));
                    //                    event(new GeneratePdfEventAck($postParameter, $application));

                    //                    // Perform the cURL request and generate the PDF
//                    //server
                    $curlHandle = curl_init('http://10.197.148.102:8081/getMLPdf');
                    //                    //local
//                    $curlHandle = curl_init('http://localhost:8081/getMLPdf');
//                    //sir
////                  $curlHandle = curl_init('http://10.21.160.179:8081/getMLPdf');
//
                    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
                    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                    $curlResponse = curl_exec($curlHandle);
                    curl_close($curlHandle);
                    // Check if the response contains a valid PDF
                    if ($curlResponse && substr($curlResponse, 0, 4) == '%PDF') {
                        // Save the PDF to storage
                        $fname = str_replace('/', '_', $application->reg_no);
                        $fileName = $fname . '_acknowledgement.pdf';
                        $path = 'applications/' . $application->id . '/' . $fileName;
                        if (Storage::disk('upload')->put($path, $curlResponse)) {
                            $application->acknowledgement_path = base64_encode($path);
                            $application->save();
                        }
                    } else {
                        Log::error('pdf service down' . $curlResponse);
                        $application->ack_mail_sent = "F";
                        $application->save();
                    }


                    if (($application->email_id != null) && ($application->ack_mail_sent == "R") && ($application->acknowledgement_path !== null)) {
                        $content = storage::disk('upload')->get(base64_decode($application->acknowledgement_path));
                        if ($content && $content != null) {
                            $base644data = base64_encode($content);
                            $fname = str_replace('/', '_', $application->reg_no);
                            $file_name = $fname . "_acknowledgement.pdf";
                            $body = $application->applicant_title . " " . $application->applicant_name . ",<br><br>
                                 Your Petition has been received in Rashtrapati Bhavan with ref no " . $application->reg_no . " and forwarded to " . $application->department_org->org_desc . " for further necessary action.<br><br>
                                    Regards, <br>
                             President's Secretariat<br>";
                            $to = $application->email_id;
                            //                            $to = "us.petitions@rb.nic.in";

                            $cc = [];
                            //                            $cc[]="sayantan.saha@gov.in";
//                            $cc[]="prustysarthak123@gmail.com";
                            $cc[] = "us.petitions@rb.nic.in";
                            if ($application->createdBy->organizations()->where('user_organization.active', 1)->pluck('org_id')->contains(174)) {
                                $cc[] = "so-public1@rb.nic.in";
                                //                                $cc[] = "suman.kumari55@rb.nic.in";
                            }
                            if ($application->createdBy->organizations()->where('user_organization.active', 1)->pluck('org_id')->contains(175)) {
                                $cc[] = "so-public2@rb.nic.in";
                                //                                $cc[] = "rakesh.kumar.rb.@nic.in";
                            }
                            $data = [
                                "From" => "us.petitions@rb.nic.in",
                                "To" => [$to],
                                "Cc" => $cc,
                                "Subject" => "Reply From Rashtrapati Bhavan",
                                "Body" => $body,
                                "Attachments" => [
                                    [
                                        "AttachmentName" => $file_name,
                                        "AttachmentFile" => $base644data
                                    ],
                                ]
                            ];

                            $emailData = new EmailLog();
                            $emailData->sent_to = json_encode([
                                'to' => $to,
                                'cc' => $cc
                            ]);
                            $emailData->application_id = $application->id;
                            $emailData->email_type = 'A';
                            $jsonData = json_encode($data);
                            $jsonSizeInBytes = strlen($jsonData);
                            $jsonSizeInKB = $jsonSizeInBytes / 1024;
                            $jsonSizeInKB = round($jsonSizeInKB, 2);
                            $emailData->json_size = $jsonSizeInKB;
                            if ($base644data && $base644data != null) {
                                $headers = [
                                    'Authorization: Bearer YourAccessToken',
                                    'Content-Type: application/json',
                                    'Custom-Header: Value'
                                ];
                                $jsonData = json_encode($data);

                                $apiUrl = 'https://rb.nic.in/emailapi/api/emailsend';
                                $emailData->email_api = $apiUrl;
                                $emailData->sent_at = carbon::now()->toDateTimeLocalString();
                                $curl = curl_init();
                                curl_setopt($curl, CURLOPT_URL, $apiUrl);
                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
                                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                                $curlResponse = curl_exec($curl);
                                $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                $decode_curlResponse = json_decode($curlResponse);
                                $emailData->response_code = $responseCode;
                                $emailData->response_message = $decode_curlResponse;
                                if ($decode_curlResponse == "Email sent successfully") {
                                    $application->ack_mail_sent = "T";
                                    $application->ack_offline_post = "NR";
                                    $emailData->received_at = carbon::now()->toDateTimeLocalString();
                                    $application->save();
                                } else {
                                    //                                    $error = curl_error($curl);
                                    $application->ack_mail_sent = "F";
                                    $application->save();
                                    $emailData->received_at = carbon::now()->toDateTimeLocalString();
                                    Log::error('Failed to send ack email: ' . $curlResponse);
                                }
                                $sentAtCarbon = Carbon::parse($emailData->sent_at);
                                $receivedAtCarbon = Carbon::parse($emailData->received_at);
                                $responseTimeInSeconds = $sentAtCarbon->diffInSeconds($receivedAtCarbon);
                                $hours = floor($responseTimeInSeconds / 3600);
                                $minutes = floor(($responseTimeInSeconds % 3600) / 60);
                                $seconds = $responseTimeInSeconds % 60;
                                $responseTimeFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                                $emailData->response_time = $responseTimeFormatted;
                                $emailData->created_at = carbon::now()->toDateTimeLocalString();
                                $emailData->created_by = Auth::user()->id;
                                $emailData->created_from = $request->ip();
                                $emailData->save();
                                curl_close($curl);
                            } else {
                                $application->ack_mail_sent = "F";
                                $application->save();
                            }
                        } else {
                            $application->ack_mail_sent = "F";
                            $application->save();
                        }

                        //                $email = $application->email_id;
//                    $email = 'us.petitions@rb.nic.in';
//                    $cc = [];
//                    $cc[] = 'sayantan.saha@gov.in';
//                    $cc[] = 'so-public1@rb.nic.in';
//                    $cc[] = 'so-public2@rb.nic.in';
//                    $cc[] = 'prustysarthak123@gmail.com';
//                        $fname = str_replace('/', '_', $application->reg_no);
////                        $email = 'sayantan.saha@gov.in';
////                        $cc = [];
////                        $cc[] = 'prustysarthak123@gmail.com';
////                        $cc[] = 'shantanubaliyan935@gmail.com';
//                        $subject = 'Reply From Rashtrapati Bhavan';
//                        $details = $application->applicant_title . " " . $application->applicant_name . ",<br><br>
//                                 Your Petition has been received in Rashtrapati Bhavan with ref no " . $application->reg_no . " and forwarded to " . $application->department_org->org_desc . " for further necessary action.<br><br>
//                                    Regards, <br>
//                             President's Secretariat<br>";
//                        $content = storage::disk('upload')->get(base64_decode($application->acknowledgement_path));
//                        try {
//                            Mail::send([], [], function ($message) use ($email, $subject, $details, $content, $cc,$fname) {
//                                $message->to($email)->cc($cc[0])
//                                    ->cc($cc[1])
//                                    ->cc($cc[2])
//                                    ->cc($cc[3])
//                                    ->subject($subject)
//                                    ->html($details)
//                                    ->attachData($content, $fname . '_acknowledgement.pdf', [
//                                        'mime' => 'application/pdf',
//                                    ]);
//                            });
//                            $application->ack_mail_sent = "T";
//                            $application->ack_offline_post = "F";
//                            $application->save();
//                        } catch (\Exception $e) {
//                            $application->ack_mail_sent = "F";
//                            $application->save();
//                            Log::error('Failed to send ack email: ' . $e->getMessage());
//                        }
                    }
                    if ($application->email_id == null) {
                        $application->ack_mail_sent = "F";
                        $application->save();
                    }
                }


                if ($application->department_org && $application->department_org->id !== null) {
                    $imagePath = Storage::disk('upload')->path(base64_decode(Auth::user()->authority->Sign_path));
                    $imageData = file_get_contents($imagePath);
                    $imageBase64 = base64_encode($imageData);
                    $logoPath = public_path('storage/logo.png');
                    $logoData = file_get_contents($logoPath);
                    $logoBase64 = base64_encode($logoData);
                    $html = view('forwardedletter', compact('application', 'imageBase64', 'logoBase64'))->render();
                    ;
                    $postParameter = array(
                        'htmlSource' => $html
                    );
                    Log::info('post param:' . json_encode($postParameter));
                    $name = Auth::user()->authority->name;
                    $name_hin = Auth::user()->authority->name_hin;
                    //                    event(new GeneratePdfEventFwd($postParameter, $application,$name,$name_hin));
////                    $curlHandle = curl_init('http://localhost:8081/getMLPdf');
                    $curlHandle = curl_init('http://10.197.148.102:8081/getMLPdf');
                    //
//
                    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
                    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                    $curlResponse = curl_exec($curlHandle);
                    if (!$curlResponse) {
                        Log::error('curl error' . curl_error($curlHandle));
                    }
                    curl_close($curlHandle);
                    if ($curlResponse && substr($curlResponse, 0, 4) == '%PDF') {
                        $fname = str_replace('/', '_', $application->reg_no);
                        $fileName = $fname . '_forward.pdf';
                        $path = 'applications/' . $application->id . '/' . $fileName;
                        if (Storage::disk('upload')->put($path, $curlResponse)) {
                            $application->forwarded_path = base64_encode($path);
                            $application->save();
                        }
                    } else {
                        Log::error('pdf service down' . $curlResponse);
                        $application->fwd_mail_sent = "F";
                        $application->save();
                    }
                    if (($application->department_org->mail !== null) && ($application->fwd_mail_sent == "R") && ($application->forwarded_path !== null) && ($application->file_path)) {

                        $content = storage::disk('upload')->get(base64_decode($application->forwarded_path));
                        if ($content && $content != null) {
                            $base64co = base64_encode($content);
                            $fname = str_replace('/', '_', $application->reg_no);
                            $attachments = [
                                [
                                    "AttachmentName" => $fname . "_forward letter.pdf",
                                    "AttachmentFile" => $base64co,
                                ],
                            ];

                            if ($application->file_path) {
                                $file = storage::disk('upload')->get(base64_decode($application->file_path));
                                if ($file && $file != null) {
                                    $bas64file = base64_encode($file);
                                    $attachments[] = [
                                        "AttachmentName" => $fname . "_file.pdf",
                                        "AttachmentFile" => $bas64file,
                                    ];
                                }
                            }



                            $body = "महोदय / महोदया,<br>
                                    Sir / Madam,<br><br>
                                    कृपया उपरोक्त विषय पर भारत के राष्ट्रपति जी को संबोधित स्वतः स्पष्ट याचिका उपयुक्त ध्यानाकर्षण के लिए संलग्न है। याचिका पर की गई कार्रवाई की सूचना सीधे याचिकाकर्ता को दे दी जाये।<br>
                                    Attached please find for appropriate attention a petition addressed to the President of India which is self-explanatory. Action taken on the petition may please be communicated to the petitioner directly.<br>
                                    सादर,<br>
                                    regards,<br><br>
                                    ($name)<br>
                                    ($name_hin)<br>
                                    अवर सचिव<br>
                                    Under Secretary<br>
                                    राष्ट्रपति सचिवालय<br>
                                    President's Secretariat<br>
                                    राष्ट्रपति भवन, नई दिल्ली<br>
                                    Rashtrapati Bhavan, New Delhi";
                            $subject = $application->reg_no;
                            $to = $application->department_org->mail;
                            //                            $to = "us.petitions@rb.nic.in";

                            $cc = [];
                            //                            $cc[]="sayantan.saha@gov.in";
//                            $cc[]="prustysarthak123@gmail.com";
                            $cc[] = "us.petitions@rb.nic.in";
                            if ($application->createdBy->organizations()->where('user_organization.active', 1)->pluck('org_id')->contains(174)) {
                                $cc[] = "so-public1@rb.nic.in";
                                //                                $cc[] = "suman.kumari55@rb.nic.in";
                            }
                            if ($application->createdBy->organizations()->where('user_organization.active', 1)->pluck('org_id')->contains(175)) {
                                $cc[] = "so-public2@rb.nic.in";
                                //                                $cc[] = "rakesh.kumar.rb.@nic.in";
                            }
                            $data = [
                                "From" => "us.petitions@rb.nic.in",
                                "To" => [$to],
                                "Cc" => $cc,
                                "Subject" => $subject,
                                "Body" => $body,
                                "Attachments" => $attachments,
                            ];
                            $emailData = new EmailLog();
                            $emailData->sent_to = json_encode([
                                'to' => $to,
                                'cc' => $cc
                            ]);
                            $emailData->application_id = $application->id;
                            $emailData->email_type = 'F';
                            $jsonData = json_encode($data);
                            $jsonSizeInBytes = strlen($jsonData);
                            $jsonSizeInKB = $jsonSizeInBytes / 1024;
                            $jsonSizeInKB = round($jsonSizeInKB, 2);
                            $emailData->json_size = $jsonSizeInKB;
                            if ($base64co && $base64co != null) {
                                $headers = [
                                    'Authorization: Bearer YourAccessToken',
                                    'Content-Type: application/json',
                                    'Custom-Header: Value'
                                ];
                                $jsonData = json_encode($data);
                                $apiUrl = 'https://rb.nic.in/emailapi/api/emailsend';
                                $emailData->email_api = $apiUrl;
                                $emailData->sent_at = carbon::now()->toDateTimeLocalString();
                                $curl = curl_init();
                                curl_setopt($curl, CURLOPT_URL, $apiUrl);
                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
                                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                                $curlResponse = curl_exec($curl);
                                $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                $decode_curlResponse = json_decode($curlResponse);
                                $emailData->response_code = $responseCode;
                                $emailData->response_message = $decode_curlResponse;
                                if ($decode_curlResponse == "Email sent successfully") {
                                    $emailData->received_at = carbon::now()->toDateTimeLocalString();
                                    $application->fwd_mail_sent = "T";
                                    $application->fwd_email_id = $application->department_org->mail;
                                    $application->fwd_offline_post = "NR";
                                    $application->save();
                                } else {
                                    $emailData->received_at = carbon::now()->toDateTimeLocalString();
                                    $application->fwd_mail_sent = "F";
                                    $application->save();
                                    Log::error('Failed to send forward email: ' . $curlResponse);
                                }
                                $sentAtCarbon = Carbon::parse($emailData->sent_at);
                                $receivedAtCarbon = Carbon::parse($emailData->received_at);
                                $responseTimeInSeconds = $sentAtCarbon->diffInSeconds($receivedAtCarbon);
                                $hours = floor($responseTimeInSeconds / 3600);
                                $minutes = floor(($responseTimeInSeconds % 3600) / 60);
                                $seconds = $responseTimeInSeconds % 60;
                                $responseTimeFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                                $emailData->response_time = $responseTimeFormatted;
                                $emailData->created_at = carbon::now()->toDateTimeLocalString();
                                $emailData->created_by = Auth::user()->id;
                                $emailData->created_from = $request->ip();
                                $emailData->save();
                                curl_close($curl);
                            } else {
                                $application->fwd_mail_sent = "F";
                                $application->save();
                            }
                        } else {
                            $application->fwd_mail_sent = "F";
                            $application->save();
                        }
                        //                    $email = $application->department_org->mail;
//                        $fname = str_replace('/', '_', $application->reg_no);
////                        $email = 'sayantan.saha@gov.in';
////                        $cc = [];
////                        $cc[] = 'prustysarthak123@gmail.com';
////                        $cc[] = 'shantanubaliyan935@gmail.com';
//                    $email = 'us.petitions@rb.nic.in';
//                    $cc = [];
//                    $cc[] = 'sayantan.saha@gov.in';
//                    $cc[] = 'so-public1@rb.nic.in';
//                    $cc[] = 'so-public2@rb.nic.in';
//                    $cc[] = 'prustysarthak123@gmail.com';
//                        $subject = $application->reg_no;
//                        $details = "महोदय / महोदया,<br>
//                                    Sir / Madam,<br><br>
//                                    कृपया उपरोक्त विषय पर भारत के राष्ट्रपति जी को संबोधित स्वतः स्पष्ट याचिका उपयुक्त ध्यानाकर्षण के लिए संलग्न है। याचिका पर की गई कार्रवाई की सूचना सीधे याचिकाकर्ता को दे दी जाये।<br>
//                                    Attached please find for appropriate attention a petition addressed to the President of India which is self-explanatory. Action taken on the petition may please be communicated to the petitioner directly.<br>
//                                    सादर,<br>
//                                    regards,<br><br>
//                                    ($name)<br>
//                                    ($name_hin)<br>
//                                    अवर सचिव<br>
//                                    Under Secretary<br>
//                                    राष्ट्रपति सचिवालय<br>
//                                    President's Secretariat<br>
//                                    राष्ट्रपति भवन, नई दिल्ली<br>
//                                    Rashtrapati Bhavan, New Delhi";
//
//                        $content = storage::disk('upload')->get(base64_decode($application->forwarded_path));
//                        $file = storage::disk('upload')->get(base64_decode($application->file_path));
//                        try {
//                            $callback = function ($message) use ($email, $subject, $content, $cc, $file, $fname, $details) {
//                                $message->to($email)->cc($cc[0])
//                                    ->cc($cc[1])
//                                    ->cc($cc[2])
//                                    ->cc($cc[3])
//                                    ->subject($subject)
//                                    ->html($details)
//                                    ->attachData($content, $fname . '_forward letter.pdf', [
//                                        'mime' => 'application/pdf',
//                                    ]);
//                                if (!empty($file)) {
//                                    $message->attachData($file, $fname . '_file.pdf', [
//                                        'mime' => 'application/pdf',
//                                    ]);
//                                }
//                            };
//                            Mail::send([], [], $callback);
//                            $application->fwd_mail_sent = "T";
//                            $application->fwd_offline_post = "F";
//                            $application->save();
//                        } catch (\Exception $e) {
//                            $application->fwd_mail_sent = "F";
//                            $application->save();
//                            Log::error('Failed to send fwd email: ' . $e->getMessage());
//                        }

                    }
                    if ($application->department_org->mail == null) {
                        $application->fwd_mail_sent = "F";
                        $application->save();
                    }
                }
                return redirect(url(route('applications.index')))->with('success', 'Status created successfully.');
            } elseif ($action == 'Return') {
                $request->validate([
                    'remarks' => 'required',
                ]);

                $status = $application->statuses()->wherePivot('active', 1)->get();
                $application->statuses()->updateExistingPivot(
                    $status,
                    [
                        'active' => 0,
                        'updated_at' => carbon::now()->toDateTimeLocalString()
                    ]
                );
                $status_id = 2;
                $status = Status::findOrFail($status_id);
                $application->statuses()->attach(
                    $status,
                    [
                        'remarks' => $remarks,
                        'created_from' => $request->ip(),
                        'created_by' => Auth::user()->id,
                        'created_at' => carbon::now()->toDateTimeLocalString()
                    ]
                );
                return redirect(url(route('applications.index')))->with('success', 'Status created successfully.');
            } else {
                return redirect()->back()->with('error', 'approve not working');
            }
        } else {
            return redirect()->back()->with('error', 'role not found');
        }
    }
    public function acceptFromCR(Request $request, string $application_id)
    {
        // Validate the incoming request data
        $validator = Validator::make(['application_id' => $application_id], [
            'application_id' => 'required|integer|exists:application_status,application_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid application ID.'], 400);
        }

        try {
            // Check if the application and its status are active
            // $isActiveInApplications = DB::table('applications')
            //     ->where('id', $application_id)
            //     ->where('active', 1)
            //     ->exists();

            // $isActiveInApplicationStatus = DB::table('application_status')
            //     ->where('application_id', $application_id)
            //     ->where('active', 1)
            //     ->exists();

            // If either check fails, return an error
            // if (!$isActiveInApplications || !$isActiveInApplicationStatus) {
            //     return response()->json(['error' => 'The application or its status is not active.'], 400);
            // }

            // Begin transaction
            DB::beginTransaction();

            // Deactivate previous application_status record
            DB::table('application_status')
                ->where('application_id', $application_id)
                ->update(['active' => 0]);


            // Insert new application_status record
            DB::table('application_status')->insert([
                'application_id' => $application_id,
                'status_id' => 0,
                'remarks' => null, // Adjust this if dynamic remarks are needed
                'created_at' => now(),
                'active' => 1,
                'created_from' => $request->ip(), // System IP address
                'created_by' => auth()->id(), // Current authenticated user ID
            ]);

            // Update the applications table only where active = 1
            $userId = auth()->id();
            $affectedRows = DB::table('applications')
                ->where('id', $application_id)
                ->where('active', 1)
                ->update(['received_by' => $userId]);

            // Check if the applications table was updated successfully
            if ($affectedRows === 0) {
                DB::rollBack();
                return response()->json(['error' => 'Failed to update the applications table.'], 400);
            }

            // Commit transaction
            DB::commit();

            return response()->json([
                'message' => 'Application status updated successfully.',
                'redirect_url' => route('applications.edit', ['application' => $application_id]),
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on failure
            DB::rollBack();

            // Log the error for debugging
            \Log::error('Error accepting application from CR: ', [
                'message' => $e->getMessage(),
                'application_id' => $application_id,
            ]);

            return response()->json(['error' => 'An error occurred while accepting the application.'], 500);
        }
    }

    public function forwardTo(Request $request, string $application_id)
    {
        // Validate the incoming request data
        // echo "<pre>";print_r($request->all());die;
        $validator = Validator::make(
            [
                'application_id' => $application_id,
                'event_id' => $request->input('event_id'),
                'remarks' => $request->input('remarks'),
            ],
            [
                'application_id' => 'required',
                'event_id' => 'required',
                'remarks' => 'required|max:255',
            ]
        );

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Check if the application and its status are active
        // $isActiveInApplications = DB::table('applications')
        //     ->where('id', $application_id)
        //     ->where('active', 1)
        //     ->exists();

        // $isActiveInApplicationStatus = DB::table('application_status')
        //     ->where('application_id', $application_id)
        //     ->where('active', 1)
        //     ->exists();
        // print_r($isActiveInApplicationStatus,$isActiveInApplications);die;
        // If either check fails, return an error
        // if (!$isActiveInApplications || !$isActiveInApplicationStatus) {
        //     return response()->json(['error' => 'The application or its status is not active.'], 400);
        // }

        try {
            // Begin transaction
            DB::beginTransaction();

            // Deactivate previous application_status record
            DB::table('application_status')
                ->where('application_id', $application_id)
                ->update(['active' => 0]);


            // Insert new application_status record
            DB::table('application_status')->insert([
                'application_id' => $application_id,
                'status_id' => 0.5,
                'remarks' => $request->input('remarks'),
                'created_at' => now(),
                'active' => 1,
                'forwarded_section' => $request->input('event_id'),
                'created_from' => $request->ip(), // System IP address
                'created_by' => auth()->id(), // Current authenticated user ID
            ]);

            // Update the applications table
            DB::table('applications')
                ->where('id', $application_id)
                ->update([
                    'forwarded_section' => $request->input('event_id'),
                ]);

            // Commit transaction
            DB::commit();

            // Return success response
            return response()->json([
                'message' => 'Application forwarded successfully.',
                'redirect_url' => route('applications.index'),
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on failure
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while forwarding the application.'], 500);
        }
    }

    public function updatePrint(Request $request)
    {
        $applications = Application::find($request->input('selectedId'));

        if ($request->input('action') === 'open') {
            foreach ($applications as $application) {
                if ($request->letter == 'Acknowledgement Letter') {
                    $application->ack_offline_post = 'T';
                    $application->save();
                    return view('acknowledgementprint', compact('applications'));
                } elseif ($request->letter == 'Forward Letter') {
                    $application->fwd_offline_post = 'T';
                    $application->save();
                    return view('forwardprint', compact('applications'));
                }
            }
        }

        if ($request->input('action') === 'update') {
            foreach ($applications as $application) {
                if ($request->letter == 'Acknowledgement Letter') {
                    $application->ack_offline_post = 'T';
                    $application->save();
                } elseif ($request->letter == 'Forward Letter') {
                    $application->fwd_offline_post = 'T';
                    $application->save();
                }
            }
            return redirect()->back()->with('success', 'dispatch Status updated successfully.');
        }

        if ($request->input('action') === 'mail') {
            foreach ($applications as $application) {
                if ($request->letter == 'Acknowledgement Letter') {
                    $user = User::findOrFail(3);
                    if ($application->acknowledgement === 'Y') {
                        if ($application->acknowledgement_path == null) {
                            if ($user->authority->Sign_path) {
                                $imagePath = Storage::disk('upload')->path(base64_decode($user->authority->Sign_path));
                                $imageData = file_get_contents($imagePath);
                                $imageBase64 = base64_encode($imageData);
                                $logoPath = public_path('storage/logo.png');
                                $logoData = file_get_contents($logoPath);
                                $logoBase64 = base64_encode($logoData);
                                $html = view('acknowledgementletter', compact('application', 'imageBase64', 'logoBase64', 'user'))->render();
                                $postParameter = array(
                                    'htmlSource' => $html
                                );
                                Log::info('post param:' . json_encode($postParameter));
                                $curlHandle = curl_init('http://10.197.148.102:8081/getMLPdf');
                                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
                                curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                                $curlResponse = curl_exec($curlHandle);
                                curl_close($curlHandle);
                                if ($curlResponse && substr($curlResponse, 0, 4) == '%PDF') {
                                    // Save the PDF to storage
                                    $fname = str_replace('/', '_', $application->reg_no);
                                    $fileName = $fname . '_acknowledgement.pdf';
                                    $path = 'applications/' . $application->id . '/' . $fileName;
                                    if (Storage::disk('upload')->put($path, $curlResponse)) {
                                        $application->acknowledgement_path = base64_encode($path);
                                        $application->save();
                                    }
                                } else {
                                    Log::error('pdf service down' . $curlResponse);
                                    $application->ack_mail_sent = "F";
                                    $application->save();
                                    return back()->withErrors(['username' => 'Sorry, pdf service down']);
                                }
                            } else
                                return back()->withErrors(['username' => 'Sorry, sign not found']);
                        }

                        if (($application->email_id != null) && ($application->ack_mail_sent == "F") && ($application->acknowledgement_path !== null)) {
                            $content = storage::disk('upload')->get(base64_decode($application->acknowledgement_path));
                            if ($content && $content != null) {
                                $base644data = base64_encode($content);
                                $fname = str_replace('/', '_', $application->reg_no);
                                $file_name = $fname . "_acknowledgement.pdf";
                                $body = $application->applicant_title . " " . $application->applicant_name . ",<br><br>
                                 Your Petition has been received in Rashtrapati Bhavan with ref no " . $application->reg_no . " and forwarded to " . $application->department_org->org_desc . " for further necessary action.<br><br>
                                    Regards, <br>
                                President's Secretariat<br>";
                                $to = $application->email_id;
                                //                              $to = "us.petitions@rb.nic.in";

                                $cc = [];
                                //                                $cc[]="sayantan.saha@gov.in";
//                                $cc[]="prustysarthak123@gmail.com";
                                $cc[] = "us.petitions@rb.nic.in";
                                if ($application->createdBy->organizations()->where('user_organization.active', 1)->pluck('org_id')->contains(174)) {
                                    $cc[] = "so-public1@rb.nic.in";
                                    //                                    $cc[] = "suman.kumari55@rb.nic.in";
                                }
                                if ($application->createdBy->organizations()->where('user_organization.active', 1)->pluck('org_id')->contains(175)) {
                                    $cc[] = "so-public2@rb.nic.in";
                                    //                                    $cc[] = "rakesh.kumar.rb.@nic.in";
                                }
                                $data = [
                                    "From" => "us.petitions@rb.nic.in",
                                    "To" => [$to],
                                    "Cc" => $cc,
                                    "Subject" => "Reply From Rashtrapati Bhavan",
                                    "Body" => $body,
                                    "Attachments" => [
                                        [
                                            "AttachmentName" => $file_name,
                                            "AttachmentFile" => $base644data
                                        ],
                                    ]
                                ];
                                $emailData = new EmailLog();
                                $emailData->sent_to = json_encode([
                                    'to' => $to,
                                    'cc' => $cc
                                ]);
                                $emailData->application_id = $application->id;
                                $emailData->email_type = 'A';
                                $jsonData = json_encode($data);
                                $jsonSizeInBytes = strlen($jsonData);
                                $jsonSizeInKB = $jsonSizeInBytes / 1024;
                                $jsonSizeInKB = round($jsonSizeInKB, 2);
                                $emailData->json_size = $jsonSizeInKB;
                                if ($base644data && $base644data != null) {
                                    $headers = [
                                        'Authorization: Bearer YourAccessToken',
                                        'Content-Type: application/json',
                                        'Custom-Header: Value'
                                    ];
                                    $jsonData = json_encode($data);
                                    $apiUrl = 'https://rb.nic.in/emailapi/api/emailsend';
                                    $emailData->email_api = $apiUrl;
                                    $emailData->sent_at = carbon::now()->toDateTimeLocalString();
                                    $curl = curl_init();
                                    curl_setopt($curl, CURLOPT_URL, $apiUrl);
                                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
                                    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                                    $curlResponse = curl_exec($curl);
                                    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                    $decode_curlResponse = json_decode($curlResponse);
                                    $emailData->response_code = $responseCode;
                                    $emailData->response_message = $decode_curlResponse;
                                    if ($decode_curlResponse == "Email sent successfully") {
                                        $emailData->received_at = carbon::now()->toDateTimeLocalString();
                                        $application->ack_mail_sent = "T";
                                        $application->ack_offline_post = "NR";
                                        $application->save();
                                    } else {
                                        $emailData->received_at = carbon::now()->toDateTimeLocalString();
                                        $application->ack_mail_sent = "F";
                                        $application->save();
                                        Log::error('Failed to send ack email: ' . $curlResponse);
                                    }
                                    $sentAtCarbon = Carbon::parse($emailData->sent_at);
                                    $receivedAtCarbon = Carbon::parse($emailData->received_at);
                                    $responseTimeInSeconds = $sentAtCarbon->diffInSeconds($receivedAtCarbon);
                                    $hours = floor($responseTimeInSeconds / 3600);
                                    $minutes = floor(($responseTimeInSeconds % 3600) / 60);
                                    $seconds = $responseTimeInSeconds % 60;
                                    $responseTimeFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                                    $emailData->response_time = $responseTimeFormatted;
                                    $emailData->created_at = carbon::now()->toDateTimeLocalString();
                                    $emailData->created_by = Auth::user()->id;
                                    $emailData->created_from = $request->ip();
                                    $emailData->save();
                                    curl_close($curl);
                                } else {
                                    $application->ack_mail_sent = "F";
                                    $application->save();
                                }
                            } else {
                                $application->ack_mail_sent = "F";
                                $application->save();
                            }
                        }
                        if ($application->email_id == null) {
                            $application->ack_mail_sent = "F";
                            $application->save();
                        }
                    }
                } elseif ($request->letter == 'Forward Letter') {
                    $user = User::findOrFail(3);
                    $name = $user->authority->name;
                    $name_hin = $user->authority->name_hin;
                    if ($application->department_org && $application->department_org->id !== null) {
                        if ($application->forwarded_path == null) {
                            if ($user->authority->Sign_path) {
                                $imagePath = Storage::disk('upload')->path(base64_decode($user->authority->Sign_path));
                                $imageData = file_get_contents($imagePath);
                                $imageBase64 = base64_encode($imageData);
                                $logoPath = public_path('storage/logo.png');
                                $logoData = file_get_contents($logoPath);
                                $logoBase64 = base64_encode($logoData);
                                $html = view('forwardedletter', compact('application', 'imageBase64', 'logoBase64', 'user'))->render();
                                ;
                                $postParameter = array(
                                    'htmlSource' => $html
                                );
                                Log::info('post param:' . json_encode($postParameter));
                                $curlHandle = curl_init('http://10.197.148.102:8081/getMLPdf');
                                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
                                curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                                $curlResponse = curl_exec($curlHandle);
                                if (!$curlResponse) {
                                    Log::error('curl error' . curl_error($curlHandle));
                                }
                                curl_close($curlHandle);
                                if ($curlResponse && substr($curlResponse, 0, 4) == '%PDF') {
                                    $fname = str_replace('/', '_', $application->reg_no);
                                    $fileName = $fname . '_forward.pdf';
                                    $path = 'applications/' . $application->id . '/' . $fileName;
                                    if (Storage::disk('upload')->put($path, $curlResponse)) {
                                        $application->forwarded_path = base64_encode($path);
                                        $application->save();
                                    }
                                } else {
                                    Log::error('pdf service down' . $curlResponse);
                                    $application->fwd_mail_sent = "F";
                                    $application->save();
                                    return back()->withErrors(['username' => 'Sorry, pdf service down']);
                                }
                            } else
                                return back()->withErrors(['username' => 'Sorry, sign not found']);
                        }
                        if (($application->department_org->mail !== null) && ($application->fwd_mail_sent == "F") && ($application->forwarded_path !== null) && ($application->file_path !== null)) {
                            $content = storage::disk('upload')->get(base64_decode($application->forwarded_path));
                            if ($content && $content != null) {
                                $base64co = base64_encode($content);
                                $fname = str_replace('/', '_', $application->reg_no);
                                $attachments = [
                                    [
                                        "AttachmentName" => $fname . "_forward letter.pdf",
                                        "AttachmentFile" => $base64co,
                                    ],
                                ];

                                if ($application->file_path) {
                                    $file = storage::disk('upload')->get(base64_decode($application->file_path));
                                    if ($file && $file != null) {
                                        $bas64file = base64_encode($file);
                                        $attachments[] = [
                                            "AttachmentName" => $fname . "_file.pdf",
                                            "AttachmentFile" => $bas64file,
                                        ];
                                    }
                                }


                                $body = "महोदय / महोदया,<br>
                                    Sir / Madam,<br><br>
                                    कृपया उपरोक्त विषय पर भारत के राष्ट्रपति जी को संबोधित स्वतः स्पष्ट याचिका उपयुक्त ध्यानाकर्षण के लिए संलग्न है। याचिका पर की गई कार्रवाई की सूचना सीधे याचिकाकर्ता को दे दी जाये।<br>
                                    Attached please find for appropriate attention a petition addressed to the President of India which is self-explanatory. Action taken on the petition may please be communicated to the petitioner directly.<br>
                                    सादर,<br>
                                    regards,<br><br>
                                    ($name)<br>
                                    ($name_hin)<br>
                                    अवर सचिव<br>
                                    Under Secretary<br>
                                    राष्ट्रपति सचिवालय<br>
                                    President's Secretariat<br>
                                    राष्ट्रपति भवन, नई दिल्ली<br>
                                    Rashtrapati Bhavan, New Delhi";
                                $subject = $application->reg_no;
                                $to = $application->department_org->mail;
                                //$to = "us.petitions@rb.nic.in";
                                $cc = [];
                                //$cc[]="sayantan.saha@gov.in";
                                //$cc[]="prustysarthak123@gmail.com";
                                $cc[] = "us.petitions@rb.nic.in";
                                if ($application->createdBy->organizations()->where('user_organization.active', 1)->pluck('org_id')->contains(174)) {
                                    $cc[] = "so-public1@rb.nic.in";
                                    //$cc[] = "suman.kumari55@rb.nic.in";
                                }
                                if ($application->createdBy->organizations()->where('user_organization.active', 1)->pluck('org_id')->contains(175)) {
                                    $cc[] = "so-public2@rb.nic.in";
                                    //$cc[] = "rakesh.kumar.rb.@nic.in";
                                }
                                $data = [
                                    "From" => "us.petitions@rb.nic.in",
                                    "To" => [$to],
                                    "Cc" => $cc,
                                    "Subject" => $subject,
                                    "Body" => $body,
                                    "Attachments" => $attachments,
                                ];
                                $emailData = new EmailLog();
                                $emailData->sent_to = json_encode([
                                    'to' => $to,
                                    'cc' => $cc
                                ]);
                                $emailData->application_id = $application->id;
                                $emailData->email_type = 'F';
                                $jsonData = json_encode($data);
                                $jsonSizeInBytes = strlen($jsonData);
                                $jsonSizeInKB = $jsonSizeInBytes / 1024;
                                $jsonSizeInKB = round($jsonSizeInKB, 2);
                                $emailData->json_size = $jsonSizeInKB;
                                if ($base64co && $base64co != null) {
                                    $headers = [
                                        'Authorization: Bearer YourAccessToken',
                                        'Content-Type: application/json',
                                        'Custom-Header: Value'
                                    ];
                                    $jsonData = json_encode($data);
                                    $apiUrl = 'https://rb.nic.in/emailapi/api/emailsend';
                                    $emailData->email_api = $apiUrl;
                                    $emailData->sent_at = carbon::now()->toDateTimeLocalString();
                                    $curl = curl_init();
                                    curl_setopt($curl, CURLOPT_URL, $apiUrl);
                                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
                                    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                                    $curlResponse = curl_exec($curl);
                                    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                    $decode_curlResponse = json_decode($curlResponse);
                                    $emailData->response_code = $responseCode;
                                    $emailData->response_message = $decode_curlResponse;
                                    if ($decode_curlResponse == "Email sent successfully") {
                                        $emailData->received_at = carbon::now()->toDateTimeLocalString();
                                        $application->fwd_mail_sent = "T";
                                        $application->fwd_email_id = $application->department_org->mail;
                                        $application->fwd_offline_post = "NR";
                                        $application->save();
                                    } else {
                                        $emailData->received_at = carbon::now()->toDateTimeLocalString();
                                        $application->fwd_mail_sent = "F";
                                        $application->save();
                                        Log::error('Failed to send forward email: ' . $curlResponse);
                                    }
                                    $sentAtCarbon = Carbon::parse($emailData->sent_at);
                                    $receivedAtCarbon = Carbon::parse($emailData->received_at);
                                    $responseTimeInSeconds = $sentAtCarbon->diffInSeconds($receivedAtCarbon);
                                    $hours = floor($responseTimeInSeconds / 3600);
                                    $minutes = floor(($responseTimeInSeconds % 3600) / 60);
                                    $seconds = $responseTimeInSeconds % 60;
                                    $responseTimeFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                                    $emailData->response_time = $responseTimeFormatted;
                                    $emailData->created_at = carbon::now()->toDateTimeLocalString();
                                    $emailData->created_by = Auth::user()->id;
                                    $emailData->created_from = $request->ip();
                                    $emailData->save();
                                    curl_close($curl);
                                } else {
                                    $application->fwd_mail_sent = "F";
                                    $application->save();
                                }
                            } else {
                                $application->fwd_mail_sent = "F";
                                $application->save();
                            }
                        }
                        if ($application->department_org->mail == null) {
                            $application->fwd_mail_sent = "F";
                            $application->save();
                        }
                    }
                }
            }
            return redirect()->back()->with('success', 'dispatch Status updated successfully.');
        }

        return back()->withErrors([
            'username' => 'Sorry, something got wrong',
        ])->withInput();
    }

    /**
     * table or letter genration
     */
    public function reportprint(Request $request)
    {
        // dd($request);
        if (auth()->check() && auth()->user()->roles->pluck('id')->contains(1)) {
            $org_id = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();

            //        $organizationIds="";
            $name = "";
            $organizations = Organization::all();
            $arr = [];
            $arr[] = ['active', 1];
            if ($request->reg_no && $request->reg_no != '') {
                $arr[] = ['reg_no', 'like', '%' . $request->reg_no . '%'];
            }
            if ($request->app_date_from && $request->app_date_from != '') {
                $arr[] = ['created_at', '>=', $request->app_date_from];
                $date_from = \Carbon\Carbon::parse($request->app_date_from)->format('d-m-Y');
            }
            if ($request->app_date_to && $request->app_date_to != '') {
                $endDate = (new \DateTime($request->app_date_to))->setTime(23, 59, 59);
                $arr[] = ['created_at', '<=', $endDate->format('Y-m-d H:i:s')];
                $date_to = \Carbon\Carbon::parse($request->app_date_to)->format('d-m-Y');
            }

            if ($request->orgTypeMin && $request->orgTypeMin != '') {
                if ($request->orgTypeMin == 'name') {
                    $arr[] = ['action_org', 'F'];
                    if ($request->orgDescMin && $request->orgDescMin != '') {
                        $arr[] = ['department_org_id', $request->orgDescMin];
                    }
                } elseif ($request->orgTypeMin == 'type') {
                    $arr[] = ['action_org', 'S'];
                    if ($request->orgDescStat && $request->orgDescStat != '') {
                        $arr[] = ['department_org_id', $request->orgDescStat];
                    }
                }
            }

            if ($request->org_reason && $request->org_reason != '') {
                if ($request->org_reason == 'noaction') {
                    if ($request->org_reason_mn && $request->org_reason_mn != '') {
                        if ($request->org_reason_mn == 'N') {
                            $arr[] = ['action_org', 'N'];
                            if ($request->org_reason_N && $request->org_reason_N != '') {
                                $arr[] = ['reason_id', $request->org_reason_N];
                                $reason = Reason::findOrFail($request->org_reason_N);
                                $name = $reason->reason_desc;
                            }
                        } else if ($request->org_reason_mn == 'M') {
                            $arr[] = ['action_org', 'M'];
                            if ($request->org_reason_M && $request->org_reason_M != '') {
                                $arr[] = ['reason_id', $request->org_reason_M];
                                $reason = Reason::findOrFail($request->org_reason_M);
                                $name = $reason->reason_desc;
                            }
                        }
                    }
                }
                if ($request->org_reason == 'forwarded') {
                    if ($request->org_reason_org && $request->org_reason_org != '') {
                        if ($request->org_reason_org == 'S') {
                            $arr[] = ['action_org', 'S'];
                            if ($request->org_reason_state && $request->org_reason_state != '') {
                                $arr[] = ['department_org_id', $request->org_reason_state];
                                $reason = Organization::findOrFail($request->org_reason_state);
                                $name = $reason->org_desc;
                            }
                        } else if ($request->org_reason_org == 'F') {
                            $arr[] = ['action_org', 'F'];
                            if ($request->org_reason_cabinate && $request->org_reason_cabinate != '') {
                                $arr[] = ['department_org_id', $request->org_reason_cabinate];
                                $reason = Organization::findOrFail($request->org_reason_cabinate);
                                $name = $reason->org_desc;
                            }
                        }
                    }
                }
                // if ($request->orgTT == 'name') {
                //     $arr[] = ['action_org', 'F'];
                //     if ($request->orgDescMM && $request->orgDescMM != '') {
                //         $arr[] = ['department_org_id', $request->orgDescMM];
                //         $org = Organization::findOrFail($request->orgDescMM);
                //         $name = $org->org_desc;
                //     }
                // } elseif ($request->orgTT == 'type') {
                //     $arr[] = ['action_org', 'S'];
                //     if ($request->orgDescSS && $request->orgDescSS != '') {
                //         $arr[] = ['department_org_id', $request->orgDescSS];
                //         $org = Organization::findOrFail($request->orgDescSS);
                //         $name = $org->org_desc;
                //     }
                // }elseif($request->orgTT == 'noaction'){
                //     $arr[] = ['action_org', 'N'];
                //     if ($request->reasonN && $request->reasonN != '') {
                //         $arr[] = ['reason_id', $request->reasonN];
                //         $reason = Reason::findOrFail($request->reasonN);
                //         $name = $reason->reason_desc;
                //     }
                // }elseif($request->orgTT == 'miscellaneous'){
                //     $arr[] = ['action_org', 'M'];
                //     if ($request->reasonM && $request->reasonM != '') {
                //         $arr[] = ['reason_id', $request->reasonM];
                //         $reason = Reason::findOrFail($request->reasonM);
                //         $name = $reason->reason_desc;
                //     }
                // }
            }
            if ($request->organization && $request->organization != '') {
                $org_id = [];
                $org_id[] = $request->organization;
            }
            if ($request->from && $request->from != '') {
                $arr[] = ['created_by', '=', auth()->user()->id];
            }

            $us = SignAuthority::where('active', 1)->first();

            //        if ($request->state && $request->state != '') {
//            $state= State::findOrFail($request->state);
//            $name = $state->state_name;
//            $organizationIds = Organization::where('state_id',$request->state)->pluck('id')->toArray();
//            $arr[] = ['department_org_id', $organizationIds];
//        }


            $query = Application::where($arr)
                ->whereIn('received_by', function ($query) use ($org_id) {
                    $query->select('users.id')
                        ->from('users')
                        ->join('user_organization', 'users.id', '=', 'user_organization.user_id')
                        ->whereIn('user_organization.org_id', $org_id);
                })
                ->whereHas('statuses', function ($query) {
                    $query->whereIn('status_id', [4, 5])
                        ->where('application_status.active', 1);
                });

            // Add additional conditions based on the 'submit' value
            switch ($request->input('submit')) {

                case 'acknowledgement':
                    $query->where('acknowledgement', 'Y')
                        //                    ->where('acknowledgement_path', '!=', null)
                        ->when($request->filled('mail') && $request->mail == 'mailed', function ($query) {
                            return $query->where('ack_mail_sent', "T")
                                ->where('ack_offline_post', "NR");
                        })
                        ->when($request->filled('mail') && $request->mail == 'Pending', function ($query) {
                            return $query->where('ack_mail_sent', "F")
                                ->where('ack_offline_post', "R");
                        })
                        ->when($request->filled('mail') && $request->mail == 'Pending_mail', function ($query) {
                            return $query->where('ack_mail_sent', "F")
                                ->where('ack_offline_post', "R")
                                ->where('acknowledgement', 'Y')
                                ->whereNotNull('email_id');
                        })
                        ->when($request->filled('mail') && $request->mail == 'Pending_noMail', function ($query) {
                            return $query->where('ack_mail_sent', "F")
                                ->where('ack_offline_post', "R")
                                ->where('acknowledgement', 'Y')
                                ->whereNull('email_id');
                        })
                        ->when($request->filled('mail') && $request->mail == 'Offline', function ($query) {
                            return $query->where('ack_mail_sent', "F")
                                ->where('ack_offline_post', "T");
                        })
                        ->when($request->filled('mail') && $request->mail == 'all', function ($query) {
                            return $query->where(function ($query) {
                                $query->orWhere(function ($query) {
                                    $query->where('ack_mail_sent', 'T')
                                        ->where('ack_offline_post', 'NR');
                                })
                                    ->orWhere(function ($query) {
                                        $query->where('ack_mail_sent', 'F')
                                            ->where('ack_offline_post', 'R');
                                    })
                                    ->orWhere(function ($query) {
                                        $query->where('ack_mail_sent', 'F')
                                            ->where('ack_offline_post', 'T');
                                    });
                            });
                        });
                    //                    ->when($request->filled('mail') && $request->mail == 'none', function ($query) use (&$offlineAapplications) {
//                        $offlineAapplications = $query->Where(function ($query) {
//                            $query->where('ack_mail_sent', "F")
//                                ->where('ack_offline_post', "R");
//                        });
//                        return $query;
//                    });
                    $applications = $query->get();
                    // echo '<pre>';print_r($applications);die;
//                if ((($request->filled('mail') && $request->mail == 'Pending')) || ($offlineAapplications && $offlineAapplications!==null)) {
//                    if (($request->filled('mail') && $request->mail == 'Pending')) {
//                        $letter = 'Acknowledgement Letter';
//                        return view('printList', compact('applications', 'letter'));
//                    } else {
//                        return view('acknowledgementprint', compact('applications'));
//                    }
                    if ($request->dashboard && $request->dashboard == "toPrintStatus" && $request->mail == 'Pending_mail') {
                        $letter = 'Acknowledgement Letter';
                        $button = 'mailable';
                        return view('printList', compact('applications', 'letter', 'button'));
                    }
                    if ($request->dashboard && $request->dashboard == "toPrintStatus" && $request->mail == 'Pending_noMail') {
                        $letter = 'Acknowledgement Letter';
                        $button = 'nomailable';
                        return view('printList', compact('applications', 'letter', 'button'));
                    } else {
                        return view('acknowledgementprint', compact('applications'));
                    }

                case 'Forward':
                    $query->whereIn('action_org', ['S', 'F'])
                        //                    ->where('forwarded_path', '!=', null)
                        ->when($request->filled('mail') && $request->mail == 'mailed', function ($query) {
                            return $query->where('fwd_mail_sent', "T")
                                ->where('fwd_offline_post', "NR");
                        })
                        ->when($request->filled('mail') && $request->mail == 'Pending', function ($query) {
                            return $query->where('fwd_mail_sent', "F")
                                ->where('fwd_offline_post', "R");
                        })
                        ->when($request->filled('mail') && $request->mail == 'Pending_mail', function ($query) {
                            return $query->where('fwd_mail_sent', "F")
                                ->where('fwd_offline_post', "R")
                                ->whereExists(function ($subquery) {
                                    $subquery->selectRaw(1)
                                        ->from('organizations')
                                        ->whereColumn('organizations.id', 'applications.department_org_id')
                                        ->whereNotNull('organizations.mail');
                                });
                        })
                        ->when($request->filled('mail') && $request->mail == 'Pending_noMail', function ($query) {
                            return $query->where('fwd_mail_sent', "F")
                                ->where('fwd_offline_post', "R")
                                ->whereExists(function ($subquery) {
                                    $subquery->selectRaw(1)
                                        ->from('organizations')
                                        ->whereColumn('organizations.id', 'applications.department_org_id')
                                        ->whereNull('organizations.mail');
                                });
                        })
                        ->when($request->filled('mail') && $request->mail == 'Offline', function ($query) {
                            return $query->where('fwd_mail_sent', "F")
                                ->where('fwd_offline_post', "T");
                        })
                        ->when($request->filled('mail') && $request->mail == 'all', function ($query) {
                            return $query->where(function ($query) {
                                $query->orWhere(function ($query) {
                                    $query->where('fwd_mail_sent', "T")
                                        ->where('fwd_offline_post', "NR");
                                })
                                    ->orWhere(function ($query) {
                                        $query->where('fwd_mail_sent', "F")
                                            ->where('fwd_offline_post', "R");
                                    })
                                    ->orWhere(function ($query) {
                                        $query->where('fwd_mail_sent', "F")
                                            ->where('fwd_offline_post', "T");
                                    });
                            });
                        });
                    //                    ->when($request->filled('mail') && $request->mail == 'none', function ($query) use (&$offlineFapplications) {
//                        $offlineFapplications = $query->Where(function ($query) {
//                            $query->where('fwd_mail_sent', "F")
//                                ->where('fwd_offline_post', "R");
//                        });
//                        return $query;
//                    });

                    $applications = $query->get();

                    //                if ((($request->filled('mail') && $request->mail == 'Pending')) || ($offlineFapplications && $offlineFapplications!==null)) {
//                    if (($request->filled('mail') && $request->mail == 'Pending')) {
//                        $letter = 'Forward Letter';
//                        return view('printList', compact('applications', 'letter'));
//                    } else {
//                        return view('forwardprint', compact('applications'));
//                    }
                    if ($request->dashboard && $request->dashboard == "toPrintStatus" && $request->mail == 'Pending_mail') {
                        $letter = 'Forward Letter';
                        $button = 'mailable';
                        return view('printList', compact('applications', 'letter', 'button'));
                    }
                    if ($request->dashboard && $request->dashboard == "toPrintStatus" && $request->mail == 'Pending_noMail') {
                        $letter = 'Forward Letter';
                        $button = 'nomailable';
                        return view('printList', compact('applications', 'letter', 'button'));
                    } else {
                        return view('forwardprint', compact('applications'));
                    }

                case 'forwardTable':
                    $query->where('department_org_id', '!=', null);
                    $applications = $query->get();
                    return view('forwardTableReport', compact('applications', 'date_from', 'date_to', 'name', 'us'));

                case 'final_Reply':
                    $query->where('reply', '!=', null);
                    $applications = $query->get();
                    return view('finalReplyReport', compact('applications', 'date_from', 'date_to', 'name', 'us'));

                case 'reportMN':
                    $query->where('reason_id', '!=', null);
                    $applications = $query->get();
                    // echo '<pre>';print_r($applications);die;
                    return view('Noaction_report', compact('applications', 'date_from', 'date_to', 'name', 'us'));

                default:
                // Handle the default case if needed
            }
            return back()->withErrors([
                'username' => 'Sorry, something got wrong',
            ])->withInput();
        }
        return back()->withErrors([
            'username' => 'Sorry, user not allowed',
        ])->withInput();
    }

    /**
     * counts by status of application
     */
    public function dashboard(Request $request)
    {

        //echo "<pre>";print_r(auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->first());die;
        $isCrOrgId = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->first();
        if ($isCrOrgId == 176) {
            return redirect()->action([self::class, 'index']);
        }
        $org_id = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();
        $org = "All";
        $org_idclick = [];
        $userDetailsp1 = [];
        $userDetailsp2 = [];

        if ($request->organization && $request->organization != '') {
            $org_id = $request->organization;
            $org = Organization::find($org_id)->org_desc;
            $org_id = [];
            $org_id[] = $request->organization;
            $org_idclick[] = $request->organization;
        }
        // if (auth()->user()->id != 3) {
        //     if (in_array(174, $org_id))
        //         $userDetails = User::getUsersWithCountsForOrg174();
        //     if (in_array(175, $org_id))
        //         $userDetails = User::getUsersWithCountsForOrg175();
        // } else
        //     $userDetails = User::getUsersWithCounts();


        $applicationData = Application::where('applications.active', 1)
            ->when(auth()->check() && auth()->user()->roles->pluck('id')->contains(1), function ($query) {
                $query->where('applications.received_by', auth()->user()->id); // Apply the filter if the user has the role with id 1
            })
            ->join('user_organization', 'applications.received_by', '=', 'user_organization.user_id')
            ->leftJoin('users', 'users.id', '=', 'user_organization.user_id')
            ->wherein('user_organization.org_id', $org_id)
            ->where('users.id', '!=', 16)
            ->where('users.id', '!=', 2)
            ->where('users.id', '!=', 3)
            ->leftJoin('organizations', 'organizations.id', '=', 'applications.department_org_id')
            ->join('application_status', 'application_status.application_id', '=', 'applications.id')
            ->where('application_status.active', 1)
            ->selectRaw('
                    SUM(CASE WHEN application_status.status_id = 0 THEN 1 ELSE 0 END) as in_draft,
                    SUM(CASE WHEN application_status.status_id = 1 THEN 1 ELSE 0 END) as pending_with_dh,
                    SUM(CASE WHEN application_status.status_id = 2 THEN 1 ELSE 0 END) as pending_with_so,
                    SUM(CASE WHEN application_status.status_id = 3 THEN 1 ELSE 0 END) as pending_with_us,
                    SUM(CASE WHEN application_status.status_id = 4 THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN application_status.status_id = 5 THEN 1 ELSE 0 END) as submitted,

                    SUM(CASE WHEN application_status.status_id IN (4, 5) AND applications.department_org_id IS NOT NULL AND fwd_mail_sent = "T" AND fwd_offline_post = "NR" THEN 1 ELSE 0 END) as fwdMailSent,
                    SUM(CASE WHEN application_status.status_id IN (4, 5) AND applications.department_org_id IS NOT NULL AND organizations.mail IS NOT NULL AND fwd_mail_sent = "F" AND fwd_offline_post = "R" THEN 1 ELSE 0 END) as fwdPendingWithMail,
                    SUM(CASE WHEN application_status.status_id IN (4, 5) AND applications.department_org_id IS NOT NULL AND organizations.mail IS NULL AND fwd_mail_sent = "F" AND fwd_offline_post = "R" THEN 1 ELSE 0 END) as fwdPendingWithoutMail,
                    SUM(CASE WHEN application_status.status_id IN (4, 5) AND applications.department_org_id IS NOT NULL AND fwd_mail_sent = "F" AND fwd_offline_post = "T" THEN 1 ELSE 0 END) as fwdPostDispatch,
                    SUM(CASE WHEN application_status.status_id IN (4, 5) AND applications.department_org_id IS NOT NULL AND ack_mail_sent = "T" AND ack_offline_post = "NR" THEN 1 ELSE 0 END) as ackMailSent,
                    SUM(CASE WHEN application_status.status_id IN (4, 5) AND applications.department_org_id IS NOT NULL AND email_id IS NOT NULL AND acknowledgement = "Y" AND ack_mail_sent = "F" AND ack_offline_post = "R" THEN 1 ELSE 0 END) as ackPendingWithMail,
                    SUM(CASE WHEN application_status.status_id IN (4, 5) AND applications.department_org_id IS NOT NULL AND email_id IS NULL AND acknowledgement = "Y" AND ack_mail_sent = "F" AND ack_offline_post = "R" THEN 1 ELSE 0 END) as ackPendingWithoutMail,
                    SUM(CASE WHEN application_status.status_id IN (4, 5) AND applications.department_org_id IS NOT NULL AND ack_mail_sent = "F" AND ack_offline_post = "T" THEN 1 ELSE 0 END) as ackPostDispatch,

                    COUNT(DISTINCT CASE WHEN applications.created_at >= CURDATE() THEN applications.id END) as today_count,
                    COUNT(DISTINCT CASE WHEN applications.created_at BETWEEN CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY AND CURDATE() + INTERVAL (6 - WEEKDAY(CURDATE())) DAY THEN applications.id END) as weekly_count,
                    COUNT(DISTINCT CASE WHEN applications.created_at BETWEEN DATE_FORMAT(CURDATE(), "%Y-%m-01") AND LAST_DAY(CURDATE()) THEN applications.id END) as monthly_count,
                    COUNT(DISTINCT CASE WHEN applications.created_at BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, "%Y-%m-01") AND LAST_DAY(CURDATE() - INTERVAL 1 MONTH) THEN applications.id END) as previous_month_count,
                    COUNT(DISTINCT applications.id) as lifetime_count,
                    users.id,
                    users.username as name,
                    users.employee_name,
                    users.active
                ')
            ->groupBy('users.id', 'users.username', 'users.employee_name')
            ->get();


        // SELECT applications.id AS application_id, application_status.status_id, application_status.created_at
        // FROM applications
        // JOIN application_status ON application_status.application_id = applications.id
        // WHERE application_status.status_id IN (0,1,2,3,4, 5)
        //   AND application_status.active = 1
        //   AND applications.id IN (
        //       SELECT applications.id
        //       FROM applications
        //       JOIN application_status ON application_status.application_id = applications.id
        //       WHERE application_status.status_id IN (0,1,2,3,4, 5)
        //         AND application_status.active = 1
        //       GROUP BY applications.id
        //       HAVING COUNT(application_status.status_id) > 1
        //   )
        // ORDER BY applications.id;

        $pending_with_dh = 0;
        $pending_with_so = 0;
        $pending_with_us = 0;
        $in_draft = 0;
        $approved = 0;
        $submitted = 0;
        $fwdMailSent = 0;
        $fwdPendingWithMail = 0;
        $fwdPendingWithoutMail = 0;
        $fwdDispatched = 0;
        $ackMailSent = 0;
        $ackPendingWithMail = 0;
        $ackPendingWithoutMail = 0;
        $ackDispatched = 0;


        foreach ($applicationData as $data) {
            $pending_with_dh += $data->pending_with_dh;
            $pending_with_so += $data->pending_with_so;
            $pending_with_us += $data->pending_with_us;
            $in_draft += $data->in_draft;
            $approved += $data->approved;
            $submitted += $data->submitted;
            $fwdMailSent += $data->fwdMailSent;
            $fwdPendingWithMail += $data->fwdPendingWithMail;
            $fwdPendingWithoutMail += $data->fwdPendingWithoutMail;
            $fwdDispatched += $data->fwdPostDispatch;
            $ackMailSent += $data->ackMailSent;
            $ackPendingWithMail += $data->ackPendingWithMail;
            $ackPendingWithoutMail += $data->ackPendingWithoutMail;
            $ackDispatched += $data->ackPostDispatch;
        }

        $organizations = Organization::all();
        $org_id = auth()->user()->organizations()->wherePivot('active', 1)->pluck('org_id')->toArray();

        if (auth()->check() && auth()->user()->organizations()->where('user_organization.active', 1)->count() > 1) {
            $allowfilter = true;
        } else {
            $allowfilter = false;
        }

        if (auth()->check() && auth()->user()->roles->pluck('id')->contains(1)) {
            $allowDH = true;
        } else {
            $allowDH = false;
        }

        return view('dashboard', compact('ackMailSent', 'ackPendingWithMail', 'ackPendingWithoutMail', 'ackDispatched', 'fwdMailSent', 'fwdPendingWithMail', 'fwdPendingWithoutMail', 'fwdDispatched', 'in_draft', 'pending_with_dh', 'pending_with_so', 'pending_with_us', 'approved', 'submitted', 'org', 'allowfilter', 'organizations', 'org_id', 'org_idclick', 'applicationData', 'allowDH'));
    }

    public function indDetails(Request $request)
    {
        $organizations = Organization::all();
        $states = State::all();

        $query = Application::with('state')
            ->where('received_by', $request->userId)
            ->where('active', 1);

        switch ($request->countDetail) {
            case 'today_count':
                $query->where('created_at', '>=', now()->startOfDay());
                break;

            case 'weekly_count':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;

            case 'monthly_count':
                $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                break;

            case 'previous_month_count':
                $query->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()]);
                break;

            case 'draft':
                $query->whereHas('statuses', function ($query) {
                    $query->whereIn('status_id', [0])->where('application_status.active', 1);
                });
                break;

            case 'pending_dh':
                $query->whereHas('statuses', function ($query) {
                    $query->whereIn('status_id', [1])->where('application_status.active', 1);
                });
                break;
        }

        $applications = $query->paginate(18) // Pagination with 10 items per page
            ->appends($request->except('page'));

        foreach ($applications as $application) {
            if (auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && ($application->created_by == auth()->user()->id) && ($application->statuses->isEmpty() || $application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(1) || $application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(0))) {
                $application->allowEdit = true;
            } else {
                $application->allowEdit = false;
            }

            if (auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && $application->statuses->first() && $application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(4) && $application->reply == '') {
                $application->allowFinalReply = true;
            } else {
                $application->allowFinalReply = false;
            }

            if (
                auth()->check() && auth()->user()->roles->pluck('id')->contains(2) && ($application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(3)) &&
                ($this->arraysAreEqual(
                    auth()->user()->organizations()->wherePivot('active', 1)->pluck('org_id')->toArray(),
                    $application->createdBy->organizations()->wherePivot('active', 1)->pluck('org_id')->toArray()
                ))
            ) {
                $application->allowPullBack = true;
            } else if (auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && ($application->created_by == auth()->user()->id) && ($application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(2))) {
                $application->allowPullBack = true;
            } else {
                $application->allowPullBack = false;
            }

            if ($application->department_org && $application->department_org->org_desc) {
                $remark = $application->department_org->org_desc;
                $application->trimmedremark = strlen($remark) > 30 ? substr($remark, 0, 25) . '...' : $remark;
            } elseif ($application->reason && $application->reason->reason_desc) {
                $remark = $application->reason->reason_desc;
                $application->trimmedremark = strlen($remark) > 30 ? substr($remark, 0, 25) . '...' : $remark;
            }
        }
        // echo '<pre>';print_r($application);die;
        // $notpaginate = true;
        return view('application_list', compact('applications', 'states', 'organizations'));

    }


    /**
     * file of application
     */
    public function getFile(string $path)
    {
        // return response()->file(base64_decode($path));
        $content = Storage::disk('upload')->get(base64_decode($path));
        if ($content != '') {
            return response($content, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline');
        } else {
            return response()->json(['code' => 404, 'msg' => 'Details not found'], 404);
        }
    }


    /**
     * @param $app
     * @return bool
     */
    public function Forwardbuttoncommon($app): bool
    {


        $org_id = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();
        $condition = false;
        if (in_array(176, $org_id)) {
            $condition = $app->created_by == auth()->user()->id;
        } else {
            $condition = $app->received_by == auth()->user()->id;
        }

        // return $allowOnlyForward;
        if ($condition && (auth()->check() && auth()->user()->roles->pluck('id')->intersect([1, 4])->isNotEmpty())) {
            $allowOnlyForward = true;
            // echo "Here";die;
        } else {
            $allowOnlyForward = false;
            // echo "Here2";die;
        }
        // $allowOnlyForward = false;
        return $allowOnlyForward;

    }

    /**
     * @param Application $app
     * @param Request $request
     * @return void
     */
    public function applicantFileCommon(Application $app, Request $request): void
    {
        $fname = str_replace('/', '_', $app->reg_no);
        if ($fname || $fname == null)
            $fname = 'file_' . date('Ymd_His');
        $filename = $fname . '.' . $request->file('file_path')->getClientOriginalExtension();
        $path = $request->file('file_path')->storeAs('applications/' . $app->id, $filename, 'upload');
        $app->file_path = base64_encode($path);
        $app->update(['file_path' => base64_encode($path)]);
    }

    /**
     * @param $app
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function ReturnapplicationView($app): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View
    {
        $notecheck = false;
        $noteblock = false;
        $finalreplyblock = false;
        $hasActiveStatusFive = false;
        $signbutton = false;
        $transferOrAccept = false;
        $canForward = false;
        $statuses = [];
        //echo "<pre>";print_r($app['forwarded_section']);die;
        $org_id = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();

        if ((in_array(175, $org_id)) || (in_array(174, $org_id))) {
            if ($app->statuses->first() && $app->statuses()->where('application_status.active', 1)->pluck('status_id')->whereIn('status_id', [1, 2, 3])) {
                $notecheck = true;
            } else {
                $notecheck = false;
            }

            if ((auth()->check() && auth()->user()->roles->pluck('id')->contains(2) && $app->statuses->first() && ($app->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(2))) || (auth()->check() && auth()->user()->roles->pluck('id')->contains(3) && Auth::user()->authority && Auth::user()->authority->Sign_path && Auth::user()->authority->Sign_path != null && $app->statuses->first() && $app->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(3))) {

                $noteblock = true;
            } else {
                $noteblock = false;
            }
            if (auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && $app->statuses->first() && $app->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(4) && $app->reply == '') {
                $finalreplyblock = true;
            } else {
                $finalreplyblock = false;
            }
            if ($app->statuses->first() && $app->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(5)) {
                $hasActiveStatusFive = true;
            } else {
                $hasActiveStatusFive = false;
            }
            if (auth()->check() && auth()->user()->roles->pluck('id')->contains(3) && $app->statuses->first() && $app->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(3) && (!Auth::user()->authority || !Auth::user()->authority->Sign_path || Auth::user()->authority->Sign_path == null)) {
                $signbutton = true;
            } else {
                $signbutton = false;
            }
            if (auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && $app->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(0.5)) {
                $transferOrAccept = true;
            } else {
                $transferOrAccept = false;
            }

            $canForward = true; // Default value

            if (empty($app['forwarded_section'])) {
                $canForward = true; // Forward is allowed if 'forwarded_section' is null or blank
            } else {
                $canForward = false; // Forward is not allowed if 'forwarded_section' has a value
            }
            $org_id = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();

            $statuses = $app->statuses()
                ->whereIn('application_status.active', [0, 1])
                // ->whereNotNull('remarks')
                ->get();
            //echo '<pre>';print_r($statuses);die;
            foreach ($statuses as $status)
                $status->user = User::findorfail($status->pivot->created_by);

        }

        return view('application_view', compact('app', 'noteblock', 'signbutton', 'finalreplyblock', 'notecheck', 'statuses', 'hasActiveStatusFive', 'transferOrAccept', 'org_id', 'canForward'));
    }

    /**
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $applications
     * @return void
     */
    public function applicationlistRequirements(\Illuminate\Contracts\Pagination\LengthAwarePaginator $applications): void
    {
        $org_id = auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();
        foreach ($applications as $application) {
            $condition = false;
            if (in_array(176, $org_id)) {
                $condition = $application->created_by == auth()->user()->id;
            } else {
                $condition = $application->received_by == auth()->user()->id;
            }
            if (
                auth()->check() &&
                auth()->user()->roles->pluck('id')->intersect([1, 4])->isNotEmpty() &&
                $condition &&
                (
                    $application->statuses->isEmpty() ||
                    $application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(1) ||
                    $application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(0)
                )
            ) {
                $application->allowEdit = true;
            } else {
                $application->allowEdit = false;
            }


            if (auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && $application->statuses->first() && $application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(4) && $application->reply == '') {
                $application->allowFinalReply = true;
            } else {
                $application->allowFinalReply = false;
            }

            if (
                auth()->check() && auth()->user()->roles->pluck('id')->contains(2) && ($application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(3)) &&
                ($this->arraysAreEqual(
                    auth()->user()->organizations()->wherePivot('active', 1)->pluck('org_id')->toArray(),
                    $application->createdBy->organizations()->wherePivot('active', 1)->pluck('org_id')->toArray()
                ))
            ) {
                $application->allowPullBack = true;
            } else if (auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && ($application->created_by == auth()->user()->id) && ($application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(2))) {
                $application->allowPullBack = true;
            } else {
                $application->allowPullBack = false;
            }

            if ($application->department_org && $application->department_org->org_desc) {
                $remark = $application->department_org->org_desc;
                $application->trimmedremark = strlen($remark) > 30 ? substr($remark, 0, 25) . '...' : $remark;
            } elseif ($application->reason && $application->reason->reason_desc) {
                $remark = $application->reason->reason_desc;
                $application->trimmedremark = strlen($remark) > 30 ? substr($remark, 0, 25) . '...' : $remark;
            }
        }
    }


    //ProcessApplicationJob::dispatch($application, $action, $remarks); // <-- Pass $remarks here

    //                    $dompdf = new Dompdf();
//                    $options = new Options();
//                    $options->setFontDir('/public/fonts');
//                    $options->setDefaultFont('hindi');
//                    $options->set('isRemoteEnabled', true);
//                    $dompdf->setOptions($options);
//                    $imagePath = Storage::disk('upload')->path(base64_decode(Auth::user()->authority->Sign_path));
//                    $imageData = file_get_contents($imagePath);
//                    $imageBase64 = base64_encode($imageData);
//                    $html = View::make('forwardedletter', compact('application','imageBase64'))->render();
//                    $dompdf->loadHtml($html);
//                    $dompdf->setPaper('A4', 'portrait');
////                    $dompdf->getOptions()->set('isFontSubsettingEnabled', false);
//                    $dompdf->render();
//                    $dompdf->stream('acknowledgement.pdf');
//                    $pdffile = $dompdf->output();
//                    $fileName = 'acknowledgement.pdf';
//                    $path = 'applications/' . $application->id . '/' . $fileName;
//                    if (Storage::disk('upload')->put($path, $pdffile)) {
//                        $application->acknowledgement_path = base64_encode($path);
//                        $application->save();
//                    }

    //                      SNAPPYPDF
//                    $imagePath = Storage::disk('upload')->path(base64_decode(Auth::user()->authority->Sign_path));
//                    $imageData = file_get_contents($imagePath);
//                    $imageBase64 = base64_encode($imageData);
//                    $html = View::make('acknowledgementletter', compact('application','imageBase64'))->render();
//                    $pdf = SnappyPdf::loadHTML($html);
//                    $binaryPdf = $pdf->output();
//                    $fileName = 'acknowledgement.pdf';
//                    $path = 'applications/' . $application->id . '/' . $fileName;
//                    Storage::disk('upload')->put($path, $binaryPdf);
//                    $application->acknowledgement_path = base64_encode($path);
//                    $application->save();

    //                    if ($application->mobile_no && !is_null($application->mobile_no) && preg_match('/^\+91\d{10}$/', $application->mobile_no)) {
//                        try {
//                            $sid = config('services.twilio.sid');
//                            $token = config('services.twilio.token');
//                            $twilioNumber = config('services.twilio.phoneNumber');
//                            $httpClient = new CurlClient();
//                            $client = new Client($sid, $token, $twilioNumber,'us1',$httpClient, [
//                                'verify' => false
//                            ]);
//                            $details = '<p>Hello, Mr/Mrs.' . $application->applicant_name . ',<br/>Your Petition has been received in Rashtrapati Bhavan with ref no ' . $application->reg_no . ' and forwarded to ' . $application->department_org->org_desc . ' for further necessary action.</p>';
//                            $details = str_replace("\n", '<br>', $details);
//                            $mobile = +918984131657;
//                            $mobile = $application->mobile_no;
//                            $message = $client->messages->create(
//                                $mobile, // recipient's phone number
//                                [
//                                    'from' => $twilioNumber,
//                                    'body' => $detail
//                                ]
//                            );
//                            return "SMS sent succesfully " ;
//                        } catch (\Exception $e) {
//                            // Handle exception and return error message
//                            return "Failed to send SMS: " . $e->getMessage();
//                        }
//                    }


    public function pullback(Request $request)
    {
        $request->validate([
            'remark' => 'required',
            'app_no' => 'required'
        ]);

        $remarks = $request->remark;
        $application = Application::findOrFail($request->app_no);
        if (
            auth()->check() && auth()->user()->roles->pluck('id')->contains(2) && ($application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(3)) &&
            ($this->arraysAreEqual(auth()->user()->organizations()->wherePivot('active', 1)->pluck('org_id')->toArray(), $application->createdBy->organizations()->wherePivot('active', 1)->pluck('org_id')->toArray()))
        ) {
            $status = $application->statuses()->wherePivot('active', 1)->get();
            $application->statuses()->updateExistingPivot(
                $status,
                [
                    'active' => 0,
                    'updated_at' => carbon::now()->toDateTimeLocalString()
                ]
            );
            $status_id = 2;
            $status = Status::findOrFail($status_id);
            $application->statuses()->attach(
                $status,
                [
                    'remarks' => $remarks,
                    'created_from' => $request->ip(),
                    'created_by' => Auth::user()->id,
                    'created_at' => carbon::now()->toDateTimeLocalString()
                ]
            );

            return redirect(url(route('applications.show', ['application' => $application])))->with('success', 'Status created successfully.');

        } elseif (auth()->check() && auth()->user()->roles->pluck('id')->contains(1) && ($application->created_by == auth()->user()->id) && ($application->statuses()->where('application_status.active', 1)->pluck('status_id')->contains(2))) {
            $status = $application->statuses()->wherePivot('active', 1)->get();
            $application->statuses()->updateExistingPivot(
                $status,
                [
                    'active' => 0,
                    'updated_at' => carbon::now()->toDateTimeLocalString()
                ]
            );
            $status_id = 1;
            $status = Status::findOrFail($status_id);
            $application->statuses()->attach(
                $status,
                [
                    'remarks' => $remarks,
                    'created_from' => $request->ip(),
                    'created_by' => Auth::user()->id,
                    'created_at' => carbon::now()->toDateTimeLocalString()
                ]
            );
            return redirect(url(route('applications.edit', ['application' => $application])))->with('success', 'Status created successfully.');
        }

        return back()->with('error', 'unauthorised pullback.');


    }

}

