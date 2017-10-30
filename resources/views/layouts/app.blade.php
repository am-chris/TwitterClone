<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="loggedIn" content="{{ Auth::check() }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @guest
            @include('tpl/navbar-guest')
        @else
            @include('tpl/navbar-user')
        @endguest

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('js/autosize.min.js') }}"></script>
    <script src="{{ asset('js/clipboard.min.js') }}"></script>

    <script>
        $(function () {
            $('[rel="tooltip"]').tooltip();
        });

        // Initialize javascript/jquery plugins
        new Clipboard('.clipboard');

        autosize($('.autosize'));

        $('.maxlength').maxlength({
            alwaysShow: true,
            threshold: 0,
            warningClass: 'text-muted',
            limitReachedClass: 'text-danger',
            placement: 'top left',
            preText: '',
            separator: '/',
            postText: '',
            appendToParent: true
        });
    </script>
</body>
</html>
