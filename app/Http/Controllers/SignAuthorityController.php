<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\SignAuthority;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SignAuthorityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $states = State::all();
        $organizations = Organization::all();
        $authority_id= Auth::user()->sign_id;
        $authority = SignAuthority::findorfail($authority_id);
        return view('sign_authority_list', compact('authority','organizations','states'));

    }

    public function create()
    {
        $states = State::all();
        $organizations = Organization::all();
        $signAuthority = new SignAuthority();
        return view('sign_authority', compact('signAuthority','organizations','states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Sign_path'=>'nullable|file|mimes:png|max:1000',
            'name'=>'required',
//            'dept_id'=>'nullable',
            'from_date'=>'required|date_format:Y-m-d|after_or_equal:today',
            'to_date'=>'nullable|date_format:Y-m-d|after:from_date',

        ]);
        $signAuthority = new SignAuthority;

        $signAuthority->name = $request->name;
        $signAuthority->from_date = $request->from_date;
//        $signAuthority->dept_id = $request->dept_id;


        $signAuthority->created_at = Carbon::now()->toDateTimeLocalString();
        $signAuthority->created_by = Auth::user()->id;
        $signAuthority->created_from = $request->ip();

        if ($request->input('submit') == 'Save') {
            SignAuthority::where('created_by', auth()->user()->id)
                ->update([
                    'active' => 0,
                    'to_date' => Carbon::now()->toDateString(),
                    'last_updated_by' =>Auth::user()->id,
                    'last_updated_from'=>$request->ip()
                ]);

            if ($signAuthority->save()) {
                if ($request->hasFile('Sign_path')) {
                    $filename = time() . '.' . $request->file('Sign_path')->getClientOriginalExtension();
                    $path = $request->file('Sign_path')->storeAs('SignatureAuthority', $filename, 'upload');
                    $signAuthority->Sign_path = base64_encode($path);
                    $signAuthority->update(['Sign_path' => base64_encode($path)]);
                }
                $user = Auth::user();
                $user->sign_id = $signAuthority->id;
                $user->save();
                return redirect()->route('authority.index')->with('success', 'Authority Assigned successfully.');
            }
        }
            else
                return back()->withErrors([
                    'username' => 'Sorry, could not save the data.',
                ]);
    }

//    public function edit(string $id)
//    {
//        $signAuthority = SignAuthority::findorfail($id);
//        $organizations = Organization::all();
//        $states=State::all();
//        return view('sign_authority', compact('signAuthority','organizations','states'));
//    }

    public function signFile(String $path)
    {
        // return response()->file(base64_decode($path));
        $content = Storage::disk('upload')->get(base64_decode($path));
        if ($content != '') {
            return response($content, 200)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'inline');
        } else {
            return response()->json(['code' => 404, 'msg' => 'Details not found'], 404);
        }
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
