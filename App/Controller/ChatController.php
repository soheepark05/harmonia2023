<?php

namespace Dajangter\Controller;

use Dajangter\DB;

class ChatController extends MasterController {

	public function index()
	{	
		if(isset($_SESSION['user'])){
			$user = $_SESSION['user'];
			$this->render("chat", ["user" => $user]);
        }else {
			echo("<script>history.back();</script>");
		}
	}
}