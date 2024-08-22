<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Election System - @yield('page_title')</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets') }}/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('assets') }}/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/custom-toggle.css" rel="stylesheet">

    <style>
        .select2-selection{
            height: 40px;
        }
    </style>
    @yield('style_script')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('layouts.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('layouts.header')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">@yield('page_title')</h1>
                    </div>
                    <x-alert/>
                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website {{date('Y')}}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade deleteModal" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <form action="" method="POST" class="deleteModalForm">
            @csrf
            <input type="hidden" name="id" id="deleteInputID">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Do you really want to delete?</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-danger" type="submit">Yes,DELETE IT</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade statusModal" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <form action="" method="POST" class="statusModalForm">
            @csrf
            <input type="hidden" name="id" id="statusInputID">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Status</h5>
                        {{-- <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button> --}}
                    </div>
                    <div class="modal-body">Do you really want to change the status?</div>
                    <div class="modal-footer">
                        <a href="" class="btn btn-secondary" type="button">Cancel</a>
                        <button class="btn btn-success" type="submit">Yes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets') }}/vendor/jquery/jquery.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets') }}/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets') }}/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('assets') }}/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('assets') }}/js/demo/chart-area-demo.js"></script>
    <script src="{{ asset('assets') }}/js/demo/chart-pie-demo.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2-multiple').select2();

            let table = new DataTable('.datatable');

            $(".deleteBtn").click(function(){
                var id = $(this).data('id');
                var route = $(this).data('route');
                $("#deleteInputID").val(id);
                $(".deleteModalForm").attr("action", route);
                $(".deleteModal").modal("show");
            });

            $(".statusBtn").click(function(){
                var id = $(this).data('id');
                var route = $(this).data('route');
                $("#statusInputID").val(id);
                $(".statusModalForm").attr("action", route);
                $(".statusModal").modal("show");
            });

            $(".deleteAccountModalOpenBtn").click(function(){
                $("#confirm-user-deletion").modal("show");
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(function() {
                var bsAlert = new bootstrap.Alert(successAlert);
                bsAlert.close();
            }, 2000);
        }

        var errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            setTimeout(function() {
                var bsAlert = new bootstrap.Alert(errorAlert);
                bsAlert.close();
            }, 2000);
        }
    });
    </script>
@yield('script')
</body>

</html>
