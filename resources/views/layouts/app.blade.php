<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="robots" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Boltz : Crypto Admin Template" />
	<meta property="og:title" content="Boltz : Crypto Admin Template" />
	<meta property="og:description" content="Boltz : Crypto Admin Template" />
	<meta property="og:image" content="https://boltz.dexignzone.com/xhtml/social-image.png" />
	<meta name="format-detection" content="telephone=no">

	<!-- PAGE TITLE HERE -->
	<title>SRPB -
        @if (request()->segment(1) == '')
            Dashboard
        @elseif(request()->segment(1) == 'program-bantuan')
            Program Bantuan
        @else
            {{ ucwords(request()->segment(1)) }}
        @endif
    </title>

	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="{{ asset('assets') }}/images/icon.png" />

	<link href="{{ asset('assets') }}/vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
	<link href="{{ asset('assets') }}/vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
	<!-- Style css -->
    <link href="{{ asset('assets') }}/css/style.css" rel="stylesheet">

</head>
<body>

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="{{ url('/') }}" class="brand-logo">
				<h3 class="text-primary" style="margin-top: 10px; letter-spacing:1px; font-weight:800;">SRPB</h3>

            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->


		<!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">

                        </div>
                        <ul class="navbar-nav header-left">
							<a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                                <img src="{{ asset('assets') }}/images/profile/pic1.jpg" width="60" alt=""/>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <span class="dropdown-item ai-icon">
                                    Halo, <br>Perguruan Tinggi
                                </span>
                                <!-- <a href="{{ asset('assets') }}/app-profile.html" class="dropdown-item ai-icon">
                                    <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <span class="ms-2">Profile </span>
                                </a> -->
                                <a href="{{ route('logout_form',$userId) }}" class="dropdown-item ai-icon" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                    <span class="ms-2">Logout </span>
                                </a>
                                <form id="logout-form" action="{{ route('logout_form',$userId) }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                                {{-- <a href="#" class="dropdown-item ai-icon" onclick="logout()">
                                    <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                    <span class="ms-2">Logouts </span>
                                </a> --}}
                            </div>
                        </ul>
                    </div>
				</nav>
			</div>
		</div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="deznav">
            <div class="deznav-scroll">
				<div class="dropdown header-profile">
					<a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
						<img src="{{ asset('assets') }}/images/profile/pic1.jpg" width="20" alt=""/>
						<div class="header-info">
							<span class="font-w400 mb-0">Halo,<b>{{ $username }}</b></span>

							<small class="text-end font-w400">{{ $email }}</small>
						</div>
					</a>
					<div class="dropdown-menu dropdown-menu-end">
						<!-- <a href="{{ asset('assets') }}/app-profile.html" class="dropdown-item ai-icon">
							<svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
							<span class="ms-2">Profile </span>
						</a> -->
                        <a href="{{ route('logout_form',$userId) }}" class="dropdown-item ai-icon" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                    <span class="ms-2">Logout </span>
                                </a>
                                <form id="logout-form" action="{{ route('logout_form',$userId) }}" method="POST" class="d-none">
                                    @csrf
                                </form>
					</div>
				</div>
				<ul class="metismenu" id="menu">
                    <li><a href="{{ url('/?id='.$userId) }}" class="ai-icon" aria-expanded="false">
							<i class="flaticon-025-dashboard"></i>
							<span class="nav-text">Dashboard</span>
						</a>
					</li>
                    <li><a href="{{ url('/program-bantuan/?id='.$userId) }}" class="ai-icon" aria-expanded="false">
							<i class="flaticon-033-feather"></i>
							<span class="nav-text">Program Bantuan</span>
						</a>
					</li>
                </ul>
			</div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

		<!--**********************************
            Content body start
        ***********************************-->
        @yield('content')
        <!--**********************************
            Content body end
        ***********************************-->



        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">

            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="https://dexignzone.com/" target="_blank">DexignZone</a> 2021</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

		<!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


	</div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->

    <script>
    function logout() {
        fetch('http://127.0.0.1:8000/sanctum/csrf-cookie', {
            method: 'GET',
            credentials: 'include',  // Pastikan credentials diaktifkan
        })
        .then(response => {
            return fetch('http://127.0.0.1:8000/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')  // Pastikan token ada di localStorage
                },
                credentials: 'include',  // Tambahkan ini untuk mengirimkan cookies
            });
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Logout failed: ' + response.statusText);
            }
            localStorage.removeItem('token'); // Hapus token dari localStorage
            window.location.href = 'http://127.0.0.1:8000/login'; // Redirect ke halaman login
        })
        .catch(error => console.error(error.message));
    }
    </script>
    <!-- Required vendors -->
    <script src="{{ asset('assets') }}/vendor/global/global.min.js"></script>
	<script src="{{ asset('assets') }}/vendor/chart.js/Chart.bundle.min.js"></script>
	<script src="{{ asset('assets') }}/vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>

	<!-- Apex Chart -->
	<script src="{{ asset('assets') }}/vendor/apexchart/apexchart.js"></script>
	<script src="{{ asset('assets') }}/vendor/owl-carousel/owl.carousel.js"></script>

	<!-- Dashboard 1 -->
	<script src="{{ asset('assets') }}/js/dashboard/dashboard-1.js"></script>

    <script src="{{ asset('assets') }}/js/custom.min.js"></script>
	<script src="{{ asset('assets') }}/js/deznav-init.js"></script>




</body>
</html>
