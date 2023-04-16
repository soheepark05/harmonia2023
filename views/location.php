<div class="uk-container">
    <div class="changeMyLocation">
        <p>변경할 주거래 장소 : <input type="radio" name="myLocation" class="uk-radio" id="myLocation1" value="1"> <b id="myLocation1B"><?= $data->location1?></b> / <input type="radio" name="myLocation" class="uk-radio" id="myLocation2" value="2"> <b id="myLocation2B"><?= $data->location2?></b></p>
    </div>

    <div id="map" style="width:100%;height:400px;"></div>
    <p id="centerAddr">내위치 : <span></span></p>
    <button class="uk-button uk-button-primary uk-width-1-1 setLocationBtn" type="button">내 위치로 설정하기</button>
</div>


<script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=1e4f6947ffe0633761669d9089236567&libraries=services"></script>
<script src="/dev/js/location.js"></script>