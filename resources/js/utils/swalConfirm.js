window.swalConfirm = function () {
    if (typeof swal == 'undefined') {
        console.warn('Warning - sweet_alert.min.js is not loaded.');
        return;
    }
    // Defaults
    var swalInit = swal.mixin({
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        cancelButtonClass: 'btn btn-light'
    });
    var _onConfirm = function () {
        swalInit.fire(
            'Deleted!',
            'Your file has been deleted.',
            'success'
        );
    }
    var _onCancel = function () {
        swalInit.fire(
            'Cancelled',
            'Your imaginary file is safe :)',
            'error'
        );
    }
    swalInit.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false
    }).then(function (result) {
        if (result.value) {
            _onConfirm();
        }
        else if (result.dismiss === swal.DismissReason.cancel) {
            _onCancel();
        }
    });
    return {
        onConfirm: function () {
            _onConfirm();
        },
        onCancel: function () {
            _onCancel();
        }
    }
}();
