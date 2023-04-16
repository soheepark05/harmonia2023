window.addEventListener("load", function () {
    init();
});

function init() {
    deleteItem();
    viewSteamBtn();
}

function deleteItem() {
    $(".deleteBtn").on("click", function (e) {
        swal({
            title: "Warning",
            text: "정말로 상품을 삭제하시겠습니까?\n실행 후 되돌릴 수 없습니다.",
            icon: "warning",
            buttons: ["취소", "삭제"],
            dangerMode: true,
        }).then(function (status) {
            if (status) {
                let formData = new FormData();
                formData.append("idx", $(e.target).data("idx"));

                $.ajax({
                    type: "POST",
                    url: "/view/delete",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.result == true) {
                            swal("Success", "상품이 정상적으로 삭제되었습니다.", "success").then(function () {
                                history.back();
                            });
                            return;
                        } else if (response.result == false && response.code == -1) {
                            console.log(response.d);
                            swal("Error", "상품등록자와 로그인자가 달라 삭제할 수 없습니다.", "error");
                            return;
                        } else {
                            swal("Error", "장애가 발생하여 상품을 삭제할 수 없습니다.", "error");
                            return;
                        }
                    },
                    error: function (e) {
                        swal("Error", "장애가 발생하여 상품을 삭제할 수 없습니다.", "error");
                        return;
                    }
                });
            }
        });
    });
}

function viewSteamBtn() {
    $(".viewSteamBtn").on("click", function (e) {
        let formData = new FormData();
        formData.append("idx", $(e.target).data("idx"));

        $.ajax({
            type: "POST",
            url: "/view/steam",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                let plusNum =  Number($(e.target).text().replace(/[^0-9]/g, '')) + 1;
                let minusNum =  Number($(e.target).text().replace(/[^0-9]/g, '')) - 1;

                if (response.result == true && response.code == 200) {
                    swal("Success", "상품 찜이 완료되었습니다.", "success");
                    $(e.target).html("♥ " + plusNum);
                    return;
                } else if (response.result == true && response.code == 0) {
                    swal("Success", "상품 찜이 취소되었습니다.", "success");
                    $(e.target).html("♥ " + minusNum);
                } else if (response.result == false && response.code == -22) {
                    swal("Error", "상품 찜 처리에 실패하였습니다.", "error");
                } else if (response.result == false && response.code == -33) {
                    swal("Error", "상품 찜 취소 처리에 실패하였습니다.", "error");
                } else if (response.result == false && response.code == -99) {
                    swal("Error", "로그인 후 찜 가능합니다.", "error");
                } else {
                    swal("Error", "장애가 발생하여 찜을 할 수 없습니다.", "error");
                }
            },
            error: function (response) {
                console.log(response);
                swal("Error", "장애가 발생하여 찜을 할 수 없습니다.", "error");
                return;
            }
        });
    });
}