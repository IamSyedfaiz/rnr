<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dashboard - RNR</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    @stack('style')
</head>

<body>
    <!-- ======= Header ======= -->
    @include('backend.layouts.navbar')

    <!-- End Header -->

    <!-- ======= Sidebar ======= -->

    @include('backend.layouts.sidebar')

    @yield('content')


    <!-- End Sidebar-->


    <!-- End #main -->

    <!-- ======= Footer ======= -->
    @include('backend.layouts.footer')

    <!-- End Footer -->


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('public/backend/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('public/backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/backend/assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('public/backend/assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('public/backend/assets/vendor/quill/quill.js') }}"></script>
    <script src="{{ asset('public/backend/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('public/backend/assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('public/backend/assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('public/backend/assets/js/main.js') }}"></script>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    @yield('script')
    {{-- <script>
        // $(document).ready(function() {
        //     $('#example').DataTable();
        // });
        new DataTable("#example", {
            scrollCollapse: true,
            scrollY: "500px",
        });
    </script>
    <!-- Template Javascript -->
    <script src="{{ asset('public/backend/dashmin/js/main.js') }}"></script>

    <script type="text/javascript">
        function custom_template(obj) {
            var data = $(obj.element).data();
            var text = $(obj.element).text();
            if (data && data['img_src']) {
                img_src = data['img_src'];
                template = $("<div><img src=\"" + img_src +
                    "\" style=\"width:100%;height:150px;\"/><p style=\"font-weight: 700;font-size:14pt;text-align:center;\">" +
                    text + "</p></div>");
                return template;
            }
        }
        var options = {
            'templateSelection': custom_template,
            'templateResult': custom_template,
        }
        $('#colorPalette').select2(options);
        $('.select2-container--default .select2-selection--single').css({
            'height': '220px'
        });
    </script>

    @yield('script')
    @stack('style') --}}
</body>

</html>
