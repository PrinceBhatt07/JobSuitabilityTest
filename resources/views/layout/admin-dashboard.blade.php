<!doctype html>
<html lang="en">

<head>
    <title>Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.10/dayjs.min.js"
        integrity="sha512-FwNWaxyfy2XlEINoSnZh1JQ5TRRtGow0D6XcmAWmYCRgvqOUTnzCxPc9uF35u5ZEpirk1uhlPVA19tflhvnW1g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />



    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .Active {
            background: #007bff;
            color: white;
        }

        .Active a span {
            color: white;
        }
    </style>
</head>

<body>
    <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar">
            <div class="custom-menu">
                <button type="button" id="sidebarCollapse" class="btn btn-primary">
                    <i class="fa fa-bars"></i>
                    <span class="sr-only">Toggle Menu</span>
                </button>
            </div>
            <h1><a href="/admin/dashboard" class="logo">Admin Dashboard</a></h1>
            <ul class="list-unstyled components mb-5">
                <li class="{{ request()->is('admin/qna-ans') ? 'Active' : '' }}">
                    <a href="/admin/qna-ans"><span class="fa fa-question-circle mr-3"></span> Q&A</a>
                </li>
                <li class="{{ request()->is('admin/tags') ? 'Active' : '' }}">
                    <a href="/admin/tags"><span class="fa fa-tags mr-3"></span> Tags</a>
                </li>

                <li class="{{ request()->is('admin/dashboard') ? 'Active' : '' }}">
                    <a href="/admin/dashboard"><span class="fa fa-book mr-3"></span> Tags</a>
                </li>
                <li class="{{ request()->is('admin/exam') ? 'Active' : '' }}">
                    <a href="/admin/exam"><span class="fa fa-tasks mr-3"></span> Exams</a>
                </li>
                <li class="{{ request()->is('admin/marks') ? 'Active' : '' }}">
                    <a href="/admin/marks"><span class="fa fa-check mr-3"></span> Marks</a>
                </li>
                <li class="{{ request()->is('admin/students') ? 'Active' : '' }}">
                    <a href="/admin/students"><span class="fa fa-graduation-cap mr-3"></span>Students</a>
                </li>
                <li class="{{ request()->is('admin/students-exam') ? 'Active' : '' }}">
                    <a href="/admin/students-exam"><span class="fa fa-graduation-cap mr-3"></span>Students Exams</a>
                </li>
                <li class="{{ request()->is('admin/review') ? 'Active' : '' }}">
                    <a href="/admin/review"><span class="fa fa-comments mr-3"></span>Exam Review</a>
                </li>
                <li class="{{ request()->is('admin/remarks') ? 'Active' : '' }}">
                    <a href="/admin/remarks"><span class="fa fa-bar-chart mr-3"></span>Remarks</a>
                </li>
                <li class="{{ request()->is('admin/logout') ? 'Active' : '' }}">
                    <a href="/logout"><span class="fa fa-sign-out mr-3"></span> Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        @yield('content')

        {{-- <script src="{{ asset('js/jquery.min.js') }}"></script> --}}
        <script src="{{ asset('js/timepicker-bs4.js') }}"></script>
        <script src="{{ asset('js/popper.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.10/plugin/customParseFormat.min.js"
            integrity="sha512-FM59hRKwY7JfAluyciYEi3QahhG/wPBo6Yjv6SaPsh061nFDVSukJlpN+4Ow5zgNyuDKkP3deru35PHOEncwsw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" defer="defer"></script>
        <script src="{{ asset('js/main.js') }}"></script>
        {{-- <script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script> --}}
        @include('Admin.user-info-modal')

        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"
            integrity="sha512-Dz4zO7p6MrF+VcOD6PUbA08hK1rv0hDv/wGuxSUjImaUYxRyK2gLC6eQWVqyDN9IM1X/kUA8zkykJS/gEVOd3w=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            jQuery('.bs-time').timepicker({
                showInputs: true,
                // options here
                format: 'hh:mm A',
            });



            jQuery('.bs-time-24').timepicker({
                // options here
                format: 'HH:mm',
            });

            var backgroundColors = [
                '#000000', '#FF0000', '#0000FF', '#800080',
                '#008080', '#FFD700', '#008000', '#800000', '#FFFF00',
                '#00FFFF', '#808080', '#FFC0CB', '#FF4500', '#7CFC00',
                '#4B0082', '#32CD32', '#FF69B4', '#1E90FF', '#FF6347'
            ];
        </script>

</body>

</html>
