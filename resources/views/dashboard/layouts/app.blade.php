<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendors/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('dashboard/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('dashboard/assets/images/favicon.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Sweetalert2 style -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/dataTables.min.css') }}">
    @stack('styles')
    <style>
        input:focus {
            outline: none ! important;
            box-shadow: none ! important;
        }
        select:focus {
            outline-color: #86b7fe ! important;
            box-shadow: none ! important;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        @include('dashboard.layouts.partials.header')
        <div class="container-fluid page-body-wrapper">
            @include('dashboard.layouts.partials.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                @include('dashboard.layouts.partials.footer')
            </div>
        </div>
    </div>

    @include('dashboard.layouts.partials.media-modal')

    <form action="" method="POST" id="deleteForm">
        @csrf
        @method('DELETE')
    </form>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('dashboard/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('dashboard/assets/vendors/chart.js') }}/chart.umd.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('dashboard/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/misc.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/settings.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/todolist.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/jquery.cookie.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{ asset('dashboard/assets/js/dashboard.js') }}"></script>
    <!-- End custom js for this page -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Sweetalert2 js -->
    <script src="{{ asset('dashboard/assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/custom.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/dataTables.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.deleteBtn').on('click', function(e) {
                e.preventDefault();
                const href = $(this).attr('href');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#deleteForm').attr('action', href).submit();
                    }
                });
            })
        });
    </script>
    <script>
        function showToast(type, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: message
            });
        }
    </script>
    @if (session('success'))
        <script>
            showToast('success', '{{ session('success') }}');
        </script>
    @endif
    @if (session('error'))
        <script>
            showToast('error', '{{ session('error') }}');
        </script>
    @endif

    <script>
        $(document).ready(function() {
            // Disable DataTables error messages
            $.fn.dataTable.ext.errMode = 'throw';
        });
    </script>

    @stack('scripts')
</body>

</html>
