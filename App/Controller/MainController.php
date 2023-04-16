<?php

namespace Dajangter\Controller;

use Dajangter\DB;

class MainController extends MasterController {

	public function index()
	{	
		if(isset($_SESSION['user'])){
			$user = $_SESSION['user'];
        }else {
			$user = null;
		}

		$itemList = MainController::itemList();
        $this->render("main", ["user" => $user, "itemList" => $itemList]);
	}

	public static function itemList()
	{
		$sql = "SELECT * FROM `item` ORDER BY rand() limit 20";
		return DB::fetchAll($sql, []);
	}

	public static function myVillage()
	{
		$myVillage = $_POST["myVillage"];
		$myVillage = htmlentities($myVillage);

		$myVillageArr = explode(' ', $myVillage);

		$sql = "SELECT * FROM `item` WHERE `location` LIKE '%" . $myVillageArr[1] . "%' ORDER BY rand() limit 5";
		$data = DB::fetchAll($sql, []);

		if($data !=null) {
			echo json_encode(
				['result' => true, 'code' => 200, 'dataArr' => $data],
				JSON_UNESCAPED_UNICODE
			);

			exit;
		}else {
			echo json_encode(
				['result' => false, 'code' => -1],
				JSON_UNESCAPED_UNICODE
			);
			exit;
		}
	}
}