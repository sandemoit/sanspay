<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="csrf-token" content="{!! csrf_token() !!}" />
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    <title>{{ configWeb('title')->value }}</title>

    {{-- icon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset(configWeb('favicon')->value) }}" />

    <!-- loader-->
    {{-- <link href="{{ asset('/') }}css/pace.min.css" rel="stylesheet" />
    <script src="{{ asset('/') }}js/pace.min.js"></script> --}}

    <!--plugins-->
    <link async href="{{ asset('/') }}plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link async href="{{ asset('/') }}plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />

    <!-- CSS Files -->
    <link async href="{{ asset('/') }}css/bootstrap.min.css" rel="stylesheet">
    <link async href="{{ asset('/') }}css/bootstrap-extended.css" rel="stylesheet">
    <link async href="{{ asset('/') }}css/style.css" rel="stylesheet">
    <link async href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link async href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!--Theme Styles-->
    {{-- <link async href="{{ asset('/') }}css/dark-theme.css" rel="stylesheet" /> --}}

    @stack('custom-css')

</head>

<body>
    <!--start wrapper-->
    <div class="wrapper">

        @include('layouts.sidebar')
        @include('layouts.navigation')

        <!-- Page Content -->
        <div class="page-content-wrapper">
            <div class="page-content">
                @if (isMode() === 'local')
                    <div class="alert alert-warning">
                        <ion-icon name="shield-half-outline"></ion-icon>
                        Anda sedang dalam mode development.
                    </div>
                @endif
                {{ $slot }}
            </div>

            <!--start footer-->
            {{-- <footer class="footer">
                <div class="footer-text">
                    Copyright © {{ date('Y') }}. All right reserved.
                </div>
            </footer> --}}
            <!--end footer-->


            <!--Start Back To Top Button-->
            <a href="javaScript:;" class="back-to-top">
                <ion-icon name="arrow-up-outline"></ion-icon>
            </a>
            <!--End Back To Top Button-->

            <!--start overlay-->
            <div class="overlay nav-toggle-icon"></div>
            <!--end overlay-->
        </div>

        <!-- JS Files-->
        <script src="{{ asset('/') }}js/jquery.min.js"></script>
        <script async src="{{ asset('/') }}plugins/simplebar/js/simplebar.min.js"></script>
        <script async src="{{ asset('/') }}plugins/metismenu/js/metisMenu.min.js"></script>
        <script async src="{{ asset('/') }}js/bootstrap.bundle.min.js"></script>
        <script async type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <!--plugins-->
        <script async src="{{ asset('/') }}plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
        {{-- <script src="{{ asset('/') }}js/index.js"></script> --}}
        <!-- Main JS-->
        <script async src="{{ asset('/') }}js/main.js"></script>
        <script async src="{{ asset('/') }}js/theme.js"></script>

        <script async src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script async src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script async src="{{ asset('/') }}js/message-alert.js"></script>

        <script>
            @if (Session::has('error'))
                var error = "{{ Session::get('error') }}";
            @elseif (Session::has('success'))
                var success = "{{ Session::get('success') }}";
            @endif
        </script>

        <script src="{{ asset('/sw.js') }}"></script>
        <script>
            if ("serviceWorker" in navigator) {
                // Register a service worker hosted at the root of the
                // site using the default scope.
                navigator.serviceWorker.register("/sw.js");
            }
        </script>

        <script>
            let deferredPrompt;

            window.addEventListener('beforeinstallprompt', (e) => {
                deferredPrompt = e;
            });

            const installApp = document.getElementById('installApp');
            installApp.addEventListener('click', async () => {
                if (deferredPrompt !== null) {
                    deferredPrompt.prompt();
                    const {
                        outcome
                    } = await deferredPrompt.userChoice;
                    if (outcome === 'accepted') {
                        deferredPrompt = null;
                    }
                }
            });
        </script>

        @stack('custom-js')
</body>

</html>
