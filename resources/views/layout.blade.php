<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/assets/assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('/assets/assets/img/favicon.png') }}">
    <title>
        La Tenda Information System
    </title>
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <link id="pagestyle" href="{{ asset('/assets/assets/css/soft-ui-dashboard.css?v=1.1.0') }}" rel="stylesheet" />
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-100">
    @include('partials.side')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        @include('partials.nav')
        <div class="card mx-4 shadow-xl">
            <div class="card-body">
                @yield('container')
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('/assets/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('/assets/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/assets/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('/assets/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('/assets/assets/js/plugins/chartjs.min.js') }}"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script src="{{ asset('/assets/assets/js/soft-ui-dashboard.min.js?v=1.1.0') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('#breadcrumb').innerHTML = document.querySelector('.active').children[1]
                .innerHTML;
        })
    </script>
    @if (session('message'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    position: "center",
                    icon: "{{ session('status') }}",
                    title: "{{ session('message') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            })
        </script>
    @endif
    <script>
        const submitForm = (form, text) => {
            event.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: `Yes, ${text} it!`
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    @stack('js')
</body>

</html>
