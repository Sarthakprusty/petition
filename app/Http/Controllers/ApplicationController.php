<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Grievance;
use App\Models\Organization;
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
use Dompdf\Options;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use App\Mail\SendPdfEmail;
use Illuminate\Support\Facades\Mail;

use App\Models\Status;
class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = $request->query();
        if(isset($query['status']) && $query['status']!=''){

        }

        $applications = Application::where('active', 1)->where('created_by',Auth::user()->id)->paginate(18);
        return view('application_list', compact('applications'));
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
        if($request->language_of_letter!='O'){
        if ($request->input('submit') === 'Save') {
        $request->validate([
            'reg_no'=>'nullable',
            'applicant_title'=>'required',
            'applicant_name'=>'required|regex:/^[a-zA-Z .]+$/',
            'address'=>'required',
            'pincode'=>'nullable|numeric|min:100000|max:999999',
            'state_id'=>'nullable|numeric',
            'org_from'=>'nullable',
            'letter_date'=>'nullable|date_format:Y-m-d|before_or_equal:today',
            'gender' => ['nullable', new Gender],
            'language_of_letter'=>['nullable', new Language],
            'country'=>['required', new Country],
            'phone_no'=>'nullable|numeric|min:10000000000|max:99999999999',
            'mobile_no'=>'nullable|numeric|min:1000000000|max:9999999999',
            'email_id'=>'nullable|email',
            'letter_no'=>'nullable',
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
        $app->mobile_no = $request->mobile_no;
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
                if (($request->reg_no) && $request->reg_no != '') {
                    $app->reg_no = $request->reg_no;

                    //if reg no exist it means it is getting updated that's why update details are here.
                    $app->updated_at = Carbon::now()->toDateTimeLocalString();
                    $app->last_updated_by = Auth::user()->id;
                    $app->last_updated_from = $request->ip();
                    $app->save();

                } else {
                    $currentYear = Carbon::now()->format('y');
                    $currentMonth = Carbon::now()->format('m');
                    $currentDay = Carbon::now()->format('d');
                    if ($currentMonth >= 1 && $currentMonth <= 3) {
                        $financialYear = ($currentYear - 1) . '-' . $currentYear;
                    } else {
                        $financialYear = $currentYear . '-' . (Carbon::now()->addYear()->format('y'));
                    }
                    $matchingRowCount = Application::whereRaw("SUBSTRING(reg_no,-5) = ?", [$financialYear])->count();
                    $matchingRowCount++;
                    $rtiNumber = sprintf('%04d', $matchingRowCount);
                    $month = sprintf('%02d', $currentMonth);
                    $day = sprintf('%02d', $currentDay);
                    $givenString = Auth::user()->username;
                    $modifiedString = substr($givenString, 0, 2) . '/' . substr($givenString, 2);
                    $app->reg_no = $modifiedString . '/' . $day . $month . $currentYear . $rtiNumber;

                    //if reg no does not exist it means it is a new record that's why create details are here.
                    $app->created_at = Carbon::now()->toDateTimeLocalString();
                    $app->created_by = Auth::user()->id;
                    $app->created_from = $request->ip();
                    $app->save();
                }

                //file save
                if ($request->hasFile('file_path')) {
                    $filename = time() . '.' . $request->file('file_path')->getClientOriginalExtension();
                    $path = $request->file('file_path')->storeAs('office_file', $filename, 'upload');
                    $app->file_path = base64_encode($path);
                    $app->update(['file_path' => base64_encode($path)]);
                }

                $applicationId = $app->id;
                $status = $app->statuses()->wherePivot('active', 1)->get();
                $app->statuses()->updateExistingPivot(
                    $status,
                    [
                        'active' => 0,
                        'created_from' => $request->ip(),
                        'created_by' => Auth::user()->id
                    ]
                );
                $statusId = 2;
                $status = Status::find($statusId);
                if ($status) {
                    $app->statuses()->attach($status, [
                        'created_from' => $request->ip(),
                        'created_by' => Auth::user()->id
                    ]);
                }
                return redirect()->route('applications.index')->with('success', 'Product created successfully.');
    }

        elseif ($request->input('submit') === 'Draft') {
            if ($request->id)
                $app->created_at = Carbon::now()->toDateTimeLocalString();
            $app->created_by = Auth::user()->id;
            $app->created_from = $request->ip();
            if ($app->save()) {
                if ($request->hasFile('file_path')) {
                    $filename = time() . '.' . $request->file('file_path')->getClientOriginalExtension();
                    $path = $request->file('file_path')->storeAs('office_file', $filename, 'upload');
                    $app->file_path = base64_encode($path);
                    $app->update(['file_path' => base64_encode($path)]);
                }
                $applicationId = $app->id;
                $status = $app->statuses()->wherePivot('active', 1)->get();
                $app->statuses()->updateExistingPivot(
                    $status,
                    [
                        'active' => 0,
                        'created_from' => $request->ip(),
                        'created_by' => Auth::user()->id
                    ]
                );
                $statusId = 5;
                $status = Status::find($statusId);
                if ($status) {
                    $app->statuses()->attach($status, [
                        'created_from' => $request->ip(),
                        'created_by' => Auth::user()->id
                    ]);
                }
                return redirect()->route('applications.index')->with('success', 'Draft created successfully.');
            }
        }

        elseif ($request->input('submit') === 'Send') {
            $app = Application::findOrFail($request->id);
            $app->reply = $request->input('reply');
            $app->updated_at = Carbon::now()->toDateTimeLocalString();
            $app->last_updated_by = Auth::user()->id;
            $app->last_updated_from = $request->ip();
//            $app->active = 0;
            $app->save();

//            if ($app->acknowledgement == 'Y' && $app->email_id !== null) {
//                $content = Storage::disk('upload')->get(base64_decode($app->file_path));
//                $email = $app->email_id;
//                $subject = 'Your Subject Here';
//                $details = $app->reply ;
//
//                Mail::raw($details, function ($message) use ($email, $subject, $content) {
//                    $message->to($email)
//                        ->subject($subject)
//                        ->attachData($content, 'filename.pdf', [
//                            'mime' => 'application/pdf',
//                        ]);
//                });
//            }
            return redirect()->route('applications.index')->with('success', 'sent successfully.');

        }

        else {
            return back()->withErrors([
                'username' => 'Sorry, could not save the data.',
            ]);
        }

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
        if($content!='')
            return response()->make($content, 200, ['Content-Type' => 'applications/pdf','content-disposition'=>'inline']);
        else
            return response(array("code" => 404, "msg" => "details not found"), 404);

    }
    public function search(Request $request)
    {
        $query = Application::where('active', 1);
        if ($request->applicant_name && $request->applicant_name != '') {
            $query->where('applicant_name', 'like', '%' . $request->applicant_name . '%');
        }

        if ($request->rec_no && $request->rec_no != '') {
            $query->where('reg_no', 'like', '%' . $request->rec_no . '%');
        }

        if ($request->app_date_from && $request->app_date_from != '' && $request->app_date_to == '') {
            $query->whereDate('letter_date', '=', $request->app_date_from);
        } elseif ($request->app_date_from && $request->app_date_to != '') {
            $query->whereBetween('letter_date', [$request->app_date_from, $request->app_date_to]);
        }

        $applications = $query->paginate(18)->appends($request->all());

        return view('application_list', compact('applications'));
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
                    'created_from' => $request->ip(),
                    'created_by' => Auth::user()->id
                ]
            );

        $remarks = $request->input('remarks');
        $user = auth()->user();
        $role_ids = $user->roles()->pluck('role_id')->toArray();
        if (in_array(2, $role_ids)) {
            if ($action == 'Approve') {
                $status_id = 3;
            } elseif ($action == 'Return') {
                $status_id = 1;
            }
        }
        if (in_array(3, $role_ids)) {
            if ($action == 'Approve') {
                $status_id = 4;
            } elseif ($action == 'Return') {
                $status_id = 2;
            }
        } else
            return redirect()->back()->with('error', 'role not found');

        $status = Status::findOrFail($status_id);
        $application->statuses()->attach(
            $status,
            [
                'remarks' => $remarks,
                'created_from' => $request->ip(),
                'created_by' => Auth::user()->id
            ]
        );

        return redirect()->route('applications.index')->with('success', 'Status created successfully.');
    }


    public function generateAcknowledgementLetter($id)
    {
        $application = Application::findOrFail($id);
        return view('forwardletter',compact('application'));
    }


}

/**
 * generate and download dompdf
 */
//public function generateAcknowledgementLetter($id)
//{
//    $application = Application::findOrFail($id);
//    $data = [
//        'application' => $application,
//    ];
//
//    // Configure dompdf options
//    $options = new Options();
//    $options->set('defaultFont', 'Arial');
//    $dompdf = new Dompdf($options);
//
//    // Render the view to HTML
//    $html = View::make('forwardletter', $data)->render();
//
//    // Load the HTML into dompdf
//    $dompdf->loadHtml($html);
//
//    // (Optional) Set paper size and orientation
//    $dompdf->setPaper('A4', 'portrait');
//
//    // Render the PDF
//    $dompdf->render();
//
//    // Output the generated PDF
//    return $dompdf->stream('acknowledgement_letter.pdf');
//}
