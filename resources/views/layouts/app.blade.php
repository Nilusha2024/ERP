<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    {{-- csrf for ajax --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="dist/css/all.min.css"
        integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
        crossorigin="anonymous" />
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="dist/css/css.css?ver=0.1">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css?v=3.2.0">
    <link href="dist/css/select2.min.css" rel="stylesheet" />
    <!-- Table search css -->
    <link rel="stylesheet" type="text/css" href="dist/css/jquery.dataTables.min.css" />
    <!-- <link href="path/to/component-chosen.min.css" rel="stylesheet"> -->
    <!-- Table search and file export css -->
    <link rel="stylesheet" type="text/css" href="dist/css/buttons.dataTables.min.css">
   


    <!-- @stack('third_party_stylesheets')

    @stack('page_css') -->
</head>


<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Main Header -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ url('image/profile.png') }}" class="user-image img-circle elevation-2"
                            alt="User Image">
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- User image -->
                        <li class="user-header bg-primary">
                            <img src="{{ url('image/profile.png') }}" class="img-circle elevation-2" alt="User Image">
                            <p>
                                {{ Auth::user()->name }}
                                <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                            <a href="#" class="btn btn-default btn-flat float-right"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Sign out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.0.5
            </div>
            <strong>Copyright &copy; 2023 <a >Rabbit Solutions</a>.</strong> All rights
            reserved.
        </footer>
    </div>

    <!-- @vite(['resources/css/app.css'])
    @vite(['resources/js/app.js'])

    @stack('third_party_scripts')

    @stack('page_scripts') -->


    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="dist/js/demo.js"></script>
    <script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
    <script src="plugins/raphael/raphael.min.js"></script>
    <script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
    <script src="plugins/jquery-mapael/maps/usa_states.min.js"></script>
    <script src="dist/js/pages/dashboard2.js"></script>
    

    {{-- select2 search box cdn --}}
    <script src="dist/js/select2.min.js"></script>

    {{-- sweet alert cdn --}}
    <script src="dist/js/sweetalert2@11.js"></script>


    <!-- Table search and export files js -->

    
    <script src="dist/js/jquery.dataTables.min.js"></script>
    <script src="dist/js/dataTables.buttons.min.js"></script>
    <script src="dist/js/jszip.min.js"></script>
    <script src="dist/js/pdfmake.min.js"></script>
    <script src="dist/js/vfs_fonts.js"></script>
    <script src="dist/js/buttons.html5.min.js"></script>
    <script src="dist/js/buttons.print.min.js"></script>

    <!-- file export script -->
    <script>
$(document).ready(function() {
        $('#dataTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel'
                },
                {
                    extend: 'csv',
                    text: 'CSV'
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    customize: function (doc) {
                        doc.pageMargins = [1, 1, 1, 1];
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    customize: function (win) {
                        // Set styles or modify the print window here
                        // For example, you can set margins to fit the printable area
                        $(win.document.body).css('margin', '10mm');
                    }
                }
            ]
        });
    });

        $(document).ready(function() {
            $('#dataTableNoPagination').DataTable({
                paging: false,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'csv', 'pdf', 'print'
                ]
            });
        });
    </script>


</body>

</html>
