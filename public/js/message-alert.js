// sweet alert
document.addEventListener('DOMContentLoaded', function () {
    if (typeof error !== 'undefined') {
        toastr.error(error, {timeOut: 2000})
    } else if (typeof success !== 'undefined') {
        toastr.success(success, {timeOut: 2000})
    }
});