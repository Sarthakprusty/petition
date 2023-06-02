<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $officer = Officer::all()->where('active', 1);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Officer $officer)
    {
        $request->validate([
            'user_id'=>'sometimes|nullable',
            'desg'=>'required',
            'name'=>'required|regex:/^[a-zA-Z .]+$/',
            'form_date'=>'required|date_format:Y-m-d|before_or_equal:today',
            'to_date'=>'sometimes|nullable|date_format:Y-m-d|after:form_date',
            'signature_path' => 'file|mimes:pdf|max:2048',

        ]);
        $officer->user_id = $request->user_id;
        $officer->desg = $request->desg;
        $officer->name = $request->name;
        $officer->form_date = $request->form_date;
        if ($request->form_date) {
            $officer->to_date = date('Y-m-d', strtotime($request->form_date . ' +6 months'));
        } else {
            $officer->to_date = null;
        }

        $officer->created_at = Carbon::now()->toDateTimeLocalString();
        $officer->last_updated_by = Auth::user()->user_id;
        $officer->last_updated_from = $request->ip();


        if ($officer->save()){
            if ($request->hasFile('signature_path')) {
                $filename = time() .'.'. $request->file('signature_path')->getClientOriginalExtension();
                $path = $request->file('signature_path')->storeAs ( 'signature_file', $filename, 'upload');
                $officer->signature_path=base64_encode($path);
                $officer->update(['signature_path'=>base64_encode($path)]);
            }

            return response($officer, 201);}
        else
            return response(array("code" => 400, "msg" => "Bad request"), 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Officer $officer)
    {
        return $officer;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Officer $officer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Officer $officer,Request $request)
    {
        $officer->active = 0;
        $officer->deleted_at = Carbon::now()->toDateTimeLocalString();
        $officer->last_updated_by = Auth::user()->employee_id;
        $officer->last_updated_from = $request->ip();
        $officer->save();
        return response(array($officer,"msg" => "as per your request your details has been deleted"),202);
    }
}
