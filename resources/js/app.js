import "./bootstrap";

// Event listener for swal
document.addEventListener("message", (event) => {
    const data = event.detail;
    Swal.fire({
        position: "center",
        icon: data.type,
        title: data.title,
        showConfirmButton: false,
        timer: 1500,
    });
});

// window.addEventListener("deleted", (event) => {
//     Swal.fire({
//         title: "Deleted!",
//         text: "Data has been deleted.",
//         icon: "success",
//     });
// });

$(document).ready(function () {
    toastr.options = {
        progressBar: true,
        timeOut: "2000",
        progressBar: true,
        positionClass: "toast-bottom-right",
        closeButton: true,
        preventDuplicates: true,
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
    };
    window.addEventListener("success", (event) => {
        toastr.success(event.detail.message);
    });
    window.addEventListener("warning", (event) => {
        toastr.warning(event.detail.message);
    });

    window.addEventListener("info", (event) => {
        toastr.info(event.detail.message);
    });

    window.addEventListener("error", (event) => {
        toastr.error(event.detail.message);
    });
});

window.addEventListener("hide-form", (event) => {
    $("#update-form-modal").modal("hide");
});
window.addEventListener("update-form", (event) => {
    $("#update-form-modal").modal("show");
});

document.addEventListener("DOMContentLoaded", () => {
    document
        .querySelectorAll('input[type-currency="IDR"]')
        .forEach((element) => {
            element.addEventListener("keyup", function (e) {
                let cursorPostion = this.selectionStart;
                let value = parseInt(this.value.replace(/[^,\d]/g, ""));
                let originalLenght = this.value.length;
                if (isNaN(value)) {
                    this.value = "";
                } else {
                    this.value = value.toLocaleString("id-ID", {
                        currency: "IDR",
                        style: "currency",
                        minimumFractionDigits: 0,
                    });
                    cursorPostion =
                        this.value.length - originalLenght + cursorPostion;
                    this.setSelectionRange(cursorPostion, cursorPostion);
                }
            });
        });
});
