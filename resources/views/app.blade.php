<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('fontawesome/css/all.css')}}">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="{{mix('css/admin.css')}}">

    <meta name="csrftoken" content="{{ csrf_token() }}">
    @inertiaHead

</head>
<body>
@routes

@inertia
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
