@extends('layout')

@section('content')

    <div class="row">
        <form method="post" action="{{route('applications.updatePrint')}}">@csrf
            <div class="row">
                <div style="text-align: center">
                    <button type="submit" class="btn btn-outline-success" name="action" value="open" style="margin-left: 3%" onclick="return confirm('Are you sure? Opening selected items will be treated as a petition sent by post.')">UPDATE/OPEN</button>
                    <button type="submit" class="btn btn-outline-success" name="action" value="update" style="margin-left: 3%" onclick="return confirm('Are you sure? Updating selected items will be treated as a petition was already sent by post.')">UPDATE</button>
                    <input type="hidden" value="{{$letter}}" name="letter" >
                </div>
            </div>

            <nav aria-label="breadcrumb" style="background: #f8f5ef">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"style="font-size: 160%;text-decoration: underline;">{{$letter}}</li>
                </ol>
            </nav>

            <table class="table table-bordered">
            <thead>
            <tr>
                <th style="text-align: center">
                    <input type="checkbox" id="selectAll">
                </th>
                <th style="text-align: center">SrNo</th>
                <th style="text-align: center">Registration No.</th>
                <th style="text-align: center">Petitioner Name</th>
                <th style="text-align: center">Approving Date</th>
            </tr>
            </thead>
            <tbody>
            @php
                $count = 1;
            @endphp
            @foreach ($applications as $application)
                <tr>
                    <td style="text-align: center">
                        <input type="checkbox" class="selectItem" value="{{$application->id}}" name="selectedId[]">
                    </td>
                    <td style="text-align: center">{{ $count++ }}</td>
                    <td style="text-align: center">{{ $application->reg_no }}</td>
                    <td style="text-align: center">{{ $application->applicant_name }}</td>
                    <td style="text-align: center">@php
                            $statusId = 4;
                            $pivot = $application->statuses->first(function ($status) use ($statusId) {
                                return $status->pivot->status_id === $statusId;
                            });
                            $createdAt = $pivot ? $pivot->pivot->created_at : null;
                        @endphp
                        {{ $createdAt ? \Carbon\Carbon::parse($createdAt)->format('d/m/Y') : '' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            // Get references to the "Select All" checkbox and "Select Item" checkboxes
            const $selectAllCheckbox = $('#selectAll');
            const $selectItemCheckboxes = $('.selectItem');

            // Add a click event handler to the "Select All" checkbox
            $selectAllCheckbox.on('click', function () {
                const isChecked = $(this).prop('checked');

                // Set the checked state of "Select Item" checkboxes
                $selectItemCheckboxes.prop('checked', isChecked);
            });

            // Add a click event handler to "Select Item" checkboxes
            $selectItemCheckboxes.on('click', function () {
                const allChecked = $selectItemCheckboxes.length === $selectItemCheckboxes.filter(':checked').length;

                // Set the checked state of "Select All" checkbox
                $selectAllCheckbox.prop('checked', allChecked);
            });

            // Add a form submit event handler
            $('form').on('submit', function (event) {
                const atLeastOneChecked = $selectItemCheckboxes.is(':checked');

                // If no checkboxes are checked, prevent the form submission
                if (!atLeastOneChecked) {
                    event.preventDefault();
                    alert('Please select at least one item.');
                }
            });
        });
    </script>


@endsection
