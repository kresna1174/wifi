<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Login</title>
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
	<link href="{{ asset('assets') }}/css/material/style-responsive.min.css" rel="stylesheet" />
	<link href="{{ asset('assets') }}/css/material/theme/default.css" rel="stylesheet" id="theme" />
	<script src="{{ asset('assets') }}/plugins/pace/pace.min.js"></script>
    <style>
        .error {
            width: 50%;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body class="pace-top">
	<div id="page-loader" class="fade show">
		<div class="material-loader">
			<svg class="circular" viewBox="25 25 50 50">
				<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
			</svg>
			<div class="message">Loading...</div>
		</div>
	</div>
	<div id="page-container" class="fade">
		<div class="login bg-black animated fadeInDown">
			<div class="login-header">
				<div class="brand text-center">
					Login
				</div>
				<div class="icon">
					<i class="fa fa-lock"></i>
				</div>
			</div>
            <?php if(session('error')) { ?>
                <div class="alert alert-danger error">
                    {{session('error')}}
                </div>
            <?php } ?>
			<div class="login-content">
				<form action="{!! route('login') !!}" method="post" class="margin-bottom-0">
                {{ csrf_field() }}
					<div class="form-group m-b-20">
						<input type="text" name="username" class="form-control form-control-lg inverse-mode" placeholder="Username" required />
					</div>
					<div class="form-group m-b-20">
						<input type="password" name="password" class="form-control form-control-lg inverse-mode" placeholder="Password" required />
					</div>
					<div class="login-buttons">
						<button type="submit" class="btn btn-success btn-block btn-lg">Login</button>
					</div>
				</form>
			</div>
	</div>
	<script src="{{ asset('assets') }}/plugins/jquery/jquery-3.3.1.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/js-cookie/js.cookie.js"></script>
	<script src="{{ asset('assets') }}/js/theme/material.min.js"></script>
	<script src="{{ asset('assets') }}/js/apps.min.js"></script>
	<script>
		$(document).ready(function() {
			App.init();
		});
	</script>
</body>
</html>
