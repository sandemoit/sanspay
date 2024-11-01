// sweet alert
document.addEventListener('DOMContentLoaded', function () {
    if (typeof error !== 'undefined') {
        toastr.error(error, {timeOut: 3000})
    } else if (typeof success !== 'undefined') {
        toastr.success(success, {timeOut: 3000})
    }
});