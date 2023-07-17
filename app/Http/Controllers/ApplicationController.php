<?php

namespace App\Http\Controllers;
use TCPDF;
use setasign\Fpdi\Tcpdf\Fpdi;

use App\Models\Application;
use App\Models\Grievance;
use App\Models\Organization;
use App\Models\SignAuthority;
use App\Models\State;
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
class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $states=State::all();
        $organizations=Organization::all();


        //getting application based on role
        $role_ids = auth()->user()->roles()->where('user_roles.active', 1)->pluck('role_id')->toArray();
        $org_id =auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();
        $arr=[];
        $qr=[];
        $arr[]=['active',1];
        if (in_array(1, $role_ids)) {
            $arr[] = ['created_by', Auth::user()->id];
            $qr[]= 1;
            $qr[]=0;
        }
        if (in_array(2, $role_ids)) {
            $qr[]=2;
        }
        if (in_array(3, $role_ids)) {
            $qr[]=3;
        }
        $applications = Application::where($arr)
            ->whereIn('created_by', function ($query) use ($org_id) {
                $query->select('users.id')
                    ->from('users')
                    ->join('user_organization', 'users.id', '=', 'user_organization.user_id')
                    ->whereIn('user_organization.org_id', $org_id);
            })
            ->whereHas('statuses', function ($query)use ($qr) {
                $query->whereIn('status_id', $qr)
                    ->where('application_status.active', 1);
            })
            ->paginate(18);

        return view('application_list', compact('applications','states','organizations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizations = Organization::all();
        $states=State::all();
        $grievances=Grievance::all();

        $app = new Application();
        //  $appStatusRemark = $app->statuses()->wherePivot('active', 0)->pluck('pivot.remarks')->with('created by');
        return view('application', compact('app','organizations','states','grievances'));

//        $userOrganizationId = Auth::user()->org_id;
//        $userRoleId = Auth::user()->roles()->where('user_id', Auth::user()->id)->pluck('role_id')->first();
//        $allowNew = false;
//        if($userOrganizationId == 3 && $userRoleId == 1)
//            $allowNew = true;
//        return view('application', compact('app','organizations','states','grievances','allowNew'));

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        $errors = ['error' => 'Sorry, something went wrong.'];
//        return back()->withErrors($errors);

        if($request->language_of_letter!='O'){
            if ($request->input('submit') == 'Save') {
                $request->validate([
                    'reg_no'=>'nullable',
                    'applicant_title'=>'required',
                    'applicant_name'=>'required|regex:/^[a-zA-Z .]+$/',
                    'address'=>'required',
                    'pincode'=>['nullable', 'digits:6'],
                    'state_id'=>'nullable|numeric',
                    'org_from'=>'nullable',
                    'letter_date'=>'nullable|date_format:Y-m-d|before_or_equal:today',
                    'gender' => ['nullable', new Gender],
                    'language_of_letter'=>['nullable', new Language],
                    'country'=>['required', new Country],
                    'phone_no' => ['nullable', 'digits:11'],
                    'mobile_no' => ['nullable', 'digits:10'],
                    'email_id'=>'nullable|email',
                    'letter_no'=>'required',
                    'letter_subject'=>'required',
                    'letter_body'=>'required',
                    'acknowledgement'=>['nullable', new Acknowledgement],
                    'grievance_category_id'=>'nullable|numeric',
                    'action_org'=>['nullable', new ActionOrg],
                    'min_dept_gov_code'=>'nullable',
                    'department_org_id'=>'nullable|numeric',
                    'remarks'=>'nullable',
                    'reply'=>'nullable',
                    'file_path' => 'file|mimes:pdf|max:2048',

                ]);
            }}

        $app = new Application();
        if(isset($request->id) && $request->id){
            $app = Application::find($request->id);
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
        if($request->mobile_no)
            $app->mobile_no = '+91' . $request->mobile_no;
        $app->email_id = $request->email_id;
        $app->letter_no = $request->letter_no;
        $app->letter_subject = $request->letter_subject;
        $app->letter_body = $request->letter_body;
        $app->acknowledgement=$request->acknowledgement;
        $app->grievance_category_id = $request->grievance_category_id;
        $app->action_org = $request->action_org;
        $app->min_dept_gov_code = $request->min_dept_gov_code;
        $app->remarks=$request->remarks;
        $app->department_org_id=$request->department_org_id;







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

        if ($request->input('submit') == 'Save') {

            //reg_no
            if ($app->reg_no && $app->reg_no !== null ) {
                //if reg no exist it means it is getting updated that's why update details are here.
                $app->updated_at = Carbon::now()->toDateTimeLocalString();
                $app->last_updated_by = Auth::user()->id;
                $app->last_updated_from = $request->ip();
                $app->save();

            }
            else {
                $currentYear = Carbon::now()->format('y');
                $currentMonth = Carbon::now()->format('m');
                $currentDay = Carbon::now()->format('d');

                $givenString = Auth::user()->username;
                $modifiedString = substr($givenString, 0, 2) . '/' . substr($givenString, 2);

                if ($currentMonth >= 4) {
                    // Financial year starts from April of the current year
                    $startYear = $currentYear;
                    $endYear = Carbon::now()->addYear()->format('y');
                } else {
                    // Financial year starts from April of the previous year
                    $startYear = Carbon::now()->subYear()->format('y');
                    $endYear = $currentYear;
                }

                $startDate = Carbon::createFromFormat('y-m-d', $startYear . '-04-01')->startOfDay();
                $endDate = Carbon::createFromFormat('y-m-d', $endYear . '-03-31')->endOfDay();

                $matchingRowCount = Application::whereBetween('letter_date', [$startDate, $endDate])
                    ->whereNotNull('reg_no')
                    ->where('reg_no', 'LIKE', $modifiedString . '%')
                    ->count();

                if($matchingRowCount > 0)
                    $count = $matchingRowCount + 1 ;
                elseif($matchingRowCount == 0)
                    $count =  1;

                if ($count <= 9999) {
                    $petitionNumber = sprintf('%04d', $count);
                } else {
                    $petitionNumber = $count;
                }

                $month = sprintf('%02d', $currentMonth);
                $day = sprintf('%02d', $currentDay);

                $app->reg_no = $modifiedString . '/' . $day . $month . $currentYear . $petitionNumber;

                //if reg no does not exist it means it is a new record that's why create details are here.
                $app->created_at = Carbon::now()->toDateTimeLocalString();
                $app->created_by = Auth::user()->id;
                $app->created_from = $request->ip();
                $app->save();
            }

            //file save
            if ($request->hasFile('file_path')) {
                $filename = time() . '.' . $request->file('file_path')->getClientOriginalExtension();
                $path = $request->file('file_path')->storeAs('applications/' . $app->id . '/', $filename, 'upload');
                $app->file_path = base64_encode($path);
                $app->update(['file_path' => base64_encode($path)]);
            }

            $applicationId = $app->id;
            $status = $app->statuses()->wherePivot('active', 1)->get();
            $app->statuses()->updateExistingPivot(
                $status,
                [
                    'active' => 0,
                    'updated_at'=> carbon::now()->toDateTimeLocalString()
                ]
            );
            $statusId = 2;
            $status = Status::find($statusId);
            if ($status) {
                $app->statuses()->attach($status, [
                    'created_from' => $request->ip(),
                    'created_by' => Auth::user()->id,
                    'created_at'=>carbon::now()->toDateTimeLocalString()
                ]);
            }

            return view('application_view', compact('app'));
        }

        elseif ($request->input('submit') === 'Draft') {
            if ($app->id){
                $app->updated_at = Carbon::now()->toDateTimeLocalString();
                $app->last_updated_by = Auth::user()->id;
                $app->last_updated_from = $request->ip();
            }
            else {
                $app->created_at = Carbon::now()->toDateTimeLocalString();
                $app->created_by = Auth::user()->id;
                $app->created_from = $request->ip();
            }
            if ($app->save()) {
                if ($request->hasFile('file_path')) {
                    $filename = time() . '.' . $request->file('file_path')->getClientOriginalExtension();
                    $path = $request->file('file_path')->storeAs('applications/' . $app->id . '/', $filename, 'upload');

                    $app->file_path = base64_encode($path);
                    $app->update(['file_path' => base64_encode($path)]);
                }
                $applicationId = $app->id;
                $status = $app->statuses()->wherePivot('active', 1)->get();
                $app->statuses()->updateExistingPivot(
                    $status,
                    [
                        'active' => 0,
                        'updated_at'=> carbon::now()->toDateTimeLocalString()
                    ]
                );
                $statusId = 0;
                $status = Status::find($statusId);
                if ($status) {
                    $app->statuses()->attach($status, [
                        'created_from' => $request->ip(),
                        'created_by' => Auth::user()->id,
                        'created_at'=>carbon::now()->toDateTimeLocalString()
                    ]);
                }
                return redirect()->route('applications.index')->with('success', 'Draft created successfully.');
            }
        }

        elseif ($request->input('submit') == 'Submit') {
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
                    'updated_at'=> carbon::now()->toDateTimeLocalString(),

                ]
            );
            $statusId = 5;
            $status = Status::find($statusId);
            if ($status) {
                $app->statuses()->attach($status, [
                    'created_from' => $request->ip(),
                    'created_by' => Auth::user()->id,
                    'created_at'=>carbon::now()->toDateTimeLocalString()
                ]);
            }
            return redirect()->route('applications.index')->with('success', 'reply saved');

        }

        else {
            return back()->withErrors([
                'username' => 'Sorry, could not save the data.',
            ])->withInput();
        }

        return back()->withErrors([
            'username' => 'Sorry, something got wrong',
        ]);

    }

    public function updateStatus(Request $request,string $application_id)
    {
        $action = $request->input('submit');
        $application = Application::findOrFail($application_id);
        $status = $application->statuses()->wherePivot('active', 1)->get();
        $application->statuses()->updateExistingPivot(
            $status,
            [
                'active' => 0,
                'updated_at'=> carbon::now()->toDateTimeLocalString()
            ]
        );

        $remarks = $request->input('remarks');
        $user = auth()->user();
        $role_ids = $user->roles()->pluck('role_id')->toArray();

        if (in_array(2, $role_ids)) {
            if ($action == 'Approve') {
                $status_id = 3;
            }
            elseif ($action == 'Return') {
                $status_id = 1;
            }
        }


        if (in_array(3, $role_ids)) {
            if ($action == 'Approve') {
                $application->authority_id = Auth::user()->sign_id ;
                $application->save();
                $status_id = 4;

                if ($application->acknowledgement === 'Y') {

//                    CURL
//                    $html = view('acknowledgementletter', compact('application'))->render();
//                    $postParameter = array(
//                        'content' => $html
//                    );
//                   Log::info('post param:'.json_encode($postParameter));
//                    $curlHandle = curl_init('http://localhost:8080/pdf');
//                    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
//                    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
//                    $curlResponse = curl_exec($curlHandle);
//                    Log::info('curlresponse'.$curlResponse);
//                    if(!$curlResponse){
//                        Log::error('curl error'.curl_error($curlHandle));
//                    }
//                    curl_close($curlHandle);
//                    if ($curlResponse && substr($curlResponse, 0, 4) == '%PDF') {
//                        $fileName = 'acknowledgement.pdf';
//                        $path = 'applications/' . $application->id . '/' . $fileName;
//                    if (Storage::disk('upload')->put($path, $curlResponse)) {
//                        $application->forwarded_path = base64_encode($path);
//                        $application->save();
//                    }
//                }

                    $dompdf = new Dompdf();
                    $options = new Options();
                    $options->setFontDir('/path/to/fonts');
                    $options->setDefaultFont('DejaVu Sans');
                    $options->set('isRemoteEnabled', true);
                    $dompdf->setOptions($options);
                    $imagePath = Storage::disk('upload')->path(base64_decode(Auth::user()->authority->Sign_path));
                    $imageData = file_get_contents($imagePath);
                    $imageBase64 = base64_encode($imageData);
                    $html = View::make('acknowledgementletter', compact('application','imageBase64'))->render();
                    $dompdf->loadHtml($html);
                    $dompdf->setPaper('A4', 'portrait');
                    $dompdf->getOptions()->set('isFontSubsettingEnabled', false);
                    $dompdf->render();
//                    $dompdf->stream('acknowledgement.pdf');
                    $pdffile = $dompdf->output();
                    $fileName = 'acknowledgement.pdf';
                    $path = 'applications/' . $application->id . '/' . $fileName;
                    if (Storage::disk('upload')->put($path, $pdffile)) {
                        $application->acknowledgement_path = base64_encode($path);
                        $application->save();
                    }

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



                    if ($application->email_id != null) {
//                    if($application->mail_sent == 0 || $application->mail_sent == '' ) {
//                        $email = $application->email_id;
                        $email = 'prustysarthak123@gmail.com';
                        $cc = 'sayantan.saha@gov.in';
                        $subject = 'Reply From Rashtrapati Bhavan';
                        $details = 'Hello, Mr/Mrs. ' . $application->applicant_name . ',<br><br>'
                            . 'Your Petition has been received in Rashtrapati Bhavan with ref no ' . $application->reg_no . ' and forwarded to ' . $application->department_org->org_desc . ' for further necessary action.';
                        $content = storage::disk('upload')->get(base64_decode($application->acknowledgement_path));
                        try {
                            Mail::send([], [], function ($message) use ($email, $subject, $details, $content, $cc) {
                                $message->to($email)->cc($cc)
                                    ->subject($subject)
                                    ->html($details)
                                    ->attachData($content, 'acknowledgement.pdf', [
                                        'mime' => 'application/pdf',
                                    ]);
                            });
//                            $application->mail_sent = 1;
//                            $application->save();
                        } catch (\Exception $e) {
//                            $application->mail_sent = 0;
//                            $application->save();
//                            return "Failed to send Mail: " . $e->getMessage();
                        }
//                    }
                    }
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
                }


                if ($application->department_org && $application->department_org->id !== null) {
                    $dompdf = new Dompdf();
                    $options = new Options();
                    $options->setFontDir('/path/to/fonts');
                    $options->setDefaultFont('DejaVu Sans');
                    $options->set('isRemoteEnabled', true);
                    $dompdf->setOptions($options);
                    $imagePath = Storage::disk('upload')->path(base64_decode(Auth::user()->authority->Sign_path));
                    $imageData = file_get_contents($imagePath);
                    $imageBase64 = base64_encode($imageData);
                    $html = View::make('forwardedletter', compact('application','imageBase64'))->render();

                    $dompdf->loadHtml($html);
                    $dompdf->setPaper('A4', 'portrait');
                    $dompdf->getOptions()->set('isFontSubsettingEnabled', false);
                    $dompdf->render();
                    $pdffile = $dompdf->output();
                    $fileName = 'forward.pdf';
                    $path = 'applications/' . $application->id . '/' . $fileName;
                    if (Storage::disk('upload')->put($path, $pdffile)) {
                        $application->forwarded_path = base64_encode($path);
                        $application->save();
                    }


//                $html = view('forwardedletter', compact('application'));
//                $postParameter = array(
//                    'content' => $html
//                );
//                $curlHandle = curl_init('http://localhost:8080/pdf-service/pdf');
//                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postParameter);
//                curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
//                $curlResponse = curl_exec($curlHandle);
//                curl_close($curlHandle);
//                if ($curlResponse && substr($curlResponse, 0, 4) == '%PDF') {
//                    $fileName = 'forward.pdf';
//                    $path = 'applications/' . $application->id . '/' . $fileName;
//                    if (Storage::disk('upload')->put($path, $curlResponse)) {
//                        $application->forwarded_path = base64_encode($path);
//                        $application->save();
//                    }
//                }

                    if ($application->department_org->mail !== null) {
//                    $email = $application->department_org->mail;
                        $email = 'prustysarthak123@gmail.com';
                        $cc = 'sayantan.saha@gov.in';
                        $subject = 'REQUEST FOR ATTENTION ON HIS/HER PETITION';
                        $details = 'Kindly find the attached forwarded file for petition received in Rashtrapati Bhavan';
                        $content = storage::disk('upload')->get(base64_decode($application->forwarded_path));
                        try {
                            Mail::raw($details, function ($message) use ($email, $subject, $content, $cc) {
                                $message->to($email)->cc($cc)
                                    ->subject($subject)
                                    ->attachData($content, 'forward letter.pdf', [
                                        'mime' => 'application/pdf',
                                    ]);
                            });
//                            $application->mail_sent = 1;
//                            $application->save();
                        } catch (\Exception $e) {
//                            $application->mail_sent = 0;
//                            $application->save();
//                        return "Failed to send Mail: " . $e->getMessage();
                        }
                    }
                }
            }
            elseif ($action == 'Return') {
                $status_id = 2;
            }
        }

        else {
            return redirect()->back()->with('error', 'role not found');
        }

        $status = Status::findOrFail($status_id);
        $application->statuses()->attach(
            $status,
            [
                'remarks' => $remarks,
                'created_from' => $request->ip(),
                'created_by' => Auth::user()->id,
                'created_at'=>carbon::now()->toDateTimeLocalString()
            ]
        );

        return redirect()->route('applications.index')->with('success', 'Status created successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $app = Application::find($id);
        if(!$app)
            abort(404);
        return view('application_view', compact('app'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $app = Application::find($id);
        $organizations = Organization::all();
        $states=State::all();
        $grievances=Grievance::all();
        return view('application', compact('app','organizations','states','grievances'));
//        $userOrganizationId = Auth::user()->org_id;
//        $userRoleId = Auth::user()->roles()->where('user_id', Auth::user()->id)->pluck('role_id')->first();
//        $allowNew = false;
//        if($userOrganizationId == 3 && $userRoleId == 1)
//            $allowNew = true;
//        return view('application', compact('app','organizations','states','grievances','allowNew'));

    }

    public function getFile(String $path)
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

    public function search(Request $request)
    {
        $org_id =auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();
        $qr=  [0,1,2,3,4,5] ;
        $arr=[];
        $arr[]= ['active', 1];
        if ($request->reg_no && $request->reg_no != '') {
            $arr[]=   ['reg_no', 'like', '%' . $request->reg_no . '%'];
        }
        if ($request->state_id && $request->state_id != '') {
            $arr[]=   ['state_id', '=', $request->state_id ];
        }
        if ($request->letter_no && $request->letter_no != '') {
            $arr[]=  ['letter_no', 'like', '%' . $request->letter_no . '%'];
        }
        if ($request->applicant_name && $request->applicant_name != '') {
            $arr[]=   ['applicant_name', 'like', '%' . $request->applicant_name . '%'];
        }
        if ($request->app_date_from && $request->app_date_from != '') {
            $arr[]=   ['letter_date','>=', $request->app_date_from ];
        }
        if ($request->app_date_to && $request->app_date_to != '') {
            $arr[]=   ['letter_date','<=',  $request->app_date_to ];
        }
        if ($request->organization && $request->organization != '') {
            $org_id=  $request->organization ;
        }
        if ($request->status !== null && $request->status != '') {
            $qr=  [$request->status] ;
        }

        $organizations=Organization::all();

        $applications = Application::with(relations: 'state')->where($arr)
            ->whereIn('created_by', function ($query) use ($org_id) {
                $query->select('users.id')
                    ->from('users')
                    ->join('user_organization', 'users.id', '=', 'user_organization.user_id')
                    ->where('user_organization.org_id', $org_id);
            })
            ->whereHas('statuses', function ($query)use ($qr) {
                $query->wherein('status_id', $qr)
                    ->where('application_status.active', 1);
            })
            ->paginate(18);
        $states=State::all();
        return view('application_list', compact('applications','states','organizations'));
    }

    public function dashboard(Request $request)
    {

        $org_id =auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();
        $org="All";
        if ($request->organization && $request->organization != '') {
            $org_id=  $request->organization ;
            $org =Organization::find($org_id)->org_desc;
        }

        $applicationStatusCounts = Application::where('applications.active', 1)
            ->whereIn('applications.created_by', function ($query) use ($org_id) {
                $query->select('user_organization.user_id')
                    ->from('user_organization')
                    ->where('user_organization.org_id', $org_id);
            })
            ->withCount([
                'statuses as pending_with_dh' => function ($query) {
                    $query->where('application_status.status_id', 1)->where('application_status.active', 1);
                },
                'statuses as pending_with_so' => function ($query) {
                    $query->where('application_status.status_id', 2)->where('application_status.active', 1);
                },
                'statuses as pending_with_us' => function ($query) {
                    $query->where('application_status.status_id', 3)->where('application_status.active', 1);
                },
                'statuses as in_draft' => function ($query) {
                    $query->where('application_status.status_id', 0)->where('application_status.active', 1);
                },
                'statuses as approved' => function ($query) {
                    $query->where('application_status.status_id', 4)->where('application_status.active', 1);
                },
                'statuses as submitted' => function ($query) {
                    $query->where('application_status.status_id', 5)->where('application_status.active', 1);
                },
            ])
            ->get();

        $pending_with_dh = $applicationStatusCounts->sum('pending_with_dh');
        $pending_with_so = $applicationStatusCounts->sum('pending_with_so');
        $pending_with_us = $applicationStatusCounts->sum('pending_with_us');
        $in_draft = $applicationStatusCounts->sum('in_draft');
        $approved = $applicationStatusCounts->sum('approved');
        $submitted = $applicationStatusCounts->sum('submitted');

        return view('dashboard', compact('in_draft', 'pending_with_dh', 'pending_with_so', 'pending_with_us', 'approved', 'submitted','org'));
    }
    public function reportprint(Request $request)
    {
        $organizationIds="";
        $name="";
        $organizations = Organization::all();
        $ar=[];
        $arr = [];
        $arr[] = ['active', 1];
        if ($request->reg_no && $request->reg_no != '') {
            $arr[] = ['reg_no', 'like', '%' . $request->reg_no . '%'];
        }
        if ($request->app_date_from && $request->app_date_from != '') {
            $arr[] = ['letter_date', '>=', $request->app_date_from];
            $date_from = $request->app_date_from;
        }
        if ($request->app_date_to && $request->app_date_to != '') {
            $arr[] = ['letter_date', '<=', $request->app_date_to];
            $date_to = $request->app_date_to;

        }
        if ($request->orgDesc && $request->orgDesc != '') {
            $arr[] = ['department_org_id', $request->orgDesc];
            $org = Organization::findOrFail($request->orgDesc);
            $name = $org->org_desc;
        }

        if ($request->state && $request->state != '') {
            $state= State::findOrFail($request->state);
            $name = $state->state_name;
            $organizationIds = Organization::where('state_id',$request->state)->pluck('id')->toArray();
            $ar[] = ['department_org_id', $organizationIds];
        }

        if ($request->input('submit') === 'acknowledgement')
        {
            $applications = Application::where($arr)
            ->where('acknowledgement_path', '!=', null)
            ->whereHas('statuses', function ($query) {
                $query->wherein('status_id', [4, 5])
                    ->where('application_status.active', 1);
            })
            ->get();

//            $pdfFiles = [
//                'C:\Users\prust\OneDrive\Desktop\petition\applications\80\1689256090.pdf',
//                'C:\Users\prust\OneDrive\Desktop\petition\applications\80\forward.pdf',
//                // Add more PDF files here
//            ];
//
//            $pdf = new Fpdi();
//// Loop through the PDF files and add them to the merged PDF
//            foreach ($pdfFiles as $file) {
//                $pageCount = $pdf->setSourceFile($file);
//                for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
//                    $pdf->AddPage();
//                    $template = $pdf->importPage($pageNumber);
//                    $pdf->useTemplate($template);
//                }
//            }
//        $pdf = new Fpdi();
//        $pdf->setPrintHeader(false);
//        $pdf->setPrintFooter(false);
//        $pdf->SetMargins(10, 10, 10);
//        $pdf->SetAutoPageBreak(true, 10);
//        $pdf->AddPage();
//        foreach ($applications as $application) {
//            $pdfPath = base64_decode($application->acknowledgement_path);
//            $pdfData = Storage::disk('upload')->get($pdfPath);
//            $pdf->writeHTML($pdfData);
//            $pdf->AddPage();
//        }
//            upar foreach nahele tala ta
//            foreach ($applications as $application) {
//                $pdfPath = base64_decode($application->acknowledgement_path);
//                $pdfData = Storage::disk('upload')->get($pdfPath);
//                $pdf->AddPage();
//
//                // Create a temporary stream resource for the PDF data
//                $pdfStream = fopen('php://temp', 'rb+');
//                fwrite($pdfStream, $pdfData);
//                rewind($pdfStream);
//
//                // Set the source file and import the first page
//                $pageCount = $pdf->setSourceFile($pdfStream);
//                $tplIdx = $pdf->importPage(1);
//
//                // Use the imported page as a template
//                $pdf->useTemplate($tplIdx, 10, 10, 190, 270);
//            }
//        $pdfContent = $pdf->Output('', 'S');
//        $tempPdfPath = storage_path('app/temp/merged_pdf.pdf');
//        file_put_contents($tempPdfPath, $pdfContent);
//        $pdfUrl = asset('storage/temp/merged_pdf.pdf');
//        return view('pdfmerge', compact('pdfUrl'));
    }

        elseif($request->input('submit') === 'Forward'){
            $applications = Application::where($arr)->where('forwarded_path','!=',null)
                ->whereHas('statuses', function ($query) {
                    $query->whereIn('status_id', [4,5])
                        ->where('application_status.active', 1);
                })->get();

            return view('forwardprint',compact('applications'));}

        elseif($request->input('submit') === 'forwardTable'){
            $applications = Application::where($arr)->when(!empty($ar), function ($query) use ($organizationIds) {
                return $query->whereIn('department_org_id', $organizationIds);
            })
                ->where('department_org_id','!=',null)
                ->whereHas('statuses', function ($query) {
                    $query->whereIn('status_id',[4,5])
                        ->where('application_status.active', 1);
                })->get();
            return view('forwardTableReport',compact('applications','organizations','date_from','date_to','name'));}

        elseif($request->input('submit') === 'final_Reply'){
            $applications = Application::where($arr)->when(!empty($ar), function ($query) use ($organizationIds) {
                return $query->whereIn('department_org_id', $organizationIds);
            })
                ->where('reply','!=',null)
                ->whereHas('statuses', function ($query) {
                    $query->whereIn('status_id', [4,5])
                        ->where('application_status.active', 1);
                })->get();
            return view('finalReplyReport',compact('applications','organizations','date_from','date_to','name'));}

    }


    public function generateAcknowledgementLetter($id)
    {
        $application = Application::findOrFail($id);
        return view('acknowledgementletter',compact('application',));
    }

    public function generateForwardLetter($id)
    {

        $application = Application::findOrFail($id);
        return view('forwardedletter',compact('application',));
    }


}



