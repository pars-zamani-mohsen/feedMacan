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
    <h1>گزارش کانال دکتر ماکان آریا پارسا </h1>
    <p>ایجاد شده توسط تیم توسعه هولدینگ پارس پندار نهاد</p>
</div>

<div class="container mt-5">
    <div class="row">
        @foreach ($data as $item)
        <div class="col-12">
            <h3>{{ $item['id'] }}</h3>
            <p class="w-100 text-center">
                @if (isset($item['file']) && $item['file'])
                    @if (isset($item['file_type']) && ($item['file_type'] == 'photo' || strpos($item['file_type'], 'photo') !== false))
                        <figure>
                            <a href="{{ asset($item['file']) }}" target="_blank"><img class="w-100" src="{{ asset($item['file']) }}" alt="macan feedback image"></a>
                        </figure>
                    @elseif (isset($item['file_type']) && ($item['file_type'] == 'video' || strpos($item['file_type'], 'video') !== false))
                        <video class="w-100" controls>
                            <source src="{{ asset($item['file']) }}" type="video/mp4">
                            <source src="{{ asset($item['file']) }}" type="video/ogg">
                            <source src="{{ asset($item['file']) }}" type="video/mpeg">
                            <source src="{{ asset($item['file']) }}" type="video/webm">
                            Your browser does not support the video tag.
                        </video>

                    @elseif (isset($item['file_type']) && ($item['file_type'] == 'audio' || strpos($item['file_type'], 'audio') !== false))
                        <audio class="w-100" controls>
                            <source src="{{ asset($item['file']) }}" type="audio/ogg">
                            <source src="{{ asset($item['file']) }}" type="audio/mpeg">
                            <source src="{{ asset($item['file']) }}" type="audio/mp4">
                            Your browser does not support the audio tag.
                        </audio>
                    @else
                        <a href="{{ asset($item['file']) }}" target="_blank">دریافت فایل</a>
                    @endif
                @endif
            </p>

            <p class="text-justify">{!! $item['text'] !!}</p>
        </div>
        @endforeach
        <div class="my-paginate col-12 text-center @if(count($data->render()->elements[0]) > 1) p-3 @endif">
            {{ $data->render() }}
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
