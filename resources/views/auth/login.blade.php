<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>KopKar Trendy - Login</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" href="{{ url('/') }}/assets/images/icontelkom.png" />
	<link href="{{ url('/') }}/global_assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
	<link href="{{ url('/') }}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="{{ url('/') }}/assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
	<link href="{{ url('/') }}/assets/css/layout.min.css" rel="stylesheet" type="text/css">
	<link href="{{ url('/') }}/assets/css/components.min.css" rel="stylesheet" type="text/css">
	<link href="{{ url('/') }}/assets/css/colors.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{ url('/') }}/global_assets/js/main/jquery.min.js"></script>
	<script src="{{ url('/') }}/global_assets/js/main/bootstrap.bundle.min.js"></script>
	<script src="{{ url('/') }}/global_assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="{{ url('/') }}/global_assets/js/plugins/forms/styling/uniform.min.js"></script>

	<script src="{{ url('/') }}/assets/js/app.js"></script>
	<script src="{{ url('/') }}/global_assets/js/demo_pages/login.js"></script>
	<!-- /theme JS files -->

	<style>
		body {
			background-image: url("{{ url('/') }}/assets/images/background.jpg?v=1.1");
			background-repeat: no-repeat;
			background-size: cover;
		}

		.uniform-checker {
			background: #fbfaf8de;
		}
	</style>
</head>

<body>

	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">

				<!-- Login card -->
				<form method="POST" action="{{ route('login') }}">
					@csrf
					<div class="card mb-0" style="background-color: #0048a2a8;">
						<div class="card-body">
							<div class="text-center mb-3">
								<!-- <img src="{{ url('/') }}/global_assets/images/trendy.png" alt="" width="100px"> -->

								<span class="text-white">
									<h1 style="margin-top:20px!important">Kopkar Trendy</h1>
								</span>
								<span>
									<h5 style="color:#d2d2d2;">Please sign in</h5>
								</span>
							</div>

							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="hidden" name="status" value="1">
								<input id="email" type="email" placeholder="E-mail" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" style="background: #fbfaf8de;" required autocomplete="email" autofocus>
								<div class="form-control-feedback">
									<i class="icon-user text-muted" style="left: 5px;"></i>
								</div>
							</div>

							<div class="form-group form-group-feedback form-group-feedback-left">
								<input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" style="background: #fbfaf8de;" required autocomplete="current-password">
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted" style="left: 5px;"></i>
								</div>
								@if (\Session::has('error'))
								<span class="bg-warning text-white mt-3" role="alert">
									<strong>{!! \Session::get('error') !!}</strong>
								</span>
								@endif
								@error('email')
								<span class="bg-warning text-white mt-3" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
								@error('password')
								<span class="bg-warning text-white mt-3" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror

							</div>

							<div class="form-group">
								<div class="form-check mb-0">
									<label class="form-check-label text-white">
										<input class="form-input-styled" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
										Remember
									</label>

									<span style="float: right;"><a class="text-white" href="{{ route('lupaPassword') }}"> Lupa Password </a></span>
								</div>
							</div>

							<div class="style-msg errormsg clearfix">
								<div class="text-danger" id="login_msg" style="display:none"> </div>
							</div>

							<div class="form-group">
								<button id="btnSignin" type="submit" class="btn btn-block" style="background:#65a9da ; color:white">Masuk </button>

							</div>
							<div class="form-group text-center text-white">
								<span>Untuk registrasi akun : </span>
							</div>

							<div class="form-group">
								<a href="{{ action('RegisterCont@create') }}" class="btn btn-block" style="background: #f5a056;color:white">Daftar</a>
							</div>
							<span class="form-text text-center text-white">&copy; 2021. Kopkar Trendy PT. Telekomunikasi Indonesia</span>
						</div>
					</div>
				</form>
				<!-- /login card -->
			</div>
			<!-- /content area -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->

</body>

</html>
