<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Registrasi Akun</title>

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
			<ul class="navbar-nav ml-auto">
				 

				<li class="nav-item dropdown">
					<a href="{{ route('login') }}" class="navbar-nav-link">
						<i class="icon-arrow-left16"></i>
						<span class="ml-2">Back To Login</span>
					</a>					
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page content -->
	<div class="page-content">
  
		<!-- Main content -->
		<div class="content-wrapper card">
            @if ($message = Session::get('sukses'))
                 <div class="card-body text-info text-center mt-5">
                    <h2><?php echo nl2br($message) ;?></h2>
                </div>
            @else            
			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center" style="display: none;">
                
				<!-- Registration form -->
				<form method="POST" action="{{ route('createRegister') }}" class="flex-fill form-validate-jquery">
                    @csrf
					<div class="row">
						<div class="col-lg-6 offset-lg-3">
							<div class="card mb-0">
								<div class="card-body">
									<div class="text-center mb-5">
                                        <img class="mb-2" src="{{ url('/') }}/global_assets/images/trendy.png" alt="" width="80px">

										<!-- <i class="icon-plus3 icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i> -->
										<h5 class="mb-1">Pendaftaran Akun Khusus Anggota Koperasi Kopkar Trendy</h5>
										<span class="d-block text-muted"></span>
									</div>


									<div class="row">
										<div class="col-md-6">
											<div class="form-group form-group-feedback form-group-feedback-right">
												<input type="text" name="name" required class="form-control" placeholder="Nama Lengkap" value="{{ old('name') }}">
												<div class="form-control-feedback">
													<i class="icon-user-check text-muted"></i>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group form-group-feedback form-group-feedback-right">
												<input type="text" name="numbers" required class="form-control" placeholder="NIK" value="{{ old('numbers') }}">
												<div class="form-control-feedback">
													<i class="icon-vcard text-muted"></i>
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group form-group-feedback form-group-feedback-right">
												<input type="password" name="password" id="password" required class="form-control" placeholder="Buat password, minimal 8 character">
												<div class="form-control-feedback">
													<i class="icon-user-lock text-muted"></i>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group form-group-feedback form-group-feedback-right">
												<input type="password" name="repeat_password" id="repeat_password" required class="form-control" placeholder="Ulangi password">
												<div class="form-control-feedback">
													<i class="icon-user-lock text-muted"></i>
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label class="button-font">Captcha :</label>
												<?=captcha_img('flat');?>
											</div>
										</div>
										<div class="col-md-3 mt-3">
											<div class="form-group form-group-feedback form-group-feedback-right">
												<input type="text" style="font-size: large;" required name="captcha" id="captcha" class="form-control" placeholder="Input captcha">
												 
											</div>
										</div>
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


									<button type="submit" class="btn bg-teal-400 btn-labeled btn-labeled-right"><b><i class="icon-plus3"></i></b> Daftar</button>
								</div>
							</div>
						</div>
					</div>
				</form>
				<!-- /registration form -->

			</div>
			<!-- /content area -->
            @endif


			<!-- Footer -->
			<div class="navbar navbar-expand-lg navbar-light">
				<div class="text-center d-lg-none w-100">
					<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
						<i class="icon-unfold mr-2"></i>
						Footer
					</button>
				</div>

				<div class="navbar-collapse collapse" id="navbar-footer">
					<span class="navbar-text">
						&copy; 2021  Kopkar Trendy PT. Telekomunikasi Indonesia 
					</span>
				</div>
			</div>
			<!-- /footer -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->

</body>
</html>
