@extends('layout')

@section('content')
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .pdf-container {
            width: 100%;
            height: 100vh;
        }
    </style>
    <div class="row">
        <div style="text-align: center">
            <button class="btn btn-outline-danger" style="margin-left: 3%" onclick="printLetter()">Print</button>
        </div>
    </div>
    <div class="row"></div>

    <div id="myDiv">

    <div class="pdf-container">
        <object data="{{ $pdfUrl }}" type="application/pdf" width="100%" height="100%"></object>
    </div>
    </div>
    <script>
        function printLetter() {
            var printContents = document.getElementById('myDiv').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
