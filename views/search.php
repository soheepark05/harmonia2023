<div class="uk-container">
    <div class="main">
        <div class="searchResultBox">
            <?php if (isset($searchData) != null) : ?>
                <h2>"<?= htmlentities($_GET['text']) ?>"의 검색결과 입니다.</h2>

                <div class="row">
                    <?php foreach ($searchData as $data) : ?>
                        <a href="view?itemID=<?=$data->id?>">
                            <div class="col">
                                <div class="card h-70">
                                    <img src="https://via.placeholder.com/150x150" class="card-img-top" alt="item_img">

                                    <script>
                                        $(".card img")[$(".card img").length - 1].src = JSON.parse('<?= json_decode(json_encode($data->item_img)) ?>')[0];
                                    </script>

                                    <div class="card_below">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= $data->title ?></h5>

                                            <div class="card-info">
                                                <small class="text-muted"><?= $data->price ?>원</small>
                                                <small class="text-muted"><?= $data->item_add_date ?></small>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <p><i class="fas fa-map-marker-alt"></i> <?= $data->location ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="more"><a href="#">[더보기]</a></div>
            <?php else : ?>
                <h2>"<?= $_GET['text'] ?>"의 검색결과가 없습니다.</h2>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="/dev/js/search.js"></script>