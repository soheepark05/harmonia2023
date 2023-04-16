<?php
// if (!isset($_SERVER['HTTPS'])) {
// 	header("Location:" . "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
// }
?>

<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
	<link rel="stylesheet" href="/dev/lib/fontawesome/css/all.css">
	<link rel="stylesheet" href="/dev/css/style.css">
	<link rel="stylesheet" href="/dev/lib/uikit-3.6.5/css/uikit.min.css" />
	<link rel="stylesheet" href="/dev/lib/semantic.min.css">

	<script src="/dev/js/jquery-3.3.1.js"></script>
	<script async src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script async src="/dev/lib/uikit-3.6.5/js/uikit.min.js"></script>
	<script async src="/dev/lib/uikit-3.6.5/js/uikit-icons.min.js"></script>
	<script src="/dev/lib/semantic.min.js"></script>

	<script src="/dev/js/script.js"></script>
	<link rel="icon" href="/dev/dev_img/chair_pavicon.svg">
	<title>헤르모니아 온라인 쇼룸</title>
</head>
<?php flush(); ?>
<body>



	<?php if (isset($_SESSION['user'])) : ?>
		<div class="uk-container header">
			<a onclick="alertWarningMsg('Ctrl+D 키를 눌러 즐겨찾기에 추가하실 수 있습니다.');"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNSIgdmlld0JveD0iMCAwIDE2IDE1Ij4KICAgIDxnIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+CiAgICAgICAgPHBhdGggc3Ryb2tlPSIjQ0NDIiBkPSJNLTE2LjUtMTYuNWg0OXY0OWgtNDl6Ii8+CiAgICAgICAgPHBhdGggZmlsbD0iI0U5QjQ1NyIgZmlsbC1ydWxlPSJub256ZXJvIiBkPSJNOCAwbDIuNSA0LjkzNCA1LjUuNzktNCAzLjg0OC45IDUuNDI4TDggMTIuNDM0IDMuMSAxNSA0IDkuNTcyIDAgNS43MjRsNS41LS43OXoiLz4KICAgIDwvZz4KPC9zdmc+Cg==" width="16" height="15" alt="즐겨찾기 이미지"> 즐겨찾기</a>
			<div class="loginMenu">
				<a href="/shop?idx=<?= $_SESSION['user']->idx ?>"><i class="fas fa-store-alt"></i> <?= $_SESSION['user']->nickname ?></a>
				<a href="/logout"><i class="fas fa-sign-out-alt"></i> 로그아웃</a>
			</div>
		</div>
		<hr>

		<header>
			<div class="uk-container">
				<div class="uk-margin stickyHeader">
					<!-- https://via.placeholder.com/135x40 -->
					<a href="/"><img src="/dev/dev_img/logo.png" alt="header_logo" style="width: 135px;"></a>

					<div class="uk-search uk-search-default searchHeader">
						<a class="uk-search-icon-flip searchBtn" onclick="searchBtn();" uk-search-icon></a>
						<input class="uk-search-input searchInput" type="search" onkeypress="searchEnter();" placeholder="상품명을 입력하세요">
					</div>

					<div class="headerMenu">
						<a href="/sell"><i class="fas fa-coins"></i> 판매하기</a> |
						<a href="/shop?idx=<?= $_SESSION['user']->idx ?>"><i class="fas fa-store-alt"></i> My상점</a> |
						<a onclick="alertWarningMsg('서비스 준비중입니다.\n감사합니다.');"><i class="fas fa-comment-dots"></i> 다채팅</a>
					</div>
				</div>

				<div class="subMenuBox">
					<div id="subMenu">
						<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAgCAYAAABgrToAAAAAAXNSR0IArs4c6QAAAExJREFUWAnt1sEJACAMA0DrJt1/SAVXyKfI9V8Il0+qu88afHtwthdNwOkNyUeAAAECvwuUNRNWbM2EgN4JECBAgEAoYM2EgMuaSQUv1d0EPE4sEMMAAAAASUVORK5CYII=" width="20" height="16" alt="메뉴 버튼 아이콘">
					</div>

					<div class="partnerBox">
						<a onclick="alertWarningMsg('서비스 준비중입니다.\n감사합니다.');">다장터 광고센터 &gt;</a>
					</div>
				</div>
			</div>
			<hr>
		</header>

	<?php else : ?>

		<div class="uk-container header">
			<a onclick="alertWarningMsg('Ctrl+D 키를 눌러 즐겨찾기에 추가하실 수 있습니다.');"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNSIgdmlld0JveD0iMCAwIDE2IDE1Ij4KICAgIDxnIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+CiAgICAgICAgPHBhdGggc3Ryb2tlPSIjQ0NDIiBkPSJNLTE2LjUtMTYuNWg0OXY0OWgtNDl6Ii8+CiAgICAgICAgPHBhdGggZmlsbD0iI0U5QjQ1NyIgZmlsbC1ydWxlPSJub256ZXJvIiBkPSJNOCAwbDIuNSA0LjkzNCA1LjUuNzktNCAzLjg0OC45IDUuNDI4TDggMTIuNDM0IDMuMSAxNSA0IDkuNTcyIDAgNS43MjRsNS41LS43OXoiLz4KICAgIDwvZz4KPC9zdmc+Cg==" width="16" height="15" alt="즐겨찾기 이미지"> 즐겨찾기</a>
			<a href="login">로그인/회원가입</a>
		</div>
		<hr>

		<header>
			<div class="uk-container">
				<div class="uk-margin stickyHeader">
					<!-- https://via.placeholder.com/135x40 -->
					<a href="/"><img src="/dev/dev_img/logo.png" alt="header_logo" style="width: 135px;"></a>

					<div class="uk-search uk-search-default searchHeader">
						<a class="uk-search-icon-flip searchBtn" onclick="searchBtn();" uk-search-icon></a>
						<input class="uk-search-input searchInput" type="search" onkeypress="searchEnter();" placeholder="상품명을 입력하세요">
					</div>

					<div class="headerMenu">
						<a onclick="alertWarningMsg('로그인 후 이용 가능합니다.');"><i class="fas fa-coins"></i> 판매하기</a> |
						<a onclick="alertWarningMsg('로그인 후 이용 가능합니다.');"><i class="fas fa-store-alt"></i> My상점</a> |
						<a onclick="alertWarningMsg('로그인 후 이용 가능합니다.');"><i class="fas fa-comment-dots"></i> 다채팅</a>
					</div>
				</div>

				<div class="subMenuBox">
					<div id="subMenu">
						<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAgCAYAAABgrToAAAAAAXNSR0IArs4c6QAAAExJREFUWAnt1sEJACAMA0DrJt1/SAVXyKfI9V8Il0+qu88afHtwthdNwOkNyUeAAAECvwuUNRNWbM2EgN4JECBAgEAoYM2EgMuaSQUv1d0EPE4sEMMAAAAASUVORK5CYII=" width="20" height="16" alt="메뉴 버튼 아이콘">
					</div>

					<div class="partnerBox">
						<a onclick="alertWarningMsg('로그인 후 이용 가능합니다.');">다장터 광고센터 &gt;</a>
					</div>
				</div>
			</div>
			<hr>
		</header>
	<?php endif; ?>