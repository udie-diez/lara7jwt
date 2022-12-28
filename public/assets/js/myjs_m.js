function getFormattedDate(datex) {

    var date = new Date(datex);
  var year = date.getFullYear();

  var month = (1 + date.getMonth()).toString();
  month = month.length > 1 ? month : '0' + month;

  var day = date.getDate().toString();
  day = day.length > 1 ? day : '0' + day;
  
  return day + '/' + month + '/' + year;
}

function getFDate(datex) {

    var date = new Date(datex);
  var year = date.getFullYear();

  var month = (1 + date.getMonth()).toString();
  month = month.length > 1 ? month : '0' + month;

  var day = date.getDate().toString();
  day = day.length > 1 ? day : '0' + day;
  
  return year + '/' + month + '/' + day ;
}

function currdate(){
    var currentdate = new Date(); 
    var datetime = currentdate.getDate().toString() + 
                + (currentdate.getMonth()+1).toString()  + 
                + currentdate.getFullYear().toString();  
                // + currentdate.getHours() + ":"  
                // + currentdate.getMinutes() + ":" 
                // + currentdate.getSeconds();
    return datetime;
}

function formatRupiah(angka, prefix) {
    var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        var separator;
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

$(function () {
 
    $('.select').select2({
        minimumResultsForSearch: Infinity
    });
	$('.select-search').select2();
    
    $('.pickadate').pickadate({
		format: 'dd/mm/yyyy'
	});
      
})
 

