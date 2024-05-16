@extends('layout')
@section('content')
<div class="row" id="pageContent">
    <div class="card shadow" id="state_details">
        <div class="card-body">
            <div class="row">
                
                    <div class="mb-3" id="organizationS">
                        <table class="table">
                            <tr>
                                <th>S.No.</th>
                                <th>User</th>
                                <th>Name</th>
                                <th>Change Password</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            @foreach($employees as $employee)
                            <tr>
                            <form method="post" action="{{route('users.employees_save') }}" >@csrf
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $employee->username }}</td>
                                <td><input type="text" class="form-control " name="employee_name" id="employee_name"  placeholder="Enter Name"  value="{{ $employee->employee_name }}"></td>
                                <td><input type="text" class="form-control " name="password" id="password"  placeholder="Enter Password" ></td>
                                <td>
                                <div class="form-group">
                                    <select class="form-control" name="active" id="active" >
                                        <option value="1" {{ old('active') === 1 || $employee->active === 1 ? 'selected' : '' }} selected>Enable</option>
                                        <option value="0" {{ old('active') === 0 || $employee->active === 0 ? 'selected' : '' }}>Disable</option>
                                        
                                    </select>
                                </div>
                                
                                </td>
                                <td><button type ="submit" class="btn btn-outline-primary" name="user_id" value="{{ $employee->id }}">Save</button></td>
                            </form>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                
            </div>
        </div>
    </div>
</div>
  
@endsection