<?php

namespace Dajangter\Controller;

use Dajangter\DB;

class ShopController extends MasterController
{

	public function index()
	{
		$sql = "SELECT u.idx, u.userID, u.userPW, u.nickname, u.introduce, u.profile, u.phone, u.regDate, u.stopDate, u.statusCode, u.visit_cnt, l.location1, l.location2, l.location1_cnt, l.location2_cnt FROM `user` u, `location` l WHERE u.userID = l.userID AND u.idx = ?";
		$data = DB::fetch($sql, [$_GET['idx']]);

		$visitSql = "UPDATE `user` SET `visit_cnt`= ? WHERE idx = ?";
		DB::query($visitSql, [$data->visit_cnt + 1, $data->idx]);

		$qnaCntSql = "SELECT COUNT(u.idx) AS cnt FROM `user` u, `qna` q, `user` q_u WHERE u.idx = q.user_idx AND q.qna_user = q_u.userID AND u.idx = ?";
		$cntData = DB::fetch($qnaCntSql, [$_GET['idx']]);

		$itemList = ShopController::itemList();

		$this->render("shop", ['data' => $data, 'cntData' => $cntData->cnt, 'itemList' => $itemList]);
	}

	public function shopNameChange()
	{
		$selectSql = "SELECT `nickname` FROM `user`";
		$data = DB::fetchAll($selectSql, []);

		foreach ($data as $value) {
			if ($value->nickname === htmlentities($_POST['nickname']) && $_SESSION['user']->nickname != htmlentities($_POST['nickname'])) {
				echo json_encode(
					['result' => false, 'code' => -1, 'msg' => '이미 사용중인 상점명입니다.'],
					JSON_UNESCAPED_UNICODE
				);
				exit;
			}
		}

		$sql = "UPDATE `user` SET `nickname`= ? WHERE `userID` = ?";
		$cnt = DB::query($sql, [htmlentities($_POST['nickname']), $_SESSION['user']->userID]);

		if ($cnt == 1) {
			ShopController::session_user_reload();

			echo json_encode(
				['result' => true, 'code' => 200],
				JSON_UNESCAPED_UNICODE
			);

			exit;
		} else {
			echo json_encode(
				['result' => false, 'code' => -1],
				JSON_UNESCAPED_UNICODE
			);
			exit;
		}
	}

	public function shopIntroduceChange()
	{
		$sql = "UPDATE `user` SET `introduce`= ? WHERE `userID` = ?";
		$cnt = DB::query($sql, [htmlentities($_POST['introduce']), $_SESSION['user']->userID]);

		if ($cnt == 1) {
			echo json_encode(
				['result' => true, 'code' => 200],
				JSON_UNESCAPED_UNICODE
			);

			exit;
		} else {
			echo json_encode(
				['result' => false, 'code' => -1],
				JSON_UNESCAPED_UNICODE
			);
			exit;
		}
	}

	public function shopProfileChange()
	{
		$profile = $_FILES['profile_img'];

		// 고유 파일명, 파일확장자 분리
		$temp_name = uniqid('', TRUE);
		$file_path_info = pathinfo($profile['name']);
		$file_extension = $file_path_info['extension'];

		// 파일 확장자 확인
		$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp');
		if (!in_array($file_extension, $allowed_ext)) {
			exit;
		}

		// 파일업로드
		$file_name = $temp_name . '.' . $file_extension;
		$src = "./upload/profile/" . time() . "_" . $profile['name'];
		$file = $profile["tmp_name"];
		move_uploaded_file($file, $src);

		// 업로드된 이미지파일 정보를 가져옵니다
		$file = getimagesize($src);
		// 저용량 jpg등 파일을 생성합니다
		$ext = "";
		if ($file['mime'] == 'image/png') {
			$image = imagecreatefrompng($src);
			$ext = "png";
		} else if ($file['mime'] == 'image/gif') {
			$image = imagecreatefromgif($src);
			$ext = "gif";
		} else if ($file['mime'] == 'image/jpeg') {
			$image = imagecreatefromjpeg($src);
			$ext = "jpeg";
		} else if ($file['mime'] == 'image/bmp') {
			$image = imagecreatefrombmp($src);
			$ext = "bmp";
		} else if ($file['mime'] == 'image/webp') {
			$image = imagecreatefromwebp($src);
			$ext = "webp";
		} else {
			$image = imagecreatefromjpeg($src);
			$ext = "jpeg";
		}

		// 파일 압축 및 업로드
		$thumb_src = "";

		switch ($ext) {
			case 'png':
				$thumb_src = "./upload/profile/" . time() . "_" . pathinfo($profile['name'], PATHINFO_FILENAME) . '_thumb.png';
				imagepng($image, $thumb_src, 8);
				break;
			case 'jpeg':
				$thumb_src = "./upload/profile/" . time() . "_" . pathinfo($profile['name'], PATHINFO_FILENAME) . '_thumb.jpg';
				imagejpeg($image, $thumb_src, 80);
				break;
			case 'bmp':
				$thumb_src = "./upload/profile/" . time() . "_" . pathinfo($profile['name'], PATHINFO_FILENAME) . '_thumb.bmp';
				imagebmp($image, $thumb_src, 80);
				break;
			case 'webp':
				$thumb_src = "./upload/profile/" . time() . "_" . pathinfo($profile['name'], PATHINFO_FILENAME) . '_thumb.webp';
				imagewebp($image, $thumb_src, 80);
				break;
		}

		$sql = "UPDATE `user` SET `profile`= ? WHERE `userID` = ?";

		$cnt = 0;

		if ($ext != "gif") {
			unlink($src);
			$cnt = DB::query($sql, [$thumb_src, $_SESSION['user']->userID]);
		} else {
			$cnt = DB::query($sql, [$src, $_SESSION['user']->userID]);
		}

		if ($cnt == 1) {
			ShopController::session_user_reload();

			if ($ext != "gif") {
				echo json_encode(
					['result' => true, 'src' => $thumb_src],
					JSON_UNESCAPED_UNICODE
				);
			} else {
				echo json_encode(
					['result' => true, 'src' => $src],
					JSON_UNESCAPED_UNICODE
				);
			}

			exit;
		} else {
			echo json_encode(
				['result' => false],
				JSON_UNESCAPED_UNICODE
			);
			exit;
		}
	}

	public function qnaAdd()
	{
		$user_idx = $_POST['user_idx'];
		$qna_user = $_SESSION['user']->userID;
		$qna_user_nickname = $_SESSION['user']->nickname;
		$qna_user_profile = $_SESSION['user']->profile;
		$qna_content = $_POST['qna_content'];

		$data = ["nickname" => $qna_user_nickname, "profile" => $qna_user_profile, "qna_content" => $qna_content, "write_date" => date("Y-m-d H:i:s")];

		$user_idx = htmlentities($user_idx);
		$qna_content = htmlentities($qna_content);


		$sql = "INSERT INTO `qna`(`user_idx`, `qna_user`, `qna_content`, `write_date`) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
		$cnt = DB::query($sql, [$user_idx, $qna_user, $qna_content]);

		if ($cnt == 1) {
			echo json_encode(
				['result' => true, 'code' => 200, 'data' => $data],
				JSON_UNESCAPED_UNICODE
			);

			exit;
		} else {
			echo json_encode(
				['result' => false, 'code' => -1],
				JSON_UNESCAPED_UNICODE
			);
			exit;
		}
	}

	public function loadQna()
	{
		$page = isset($_POST['page']) ? $_POST['page'] : 1;

		$qnaSql = "SELECT u.userID, q.qna_user, q.qna_content, q.write_date, q_u.nickname, q_u.profile FROM `user` u, `qna` q, `user` q_u WHERE u.idx = q.user_idx AND q.qna_user = q_u.userID AND u.idx = ? ORDER BY q.idx DESC Limit " . ($page - 1) * 20 . ", 20";
		$qnaData = DB::fetchAll($qnaSql, [$_POST['idx']]);

		if ($qnaData != null) {
			echo json_encode(
				['result' => true, 'code' => 200, 'data' => $qnaData, 'cnt' => COUNT($qnaData), 'page' => $_POST['page']],
				JSON_UNESCAPED_UNICODE
			);

			exit;
		} else {
			echo json_encode(
				['result' => false, 'code' => -1, 'test' => $_POST['idx']],
				JSON_UNESCAPED_UNICODE
			);
			exit;
		}
	}

	public static function itemList()
	{
		$sql = "SELECT * FROM `item` WHERE `user_idx` = ? ORDER BY `id` DESC";
		return DB::fetchAll($sql, [$_GET['idx']]);
	}

	// Util
	public static function session_user_reload()
	{
		$sql = "SELECT * FROM `user` WHERE userID = ?";

		$user = DB::fetch($sql, [$_SESSION['user']->userID]);

		unset($_SESSION['user']);

		$user = $_SESSION['user'] = $user;
	}
}
