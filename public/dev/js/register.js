window.onload = function () {
    let loading = $('<div id="loading" class="loading"><img id="loading_img" alt="loading" src="/dev/dev_img/loader.gif"></div>').appendTo(document.body).hide();

    $(window).ajaxStart(function () {
        loading.show();
    }).ajaxStop(function () {
        loading.hide();
    });


    let location1 = "";

    //uk-form-danger
    let email = document.querySelector("#email");
    let password = document.querySelector("#password");
    let rePassword = document.querySelector("#rePassword");
    let nickname = document.querySelector("#nickname");
    let phone = document.querySelector("#phone");
    let registerBtn = document.querySelector("#registerBtn");

    let regEmail = /[0-9a-zA-Z]@([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
    let regPw = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/;
    let regPhone = /^\d{3}-\d{3,4}-\d{4}$/;

    this.addEventListener("input", () => {
        if (regEmail.test(email.value) == true) {
            email.classList.remove("uk-form-danger");
            email.classList.add("uk-form-success");
        } else {
            email.classList.remove("uk-form-success");
            email.classList.add("uk-form-danger");
        }

        if (regPw.test(password.value) == true) {
            if (password.value === rePassword.value) {
                password.classList.remove("uk-form-danger");
                password.classList.add("uk-form-success");
                rePassword.classList.remove("uk-form-danger");
                rePassword.classList.add("uk-form-success");
            } else {
                password.classList.remove("uk-form-success");
                password.classList.add("uk-form-danger");
                rePassword.classList.remove("uk-form-success");
                rePassword.classList.add("uk-form-danger");
            }
        } else {
            password.classList.remove("uk-form-success");
            password.classList.add("uk-form-danger");
            rePassword.classList.remove("uk-form-success");
            rePassword.classList.add("uk-form-danger");
        }

        if (nickname.value.trim() !== "" && nickname.value.length <= 12) {
            nickname.classList.remove("uk-form-danger");
            nickname.classList.add("uk-form-success");
        } else {
            nickname.classList.remove("uk-form-success");
            nickname.classList.add("uk-form-danger");
        }

        phone.value = phone.value.replace(/[^0-9]/g, "").replace(/(^02|^0505|^1[0-9]{3}|^0[0-9]{2})([0-9]+)?([0-9]{4})$/, "$1-$2-$3").replace("--", "-");

        if (regPhone.test(phone.value) == true) {
            phone.classList.remove("uk-form-danger");
            phone.classList.add("uk-form-success");
        } else {
            phone.classList.remove("uk-form-success");
            phone.classList.add("uk-form-danger");
        }
    });

    registerBtn.addEventListener("click", () => {
        if (email.value.trim() == "") {
            email.classList.add("uk-form-danger");
        }

        if (password.value == "") {
            password.classList.add("uk-form-danger");
        }

        if (rePassword.value == "") {
            rePassword.classList.add("uk-form-danger");
        }

        if (nickname.value.trim() == "") {
            nickname.classList.add("uk-form-danger");
        }

        if (phone.value.trim() == "") {
            phone.classList.add("uk-form-danger");
        }

        if (email.value.trim() == "" || password.value.trim() == "" || rePassword.value.trim() == "" || nickname.value.trim() == "" || phone.value.trim() == "") {
            swal("회원가입 실패!", "입력되지 않은 항목이 있습니다.", "error");
            return;
        }

        if (password.value !== rePassword.value) {
            swal("회원가입 실패!", "비밀번호와 비밀번호 확인이 다릅니다.", "error");
            return;
        }

        if (regEmail.test(email.value) == false) {
            swal("회원가입 실패!", "아이디(이메일 주소)를 확인해주세요.", "error");
            return;
        }

        if (nickname.value.length > 12) {
            swal("회원가입 실패!", "닉네임은 최대 12자까지 가능합니다.", "error");
            return;
        }

        if (!regPw.test(password.value)) {
            swal("회원가입 실패!", "영문(대소문자 구분), 숫자, 특수문자가\n전부 포함되어있어야 하며\n8자리 이상이여야 합니다.", "error");
            return;
        }

        if (!regPhone.test(phone.value)) {
            swal("회원가입 실패!", "휴대전화 번호가 올바르지 않습니다.", "error");
            return;
        }

        $("#registerForm").append('<input type="text" readonly style="display:none;" name="location1" id="formlocation1Send">');
        $("#formlocation1Send").val(location1);

        let formData = new FormData($("#registerForm")[0]);

        $.ajax({
            type: "POST",
            url: "/register",
            dataType: 'json',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.result == true && response.code == 200) {
                    swal({ title: '회원가입 성공', text: `${nickname.value}님의 회원가입을 축하합니다.`, icon: 'success' }).then((value) => {
                        window.location.href = "/";
                    });
                } else if (response.code == -99) {
                    swal({ title: '회원가입 실패', text: `비밀번호와 비밀번호 확인이 다릅니다.`, icon: 'error' });
                } else if (response.result == false && response.code == -1) {
                    console.log(response);
                    swal({ title: '회원가입 실패', text: `${nickname.value}님이 사용하려는 아이디가 이미 존재합니다.`, icon: 'error' });

                    email.classList.remove("uk-form-success");
                    email.classList.add("uk-form-danger");
                } else if (response.code == -22) {
                    swal({ title: '회원가입 실패', text: `서버로 누락된 값이 전송되었습니다. 새로고침 후 재시도 해주세요.`, icon: 'error' });
                } else if (response.code == -10) {
                    swal({ title: '회원가입 실패', text: response.msg, icon: 'error' });
                    nickname.classList.remove("uk-form-success");
                    nickname.classList.add("uk-form-danger");
                } else if (response.code == -11) {
                    swal({ title: '회원가입 실패', text: response.msg, icon: 'error' });
                    email.classList.remove("uk-form-success");
                    email.classList.add("uk-form-danger");
                } else {
                    swal({ title: '회원가입 실패', text: `시스템에 문제가 발생하였습니다.\nErrorCode:${response.code}`, icon: 'error' }).then((value) => {
                        window.location.href = "/register";
                    });
                }
            },

            error: function (response) {
                console.log(response);
                swal({ title: '회원가입 실패', text: `시스템에 문제가 발생하였습니다.\nErrorCode:500`, icon: 'error' }).then((value) => {
                    // window.location.href = "/register";
                });
            }
        });
    });

    //현재 주소 관련
    if (!!navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    } else {
        swal("Error", "GPS 위치정보를 활성화 후 이용 가능합니다.", "error");
    }

    function successCallback(position) {
        //lat = 위도, lng = 경도
        let lat = position.coords.latitude;
        let lng = position.coords.longitude;

        let mapContainer = document.getElementById('map'), // 지도를 표시할 div 
            mapOption = {
                center: new kakao.maps.LatLng(lat, lng), // 지도의 중심좌표
                draggable: false,
                level: 5 // 지도의 확대 레벨
            };

        // 지도를 생성합니다    
        let map = new kakao.maps.Map(mapContainer, mapOption);

        // 주소-좌표 변환 객체를 생성합니다
        let geocoder = new kakao.maps.services.Geocoder();

        let marker = new kakao.maps.Marker(), // 클릭한 위치를 표시할 마커입니다
            infowindow = new kakao.maps.InfoWindow({
                zindex: 1
            }); // 클릭한 위치에 대한 주소를 표시할 인포윈도우입니다

        // 현재 지도 중심좌표로 주소를 검색해서 지도 좌측 상단에 표시합니다
        searchAddrFromCoords(map.getCenter(), displayCenterInfo);

        // 지도를 클릭했을 때 클릭 위치 좌표에 대한 주소정보를 표시하도록 이벤트를 등록합니다
        kakao.maps.event.addListener(map, 'click', function (mouseEvent) {
            searchDetailAddrFromCoords(mouseEvent.latLng, function (result, status) {
                if (status === kakao.maps.services.Status.OK) {
                    let detailAddr = !!result[0].road_address ? '<div>도로명주소 : ' + result[0].road_address.address_name + '</div>' : '';
                    detailAddr += '<div>지번 주소 : ' + result[0].address.address_name + '</div>';

                    let content = '<div class="bAddr">' +
                        '<span class="title">법정동 주소정보</span>' +
                        detailAddr +
                        '</div>';

                    // 마커를 클릭한 위치에 표시합니다 
                    marker.setPosition(mouseEvent.latLng);
                    marker.setMap(map);

                    // 인포윈도우에 클릭한 위치에 대한 법정동 상세 주소정보를 표시합니다
                    infowindow.setContent(content);
                    infowindow.open(map, marker);
                }
            });
        });

        // 중심 좌표나 확대 수준이 변경됐을 때 지도 중심 좌표에 대한 주소 정보를 표시하도록 이벤트를 등록합니다
        kakao.maps.event.addListener(map, 'idle', function () {
            searchAddrFromCoords(map.getCenter(), displayCenterInfo);
        });

        function searchAddrFromCoords(coords, callback) {
            // 좌표로 행정동 주소 정보를 요청합니다
            geocoder.coord2RegionCode(coords.getLng(), coords.getLat(), callback);
        }

        function searchDetailAddrFromCoords(coords, callback) {
            // 좌표로 법정동 상세 주소 정보를 요청합니다
            geocoder.coord2Address(coords.getLng(), coords.getLat(), callback);
        }

        // 지도 좌측상단에 지도 중심좌표에 대한 주소정보를 표출하는 함수입니다
        function displayCenterInfo(result, status) {
            if (status === kakao.maps.services.Status.OK) {
                let infoDiv = document.querySelector('#centerAddr span');

                for (let i = 0; i < result.length; i++) {
                    // 행정동의 region_type 값은 'H' 이므로
                    if (result[i].region_type === 'H') {
                        infoDiv.innerHTML = result[i].address_name;
                        location1 = result[i].address_name;
                        break;
                    }
                }
            }
        }

    }

    function errorCallback(error) {
        console.error(error.message);
    }
}