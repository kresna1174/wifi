<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title><?= isset($title) ? $title : 'wifiger' ?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	

	<link href="https://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
	<link href="{{ asset('assets') }}/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
	<link href="{{ asset('assets') }}/plugins/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
	<link href="{{ asset('assets') }}/plugins/font-awesome/5.3/css/all.min.css" rel="stylesheet" />
	<link href="{{ asset('assets') }}/plugins/animate/animate.min.css" rel="stylesheet" />
	<link href="{{ asset('assets') }}/css/material/style.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="{{ asset('assets') }}/plugins/jquery.growl/css/jquery.growl.css">
	<link rel="stylesheet" href="{{ asset('assets') }}/plugins/sweetalert-dark/dark.css">
	<link href="{{ asset('assets') }}/css/material/style-responsive.min.css" rel="stylesheet" />
	<link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap.min.css" rel="stylesheet" />
	<link href="{{ asset('assets') }}/css/material/theme/default.css" rel="stylesheet" id="theme" />
	<link href="{{ asset('assets') }}/plugins/jquery-jvectormap/jquery-jvectormap.css" rel="stylesheet" />
	<link href="{{ asset('assets') }}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
	<script src="{{ asset('assets') }}/plugins/pace/pace.min.js"></script>
</head>
<body>

	<div id="page-loader" class="fade show">
		<div class="material-loader">
			<svg class="circular" viewBox="25 25 50 50">
				<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
			</svg>
			<div class="message">Loading...</div>
		</div>
	</div>

	

	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed page-with-wide-sidebar">

		<div id="header" class="header navbar-default">

			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed navbar-toggle-left" data-click="sidebar-minify">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="{!! route('pelanggan') !!}" class="navbar-brand">
					Wifiger
				</a>
			</div>

			

			<ul class="navbar-nav navbar-right">
				<li class="dropdown navbar-user">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
						{!! Auth::user()->name !!}
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="{!! route('change.password') !!}" class="dropdown-item">Changepassword</a>
						<div class="dropdown-divider"></div>
						<a href="{!! route('logout') !!}" class="dropdown-item">Log Out</a>
					</div>
				</li>
			</ul>
		</div>

		

		<div id="sidebar" class="sidebar" data-disable-slide-animation="true">

			<div data-scrollbar="true" data-height="100%">
				<ul class="nav">
					<li class="nav-header">Navigation</li>
					<li class="has-sub active">
						<a href="javascript:;">
							<i class="material-icons">home</i>
							<span>Dashboard</span>
						</a>
					</li>
					<li class="has-sub">
						<a href="javascript:;">
                            <b class="caret"></b>
							<i class="fa fa-database"></i>
							<span>Master</span>
						</a>
						<ul class="sub-menu">
							<li><a href="{!! route('pelanggan') !!}">pelanggan</a></li>
							<li><a href="{!! route('deposit') !!}">deposit</a></li>
						</ul>
					</li>
					<li class="has-sub">
						<a href="javascript:;">
							<b class="caret"></b>
							<i class="fa fa-exchange-alt"></i>
							<span>Transaksi</span> 
						</a>
						<ul class="sub-menu">
							<li><a href="{!! route('pembayaran') !!}">Pembayaran</a></li>
							<li><a href="{!! route('pelanggan') !!}">Tagihan</a></li>
						</ul>
					</li>
					<li>
                        <a href="{!! route('pemasangan') !!}">
							<i class="fa fa-shopping-cart"></i>
							<span>Pemasangan</span>
						</a>
					</li>
					<li>
                        <a href="{!! route('UserService') !!}">
							<i class="fa fa-users"></i>
							<span>User Service</span>
						</a>
					</li>
					<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>

				</ul>

			</div>

		</div>
		<div class="sidebar-bg"></div>

		

		<div id="content" class="content">
            @yield('content')
		</div>
	</div>

	

	<script src="{{ asset('assets') }}/plugins/jquery/jquery-3.3.1.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/js-cookie/js.cookie.js"></script>
	<script src="{{ asset('assets') }}/js/theme/material.min.js"></script>
	<script src="{{ asset('assets') }}/js/apps.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/flot/jquery.flot.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/flot/jquery.flot.time.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/flot/jquery.flot.resize.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/flot/jquery.flot.pie.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/sparkline/jquery.sparkline.js"></script>
	<script src="{{ asset('assets') }}/plugins/jquery-jvectormap/jquery-jvectormap.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/jquery-jvectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="{{ asset('assets') }}/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="{{ asset('assets') }}/plugins/bootbox/bootbox.all.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/jquery.growl/js/jquery.growl.js"></script>
	<script src="{{ asset('assets') }}/plugins/sweetalert-dark/sweetalert.min.js"></script>
	<script src="{{ asset('assets') }}/js/demo/dashboard.min.js"></script>

	
	<script>
		$(document).ready(function() {
			App.init();
			Dashboard.init();
		});
		</script>
        @yield('script')
</body>
</html>
