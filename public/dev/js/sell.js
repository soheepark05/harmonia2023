window.addEventListener("load", () => {
    let loading = $('<div id="loading" class="loading"><img id="loading_img" alt="loading" src="/dev/dev_img/loader.gif"></div>').appendTo(document.body).hide();

    $(window).ajaxStart(function () {
        loading.show();
    }).ajaxStop(function () {
        loading.hide();
    });

    this.price_number = 0;
    this.categoryValue = "";
    this.locationValue = "";

    let sellImgFile = document.querySelector("#sellImgFile");
    let blankImg = document.querySelector(".blankImg");
    this.sel_files = [];

    sellBtn();

    blankImg.addEventListener("click", () => {
        sellImgFile.click();
    });

    function readInputFile(e) {
        let files = e.target.files;
        let fileArr = Array.prototype.slice.call(files);
        let index = 0;

        //파일 중복 거르기
        let result = fileArr.filter(e => {
            return sel_files.find(x => x.lastModified === e.lastModified);
        });

        if (result.length != 0) {
            swal("Error", "이미 첨부된 이미지 입니다.", "error");
            return false;
        }

        for (let i = 0; i < fileArr.length; i++) {
            if (!fileArr[i].type.match("image/.*")) {
                swal("Error", "이미지 확장자만 업로드 가능합니다.", "error");
                return false;
            };

            if (sel_files.length > 11) {
                swal("Error", "최대 12장까지 업로드 할 수 있습니다.", "error");
                return true;
            }

            if (files.length < 12) {
                sel_files.push(fileArr[i]);

                let reader = new FileReader();
                reader.onload = function (e) {
                    let image = new Image();
                    image.src = e.target.result;

                    let img =
                        `
                    <div class="thumbImgUnit">
                        <a class="uk-inline" href="${image.src}">
                            <img src="${image.src}" data-idx="${sel_files.length}" class="thumbImg" alt="img">
                        </a>
                    </div>
                    `;
                    $('.thumbImgBox').append(img);
                    index++;
                };
                reader.readAsDataURL(fileArr[i]);
            }
        }

        document.querySelector(".blankImg").innerHTML = `<i class="fas fa-camera"></i>${sel_files.length}/12`;
    }

    $('#sellImgFile').on('change', readInputFile);


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
                        locationValue = result[i].address_name;
                        break;
                    }
                }
            }
        }

    }

    function errorCallback(error) {
        console.error(error.message);
    }

    //무료나눔 버튼
    let freeBtn = document.querySelector(".freeBtn");
    this.isFreeBtn = false;

    freeBtn.addEventListener("click", function () {
        if (freeBtn.checked == true) {
            isFreeBtn = true;
            document.querySelector(".price").disabled = true;
            document.querySelector(".price").value = "";
            price_number = 0;
        } else {
            isFreeBtn = false;
            document.querySelector(".price").disabled = false;
        }
    });
});

function sellBtn() {
    let sellBtn = document.querySelector(".sellBtn");
    let title = document.querySelector(".title");
    let category = document.querySelectorAll("#category option");
    let price = document.querySelector(".price");
    let sell_content = document.querySelector("#sell_content");
    //price_number는 10,000 형식으로 숫자와 구분기호 , 가 적용되어 저장됩니다.

    price.addEventListener("input", function () {
        let regPrice = /(?<=￦ )[0-9]+/g;

        price.value = price.value.replace(/[^0-9]/g, "");
        price.value = price.value.replace(/(^0+)/, "");
        price.value = price.value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        if (price.value.trim() == "0") {
            price.value = "";
        }

        price_number = price.value;

        if (regPrice.test(price.value) == false) {
            if (price.value == "￦" || price.value == "") {
                price.value = "";
            } else {
                price.value = "￦ " + price.value;
            }
        }
    });

    sellBtn.addEventListener("click", function () {
        if (sel_files.length <= 0 && new URLSearchParams(location.search).get("item_ID") == null) {
            swal({
                title: "Error",
                text: "(필수) 상품 이미지를 넣어주세요.",
                icon: "error",
                button: "확인",
            });

            return false;
        }

        if (title.value.trim() == "") {
            swal({
                title: "Error",
                text: "제목을 입력해주세요.",
                icon: "error",
                button: "확인",
            });

            return false;
        }

        for (let i = 0; i < category.length; i++) {
            if (category[i].value == "0" || category[i].selected == false) {
                if (category[i].selected == true) {
                    swal({
                        title: "Error",
                        text: "카테고리를 선택해주세요.",
                        icon: "error",
                        button: "확인",
                    });

                    return false;
                }
            } else {
                categoryValue = category[i].value;
            }
        }

        if (price.value.trim() == "" && isFreeBtn == false) {
            swal({
                title: "Error",
                text: "판매 가격을 입력해주세요.",
                icon: "error",
                button: "확인",
            });

            return false;
        }

        if (title.value.length > 50) {
            swal({
                title: "Error",
                text: `판매 제목은 50자까지만 가능합니다.\n초과 글자수 : ${title.value.length - 50}자`,
                icon: "error",
                button: "확인",
            });

            return false;
        }

        if (sell_content.value.length > 1000) {
            swal({
                title: "Error",
                text: `상품 설명은 1000자까지만 가능합니다.\n초과 글자수 : ${sell_content.value.length - 1000}자`,
                icon: "error",
                button: "확인",
            });

            return false;
        }

        if (sell_content.value.trim() == "") {
            swal({
                title: "Warning",
                text: "상품 설명이 비어있습니다. 이대로 등록 하시겠습니까?\n상품 설명을 작성하실 경우,\n구매자들이 더 신뢰를 갖고 빠른 거래가 가능합니다.",
                icon: "warning",
                buttons: ["취소", "판매"],
                dangerMode: true,
            }).then((value) => {
                if (value) ajaxSellPS();
            });
        } else {
            ajaxSellPS();
        }
    });

    function ajaxSellPS() {
        let price = document.querySelector(".price");
        let regPrice = /(?<=￦ )[0-9]+/g;

        price.value = price.value.replace(/[^0-9]/g, "");
        price.value = price.value.replace(/(^0+)/, "");
        price.value = price.value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        if (price.value.trim() == "0") {
            price.value = "";
        }

        price_number = price.value;

        if (regPrice.test(price.value) == false) {
            if (price.value == "￦" || price.value == "") {
                price.value = "";
            } else {
                price.value = "￦ " + price.value;
            }
        }


        let formData = new FormData();

        for (let i = 0; i < sel_files.length; i++) {
            formData.append("item_img[]", sel_files[i]);
        }
        formData.append("title", title.value);
        formData.append("category", categoryValue);
        formData.append("price", price_number);
        formData.append("sell_content", sell_content.value);
        formData.append("location", locationValue);
        formData.append("mod", new URLSearchParams(location.search).get("item_ID"));

        let msg = "";

        if (new URLSearchParams(location.search).get("item_ID") != null) {
            msg = "수정";
        } else {
            msg = "등록";
        }

        $.ajax({
            type: "POST",
            url: "/sell/process",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.result == true) {
                    swal({
                        title: "Success",
                        text: `상품이 성공적으로 ${msg}되었습니다.`,
                        icon: "success",
                        button: "확인",
                    }).then((value) => {
                        location.href = "/";
                    });
                } else if (response.result == false && response.code == -22) {
                    swal({
                        title: "위치정보가 확인되지 않습니다.",
                        text: "위치정보(GPS)를 활성화 해주세요.",
                        icon: "error",
                        button: "확인",
                    });
                } else {
                    swal({
                        title: "Error",
                        text: "서버 오류가 발생하였습니다.",
                        icon: "error",
                        button: "확인",
                    });
                }
            },
            error: function (response) {
                swal({
                    title: "Error",
                    text: "서버 오류가 발생하였습니다.",
                    icon: "error",
                    button: "확인",
                });
            }
        });
    }
}