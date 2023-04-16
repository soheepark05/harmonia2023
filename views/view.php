<?php
$category = "";

switch ($data->category) {
    case '1':
        $category = "디지털/가전";
        break;

    case '2':
        $category = "가구/인테리어";
        break;

    case '3':
        $category = "유아용품";
        break;

    case '4':
        $category = "생활용품";
        break;

    case '5':
        $category = "스포츠/레저";
        break;

    case '6':
        $category = "남성패션/잡화";
        break;

    case '7':
        $category = "여성패션/잡화";
        break;

    case '8':
        $category = "뷰티/미용";
        break;

    case '9':
        $category = "게임/취미";
        break;

    case '10':
        $category = "자동차";
        break;

    case '11':
        $category = "오토바이";
        break;

    case '12':
        $category = "도서/티켓/음반";
        break;

    case '13':
        $category = "반려동물용품";
        break;

    case '14':
        $category = "기타 중고물품";
        break;

    case '15':
        $category = "도와주세요";
        break;

    case '16':
        $category = "삽니다";
        break;
}

?>

<script src="/dev/js/view.js"></script>

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
</svg>


<div class="uk-container sellPage">
    <div class="alert alert-success align-items-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
            <use xlink:href="#check-circle-fill" />
        </svg>
        <div>
            (<?= $sellerInfo->nickname ?>)판매자는 더치트에 사기이력이 존재하지 않는 신뢰 가능한 판매자입니다!
        </div>
    </div>

    <script>
        let alert = document.querySelector(".alert");

        if(alert) {
            setTimeout(() => {
                $(alert).fadeOut("slow");
            }, 5000);
        }
    </script>

    <div class="sellBox">
        <div uk-slideshow="animation: slide">

            <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1">
                <div uk-lightbox>
                    <ul class="uk-slideshow-items viewImgBox"></ul>
                </div>

                <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slideshow-item="previous"></a>
                <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slideshow-item="next"></a>

            </div>

            <ul class="uk-slideshow-nav uk-dotnav uk-flex-center uk-margin"></ul>
        </div>

        <div class="sellerInfo">
            <div class="sellerProfile">
                <?php if ($sellerInfo->profile != "") : ?>
                    <a id="lightBoxProfile" href="/shop?idx=<?=$sellerInfo->idx?>">
                        <img class="ui small circular image viewProfile" src="<?= $sellerInfo->profile ?>" alt="profile">
                    </a>
                <?php else : ?>
                    <a id="lightBoxProfile" href="/shop?idx=<?=$sellerInfo->idx?>">
                        <img class="ui small circular image viewProfile" src="/upload/profile/cat.jpg" alt="profile">
                    </a>
                <?php endif; ?>
            </div>

            <div class="sellerInfoBox">
                <h3><a href="/shop?idx=<?=$sellerInfo->idx?>"><?= $sellerInfo->nickname ?></a></h3>
                <a href="tel:<?= $sellerInfo->phone ?>"><i class="fas fa-phone-square-alt"></i> 전화걸기</a>
                <a href="sms:<?= $sellerInfo->phone ?>&body='<?= $data->title ?>' 상품에 대하여 문의하고 싶습니다!"><i class="fas fa-sms"></i></i> 문자메시지 보내기</a>
            </div>
        </div>

        <div class="sell_contents">
            <input type="text" class="uk-input title" value="<?= $data->title ?>" readonly>
            <input type="text" class="uk-input" id="category" value="<?= $category ?>" readonly>

            <div class="priceBox">
                <?php if ($data->price == "0") : ?>
                    <input type="text" class="uk-input uk-width-1-2 price" value="무료나눔" readonly>
                <?php else : ?>
                    <input type="text" class="uk-input uk-width-1-2 price" value="￦ <?= $data->price ?>" readonly>
                <?php endif; ?>

                <input type="text" class="uk-input uk-width-1-2 item_add_date" value="<?= $data->item_add_date ?>" readonly>
            </div>

            <textarea name="sell_content" id="sell_content" style="width: 100%; height: 300px;" maxlength="1000" class="uk-textarea" readonly>
<?= $data->text ?>
            </textarea>

            <p id="centerAddr">위치 : <span><?= $data->location ?></span></p>

            <div class="uk-alert-danger" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p>판매자가 다장터 이외의 곳에서 거래를 요구하는 경우 거부하시고 즉시 사기 거래 신고센터 (010-9447-0496)에 신고하시기 바랍니다.</p>
            </div>

            <div class="sellBtnBox">
                <button class="btn btn-warning" onclick="history.back();">이전</button>
                <button class="btn btn-danger viewSteamBtn" data-idx="<?= $data->id ?>">♥ <?=$data->steam?></button>

                <?php if ($_SESSION['user']->idx == $data->user_idx) : ?>
                    <button class="btn btn-secondary deleteBtn" data-idx="<?= $data->id ?>">삭제</button>
                    <button class="btn btn-success updateBtn" onclick="location.href='/sell?item_ID=<?= $data->id ?>';">수정</button>
                <?php endif; ?>

                <button class="btn btn-primary">채팅하기</button>
            </div>
        </div>
    </div>
</div>

<script>
    let imgArr = JSON.parse('<?= $data->item_img ?>');
    let imgLength = imgArr.length;

    for (let i = 0; i < imgLength; i++) {
        $(".viewImgBox").append(
            `<li>
            <a class="uk-button uk-button-default" href="${imgArr[i]}"><img src="${imgArr[i]}" alt="img" uk-cover></a>
         </li>`);
    }
</script>