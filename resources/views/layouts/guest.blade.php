<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark-theme">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- loader-->
    <link href="{{ asset('/') }}css/pace.min.css" rel="stylesheet" />
    <script src="{{ asset('/') }}js/pace.min.js"></script>

    <!--plugins-->
    <link href="{{ asset('/') }}plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />

    <!-- CSS Files -->
    <link href="{{ asset('/') }}css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('/') }}css/bootstrap-extended.css" rel="stylesheet">
    <link href="{{ asset('/') }}css/style.css" rel="stylesheet">
    <link href="{{ asset('/') }}css/icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>

<body>

    <!--start wrapper-->
    <div class="wrapper">
        <div class="">
            {{ $slot }}

        </div>
    </div>
    <!--end wrapper-->

    <script src="{{ asset('/') }}js/bootstrap.bundle.min.js"></script>
</body>

</html>
