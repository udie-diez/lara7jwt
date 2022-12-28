<html>

<head>
	<title>Tanda Terima</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
</head>

<body>
	@include('layouts.terbilang')
	@include('layouts.mylib')

	<div style="text-align: center;">
		<h2 class="card-title">TANDA TERIMA</h2>
		Nomor : <span style="font-weight: bold;">{{ $invoice->nomor ?? '' }}</span>
	</div>
	<hr>
	<div style="margin-bottom: 30px;">
	Pekerjaan : <span style="font-weight: bold;">{{$data->nama}}</span>
	</div>
	@if($invoice->jenis==1)
	<table style="font-size: small;" cellpadding="5" cellspacing="0" width="100%" border="1">
		<thead>
			<tr>
				<th style="width: 30px">No.</th>
				<th style="width: 250px">Nama Barang/Jasa</th>
				<th style="width: 50px">Qty</th>
				<th style="width: 100px">Satuan</th>
			</tr>
		</thead>
		<tbody>
			 

			@if(isset($item)) @foreach($item as $row)
			 
			<tr>
				<td align="center">{{ $loop->iteration }}.</td>
				<td> {{ $row->nama}}</td>
				<td align="center">{{ number_format($row->jumlah,0,',','.')}}</td>
				<td align="center">{{ $row->satuan}}</td>

			</tr>
			@endforeach
			@endif
		</tbody>
		 
	</table>
	@endif

	@if($invoice->jenis==2)
	<table style="font-size: small;" cellpadding="5" cellspacing="0" width="100%" border="1">
		<thead>
			<tr>
				<th style="width: 30px">No.</th>
				<th style="width: 350px">U r a i a n</th>
				<th style="width: 100px">Jumlah (Rp)</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td align="center">1.</td>
				<td> Uang Muka : {{ $data->nama}}</td>
				<td align="right"> {{ number_format($invoice->total,0,',','.') }}</td>
			</tr>
			<tr>
				<td colspan="3">Terbilang : <b><i>{{terbilang($invoice->total)}}</i></b></td>
			</tr>
		</tbody>
	</table>
	@endif


	<br>
	<span style="margin-left:470px;"> Jakarta, </span><span style="margin-left: 130px;">{{date('Y')}}</span>
	
	<table style="font-size:smaller; margin-top:20px">
		<tbody>
			<tr>
				<td width="370px" style="padding-left: 20px;">KOPKAR TRENDY</td>
				<td  align="center">Yang Menerima,</td>
			</tr>
			<tr>
				<td></td>
				<td align="center">{{$data->alias}}</td>
			</tr>
			<tr>
				<td><br><br><br><br></td>
			</tr>
			<tr>
				<td style="font-weight: bold; padding-left:20px">
					<u>{{ $manager->nama }}</u>
				</td>
				<td>
					_______________________________
				</td>
			</tr><tr>
				<td style="padding-left:30px">
					{{ $manager->jabatan }}
				</td>
				<td>NIK. </td>
			</tr>
		</tbody>
	</table>
	 
</body>

</html>