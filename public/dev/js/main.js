window.onload = function () {
    let loading = $('<div id="loading" class="loading"><img id="loading_img" alt="loading" src="/dev/dev_img/loader.gif"></div>').appendTo(document.body).hide();

    $(window).ajaxStart(function () {
        loading.show();
    }).ajaxStop(function () {
        loading.hide();
    });

    this.my_village_name = "";
    map();
}

function map() {
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
                for (let i = 0; i < result.length; i++) {
                    // 행정동의 region_type 값은 'H' 이므로
                    if (result[i].region_type === 'H') {
                        my_village_name = result[i].address_name;
                        locationValue = result[i].address_name;
                        my_village();
                        break;
                    }
                }
            }
        }

    }

    function errorCallback(error) {
        notGPSForm();
        console.error(error.message);
    }
}

function my_village() {
    let formData = new FormData();
    formData.append("myVillage", my_village_name);

    $.ajax({
        type: "POST",
        url: "/",
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.result == true) {
                if (response.dataArr.length <= 0 || response.dataArr.length == undefined) {
                    myVillageDataNull();
                } else {
                    $(".myVillageBox").html("");

                    for (let i = 0; i < response.dataArr.length; i++) {
                        let data = response.dataArr[i];

                        if(data.price == "0") {
                            data.price = "무료나눔";
                        }else {
                            data.price = data.price + "원";
                        }

                        let myVillageForm =
                            `<a href="view?itemID=${data.id}">
                                <div class="col">
                                    <div class="card h-70">
                                        <img src="${JSON.parse(response.dataArr[i].item_img)[0]}" class="card-img-top" alt="item_img">
                                        <div class="card-body">
                                            <h5 class="card-title">${data.title}</h5>

                                            <div class="card-info">
                                                <small class="text-muted">${data.price}</small>
                                                <small class="text-muted">${data.item_add_date}</small>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <p><i class="fas fa-map-marker-alt"></i> ${data.location}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>`;
                        $(".myVillageBox").append(myVillageForm);
                    }
                }
            } else {
                if(response.code == -1) {
                    myVillageDataNull();
                    return false;
                }

                myVillageServerError();

                swal({
                    title: "Error",
                    text: "우리 동네 상품을 불러오는데 실패하였습니다.",
                    icon: "error",
                    button: "확인",
                });
            }
        },
        error: function (response) {
            myVillageServerError();

            swal({
                title: "Error",
                text: "우리 동네 상품을 불러오는데 실패하였습니다.",
                icon: "error",
                button: "확인",
            });
        }
    });
}

function notGPSForm() {
    let notGps =
        `<div class="notGps">
            <i class="fas fa-map-marked-alt"></i>위치정보를 활성화 해주세요.
        </div>`;

    $(".myVillageBox").html(notGps);
}

function myVillageDataNull() {
    let dataNullForm =
        `<div class="notGps">
            <i class="fas fa-search-location"></i>이런... 우리동네에 상품이 없네요..
        </div>`;

    $(".myVillageBox").html(dataNullForm);
}

function myVillageServerError() {
    let myVillageServerError =
        `<div class="notGps">
            <i class="fas fa-exclamation-triangle"></i>우리 동네 상품을 불러오는데 실패하였습니다.
            <button class="uk-button uk-button-primary myVillageReloadBtn"><i class="fas fa-sync-alt" style="color:#fff;"></i> 재시도</button>
        </div>`;

    $(".myVillageBox").html(myVillageServerError);

    $(".myVillageReloadBtn").click(function() {
        my_village();
    });
}