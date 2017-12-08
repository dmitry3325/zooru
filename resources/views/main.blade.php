<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        zoo.ru
    </title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="/css/app.css">
    @stack('style')
</head>
<body>
<div id="app">
    @include('partials.header')
    @include('partials.menu')
    <div id="content">
        <notification></notification>

        @yield('content')
{{--        @include('partials.subscription')--}}
    </div>

    <footer>
        <div class="container" style="padding: 5em 0; text-align: center;">
            <h1>hallo from footer</h1>
        </div>
    </footer>
</div>
<script type="text/javascript" src="/js/app.js"></script>
@stack('scripts')
</body>
</html>
