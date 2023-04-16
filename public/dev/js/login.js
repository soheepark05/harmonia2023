window.onload = function () {
    //uk-form-danger
    let email = document.querySelector("#userID");
    let password = document.querySelector("#userPW");
    let loginBtn = document.querySelector(".loginBtn");

    loginBtn.addEventListener("click", () => {
        if (email.value.trim() == "") {
            email.classList.add("uk-form-danger");
        } else {
            email.classList.remove("uk-form-danger")
        }

        if (password.value == "") {
            password.classList.add("uk-form-danger");
        }else {
            password.classList.remove("uk-form-danger")
        }

        if (email.value.trim() == "" || password.value.trim() == "") {
            swal("로그인 실패!", "입력되지 않은 항목이 있습니다.", "error");
            return;
        }

        let formData = new FormData($("#loginForm")[0]);

        $.ajax({
            type: "POST",
            url: "/login",
            dataType: 'json',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.result == true && response.code == 200) {
                    swal({ title: '로그인 성공', text: `${response.name}님 환영합니다~~!`, icon: 'success' }).then((value) => {
                        window.location.href = "/";
                    });
                } else if(response.code == -99) {
                    swal({ title: '로그인 실패', text: `아이디 또는 비밀번호가 올바르지 않습니다.`, icon: 'error' });
                } else {
                    swal({ title: '로그인 실패', text: `시스템에 문제가 발생하였습니다.\nErrorCode:${response.code}`, icon: 'error' });
                }
            },

            error: function (response) {
                console.log(response);
                swal({ title: '로그인 실패', text: `시스템에 문제가 발생하였습니다.\nErrorCode:500`, icon: 'error' });
            }
        });
    });
}