<?php

namespace Dajangter\Controller;

use Dajangter\DB;

class LoginController extends MasterController
{

	public function index()
	{
		if (isset($_SESSION['user'])) {
			$user = $_SESSION['user'];
		} else {
			$user = null;
		}

		$this->render("login", ["user" => $user]);
	}

	public function loginPS()
	{
		//code : -99 (password Error), -500 (Server Error), 200 (OK), -1 (id used), -22 (no field value)
		header("Content-Type: application/json");

		$email = $_POST['userID'];
		$password = $_POST['userPW'];

		$email = htmlentities($email);
		$password = htmlentities($password);

		if ($email === "" || $password === "") {
			echo json_encode(
				['result' => false, 'code' => -22],
				JSON_UNESCAPED_UNICODE
			);

			exit;
		}

		$sql = "SELECT * FROM `user` WHERE userID = ? AND userPW = PASSWORD(?)";

		$data = DB::fetch($sql, [$email, $password]);

		if ($data != null) {
			echo json_encode(
				['result' => true, 'code' => 200, 'name' => $data->nickname],
				JSON_UNESCAPED_UNICODE
			);

			$_SESSION['user'] = $data;

			exit;
		} else {
			echo json_encode(
				['result' => false, 'code' => -99],
				JSON_UNESCAPED_UNICODE
			);
			exit;
		}
	}

	public function logout()
	{
		//code : -99 (password Error), -500 (Server Error), 200 (OK), -1 (id used), -22 (no field value)
		if (isset($_SESSION['user'])) {
			unset($_SESSION['user']);
			header("location: /");
		} else {
			echo ("<h3>로그인이 되어있지 않습니다.</h3>");
		}
	}

	public function register()
	{
		$this->render("register");
	}

	public function registerPS()
	{
		//code : -99 (password Error), -500 (Server Error), 200 (OK), -1 (id used), -22 (no field value), -10 (used nickname), -11 (used userID)
		header("Content-Type: application/json");

		$datetime = date("Y-m-d H:i:s");

		$email = $_POST['email'];
		$password = $_POST['password'];
		$rePassword = $_POST['rePassword'];
		$nickname = $_POST['nickname'];
		$phone = $_POST['phone'];
		$location1 = $_POST['location1'];

		$email = htmlentities($email);
		$password = htmlentities($password);
		$rePassword = htmlentities($rePassword);
		$nickname = htmlentities($nickname);
		$phone = htmlentities($phone);
		$location1 = htmlentities($location1);

		if ($email === "" || $password === "" || $rePassword === "" || $nickname === "" || $phone === "") {
			echo json_encode(
				['result' => false, 'code' => -22],
				JSON_UNESCAPED_UNICODE
			);

			exit;
		}

		if ($password === $rePassword) {
			$nicknameUsedCheckSql = "SELECT `nickname`, `userID` FROM `user`";
			$data = DB::fetchAll($nicknameUsedCheckSql, []);

			foreach ($data as $value) {
				if ($value->nickname === $nickname) {
					echo json_encode(
						['result' => false, 'code' => -10, 'msg' => '이미 사용중인 상점명입니다.'],
						JSON_UNESCAPED_UNICODE
					);
					exit;
				}

				if ($value->userID === $email) {
					echo json_encode(
						['result' => false, 'code' => -11, 'msg' => '이미 가입되어있는 아이디입니다.'],
						JSON_UNESCAPED_UNICODE
					);
					exit;
				}
			}

			$sql = "INSERT INTO `user`(`userID`, `userPW`, `nickname`, `introduce`, `profile`, `phone`, `regDate`, `stopDate`, `statusCode`, `visit_cnt`) VALUES (?, PASSWORD(?), ?, ?, ?, ?, ?, ?, ?, ?)";

			$cnt = DB::query($sql, [$email, $password, $nickname, "", "", $phone, $datetime, "0000-00-00 00:00:00", 200, 0]);

			if ($cnt == 1) {
				$sql = "INSERT INTO `location`(`userID`, `location1`, `location1_cnt`) VALUES (?, ?, ?)";

				DB::query($sql, [$email, $location1, 1]);

				echo json_encode(
					['result' => true, 'code' => 200],
					JSON_UNESCAPED_UNICODE
				);

				exit;
			} else {
				echo json_encode(
					['result' => false, 'code' => -1, 'location' => [$email, $password, $nickname, $phone, $datetime, 200]],
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
