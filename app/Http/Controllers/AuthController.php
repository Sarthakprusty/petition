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
            return redirect(route('applications.create'));

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


}
