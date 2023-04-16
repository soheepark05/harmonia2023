<?php

namespace Dajangter\Controller;

use Dajangter\DB;

class SellController extends MasterController
{

	public function index()
	{
		if (!isset($_SESSION['user'])) {
			echo ("<script>
				alert('로그인 후 이용 가능합니다.');
				window.history.back();
			</script>");
		} else {
			$mod = 0;

			if (isset($_GET['item_ID'])) {
				//글 수정 모드
				$mod = $_GET['item_ID'];

				$sql = "SELECT * FROM `item` WHERE `id` = ?";
				$data = DB::fetch($sql, [$mod]);

				if (!$data) {
					echo "존재하지 않는 글입니다.";
					exit;
				}

				$sql = "SELECT * FROM `item` WHERE id = ? AND user_idx = ?";
				$result = DB::fetch($sql, [$mod, $_SESSION['user']->idx]);

				if ($result != true) {
					echo "잘못된 접근입니다.";
					exit;
				}
			}

			$this->render("sell", ["result" => $result]);
		}
	}

	//상품등록처리
	public function process()
	{
		$user_idx = $_SESSION['user']->idx;
		$item_img = $_FILES['item_img'];
		$title = $_POST['title'];
		$category = $_POST['category'];
		$price = $_POST['price'];
		$sell_content = $_POST['sell_content'];
		$location = $_POST['location'];
		$date = date("Y-m-d H:i:s");
		$mod = $_POST['mod'];

		if ($location == "" || $location == null) {
			echo json_encode(
				['result' => false, 'code' => -22],
				JSON_UNESCAPED_UNICODE
			);

			exit;
		}

		$title = htmlentities($title);
		$category = htmlentities($category);
		$price = htmlentities($price);
		$sell_content = htmlentities($sell_content);
		$location = htmlentities($location);
		$mod = htmlentities($mod);

		if($price == "") {
			$price = 0;
		}

		$item_img_src_array = [];

		for ($i = 0; $i < count($_FILES["item_img"]['name']); $i++) {
			// 고유 파일명, 파일확장자 분리
			$temp_name = uniqid('', TRUE);
			$file_path_info = pathinfo($item_img['name'][$i]);
			$file_extension = $file_path_info['extension'];

			// 파일 확장자 확인
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp');
			if (!in_array($file_extension, $allowed_ext)) {
				exit;
			}

			// 파일업로드
			$file_name = $temp_name . '.' . $file_extension;
			$src = "./upload/product/" . time() . "_" . $item_img['name'][$i];
			$file = $item_img["tmp_name"][$i];
			move_uploaded_file($file, $src);

			// 업로드된 이미지파일 정보를 가져옵니다
			$file = getimagesize($src);
			// 저용량 jpg등 파일을 생성합니다
			$ext = "";
			if ($file['mime'] == 'image/png') {
				$image = imagecreatefrompng($src);
				$ext = "png";
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
					$thumb_src = "./upload/product/" . time() . "_" . pathinfo($item_img['name'][$i], PATHINFO_FILENAME) . '_thumb.png';
					array_push($item_img_src_array, $thumb_src);
					imagepng($image, $thumb_src, 8);
					break;
				case 'jpeg':
					$thumb_src = "./upload/product/" . time() . "_" . pathinfo($item_img['name'][$i], PATHINFO_FILENAME) . '_thumb.jpg';
					array_push($item_img_src_array, $thumb_src);
					imagejpeg($image, $thumb_src, 80);
					break;
				case 'bmp':
					$thumb_src = "./upload/product/" . time() . "_" . pathinfo($item_img['name'][$i], PATHINFO_FILENAME) . '_thumb.bmp';
					array_push($item_img_src_array, $thumb_src);
					imagebmp($image, $thumb_src, 80);
					break;
				case 'webp':
					$thumb_src = "./upload/product/" . time() . "_" . pathinfo($item_img['name'][$i], PATHINFO_FILENAME) . '_thumb.webp';
					array_push($item_img_src_array, $thumb_src);
					imagewebp($image, $thumb_src, 80);
					break;
			}

			unlink($src);
		}

		$sql = "";
		$cnt = 0;

		if ($mod != 0) {
			$sql = "UPDATE `item` SET `title`= ?,`category`= ?,`price`= ?,`text`= ?,`location`= ? WHERE `id` = ?";

			$cnt = DB::query($sql, [$title, $category, $price, $sell_content, $location, $mod]);
		} else {
			$sql = "INSERT INTO `item`(`user_idx`, `item_img`, `title`, `category`, `price`, `text`, `location`, `item_add_date`, `steam`, `item_block`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			$cnt = DB::query($sql, [$user_idx, json_encode($item_img_src_array, JSON_UNESCAPED_SLASHES), $title, $category, $price, $sell_content, $location, $date, 0, 0]);
		}

		if ($cnt != 0) {
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
}
