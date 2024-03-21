<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $general->siteName($pageTitle ?? '') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @include('user.newInclude.css')
</head>

<body>
    @include('user.newInclude.header')
    <!-- END nav -->
    <!-- content section -->
    @yield('content')
    <!-- End content section -->
    @include('user.newInclude.loader')
    @include('user.newInclude.js')
</body>

</html>