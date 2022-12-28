<?php

function IndoTgl($tanggal = '')
{
	if ($tanggal) {
		$tgl = substr($tanggal, 8, 2);
		$bln = substr($tanggal, 5, 2);
		$thn = substr($tanggal, 0, 4);
		$time = substr($tanggal, 11);
		if ($time) {
			$awal = "$tgl/$bln/$thn $time";
		} else {
			$awal = "$tgl/$bln/$thn";
		}
	} else {
		$awal = "";
	}
	return $awal;
}

function IndoTglx($tanggal = '')
{

	$tgl = substr($tanggal, 8, 2);
	$bln = substr($tanggal, 5, 2);
	$thn = substr($tanggal, 0, 4);
	if ($bln == '01') {
		$bln = "Januari";
	};
	if ($bln == '02') {
		$bln = "Februari";
	};
	if ($bln == '03') {
		$bln = "Maret";
	};
	if ($bln == '04') {
		$bln = "April";
	};
	if ($bln == '05') {
		$bln = "Mei";
	};
	if ($bln == '06') {
		$bln = "Juni";
	};
	if ($bln == '07') {
		$bln = "Juli";
	};
	if ($bln == '08') {
		$bln = "Agustus";
	};
	if ($bln == '09') {
		$bln = "September";
	};
	if ($bln == '10') {
		$bln = "Oktober";
	};
	if ($bln == '11') {
		$bln = "November";
	};
	if ($bln == '12') {
		$bln = "Desember";
	};
	$awal = "$tgl $bln $thn";
	return $awal;
}

function bulan($no)
{
	if ($no == 1) $namabulan = 'Januari';
	if ($no == 2) $namabulan = 'Februari';
	if ($no == 3) $namabulan = 'Maret';
	if ($no == 4) $namabulan = 'April';
	if ($no == 5) $namabulan = 'Mei';
	if ($no == 6) $namabulan = 'Juni';
	if ($no == 7) $namabulan = 'Juli';
	if ($no == 8) $namabulan = 'Agustus';
	if ($no == 9) $namabulan = 'September';
	if ($no == 10) $namabulan = 'Oktober';
	if ($no == 11) $namabulan = 'November';
	if ($no == 12) $namabulan = 'Desember';
	if ($no == 0) $namabulan = '';
	return $namabulan;
}

function namahari($tgl)
{
	// $d = date('d', strtotime($tgl));
	// $m = date('m', strtotime($tgl));
	// $y = date('Y', strtotime($tgl));
	$hari = ['Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu', 'Sun' => 'Minggu'];
	$namahari = date('D', strtotime($tgl));
	return $hari[$namahari];
}


function EngTgl($tanggal = '')
{
	$tgl = substr($tanggal, 0, 2);
	$bln = substr($tanggal, 3, 2);
	$thn = substr($tanggal, 6, 4);
	if ($tgl == '' || $tgl == '00') {
		$awal = null;
	} else {
		$awal = "$thn-$bln-$tgl";
	}
	return $awal;
}	#dd/mm/yyyy

function Rupiah($nilai, $sen = 0)
{
	if (is_numeric($nilai)) {

		if ($nilai > 0) {
			return number_format($nilai, $sen, ',', '.');
		}else if ($nilai < 0) {
				return '('.number_format($nilai * (-1), $sen, ',', '.').')';
		} else if ($nilai == 0) {
			return 0;
		} else {
			return '';
		}
	}else{

		return $nilai;
	}
}

function Rupiah_no($nilai, $sen = 0)
{
	if ($nilai > 0) {
		return number_format($nilai, $sen, '.', ',');
	} else {
		return '';
	}
}

function Angkapolos($nilai)
{
	return str_replace('.', '', $nilai);
}
