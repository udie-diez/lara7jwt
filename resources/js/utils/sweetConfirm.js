window.sweetConfirm = function (onConfirmFn, onCancelFn) {
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
            // 'Deleted!',
            'The action is being process',
            'success'
        );
    }
    var _onCancel = function () {
        swalInit.fire(
            // 'Cancelled',
            'The action was cancelled',
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
        // confirmButtonClass: 'btn btn-success',
        // cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false
    }).then(function (result) {
        if (result.value) {
            _onConfirm();
            onConfirmFn;
        }
        else if (result.dismiss === swal.DismissReason.cancel) {
            _onCancel();
            onCancelFn;
        }
    });
}();
