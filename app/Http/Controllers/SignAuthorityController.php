<?php

namespace App\Http\Controllers;

use App\Models\SignAuthority;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignAuthorityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SignAuthority::all()->where('active', 1);

    }

    public function create()
    {
        $signAuthority = new SignAuthority();
        return view('sign_authority', compact('signAuthority'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Sign_path'=>'file|mimes:pdf|max:100',
            'name'=>'required',
            'desg'=>'required',
            'from_date'=>'required|date_format:Y-m-d|after_or_equal:today',
            'to_date'=>'nullable|date_format:Y-m-d|after:from_date',

        ]);
        $signAuthority = new SignAuthority;
        $signAuthority->Sign_path = $request->Sign_path;
        $signAuthority->name = $request->name;
        $signAuthority->from_date = $request->from_date;
        $signAuthority->desg = $request->desg;
        $signAuthority->to_date = $request->to_date;

        $signAuthority->created_at = Carbon::now()->toDateTimeLocalString();
        $signAuthority->last_updated_by = Auth::user()->user_id;
        $signAuthority->last_updated_from = $request->ip();

        if ($request->input('submit') == 'Save') {
            if ($signAuthority->save()) {
                if ($request->hasFile('Sign_path')) {
                    $filename = time() . '.' . $request->file('Sign_path')->getClientOriginalExtension();
                    $path = $request->file('Sign_path')->storeAs('SignatureAuthority', $filename, 'upload');
                    $signAuthority->Sign_path = base64_encode($path);
                    $signAuthority->update(['Sign_path' => base64_encode($path)]);
                }
                return redirect()->route('applications.index')->with('success', 'Authority Assigned successfully.');
            }
        }
            else
                return back()->withErrors([
                    'username' => 'Sorry, could not save the data.',
                ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SignAuthority $signAuthority)
    {
        return $signAuthority;

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SignAuthority $signAuthority)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,SignAuthority $signAuthority)
    {
        $signAuthority->active = 0;
        $signAuthority->deleted_at = Carbon::now()->toDateTimeLocalString();
        $signAuthority->last_updated_by = Auth::user()->user_id;
        $signAuthority->last_updated_from = $request->ip();
        $signAuthority->save();
        return response(array($signAuthority,"msg" => "as per your request your details has been deleted"),202);
    }
}
