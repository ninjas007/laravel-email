<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- theme meta -->
    <meta name="theme-name" content="quixlab" />

    {{-- meta csrf token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>EMAILQ - {{ strtoupper($page) ?? 'Home' }}</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('theme') }}/images/favicon.png">
    <!-- Pignose Calender -->
    <link href="{{ asset('theme') }}/plugins/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <!-- Chartist -->
    <link rel="stylesheet" href="{{ asset('theme') }}/plugins/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="{{ asset('theme') }}/plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
    <!-- Custom Stylesheet -->
    <link href="{{ asset('theme') }}/css/style.css" rel="stylesheet">

    @yield('css')

</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"
                    stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <div class="brand-logo">
                <a href="index.html">
                    <b class="logo-abbr"><img src="{{ asset('theme') }}/images/logo-white.png" alt=""> </b>
                    <span class="logo-compact"><img src="{{ asset('theme') }}/images/logo-white.png"
                            alt=""></span>
                    <span class="brand-title">
                        <img src="{{ asset('theme') }}/images/logo-white.png" alt="">
                    </span>
                </a>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->


        @include('layouts.navbar')

        @include('layouts.sidebar')


        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        @if ($page != 'home')
                            <li class="breadcrumb-item active">
                                <a href="javascript:void(0)">{{ ucfirst($page) }}</a>
                            </li>
                        @endif
                    </ol>
                </div>
            </div>

            <div class="container-fluid mt-3">
                @yield('content-app')
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->




        <!--**********************************
        Footer start
    ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright &copy; {{ date('Y')}} All rights reserved</p>
            </div>
        </div>


        <!--**********************************
            Footer end
        ***********************************-->
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="{{ asset('theme') }}/js/jquery-3.7.1.min.js"></script>

    {{-- Seet Alert --}}
    <script src="{{ asset('theme') }}/js/sweetalert.min.js"></script>

    <script src="{{ asset('theme') }}/plugins/common/common.min.js"></script>
    <script src="{{ asset('theme') }}/js/custom.min.js"></script>
    <script src="{{ asset('theme') }}/js/settings.js"></script>
    <script src="{{ asset('theme') }}/js/gleek.js"></script>
    <script src="{{ asset('theme') }}/js/styleSwitcher.js"></script>

    <!-- Chartjs -->
    {{-- <script src="{{ asset('theme') }}/plugins/chart.js/Chart.bundle.min.js"></script> --}}
    <!-- Circle progress -->
    {{-- <script src="{{ asset('theme') }}/plugins/circle-progress/circle-progress.min.js"></script> --}}
    <!-- Datamap -->
    {{-- <script src="{{ asset('theme') }}/plugins/d3v3/index.js"></script>
    <script src="{{ asset('theme') }}/plugins/topojson/topojson.min.js"></script>
    <script src="{{ asset('theme') }}/plugins/datamaps/datamaps.world.min.js"></script> --}}
    <!-- Morrisjs -->
    {{-- <script src="{{ asset('theme') }}/plugins/raphael/raphael.min.js"></script>
    <script src="{{ asset('theme') }}/plugins/morris/morris.min.js"></script> --}}
    <!-- Pignose Calender -->
    {{-- <script src="{{ asset('theme') }}/plugins/moment/moment.min.js"></script>
    <script src="{{ asset('theme') }}/plugins/pg-calendar/js/pignose.calendar.min.js"></script> --}}
    <!-- ChartistJS -->
    {{-- <script src="{{ asset('theme') }}/plugins/chartist/js/chartist.min.js"></script>
    <script src="{{ asset('theme') }}/plugins/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js"></script>

    <script src="{{ asset('theme') }}/js/dashboard/dashboard-1.js"></script> --}}

    <script type="text/javascript">
        async function getData(url) {
            let res = await fetch(url)
                .then((res) => res.json())
                .catch((err) => console.log(err));

            return res;
        }

        async function submitFormData(
            formData,
            url,
            method = "POST",
            redirectUrl = ""
        ) {
            $.ajax({
                url: url,
                type: method,
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                credentials: "include",
                beforeSend: function() {
                    swal({
                        title: "Loading...",
                        text: "Sedang mengirim data...",
                        buttons: false,
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                    });
                },
                success: function(res, textStatus, xhr) {
                    if (res.status == 'success') {
                        swal("Berhasil!", "Data berhasil disimpan.", "success").then(() => {
                            if (redirectUrl != "") {
                                window.location.href = redirectUrl;
                            } else {
                                location.reload();
                            }
                        });
                    } else {
                        swal(
                            "Error",
                            res.message || "Terjadi kesalahan. Silahkan coba lagi.",
                            "error"
                        );
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON?.errors || {};

                        let errorMessages = "";
                        for (let field in errors) {
                            const fieldErrors = errors[field].join(" ");
                            errorMessages += `${fieldErrors}<br>`;
                        }

                        swal({
                            title: "Error Validasi",
                            content: {
                                element: "div",
                                attributes: {
                                    innerHTML: errorMessages,
                                },
                            },
                            icon: "error",
                        });
                    } else {
                        swal(
                            "Error",
                            "Terjadi kesalahan. Silahkan coba lagi.",
                            "error"
                        );
                    }
                },
            });
        }

        async function deleteData(url) {
            swal({
                title: "Hapus Data",
                text: "Yakin ingin mendelete data ini",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            _method: "DELETE"
                        },
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        credentials: "include",
                        beforeSend: function() {
                            swal({
                                title: "Loading...",
                                text: "Menghapus data...",
                                buttons: false,
                                closeOnClickOutside: false,
                                closeOnEsc: false,
                            });
                        },
                        success: function(res, textStatus, xhr) {
                            if (res.status == 'success') {
                                swal("Berhasil", "Data berhasil dihapus.", "success").then(() => {
                                    location.reload();
                                });
                            } else {
                                swal(
                                    "Error",
                                    res.message ||
                                    "Terjadi kesalahan. Silahkan coba lagi.",
                                    "error"
                                );
                            }
                        },
                        error: function(xhr) {
                            swal(
                                "Error",
                                "Terjadi kesalahan. Silahkan coba lagi.",
                                "error"
                            );
                        }
                    });
                }
            });
        }
    </script>

    @yield('js')

</body>

</html>
