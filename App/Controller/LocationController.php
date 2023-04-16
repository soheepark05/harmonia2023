<?php

namespace Dajangter\Controller;

use Dajangter\DB;

class LocationController extends MasterController
{

	public function index()
	{
		if (!isset($_SESSION['user'])) {
			echo(
			"<script>
				alert('로그인 후 이용 가능합니다.');
				window.close();
			</script>"
			);
			exit;
		}else {
			$sql = "SELECT * FROM `location` WHERE `userID` = ?";

			$data = DB::fetch($sql, [$_SESSION['user']->userID]);
			
			if ($data != null) {
				$this->render("location", ['data' => $data]);
				exit;
			} else {
				$this->render("location");
				exit;
			}
		}
	}

	public function setLocation()
	{
		$indexLocation = $_POST['indexLocation'];
		$location = $_POST['location'];

		$indexLocation = htmlentities($indexLocation);
		$location = htmlentities($location);


		if ($indexLocation != null || $location != null) {
			$sql = "";

			if($indexLocation == "1") {
				$sql = "UPDATE `location` SET `location1`= ? WHERE `userID` = ?";
			}else if($indexLocation == "2") {
				$sql = "UPDATE `location` SET `location2`= ? WHERE `userID` = ?";
				
			}

			$cnt = DB::query($sql, [$location, $_SESSION['user']->userID]);

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
		} else {
			echo json_encode(
				['result' => false, 'code' => -99],
				JSON_UNESCAPED_UNICODE
			);

			exit;
		}
	}
}
