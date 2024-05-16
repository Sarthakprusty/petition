<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;



class AuthController extends Controller
{


    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('username', 'password');

        if(Auth::attempt($credentials)){
            if( Auth::user()->active==1)
            return redirect(route('applications.dashboard'));
            else{
                Auth::logout();
            return Redirect::to(route('login'));
            }

//            $request->session()->regenerate();
//            Log::debug('User logged in');
//            $user = Auth::user();
//            Log::debug('The user is :{'.json_encode($user).'}');
//            $org = $user->organization;
//            Log::debug('The organization is :{'.json_encode($org).'}');
//            if($org->org_desc=='RTI'){
//                return redirect(route('applications.index'));
//            }
//            else
        }
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');

    }

    public function logout()
    {
        Auth::logout();
        return Redirect::to(route('login'));
    }

    public function register(Request $request){
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        DB::transaction(function() use ($request) {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);



            $token = Auth::login($user);
            $ttl = env('JWT_TTL', 60);
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => [
                    'username' => $user->username,
                    'org_id'=>$user->org_id

                ],
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                    'issued_at' => Carbon::now()->toDateTimeString(),
                    'valid_till' => Carbon::now()->addMinutes($ttl)->toDateTimeString()
                ]
            ]);
        });
    }

    public function employees(){
        // $states=State::all();
        // $organizations=Organization::all();
    
        
        $org_id =auth()->user()->organizations()->where('user_organization.active', 1)->pluck('org_id')->toArray();
       
        if (in_array(174, $org_id)) {
            $employees=User::whereHas('organizations', function ($query)  {
                $query->where('org_id', 174)
                    ->where('user_organization.active', 1);
            })->get();
        }
    
        if (in_array(175, $org_id)) {
            $employees=User::whereHas('organizations', function ($query)  {
                $query->where('org_id', 175)
                    ->where('user_organization.active', 1);
            })->get();
        }
            // echo '<pre>';print_r($employees);die;
    
            return view('employeedt', compact('employees'));
        }

        public function save_employee(Request $request){
            
            $user = User::find($request->user_id);
            if($user && $user != NULL){
                $user->employee_name = $request->employee_name;
                $user->active = $request->active;
                if($request->password && $request->password !== null)
                    $user->password = Hash::make($request->password);
                $user->updated_at = Carbon::now()->toDateTimeLocalString();
                $user->last_updated_by = Auth::user()->id;
                $user->last_updated_from = $request->ip();
                if ($user->save())
                return redirect(route('applications.dashboard'));
            }           
            else
                return response(array("code" => 400, "msg" => "Bad request"), 400);
            }
}
    