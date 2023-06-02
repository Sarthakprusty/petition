<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateRequest;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return State::all()->where('active', 1)->toArray();

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, State $state)
    {
        $request->validate([
            'state_name'=>'sometimes|nullable',
            'lgd_code'=>'sometimes|nullable',
            'state_type'=>'sometimes|nullable',
            'state_name_hi'=>'sometimes|nullable',
            'state_name_local'=>'sometimes|nullable',
        ]);
        $state->state_name  = $request->state_name;
        $state->lgd_code    = $request->lgd_code;
        $state->state_type  = $request->state_type;
        $state->state_name_hi    = $request->state_name_hi;
        $state->state_name_local  = $request->state_name_local;

        $state->created_at = Carbon::now()->toDateTimeLocalString();
        $state->last_updated_by = Auth::user()->user_id;
        $state->last_updated_from = $request->ip();


        if ($state->save())
            return response($state, 201);
        else
            return response(array("code" => 400, "msg" => "Bad request"), 400);
    }


    /**
     * Display the specified resource.
     */
    public function show(State $state)
    {
        return $state;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, State $state)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(State $state,Request $request)
    {
        $state->active = 0;
        $state->deleted_at = Carbon::now()->toDateTimeLocalString();
        $state->last_updated_by = Auth::user()->user_id;
        $state->last_updated_from = $request->ip();
        $state->save();
        return response(array($state,"msg" => "as per your request your details has been deleted"),202);
    }
}
