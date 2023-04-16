<?php

namespace Dajangter\Controller;

use Dajangter\DB;

class ViewController extends MasterController
{

	public function index()
	{
		$data = ViewController::viewItem();

		$sql = "SELECT * FROM `user` WHERE `idx` = ?";
		$sellerInfo = DB::fetch($sql, [$data->user_idx]);

		$this->render("view", ["data" => $data, "sellerInfo" => $sellerInfo]);
	}

	public static function viewItem()
	{
		$sql = "SELECT * FROM `item` WHERE `id` = ?";
		return DB::fetch($sql, [htmlentities($_GET['itemID'])]);
	}

	public function deletePS()
	{
		$sql = "SELECT * FROM `item` WHERE `id` = ?";
		$target = DB::fetch($sql, [htmlentities($_POST['idx'])]);

		if ($target->user_idx == $_SESSION['user']->idx) {
			$sql = "DELETE FROM `item` WHERE `id` = ?";
			$cnt = DB::query($sql, [$_POST['idx']]);

			if ($cnt > 0) {
				echo json_encode(
					['result' => true, 'code' => 200],
					JSON_UNESCAPED_UNICODE
				);
				exit;
			} else {
				echo json_encode(
					['result' => false, 'code' => -99],
					JSON_UNESCAPED_UNICODE
				);
				exit;
			}
		} else {
			echo json_encode(
				['result' => false, 'code' => -1],
				JSON_UNESCAPED_UNICODE
			);
			exit;
		}
	}

	public function steam()
	{
		if (!isset($_SESSION['user'])) {
			echo json_encode(
				['result' => false, 'code' => -99],
				JSON_UNESCAPED_UNICODE
			);
			exit;
		}

		$sql = "SELECT * FROM `steam` WHERE `item_ID` = ? AND `user_idx` = ? AND `status` = ?";
		$data = DB::fetch($sql, [htmlentities($_POST['idx']), $_SESSION['user']->idx, 1]);

		if ($data != 0) {
			//찜 취소

			$sql = "UPDATE `steam` SET `status` = ? WHERE `user_idx` = ? AND `item_ID` = ?";
			$cnt = DB::query($sql, [0, $_SESSION['user']->idx, htmlentities($_POST['idx'])]);

			$sql = "UPDATE `item` SET `steam`= steam - 1 WHERE `id` = ?";
			$cnt = DB::query($sql, [htmlentities($_POST['idx'])]);

			if ($cnt != 0) {
				echo json_encode(
					['result' => true, 'code' => 0],
					JSON_UNESCAPED_UNICODE
				);
			} else {
				echo json_encode(
					['result' => false, 'code' => -33],
					JSON_UNESCAPED_UNICODE
				);
			}

			exit;
		} else {
			//찜 처리

			$sql = "SELECT * FROM `steam` WHERE `item_ID` = ? AND `user_idx` = ? AND `status` = ?";
			$data = DB::fetch($sql, [htmlentities($_POST['idx']), $_SESSION['user']->idx, 0]);

			if ($data != 0) {
				$sql = "UPDATE `steam` SET `status` = ? WHERE `user_idx` = ? AND `item_ID` = ?";
				$cnt = DB::query($sql, [1, $_SESSION['user']->idx, htmlentities($_POST['idx'])]);
			} else {
				$sql = "INSERT INTO `steam`(`user_idx`, `item_ID`, `status`) VALUES (?, ?, ?)";
				$cnt = DB::query($sql, [$_SESSION['user']->idx, htmlentities($_POST['idx']), 1]);
			}

			$sql = "UPDATE `item` SET `steam`= steam + 1 WHERE `id` = ?";
			$cnt = DB::query($sql, [htmlentities($_POST['idx'])]);

			if ($cnt != 0) {
				echo json_encode(
					['result' => true, 'code' => 200],
					JSON_UNESCAPED_UNICODE
				);
			} else {
				echo json_encode(
					['result' => false, 'code' => -22],
					JSON_UNESCAPED_UNICODE
				);
			}

			exit;
		}

		exit;
	}
}
