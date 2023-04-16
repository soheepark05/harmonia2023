<link rel="stylesheet" type="text/css" href="/dev/lib/semantic.min.css">

<?php
$totalCnt = $cntData;
$ppn = 20; //페이지당 글의 수
$totalPage = ceil($totalCnt / $ppn);

$cpp = 6; // 챕터당 페이지수
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$endPage = ceil($page / $cpp) * $cpp;
$startPage = $endPage - $cpp + 1;

$prev = true;
$next = true;

if ($endPage >= $totalPage) {
    $endPage = $totalPage;
    $next = false;
}

if ($startPage == 1) {
    $prev = false;
}

$userStatus = false;

if ($data->idx === $_SESSION['user']->idx) {
    $userStatus = true;
}

?>

<div class="uk-container">
    <div class="userInfoBox">
        <div class="userImgBox">
            <?php if ($data->profile != "") : ?>
                <div uk-lightbox>
                    <a id="lightBoxProfile" href="<?= $data->profile ?>">
                        <img class="ui small circular image useProfile profileImg" src="<?= $data->profile ?>" alt="profile">
                    </a>
                </div>
            <?php else : ?>
                <div uk-lightbox>
                    <a id="lightBoxProfile" href="/upload/profile/cat.jpg">
                        <img class="ui small circular image useProfile profileImg" src="/upload/profile/cat.jpg" alt="profile">
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($userStatus == true) : ?>
                <div class="shopOption">
                    <button class="ui grey basic button profileChangeBtn">프로필 변경</button>
                    <button class="ui primary button">내 상점 관리</button>
                </div>
            <?php endif; ?>
        </div>

        <div class="userContentBox">
            <div class="userNameBox">
                <h3><?= $data->nickname ?></h3>
                <?php if ($userStatus == true) : ?>
                    <button class="ui grey basic button shopNameChangeBtn" style="padding: 8px; margin-left: 5px;">상점명 수정</button>
                <?php endif; ?>
            </div>

            <div class="record">
                <p><i class="fas fa-store-alt"></i>상점오픈일 <?= date_diff(new DateTime(), new DateTime($data->regDate))->days ?>일 전</p>
                <p><i class="fas fa-sort-amount-up"></i>방문자 수 <?= $data->visit_cnt + 1 ?>명</p>
                <p><i class="fas fa-shopping-basket"></i>상품거래 291회</p>
                <p><i class="fas fa-grin-stars"></i>거래후기 123개</p>
            </div>

            <div class="reliabilityBox">
                <span>신뢰도 : </span>
                <div class="reliability_bar">
                    <div class="reliability_value">50%</div>
                </div>
            </div>

            <div class="myLocation">
                <p>나의 거래 장소 : <b><?= $data->location1 ?></b> / <b><?= $data->location2 ?></b></p>
                <?php if ($userStatus == true) : ?>
                    <button class="ui grey basic button changeLocation">거래 장소 변경</button>
                <?php endif; ?>
            </div>

            <div class="shopIntroduce">
                <?php if ($data->introduce == "") : ?>
                    <textarea name="shopIntroduceArea" id="shopIntroduceArea" readonly>작성된 소개글이 없습니다.</textarea>
                <?php else : ?>
                    <textarea name="shopIntroduceArea" id="shopIntroduceArea" readonly><?= $data->introduce ?></textarea>
                <?php endif; ?>

                <?php if ($userStatus == true) : ?>
                    <button class="ui grey basic button shopIntroduceChangeBtn">소개글 수정</button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="shopNavBar">
        <div id="shopNavProduct" class="navSelected" data-idx="1">상품</div>
        <div id="shopNavQnA" data-idx="2">상점문의</div>
        <div id="shopNavSteam" data-idx="3">찜</div>
        <div id="shopNavReview" data-idx="4">상점후기</div>
        <div id="shopNavFollowing" data-idx="5">팔로잉</div>
        <div id="shopNavFollower" data-idx="6">팔로워</div>
    </div>

    <div class="shopNavItem shopProduct">
        <h2>상품 <span><?= count($itemList) ?></span></h2>

        <div class="row">
            <?php foreach ($itemList as $itemUnit) : ?>
                <a href="view?itemID=<?= $itemUnit->id ?>">
                    <div class="col">
                        <div class="card" data-idx="<?= $itemUnit->id ?>">
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
                            <div class="card-footer">
                                <p><i class="fas fa-map-marker-alt"></i> <?= $itemUnit->location ?></p>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="shopNavItem shopQnA" id="shopQnA">
        <h2>상점문의 <span id="qnaCnt"><?= $cntData ?></span></h2>

        <div class="qna_area_box">
            <textarea name="" class="uk-textarea" id="qna_area"></textarea>

            <div class="qna_send_box">
                <span>0 / 100</span>

                <button class="uk-button uk-button-primary qna_send_btn"><i class="far fa-edit"></i> 등록</button>
            </div>
        </div>

        <div class="qna_form"></div>

        <nav aria-label="Page navigation" class="qna_form_pagi" style="margin: 30px 0;">
            <ul class="pagination justify-content-center qna_pagination">
                <li class="page-item <?= $prev ? "" : "disabled" ?>">
                    <a class="page-link" tabindex="-1">이전</a>
                </li>

                <?php for ($i = $startPage; $i <= $endPage; $i++) : ?>
                    <li class="page-item page_num">
                        <a class="page-link" data-idx="<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $next ? "" : "disabled" ?>">
                    <a class="page-link">다음</a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="shopNavItem shopSteam">
        <h2>찜 <span>2</span></h2>

        <div class="steamNav">
            <div class="steamSelection">
                <input type="checkbox" class="uk-checkbox">
                <button class="uk-button uk-button-default">선택삭제</button>
            </div>

            <div class="steamalign">
                <a href="#" class="steamNavSelected">최신순</a>
                <span> | </span>
                <a href="#">인기순</a>
                <span> | </span>
                <a href="#">저가순</a>
                <span> | </span>
                <a href="#">고가순</a>
            </div>
        </div>

        <div class="row steam_unit_box">
            <div class="col">
                <div class="card">
                    <img src="https://via.placeholder.com/150x150" class="card-img-top" alt="...">
                    <div class="card_view">
                        <div class="card-body">
                            <div class="card_control">
                                <h5 class="card-title">Card title</h5>
                                <input type="checkbox" class="uk-checkbox">
                            </div>

                            <div class="card-info">
                                <small class="text-muted">100,000원</small>
                                <small class="text-muted">1시간 전</small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p><i class="fas fa-map-marker-alt"></i> 경기도 용인시 처인구 포곡읍</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <img src="https://via.placeholder.com/150x150" class="card-img-top" alt="...">
                    <div class="card_view">
                        <div class="card-body">
                            <div class="card_control">
                                <h5 class="card-title">Card title</h5>
                                <input type="checkbox" class="uk-checkbox">
                            </div>

                            <div class="card-info">
                                <small class="text-muted">100,000원</small>
                                <small class="text-muted">1시간 전</small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p><i class="fas fa-map-marker-alt"></i> 경기도 용인시 처인구 포곡읍</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="shopNavItem shopReview"></div>

    <div class="shopNavItem shopFollowing"></div>

    <div class="shopNavItem shopFollower"></div>
</div>

<script src="/dev/lib/semantic.min.js"></script>
<script src="/dev/js/shop.js"></script>