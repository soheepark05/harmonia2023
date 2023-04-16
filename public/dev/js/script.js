window.onload = function () {

}

function searchBtn() {
    let searchInput = document.querySelector(".searchInput");

    location.href = "/search?text=" + searchInput.value;
}

function searchEnter() {
    let searchInput = document.querySelector(".searchInput");

    if (window.event.keyCode == 13) {
        location.href = "/search?text=" + searchInput.value;
    }
}

function alertErrorMsg(msg) {
    swal("Error", msg, "error");
}

function alertSuccessMsg(msg) {
    swal("Success", msg, "success");
}

function alertWarningMsg(msg) {
    swal("Warning", msg, "warning");
}