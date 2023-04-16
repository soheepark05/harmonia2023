<div class="uk-container sellPage">
    <h2>다장터 상품등록</h2>

    <div class="sellBox">
        <div class="sellImgBox">
            <div class="blankImg thumbImg"><i class="fas fa-camera"></i>0/12</div>

            <div class="thumbImgBox" uk-lightbox="animation: slide"></div>

            <script>
                for (let i = 0; i < <?= $result ? $result->item_img : "i" ?>.length; i++) {
                    let img = <?= $result->item_img ?>[i];

                    let imgForm =
                        `
                    <div class="thumbImgUnit">
                        <a class="uk-inline" href="${img}">
                            <img src="${img}" data-idx="${i}" class="thumbImg" alt="img">
                        </a>
                    </div>
                    `;

                    $('.thumbImgBox').append(imgForm);
                }
            </script>

            <input type="file" name="sellImgFile[]" id="sellImgFile" accept="image/*" multiple>
        </div>

        <div class="sell_contents">
            <input type="text" class="uk-input title" placeholder="제목" value="<?= $result ? $result->title : "" ?>">

            <select name="category" id="category" class="uk-select">
                <option value="0">카테고리</option>
                <option value="1" <?= $result->category == 1 ? "selected" : "" ?>>디지털/가전</option>
                <option value="2" <?= $result->category == 2 ? "selected" : "" ?>>가구/인테리어</option>
                <option value="3" <?= $result->category == 3 ? "selected" : "" ?>>유아용품</option>
                <option value="4" <?= $result->category == 4 ? "selected" : "" ?>>생활용품</option>
                <option value="5" <?= $result->category == 5 ? "selected" : "" ?>>스포츠/레저</option>
                <option value="6" <?= $result->category == 6 ? "selected" : "" ?>>남성패션/잡화</option>
                <option value="7" <?= $result->category == 7 ? "selected" : "" ?>>여성패션/잡화</option>
                <option value="8" <?= $result->category == 8 ? "selected" : "" ?>>뷰티/미용</option>
                <option value="9" <?= $result->category == 9 ? "selected" : "" ?>>게임/취미</option>
                <option value="10" <?= $result->category == 10 ? "selected" : "" ?>>자동차</option>
                <option value="11" <?= $result->category == 11 ? "selected" : "" ?>>오토바이</option>
                <option value="12" <?= $result->category == 12 ? "selected" : "" ?>>도서/티켓/음반</option>
                <option value="13" <?= $result->category == 13 ? "selected" : "" ?>>반려동물용품</option>
                <option value="14" <?= $result->category == 14 ? "selected" : "" ?>>기타 중고물품</option>
                <option value="15" <?= $result->category == 15 ? "selected" : "" ?>>도와주세요</option>
                <option value="16" <?= $result->category == 16 ? "selected" : "" ?>>삽니다</option>
            </select>
            <div class="priceBox">
                <input type="text" class="uk-input uk-width-5-6 price" placeholder="￦ 0" value="<?= $result ? '￦ ' . $result->price : "" ?>">
                <div class="uk-width-1-6 freeBtnBox"><input type="checkbox" value="free" class="uk-checkbox freeBtn"><span>무료나눔</span></div>
            </div>
            <textarea name="sell_content" id="sell_content" style="width: 100%; height: 300px;" maxlength="1000" class="uk-textarea"><?= $result ? $result->text : "" ?></textarea>
            <p id="centerAddr">내위치 : <span></span></p>
            <div class="sellBtnBox">
                <button class="uk-button uk-button-primary sellBtn"><?=$result ? "수정하기" : "판매하기"?></button>
            </div>
        </div>
    </div>

    <div id="map" style="display: none;"></div>
</div>

<script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=1e4f6947ffe0633761669d9089236567&libraries=services"></script>
<script src="/dev/js/sell.js"></script>