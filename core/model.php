<?php

class Model
{

	const SALT = 'dsflFWR9u2xQa';

	public function getRef()
	{
		$ref = split('//', $_SERVER['HTTP_REFERER']);
		return $ref;
	}

	public function getSimpleCrumbs($target)
	{
		$crumbs = array($target['name'] => $target['value']);
		return $crumbs;
	}

	public function getNews($count = NULL)
	{
		if ($count == NULL) {
			$count = 5;
		} else {
			$count = (int) $count;
		}

		$q = mysql_query("SELECT * FROM articles WHERE archived=0 ORDER BY datetime DESC LIMIT ".$count) or die(mysql_error());
		while ( $buf = mysql_fetch_assoc($q)) {
			$newsForSide[] = $buf;
		}

		return $newsForSide;
	}

	public function getData($pageName)
	{
		switch ($pageName) {
			case 'catalog':
				$pageDataModel['text'] = "Catalog Model";
				$pageDataModel['title'] = "Каталог продукции";
				break;

			case 'reviews':
				$nowTime = time();
				$comms = array();
				$q = mysql_query("SELECT * FROM comments WHERE target_type='reviews' AND banned=0 ORDER BY pub_time DESC");
				while ( $buf = mysql_fetch_assoc($q)) {
					$buf['good_pub_time'] = Controller::getGoodDate($buf['pub_time']);
					$comms[] = $buf;
				}
				$pageDataModel['comms'] = $comms;
				$pageDataModel['title'] = "Ваши отзывы";
				$pageDataModel['crumb'] = "Отзывы";
				break;

			case 'sales':
				$nowTime = time();
				$dataSales = array();
				$q = mysql_query("SELECT * FROM sales");
				while ( $buf = mysql_fetch_assoc($q)) {
					$endTime = strtotime($buf['end_time']);
					if ($endTime > $nowTime) {
						$dataSales[] = $buf;
					}
				}
				$pageDataModel['sales'] = $dataSales;
				break;

			case 'prods':
				$prod_new = array();
				$prod_popular = array();
				$prod_all = array();
				$q = mysql_query("SELECT * FROM prod_items WHERE labels like '%new%' AND archived = 0 ORDER BY added_time DESC ") or die(mysql_error());
				while ( $buf = mysql_fetch_assoc($q)) {
					$prod_new[] = $buf;
				}
				$q = mysql_query("SELECT * FROM prod_items WHERE labels like '%popular%' AND archived = 0 ORDER BY added_time DESC") or die(mysql_error());
				while ( $buf = mysql_fetch_assoc($q)) {
					$prod_popular[] = $buf;
				}
				$q = mysql_query("SELECT * FROM prod_items WHERE archived = 0 ORDER BY added_time DESC ") or die(mysql_error());
				while ( $buf = mysql_fetch_assoc($q)) {
					$prod_all[] = $buf;
				}
				$prod_new = Model::createProdUrl($prod_new);
				$prod_new = Model::createInStock($prod_new);
				$prod_all = Model::createProdUrl($prod_all);
				$prod_all = Model::createInStock($prod_all);
				$prod_popular = Model::createProdUrl($prod_popular);
				$prod_popular = Model::createInStock($prod_popular);
				$pageDataModel['prodItems']['all'] = $prod_all;
				$pageDataModel['prodItems']['new'] = $prod_new;
				$pageDataModel['prodItems']['popular'] = $prod_popular;
				$pageDataModel['text'] = "Catalog Model";
				$pageDataModel['title'] = "Каталог продукции.";

				$prod_cats = array();
				$q = mysql_query("SELECT * FROM prod_cat ORDER BY position") or die(mysql_error());
				while ( $buf = mysql_fetch_assoc($q)) {
					$prod_cats[$buf['id']] = $buf;
				}
				unset($q);
				$prod_cats = Model::createCatUrl($prod_cats);
				$prod_cats = Model::createCatTree($prod_cats);
				$pageDataModel['prodCats'] = $prod_cats;
				$newsForSidebar = Self::getNews('5');
				$pageDataModel['news'] = $newsForSidebar;
				break;

			default:
				$pageDataModel['text'] = "Any page text";
				break;
		}
		return $pageDataModel;
	}

	public function getUserCallback($id)
	{
		$q = mysql_query("SELECT name, phone FROM users WHERE id=$id LIMIT 1") or die(mysql_error());
		$ds = mysql_fetch_assoc($q);
		return $ds;
	}

	public function getUser($uid)
	{
		$q = mysql_query("SELECT * FROM users WHERE id='$uid'") or die(mysql_error()) ;
		$countq = mysql_num_rows($q);
		if ($countq>0) {
			$userData['profile'] = mysql_fetch_assoc($q);
			if (isset($userData['profile']['bday'])) {
				$ts = $userData['profile']['bday'];
				$userData['profile']['bday_raw'] = $ts;
				$userData['profile']['bday'] = Controller::getGoodDate($ts, 'compact');
			}
			if (isset($userData['profile']['addresses'])) {
				$ts = explode("_",$userData['profile']['addresses']);
				$userData['profile']['addresses'] = $ts;
			}
			return $userData;
		} else return false;
	}

	public function getUsers($users = NULL)
	{
		if ($users != NULL ) {
				$usersStr = implode(", ", $users);
				$select = mysql_query("SELECT * FROM users WHERE id IN ($usersStr)")or die(mysql_error());
			$ds = [];
			while ($buf = mysql_fetch_assoc($select))
			{
				$ds[$buf['id']] = $buf;
			}
				unset($select);
				unset($buf);
		} else {
			$select = mysql_query("SELECT * FROM users WHERE banned = '0' ORDER BY id")or die(mysql_error());
			while ($buf = mysql_fetch_assoc($select)) {
						$ds[$buf['id']] = $buf;
			}
			unset($select);
		}
		return $ds;
	}

	function isHasRight($arr)
	{
		if ($_SESSION['user']['isadmin']==1) {
			extract($arr);
			$userInfo = Self::getUser($uid);
			$userInfo = $userInfo['profile'];
			if ($userInfo['isadmin']==1) {
				$rights = $userInfo['admin_rights'];
				$rights = explode(',',$rights);
				if ( in_array($right,$rights) || in_array('all',$rights) ) {
					return true;
				} else {
				return false;
			}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	function sessionHasRight($type)
	{
		if ($_SESSION['user']['isadmin']==1) {
			if (is_array($_SESSION['user']['admin_rights'])) {
				$rights = $_SESSION['user']['admin_rights'];
			} else
				$rights = explode(',',$_SESSION['user']['admin_rights']);
			if (in_array($type, $rights)) {
				// echo "1";
				return true;
			} else {
				// echo "0";
				return false;
			}
		} else
		{
			// echo "0";
			return false;
		}
	}

	public function getComments($target_type=NULL, $target_id=NULL, $status=NULL)
	{
		if (isset($target_id)&&isset($target_type)) {
		if ($target_type=="reviews") {
			$select = mysql_query("SELECT * FROM comments WHERE target_type = '$target_type' AND banned = 0 ORDER BY pub_time DESC")or die(mysql_error());
		} else
		if ($target_type=="product") {
			$select = mysql_query("SELECT * FROM comments WHERE target_type = '$target_type' AND target_id = '$target_id' AND banned = 0 ORDER BY pub_time DESC")or die(mysql_error());
		} else
		if ($target_type=="all") {
			$select = mysql_query("SELECT * FROM comments ORDER BY pub_time DESC")or die(mysql_error());
		}
		while ($buf = mysql_fetch_assoc($select)) {
					$ds[] = $buf;
		}
		unset($select);

		if (count($ds)==0) {
			return false;
		}
		} else
		if (isset($status)) {
			if ($target_type=="reviews") {
				$select = mysql_query("SELECT * FROM comments WHERE target_type = '$target_type' AND $status = 1 ORDER BY pub_time DESC")or die(mysql_error());
			} else
			if ($target_type=="product") {
				$select = mysql_query("SELECT * FROM comments WHERE target_type = '$target_type' AND $status = 1 ORDER BY pub_time DESC")or die(mysql_error());
			} else
			if ($target_type=="all") {
				$select = mysql_query("SELECT * FROM comments WHERE $status = 1 ORDER BY pub_time DESC")or die(mysql_error());
			}
			while ($buf = mysql_fetch_assoc($select)) {
						$buf['status'] = $status;
						$ds[] = $buf;
			}
			unset($select);

			if (count($ds)==0) {
				return false;
			}
		}
		return $ds;
	}

	public function get_login($login, $type='login')
	{

		switch ($type) {
			case 'login':
				$select = mysql_query("SELECT * FROM users WHERE login = '$login'")or die(mysql_error());
				$ds = mysql_fetch_assoc($select);
				break;

			case 'email':
				$select = mysql_query("SELECT * FROM users WHERE email = '$login'")or die(mysql_error());
				$ds = mysql_fetch_assoc($select);
				$ds['login'] = $ds['email'];
				break;

			default:
				# code...
				break;
		}



		return $ds;
	}

	public function get_MainMenu($pageId)
	{
		$select = mysql_query("SELECT * FROM main_menu WHERE display = 1 ORDER BY position")or die(mysql_error());
		while ($buf = mysql_fetch_assoc($select)) {
			$ds[] = $buf;
		}

		return $ds;
	}


	function createProdPict($prodArr)
	{

		if (!$prodArr) {
			return false;
		}

		$arts = array();
		foreach ($prodArr as $key => $value) {
			$arts[] = "'".$key."'";
		}

		$arts = implode(', ', $arts);

		$q = mysql_query("SELECT * FROM prod_items WHERE art IN ($arts) ") or die(mysql_error());
		while ($buf = mysql_fetch_assoc($q)) {
			$ds[$buf['art']] = $buf;
		}

		foreach ($prodArr as $key => &$value) {
			$pict = explode(',',$ds[$key]['images']);
			$pict = $pict[0];
			$value['poster'] = $pict;
		}

		return $prodArr;

	}

	function createProdUrlByArt($prodArr)
	{

		if (!$prodArr) {
			return false;
		}

		$q = mysql_query("SELECT * FROM prod_cat");
		$prod_cat = array();
		while ($buf = mysql_fetch_assoc($q)) {
			$prod_cat[$buf['id']] = $buf;
		}
		unset($q);
		// TODO: переписать без sql-запросов в цикле ><
		foreach ($prodArr as &$product) {
			$art = $product["art"];
			$q = mysql_query("SELECT * FROM prod_items WHERE art='$art' ");
			$prod = mysql_fetch_assoc($q);
			$cat_name = $prod_cat[ $prod['cat'] ]['tech_name'];
			$cat_parent_id = $prod_cat[ $prod['cat'] ]['parent'];
			if ($cat_parent_id > 0) {
				$cat_name = $prod_cat[$cat_parent_id]['tech_name']."/".$cat_name;
			}
			$prod['url'] = "/catalog/".$cat_name."/".$prod['art']."_".$prod['tech_name'];
			$product['url'] = $prod['url'];
		}
		unset($product);
		return $prodArr;
	}

	function createProdUrl($prodArr)
	{

		if (!$prodArr) {
			return false;
		}

		$q = mysql_query("SELECT * FROM prod_cat");
		$prod_cat = array();
		while ($buf = mysql_fetch_assoc($q)) {
			$prod_cat[$buf['id']] = $buf;
		}
		foreach ($prodArr as &$prod) {
			$q = mysql_query("SELECT id FROM comments WHERE target_type='product' AND target_id = '".$prod['id']."' AND banned = 0 ") or die(mysql_error());
			$count_reviews = mysql_num_rows($q);
			unset($q);
			$cat_name = $prod_cat[ $prod['cat'] ]['tech_name'];
			$cat_parent_id = $prod_cat[ $prod['cat'] ]['parent'];
			if ($cat_parent_id > 0) {
				$cat_name = $prod_cat[$cat_parent_id]['tech_name']."/".$cat_name;
			}
			$prod['url'] = "/catalog/".$cat_name."/".$prod['art']."_".$prod['tech_name'];
			$prod['count_reviews'] = $count_reviews;
			$prod['count_reviews_text'] = Self::plural_form($count_reviews, array('отзыв','отзыва','отзывов'));
		}
		unset($prod);
		return $prodArr;
	}

	function createCatUrl($catArr)
	{
		foreach ($catArr as &$cat) {
			$cat_path = $cat['tech_name'];
			if (isset($cat['parent'])) {
				if ($cat['parent'] > 0) {
					$cat_path = $catArr[ $cat['parent'] ]['tech_name']."/".$cat_path;
				}
			}
			$cat['url'] = "/catalog/".$cat_path;
		}
		unset($cat);

		return $catArr;
	}

	function createCatTree($catArr)
	{
		$catArr['cats'] = $catArr;
		foreach ($catArr as &$cat) {
			if ($cat['parent'] == "0") {
				$catArr['tree'][$cat['tech_name']]=$cat;
			}
		}
		unset($cat);

		foreach ($catArr as &$cat) {
			if ($cat['parent'] > 0) {
				$parent_tech_name = $catArr[$cat['parent']]['tech_name'];
				if (!isset($catArr['tree'][$parent_tech_name]['child'])) {
					$catArr['tree'][$parent_tech_name]['child'] =[];
				}
				array_push($catArr['tree'][$parent_tech_name]['child'], $cat);
			}
		}
		unset($cat);

		return $catArr;
	}

	function createInStock($prodArr)
	{

		if (!$prodArr) {
			return false;
		}

		foreach ($prodArr as &$prod) {
			switch ($prod['in_stock']) {
				case '0':
					$prod['in_stock_val'] = "ожидается";
					break;

				case '1':
					$prod['in_stock_val'] = "в наличии";
					break;

				case '2':
					$prod['in_stock_val'] = "под заказ";
					break;

				default:
					$prod['in_stock_val'] = 'ожидается';
					break;
			}
		}
		unset($prod);
		return $prodArr;
	}


	function plural_form($number, $after) {
		$cases = array (2, 0, 1, 1, 1, 2);
		return $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
	}

	function rm_from_array($needle, &$array, $all = true){
			if(!$all){
					if(FALSE !== $key = array_search($needle,$array)) unset($array[$key]);
					return;
			}
			foreach(array_keys($array,$needle) as $key){
					unset($array[$key]);
			}
	}

}