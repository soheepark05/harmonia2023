window.onload = function () {
    let loading = $('<div id="loading" class="loading"><img id="loading_img" alt="loading" src="/dev/dev_img/loader.gif"></div>').appendTo(document.body).hide();

    $(window).ajaxStart(function () {
        loading.show();
    }).ajaxStop(function () {
        loading.hide();
    });

    reliability_Bar();
    shopNavBar();
    QnaPS();
    changeLocation();
    shopNameChangeBtnPS();
    shopIntroduceChangeBtnPS();
    changeProfile();
}

function reliability_Bar() {
    let reliability_cnt = 0;

    let reliability = setInterval(() => {
        $(".reliability_value").css("width", `${reliability_cnt}%`);
        document.querySelector(".reliability_value").innerHTML = `${reliability_cnt}%`;

        if (reliability_cnt >= 50) {
            clearInterval(reliability);
            return;
        } else {
            reliability_cnt++;
        }
    }, 15);
}

function shopNavBar() {
    document.querySelector(".shopNavBar").addEventListener("click", function (e) {
        //(1 = 상품), (2 = 상점문의), (3 = 찜), (4 = 상점후기), (5 = 팔로잉), (6 = 팔로워)
        $(".shopNavItem").hide();
        $(".shopNavBar > div").removeClass("navSelected");
        $(e.toElement).addClass("navSelected");

        switch ($(e.toElement).data("idx")) {
            case 1:
                $(".shopProduct").fadeIn("fast");
                break;

            case 2:
                qna_load(null, 0);
                $(".shopQnA").fadeIn("fast");
                break;

            case 3:
                $(".shopSteam").fadeIn("fast");
                break;

            case 4:
                $(".shopReview").fadeIn("fast");
                break;

            case 5:
                $(".shopFollowing").fadeIn("fast");
                break;

            case 6:
                $(".shopFollower").fadeIn("fast");
                break;

            default:
                swal("Error", "장애가 발생하였습니다. F5키를 눌러 새로고침 후 재시도 부탁드립니다.", "error");
                break;
        }
    });
}

function QnaPS() {
    let qnaArea = document.querySelector(".qna_area_box > textarea");
    let areaTextCnt = document.querySelector(".qna_send_box > span");
    let qnaSendBtn = document.querySelector(".qna_send_btn");

    //상점문의 글자수 Check
    qnaArea.addEventListener("input", function () {
        if (qnaArea.value.length >= 100) {
            qnaArea.value = qnaArea.value.substring(0, 100);
            areaTextCnt.innerHTML = `${qnaArea.value.length} / 100`;
            return false;
        } else {
            areaTextCnt.innerHTML = `${qnaArea.value.length} / 100`;
        }
    });

    //상점문의 등록 버튼 클릭시 PS
    qnaSendBtn.addEventListener("click", function () {
        if (qnaArea.value.length === 0) {
            swal("Warning", "문의 할 내용을 입력해주세요.", "warning");
            return false;
        }

        let qnaValue = qnaArea.value;
        let idx = new URLSearchParams(location.search).get("idx");

        let formData = new FormData();
        formData.append("user_idx", idx);
        formData.append("qna_content", qnaValue);

        $.ajax({
            type: "POST",
            url: "/shop/qnaAdd",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.result == true) {
                    let profile = "";

                    if (response.data.profile == "") {
                        profile = "/upload/profile/cat.jpg";
                    } else {
                        profile = response.data.profile;
                    }

                    $(".qna_form").prepend(
                        `<div class="qna_unit">
                        <div class="qnaContentBox">
                            <img class="ui tiny circular image" src="${profile}">

                            <div class="qnaContent">
                                <p>${response.data.nickname}</p>
                                <p>${response.data.qna_content}</p>

                                <div class="qnaBtnBox">
                                    <a href="#"><i class="fas fa-comment-dots"></i> 댓글달기</a>
                                    <span> | </span>
                                    <a href="#"><i class="fas fa-exclamation-circle"></i> 신고하기</a>
                                </div>
                            </div>
                        </div>

                        <div class="qnaDate">
                            <span>${response.data.write_date}</span>
                        </div>
                    </div>`
                    );

                    document.querySelector("#qnaCnt").innerHTML = Number(document.querySelector("#qnaCnt").textContent) + 1;
                    qnaArea.value = "";
                    areaTextCnt.innerHTML = "0 / 100";

                } else {
                    swal("Error", "상점문의 등록에 실패하였습니다.", "error");
                }
            },
            error: function (response) {
                swal("Error", "상점문의 등록에 실패하였습니다.", "error");
            }
        });
    });

    //상점문의글 Ajax로 불러오기
    let pagination_idx = $(".qna_pagination a");
    $($(".qna_pagination li")[1]).addClass("active");

    pagination_idx.on("click", function (e) {
        $(".page_num").removeClass("active");
        $(e.currentTarget).parent("li").addClass("active");
        qna_load(e, 1);
    });
}

function qna_load(target, status) {
    location.href = "#shopNavQnA";
    let idx = new URLSearchParams(location.search).get("idx");
    let page_idx = 1;

    if (status == 1) {
        page_idx = $(target.currentTarget).data("idx");
    }

    let formData = new FormData();
    formData.append("idx", idx);
    formData.append("page", page_idx);

    $.ajax({
        type: "POST",
        url: "/shop/loadQna",
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.result == true) {
                $(".qna_form").html("");

                for (let i = 0; i < response.cnt; i++) {
                    let profile = "/upload/profile/cat.jpg";

                    if (response.data[i].profile != "") {
                        profile = response.data[i].profile;
                    }

                    $(".qna_form").append(
                        `<div class="qna_unit">
                        <div class="qnaContentBox">
                            <img class="ui tiny circular image" src="${profile}">

                            <div class="qnaContent">
                                <p>${response.data[i].nickname}</p>
                                <p>${response.data[i].qna_content}</p>

                                <div class="qnaBtnBox">
                                    <a href="#"><i class="fas fa-comment-dots"></i> 댓글달기</a>
                                    <span> | </span>
                                    <a href="#"><i class="fas fa-exclamation-circle"></i> 신고하기</a>
                                </div>
                            </div>
                        </div>

                        <div class="qnaDate">
                            <span>${response.data[i].write_date}</span>
                        </div>
                    </div>`
                    );
                }
            } else {
                if (response.code == -1) {

                } else {
                    swal("Error", "상점문의 로드에 실패하였습니다.", "error");
                }
            }
        },
        error: function (response) {
            swal("Error", "상점문의 로드에 실패하였습니다.", "error");
        }
    });
}

function steamPS() {

}

function changeLocation() {
    let changeBtn = document.querySelector(".changeLocation");

    if (changeBtn) {
        changeBtn.addEventListener("click", function () {
            window.open("/location");
        });
    }
}

function shopNameChangeBtnPS() {
    let shopNameChangeBtn = document.querySelector(".shopNameChangeBtn");

    if (shopNameChangeBtn) {
        shopNameChangeBtn.addEventListener("click", function () {
            $(".userNameBox").hide();

            let editDiv =
                `<div class="userNameEditBox">
                <input type="text" class="uk-input userNameEditInput">
                <button class="ui grey basic button shopNameChangeOKBtn" style="padding: 8px; margin-left: 5px;">수정</button>
                <button class="ui grey basic button shopNameChangeCancel" style="padding: 8px; margin-left: 5px;">취소</button>
            </div>`

            $(".userContentBox").prepend(editDiv);

            document.querySelector(".userNameEditInput").value = $(".userNameBox > h3").text();

            document.querySelector(".shopNameChangeCancel").addEventListener("click", function () {
                $(".userNameEditBox").remove();
                $(".userNameBox").fadeIn();
            });

            document.querySelector(".shopNameChangeOKBtn").addEventListener("click", function () {
                let formData = new FormData();
                formData.append("nickname", document.querySelector(".userNameEditInput").value)

                $.ajax({
                    type: "POST",
                    url: "/shop/shopNameChange",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.result == true) {
                            $(".userNameBox > h3").text(document.querySelector(".userNameEditInput").value);
                        } else if (response.msg != undefined) {
                            swal("Error", response.msg, "error");
                            return false;
                        } else {
                            swal("Error", "상점명 변경에 실패하였습니다.", "error");
                        }

                        $(".userNameEditBox").remove();
                        $(".userNameBox").fadeIn();
                    },
                    error: function (response) {
                        swal("Error", "상점명 변경에 실패하였습니다.", "error");
                        $(".userNameEditBox").remove();
                        $(".userNameBox").fadeIn();
                    }
                });
            });
        });
    }
}

function shopIntroduceChangeBtnPS() {
    let shopIntroduceChangeBtn = document.querySelector(".shopIntroduceChangeBtn");

    if (shopIntroduceChangeBtn) {
        shopIntroduceChangeBtn.addEventListener("click", function () {
            $(".shopIntroduce").hide();

            let editDiv =
                `<div class="shopIntroduceEditBox">
                <textarea class="uk-textarea shopIntroduceEditArea"></textarea>
                <button class="ui grey basic button shopIntroduceChangeOKBtn" style="padding: 8px; margin-left: 5px;">수정</button>
                <button class="ui grey basic button shopIntroduceChangeCancel" style="padding: 8px; margin-left: 5px;">취소</button>
            </div>`

            $(".userContentBox").append(editDiv);

            document.querySelector(".shopIntroduceEditArea").value = $(".shopIntroduce > textarea").text();

            document.querySelector(".shopIntroduceChangeCancel").addEventListener("click", function () {
                $(".shopIntroduceEditBox").remove();
                $(".shopIntroduce").fadeIn();
            });

            document.querySelector(".shopIntroduceChangeOKBtn").addEventListener("click", function () {
                let formData = new FormData();
                formData.append("introduce", document.querySelector(".shopIntroduceEditArea").value)

                $.ajax({
                    type: "POST",
                    url: "/shop/shopIntroduceChange",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.result == true) {
                            if (document.querySelector(".shopIntroduceEditArea").value.trim() == "") {
                                $(".shopIntroduce > textarea").text("작성된 소개글이 없습니다.");
                            } else {
                                $(".shopIntroduce > textarea").text(document.querySelector(".shopIntroduceEditArea").value);
                            }
                        } else {
                            swal("Error", "소개글 변경에 실패하였습니다.", "error");
                        }

                        $(".shopIntroduceEditBox").remove();
                        $(".shopIntroduce").fadeIn();
                    },
                    error: function () {
                        swal("Error", "소개글 변경에 실패하였습니다.", "error");
                        $(".shopIntroduceEditBox").remove();
                        $(".shopIntroduce").fadeIn();
                    }
                });
            });
        });
    }
}

function changeProfile() {
    let profileChangeForm =
        `
    <div class="profileChangeBox">
        <div class="profileChange">
            <h1>프로필 변경</h1>

            <div class="imgPreviewBox">
                <img class="ui small circular image prev_profile profileImg" src="./upload/profile/cat.jpg" alt="profile">
                <i class="fas fa-arrow-circle-right"></i>
                <div uk-tooltip="이미지 업로드" class="ui small circular image profileBlank changeProfile blankImg thumbImg" data-idx="0"><i class="far fa-image"></i></div>

                <input type="file" name="profileImgFile" id="profileImgFile" accept="image/*">
            </div>

            <div class="profileBtnBox">
                <button class="ui grey basic button profileChangeCancelBtn">취소</button>
                <button class="ui primary button profileChangeOKBtn">변경하기</button>
            </div>
        </div>
    </div>
    `

    let profileChangeBtn = document.querySelector(".profileChangeBtn");

    if (profileChangeBtn) {
        profileChangeBtn.addEventListener("click", function () {
            let sel_files = [];

            $(".shopOption").append(profileChangeForm);
            document.querySelector(".prev_profile").src = document.querySelector(".useProfile").src;
            $(".profileChangeBox").hide();
            $(".profileChangeBox").fadeIn();

            let changeProfile = document.querySelector(".changeProfile");
            let profileImgFile = document.querySelector("#profileImgFile");

            changeProfile.addEventListener("click", function () {
                profileImgFile.click();
            });

            let profileChangeCancelBtn = document.querySelector(".profileChangeCancelBtn");

            profileChangeCancelBtn.addEventListener("click", function () {
                const profileChangeCancel = new Promise((res, rej) => {
                    $(".profileChangeBox").fadeOut(() => {
                        res();
                    })
                });

                profileChangeCancel.then((value) => {
                    $(".profileChangeBox").remove();
                });
            });

            function readInputFile(e) {
                let files = e.target.files;
                sel_files = [];

                try {
                    if (!files[0].type.match("image/.*")) {
                        swal("Error", "이미지 확장자만 업로드 가능합니다.", "error");
                        return false;
                    };
                } catch (undefined) {
                    return;
                }

                if (files.length != 0) {
                    sel_files.push(files);
                }

                let reader = new FileReader();
                reader.onload = function (e) {
                    let image = new Image();
                    image.src = e.target.result;

                    let img = `<img uk-tooltip="이미지 업로드" class="ui small circular image profileImg changeProfileImg changeProfile" src="${image.src}" alt="profile">`;
                    $(".changeProfile").remove();
                    $('.imgPreviewBox').append(img);

                    document.querySelector(".changeProfileImg").addEventListener("click", function () {
                        profileImgFile.click();
                    });
                };

                reader.readAsDataURL(files[0]);
            }

            $('#profileImgFile').on('change', readInputFile);

            document.querySelector(".profileChangeOKBtn").addEventListener("click", function () {
                if ($(".blankImg").data("idx") == undefined) {
                    let formData = new FormData();
                    formData.append("profile_img", sel_files[0][0]);

                    $.ajax({
                        type: "POST",
                        url: "/shop/shopProfileChange",
                        data: formData,
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.result == true) {
                                swal("Success", "프로필 이미지 변경이 완료되었습니다.", "success").then((value) => {
                                    profileChangeCancelBtn.click();
                                    document.querySelector("#lightBoxProfile").href = response.src;
                                    document.querySelector(".profileImg").src = response.src;
                                });
                            } else {
                                swal("Error", "장애가 발생하였습니다. F5키를 눌러 새로고침 후 재시도 부탁드립니다.", "error");
                            }
                        },
                        error: function (response) {
                            swal("Error", "서버 에러가 발생하였습니다. 잠시후 재시도 부탁드립니다.", "error");
                        }
                    });
                } else {
                    swal("Error", "변경할 프로필 이미지를 넣어주세요.", "error");
                }
            });
        });
    }
}