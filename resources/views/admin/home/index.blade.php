<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{mix('css/admin.css')}}">
    <link rel="stylesheet" href="{{asset('fontawesome/css/all.css')}}">
    <meta name="csrftoken" content="{{ csrf_token() }}">
    <title>Admin</title>
</head>
<body>

<div id="app">
    <loading v-if="$root.loading"></loading>
    <template>
        <page-header v-if="$store.state.auth.authenticated"></page-header>
        <div class="container-fluid">
            <div class="row">
                <panel-sidebar v-if="$store.state.auth.authenticated"></panel-sidebar>
                <div :class="{'col-12 col-md-10 ms-md-auto': $store.state.auth.authenticated}">
                    <main role="main" class="ps-md-5 d-block">
                        <router-view></router-view>
                    </main>
                </div>
            </div>
        </div>
    </template>
</div>

<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{mix('js/admin/app.js')}}"></script>

</body>
</html>
