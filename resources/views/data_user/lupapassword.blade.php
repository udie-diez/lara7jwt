<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Reset Password Akun</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
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
	<script src="{{ url('/') }}/global_assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="{{ url('/') }}/global_assets/js/plugins/forms/validation/validate.min.js"></script>
	<script src="{{ url('/') }}/global_assets/js/plugins/forms/styling/uniform.min.js"></script>

	<script src="{{ url('/') }}/assets/js/app.js"></script>
	<script src="{{ url('/') }}/global_assets/js/demo_pages/login.js"></script>
	<script src="{{ url('/') }}/global_assets/js/demo_pages/form_validation.js?v=2"></script>
	<!-- /theme JS files -->

	<script type="text/javascript">
		$(document).ready(function() {
			$('form').on('submit', function() {
				$('#btn_submit').html("<i class='icon-spinner9 spinner position-left'></i> Tunggu...");
			})
		})
	</script>

</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-dark navbar-static" style="background-color: #3273b9;">
		<div class="navbar-brand1">
			<span class="navbar-text" style="min-width: 250px;">
				<span class=""></span>
				<h6>Koperasi Trendy PT. Telekomunikasi Indonesia</h6>
			</span>
		</div>

		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">

		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper card">
			@if ($message = Session::get('sukses'))
			<div class="card-body text-info text-center mt-5">
				<h2><?php echo nl2br($message); ?></h2>
			</div>
			@else
			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center" style="display: none;">
				<!-- Registration form -->
				@if($status==0)

				<form method="POST" action="{{ route('resetPassword') }}" class="flex-fill form-validate-jquery">
					@csrf
					<div class="row">
						<div class="col-lg-6 offset-lg-3">
							<div class="card mb-0">
								<div class="card-body">
									<div class="text-center mb-5">
										<img class="mb-2" src="{{ url('/') }}/global_assets/images/trendy.png" alt="" width="80px">

										<!-- <i class="icon-plus3 icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i> -->
										<h3 class="mb-1">Reset Password </h3>
										<span class="d-block">Anda akan melakukan reset password, Silahkan memasukkan email Anda dan klik tombol Kirim. Jika email Anda valid, maka kami akan mengirimkan link Reset password melalui email Anda !</span>
									</div>

									<div class="text-center">
										<center>

											<div style="width: 200px" class="form-group form-group-feedback form-group-feedback-right text-center">
												<input type="text" name="email" id="email" required class="form-control" placeholder="Email">
												<div class="form-control-feedback">
													<i class="icon-mail5 text-muted"></i>
												</div>
											</div>

										</center>

									</div>

									<div class="row">
										<div class="col-sm-9">
											<div class="form-group">

												@if ($errors->any())
												<div class="alert alert-danger">
													<ul>
														<button type="button" class="close" data-dismiss="alert">×</button>
														@foreach ($errors->all() as $error)
														@if ($errors->has('captcha'))
														<li>Captcha tidak sesuai !!!</li>
														@else
														<li>{{ $error }}</li>
														@endif
														@endforeach
													</ul>
												</div>
												@endif
												@if ($message = Session::get('sukses'))
												<div class="alert alert-success alert-block">
													<button type="button" class="close" data-dismiss="alert">×</button>
													<strong>{{ $message }}</strong>
												</div>
												@endif
											</div>
										</div>
									</div>

									<div class="text-center">
										<button type="submit" id="btn_submit" class="btn bg-info">Kirim</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
				@elseif($status==1)
				<h4 >{{ $msg }}</h4>
				@elseif($status==2)
				<h4 class="text-center">{{ $msg }}</h4><br>

				@endif
			</div>
			<!-- /content area -->
			@endif


			<!-- Footer -->
			<div class="navbar navbar-expand-lg navbar-light">
				<span class="navbar-text">
					&copy; 2021 Kopkar Trendy PT. Telekomunikasi Indonesia
				</span>
			</div>
			<!-- /footer -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->

</body>

</html>