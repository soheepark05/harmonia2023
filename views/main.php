<div class="uk-container">
    <!-- visual -->
    <div class="uk-position-relative uk-visible-toggle uk-light visual" tabindex="-1" uk-slideshow="autoplay: true">
        <ul class="uk-slideshow-items">
            <li>
                <img src="/dev/dev_img/visual_img1.jpg" alt="visual_img1" uk-cover>
            </li>
            <li>
                <img src="/dev/dev_img/visual_img2.jpg" alt="visual_img2" uk-cover>
            </li>
            <li>
                <img src="/dev/dev_img/visual_img3.jpg" alt="visual_img3" uk-cover>
            </li>
        </ul>

        <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slideshow-item="previous"></a>
        <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slideshow-item="next"></a>
    </div>

    <div class="main">
        <div class="myVillage">
            <h2>우리 동네 상품</h2>

            <div class="row row-cols-1 row-cols-md-5 g-4 myVillageBox"></div>

            <div class="more"><a href="#">[더보기]</a></div>
        </div>

        <div class="todayRecommend">
            <h2>오늘의 추천 상품</h2>

            <div class="row row-cols-1 row-cols-md-5 g-4">
                <?php foreach ($itemList as $itemUnit) : ?>
                    <a href="view?itemID=<?= $itemUnit->id ?>">
                        <div class="col">
                            <div class="card h-70" data-idx="<?= $itemUnit->id ?>">
                                <img src="https://via.placeholder.com/150x150" class="card-img-top" alt="item" data-idx="<?= $itemUnit->id ?>">
                                <script>
                                    $(".card img")[$(".card img").length - 1].src = JSON.parse('<?= json_decode(json_encode($itemUnit->item_img)) ?>')[0];
                                </script>
                                <div class="card-body">
                                    <h5 class="card-title"><?= $itemUnit->title ?></h5>

                                    <div class="card-info">
                                        <?php if ($itemUnit->price == "0") : ?>
                                            <small class="text-muted">무료나눔</small>
                                        <?php else : ?>
                                            <small class="text-muted"><?= $itemUnit->price ?>원</small>
                                        <?php endif; ?>
                                        <small class="text-muted"><?= $itemUnit->item_add_date ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="more"><a href="#">[더보기]</a></div>
        </div>
    </div>
</div>

<div id="map" style="display: none;"></div>

<script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=1e4f6947ffe0633761669d9089236567&libraries=services"></script>
<script src="/dev/js/main.js"></script>