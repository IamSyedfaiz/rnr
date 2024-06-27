<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>DASHMIN - Bootstrap Admin Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('public/backend/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backend/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backend/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backend/assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backend/assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backend/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backend/assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backend/assets/css/style.css') }}" rel="stylesheet">
</head>

<body>

    @yield('content')
</body>

<script src="{{ asset('public/backend/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('public/backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('public/backend/assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('public/backend/assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('public/backend/assets/vendor/quill/quill.js') }}"></script>
<script src="{{ asset('public/backend/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('public/backend/assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('public/backend/assets/vendor/php-email-form/validate.js') }}"></script>
<script src="{{ asset('public/backend/assets/js/main.js') }}"></script>

</html>
