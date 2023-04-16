window.onload = function () {
    let loading = $('<div id="loading" class="loading"><img id="loading_img" alt="loading" src="/dev/dev_img/loader.gif"></div>').appendTo(document.body).hide();

    $(window).ajaxStart(function () {
        loading.show();
    }).ajaxStop(function () {
        loading.hide();
    });

    GPS_Location();
}


function GPS_Location() {
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
                        changeLocation(result[i].address_name);
                        infoDiv.innerHTML = result[i].address_name;
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

function changeLocation(location) {
    let myLocationP = document.querySelector(".changeMyLocation p");

    let myLocation1B = document.querySelector("#myLocation1B");
    let myLocation2B = document.querySelector("#myLocation2B");

    let setLocationBtn = document.querySelector(".setLocationBtn");

    myLocationP.addEventListener("click", function (e) {
        if (e.target == myLocation1B) {
            $(e.currentTarget).find("input")[0].checked = true;
        } else if (e.target == myLocation2B) {
            $(e.currentTarget).find("input")[1].checked = true;
        }
    });

    setLocationBtn.addEventListener("click", function () {
        let changeRadioValue = "";

        for (let i = 0; i < $(myLocationP).find("input").length; i++) {
            if ($(myLocationP).find("input")[i].checked == true) {
                changeRadioValue = $(myLocationP).find("input")[i].value;
            }
        };

        if (changeRadioValue == "" || !changeRadioValue) {
            swal("Warning", "변경할 주거래 장소를 선택해주세요.", "warning");
            return false;
        }

        let formData = new FormData();

        formData.append("indexLocation", changeRadioValue)
        formData.append("location", location);
        console.log(location);

        $.ajax({
            type: "POST",
            url: "/location",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.result == true) {
                    swal("Success", "위치 변경이 완료되었습니다.", "success").then((value) => {
                        window.close();
                    });
                } else {
                    swal("Error", "에러가 발생하였습니다.", "error");
                }
            },
            error: function (response) {
                swal("Error", "에러가 발생하였습니다.", "error");
            }
        });
    });
}