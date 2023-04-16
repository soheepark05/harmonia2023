<?php

namespace Dajangter\Controller;

use Dajangter\DB;

class SearchController extends MasterController {

	public function index()
	{
        $text = $_GET['text'];

        if($text == "") {
            $searchData = null;
        }else {
            $sql = "SELECT * FROM `item` WHERE `title` LIKE '%" . $text . "%' OR `text` LIKE '%" . $text . "%' ORDER BY `id` DESC";

		    $searchData = DB::fetchAll($sql, []);
        }

      

        if($searchData != null) {
            $this->render("search", ["searchData" => $searchData]);
        }else {
            $this->render("search");
        }
	}
}