<?php


class Model
{

	public function get_login($login)
	{
		$select = mysql_query("SELECT * FROM users WHERE login = '$login'")or die(mysql_error());

		$ds = mysql_fetch_assoc($select);

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
			$cat_name = $prod_cat[ $prod['cat'] ]['tech_name'];
			$cat_parent_id = $prod_cat[ $prod['cat'] ]['parent'];
			if ($cat_parent_id > 0) {
				$cat_name = $prod_cat[$cat_parent_id]['tech_name']."/".$cat_name;
			}
			$prod['url'] = "/catalog/".$cat_name."/".$prod['art']."_".$prod['tech_name'];
		}
		unset($prod);
		return $prodArr;
	}

	function createCatUrl($catArr)
	{
		foreach ($catArr as &$cat) {
			$cat_path = $cat['tech_name'];
			if ($cat['parent'] > 0) {
				$cat_path = $catArr[ $cat['parent'] ]['tech_name']."/".$cat_path;
			}
			$cat['url'] = "/catalog/".$cat_path;
		}
		unset($cat);
	/*	echo "<pre>";
		var_dump($catArr);
		echo "</pre>";*/
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
				/*echo "<pre>";
				var_dump($parent_tech_name);
				echo "</pre>";*/
				if (!isset($catArr['tree'][$parent_tech_name]['child'])) {
					$catArr['tree'][$parent_tech_name]['child'] =[];
				}
				array_push($catArr['tree'][$parent_tech_name]['child'], $cat);
			}
		}
		unset($cat);

		// echo "<pre>";
		// var_dump($catArr['tree']);
		// echo "</pre>";

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


}