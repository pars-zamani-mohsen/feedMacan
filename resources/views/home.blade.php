<!DOCTYPE html>
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/toastify/toastify.css') }}">

    <script src="{{ asset('assets/vendors/fontawesome/all.min.js') }}"></script>
</head>

<body>

<div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>My First Bootstrap Page</h1>
    <p>Resize this responsive page to see the effect!</p>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-sm-4">
            <h3>Column 1</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
            <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
        </div>
        <div class="col-sm-4">
            <h3>Column 2</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
            <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
        </div>
        <div class="col-sm-4">
            <h3>Column 3</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
            <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
        </div>
    </div>
</div>




<script src="{{ asset('assets/vendors/toastify/toastify.js') }}"></script>
@yield('script')
@if (count($errors) > 0)
    @foreach ($errors->all() as $key => $error)
        <script>
            Toastify({
                text: "{!! $error !!}",
                duration: 8000,
                close:true,
                gravity:"top",
                position: "center",
                backgroundColor: "#dc3545",
            }).showToast();
        </script>
    @endforeach
@endif

@if (Session::has('message'))
    <script>
        Toastify({
            text: "{{ Session::get('message') }}",
            duration: 8000,
            close:true,
            gravity:"top",
            position: "center",
            backgroundColor: "#198754",
        }).showToast();
    </script>
@endif

@if (Session::has('alert'))
    <script>
        Toastify({
            text: "{{ Session::get('alert') }}",
            duration: 8000,
            close:true,
            gravity:"top",
            position: "center",
            backgroundColor: "#ffc107",
        }).showToast();
    </script>
@endif

@if (Session::has('error'))
    <script>
        Toastify({
            text: "{{ Session::get('error') }}",
            duration: 8000,
            close:true,
            gravity:"top",
            position: "center",
            backgroundColor: "#dc3545",
        }).showToast();
    </script>
@endif
</body>
</html>
