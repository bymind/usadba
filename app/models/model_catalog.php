<?php

class Model_Catalog extends Model
{
	/**
	* getData($pageName)
	* Getting data for page
	* @param $pageName
	* @return $data
	*/

	public $pageDataModel;
	public $prod_items = array();
	private $allCatsTree;

	public function getData($pageName)
	{
		switch ($pageName) {
			case 'catalog':
				$pageDataModel['text'] = "Catalog Model";
				$pageDataModel['title'] = "Каталог продукции";
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
				$q = mysql_query("SELECT * FROM prod_items WHERE labels like '%popular%'  AND archived = 0 ORDER BY added_time DESC") or die(mysql_error());
				while ( $buf = mysql_fetch_assoc($q)) {
					$prod_popular[] = $buf;
				}
				$q = mysql_query("SELECT * FROM prod_items WHERE archived = 0  ORDER BY added_time DESC ") or die(mysql_error());
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
				break;


			default:
				$pageDataModel['text'] = "Any page text";
				break;
		}
		return $pageDataModel;
	}

	function getProdsTag($tag)
	{
		$prod_tag = array();
		$prod_cats = array();
		if ($tag=="favs") {
			$pageDataModel['text'] = "Catalog Model";
			$pageDataModel['title'] = "Мои избранные товары.";
			$pageDataModel['title_tag'] = "Мои избранные товары";
			if ($_SESSION['user']['id']) {
				$favs_ids = array();
				$q = mysql_query("SELECT favs FROM users WHERE id = '".$_SESSION['user']['id']."'") or die(mysql_error());
				$r = mysql_fetch_array($q);
				$r = $r[0];
				if ($r[0]==",") {
					$r = substr($r, 1);
					$q = mysql_query("UPDATE users SET favs='$r' WHERE id = '".$_SESSION['user']['id']."'") or die(mysql_error());
					$_SESSION['user']['favs'] = $r;
					if ($r[strlen($r)-1]==",") {
						$r = substr($r, 0, strlen($r)-2);
						$q = mysql_query("UPDATE users SET favs='$r' WHERE id = '".$_SESSION['user']['id']."'") or die(mysql_error());
						$_SESSION['user']['favs'] = $r;
					}
				} else
				if ($r[strlen($r)-1]==",") {
					$r = substr($r, 0, strlen($r)-2);
					$q = mysql_query("UPDATE users SET favs='$r' WHERE id = '".$_SESSION['user']['id']."'") or die(mysql_error());
					$_SESSION['user']['favs'] = $r;
					if ($r[0]==",") {
					$r = substr($r, 1);
					$q = mysql_query("UPDATE users SET favs='$r' WHERE id = '".$_SESSION['user']['id']."'") or die(mysql_error());
					$_SESSION['user']['favs'] = $r;
					}
				}
				$favs_ids = $r;
				if ($favs_ids!="") {
					$q = mysql_query("SELECT * FROM prod_items WHERE id in ($favs_ids) AND archived = 0 ") or die(mysql_error());
					while ( $buf = mysql_fetch_assoc($q)) {
						$prod_tag[$buf['id']] = $buf;
					}
				$massVals = explode(',',$favs_ids);
				for ($i=0; $i < count($massVals); $i++) {
					$massVals[$i] = $prod_tag[$massVals[$i]];
				}
				unset($prod_tag);
				$prod_tag = array();
				$prod_tag = array_reverse($massVals);
				} else {
					$prod_tag = array();
				}
			} else {
				Route::Catch_Error('404');
			}
		} else {
			$q = mysql_query("SELECT * FROM prod_items WHERE labels like '%".$tag."%'  AND archived = 0 ORDER BY added_time DESC ") or die(mysql_error());
			while ( $buf = mysql_fetch_assoc($q)) {
				$prod_tag[] = $buf;
			}
			$pageDataModel['text'] = "Catalog Model";
			if ($tag=="new") {
				$pageDataModel['title'] = "Новинки.";
				$pageDataModel['title_tag'] = "Новинки";
			} else
			if ($tag=="popular") {
				$pageDataModel['title'] = "Популярные товары.";
				$pageDataModel['title_tag'] = "Популярные товары";
			} else {
				$pageDataModel['title'] = "Каталог продукции.";
				$pageDataModel['title_tag'] = "Каталог продукции";
			}

			$q = mysql_query("SELECT * FROM prod_cat ORDER BY position") or die(mysql_error());
			while ( $buf = mysql_fetch_assoc($q)) {
				$prod_cats[$buf['id']] = $buf;
			}
		}
		unset($q);
		$prod_tag = Model::createProdUrl($prod_tag);
		$prod_tag = Model::createInStock($prod_tag);
		$pageDataModel['prodItems']['tag'] = $prod_tag;
		$prod_cats = Model::createCatUrl($prod_cats);
		$prod_cats = Model::createCatTree($prod_cats);
		$pageDataModel['prodCats'] = $prod_cats;


		return $pageDataModel;
	}

	function setPaginationLimit($table,$key_column,$key_value, $per_page)
	{
		$limit = "";
		if (isset($_GET['page'])) {
			$page = preg_replace("/[^0-9]/", '', $_GET['page']);
		}
		if (($page)&&($page>1)) {
			$q = mysql_query("SELECT id FROM $table WHERE $key_column='$key_value' AND archived = 0 ") or die(mysql_error());
			$countItems = mysql_num_rows($q);
			if (($page-1)*$per_page >= $countItems) {
				Route::Catch_Error('404');
			} else {
				$startId = ($page-1)*$per_page;
				$limit = " LIMIT ".$startId.", ".$per_page;
			}
		}
		return $limit;
	}

	/**
	* getCategoryData($catName)
	* Инфа про категорию
	* @param $catName
	* @return $products
	*/
	function getCategoryData($catName, $sect = NULL)
	{
		$has_new = false;
		$has_sales = false;
		$category = $catName;
		$q = mysql_query("SELECT * FROM prod_cat WHERE tech_name='$category'");
		$categoryData = mysql_fetch_assoc($q);
		if (!$categoryData) {
			Route::Catch_Error('404');
		}
		unset($q);
		$products = [];
		$products['cat'] = $categoryData;
		$pos = $products['cat']['position'];


		$allCatsTree = Self::getData('prods');
		$curCatsTree = $allCatsTree['prodCats']['tree'];
		$curCatInfo = $curCatsTree[$category];

		if ($products['cat']['parent'] != 0) {
			$q = mysql_query("SELECT * FROM prod_cat WHERE id='".$products['cat']['parent']."'");
			$products['parent'] = mysql_fetch_assoc($q);
			$pos = $products['parent']['position'];
			$q = mysql_query("SELECT * FROM prod_items WHERE cat='".$products['parent']['id']."'  AND archived = 0 ORDER BY added_time DESC");
			while ( $buf = mysql_fetch_assoc($q)) {
				if (strstr($buf['labels'], 'new') ) {
					$has_new = true;
				}
				if (strstr($buf['labels'], 'sales') ) {
					$has_sales = true;
				}
			}
		}

		$q = mysql_query("SELECT * FROM prod_cat WHERE parent=0");
		while ( $buf = mysql_fetch_assoc($q)) {
			$products['parents_cats'][$buf['position']] = $buf;
		}


		for ($i= 1; $i <= count($products['parents_cats']); $i++) {

			if ($pos == 1) {
				$products['cat']['prev'] = $products['parents_cats'][ count($products['parents_cats']) ];
				$products['cat']['next'] = $products['parents_cats'][ 2 ];
			} else
			if ($pos == count($products['parents_cats'])) {
				$products['cat']['prev'] = $products['parents_cats'][ count($products['parents_cats']) - 1];
				$products['cat']['next'] = $products['parents_cats'][1];
			} else
			if ($pos+1 == $products['parents_cats'][$i]['position']) {
				$products['cat']['next'] = $products['parents_cats'][$i];
			} else
			if ($pos-1 == $products['parents_cats'][$i]['position']) {
				$products['cat']['prev'] = $products['parents_cats'][$i];
			}
		}

		$categoryId = $categoryData['id'];
		if (($sect)&&(( $sect=='new' )||( $sect=='sales' ))) {
			// $limit_pagination = Self::setPaginationLimit('prod_items','cat',$categoryId, $categoryData['per_page']);

			$q = mysql_query("SELECT * FROM prod_items WHERE cat='$categoryId' AND labels like '%".$sect."%' AND archived = 0 ORDER BY added_time DESC");
			while ( $buf = mysql_fetch_assoc($q)) {
				$products['items'][$buf['id']] = $buf;
				if (strstr($buf['labels'], 'new') ) {
					$has_new = true;
				}
				if (strstr($buf['labels'], 'sales') ) {
					$has_sales = true;
				}
			}

			$q = mysql_query("SELECT * FROM prod_items WHERE cat='$categoryId' AND archived = 0 AND labels LIKE '%popular%' ORDER BY added_time DESC");
			while ( $buf = mysql_fetch_assoc($q)) {
					$products['populars'][$buf['id']] = $buf;
			}
		} else if ($sect) {
			Route::Catch_Error('404');
		} else
		$q = mysql_query("SELECT * FROM prod_items WHERE cat='$categoryId' AND archived = 0  ORDER BY added_time DESC");
		while ( $buf = mysql_fetch_assoc($q)) {
			$products['items'][$buf['id']] = $buf;
			if (strstr($buf['labels'], 'new') ) {
				$has_new = true;
			}
			if (strstr($buf['labels'], 'sales') ) {
				$has_sales = true;
			}
			if (strstr($buf['labels'], 'popular') ) {
				$products['populars'][$buf['id']] = $buf;
			}
		}

		if ($products['cat']['parent'] == 0) {
		if ($curCatInfo['child']) {
		foreach ($curCatInfo['child'] as $child) {
			$categoryId = $child['id'];
				if ($sect) {
					$q = mysql_query("SELECT * FROM prod_items WHERE cat='$categoryId' AND labels like '%".$sect."%' AND archived = 0  ORDER BY added_time DESC");
					while ( $buf = mysql_fetch_assoc($q)) {
						$products['items'][$buf['id']] = $buf;
						if (strstr($buf['labels'], 'new') ) {
							$has_new = true;
						}
						if (strstr($buf['labels'], 'sales') ) {
							$has_sales = true;
						}
					}
					$q = mysql_query("SELECT * FROM prod_items WHERE cat='$categoryId' AND archived = 0  ORDER BY added_time DESC");
					while ( $buf = mysql_fetch_assoc($q)) {
						if (strstr($buf['labels'], 'popular') ) {
							$products['populars'][$buf['id']] = $buf;
						}
					}
				} else
				$q = mysql_query("SELECT * FROM prod_items WHERE cat='$categoryId' AND archived = 0  ORDER BY added_time DESC");
				while ( $buf = mysql_fetch_assoc($q)) {
					$products['items'][$buf['id']] = $buf;
					if (strstr($buf['labels'], 'new') ) {
						$has_new = true;
					}
					if (strstr($buf['labels'], 'sales') ) {
						$has_sales = true;
					}
					if (strstr($buf['labels'], 'popular') ) {
						$products['populars'][$buf['id']] = $buf;
					}
				}
			}
			}
		}

		$per_page = $categoryData['per_page'];
		$countItems = count($products['items']);
		$countPages = ceil($countItems/$per_page);
		if (isset($_GET['page'])) {
			if ($countItems > $per_page) {
				$page = preg_replace("/[^0-9]/", '', $_GET['page']);
				if ($page!=$_GET['page']) {
					Route::Catch_301_Redirect($_SERVER["REDIRECT_URL"]."?page=".$page);
				}
				if ($page < 1) {
					Route::Catch_301_Redirect($_SERVER["REDIRECT_URL"]);
				}
			} else {
				Route::Catch_301_Redirect($_SERVER["REDIRECT_URL"]);
			}
		} else {
			$page = 1;
		}
		if (($page)&&($countItems > $per_page)) {
			if (($page-1)*$per_page >= $countItems) {
				Route::Catch_301_Redirect($_SERVER["REDIRECT_URL"]."?page=".$countPages);
			} else {
				$startId = ($page-1)*$per_page;
				// var_dump($startId);
				$endId = $startId + $per_page;
				$pagProd = array();
				// var_dump($products['items']);
				$i = 0;
				foreach ($products['items'] as $key => $prod) {
					$pagProd[$i] = $prod;
					$i++;
				}
				$pagProdTrue = array();
				for ($i=0; $i < $per_page; $i++) {
					if ($pagProd[$startId+$i]) {
						$pagProdTrue[] = $pagProd[$startId+$i];
					}
				}
				$products['items'] = array();
				foreach ($pagProdTrue as $key => $paginationProd) {
					$products['items'][$paginationProd['id']] = $paginationProd;
				}
				$products['pagination'] = array('cur_page' => $page,
				                                'count_pages' => $countPages);
			}
		} else {

		}

		$products['items'] = Model::createProdUrl($products['items']);
		$products['items'] = Model::createInStock($products['items']);
		$products['populars'] = Model::createProdUrl($products['populars']);
		$products['populars'] = Model::createInStock($products['populars']);
		$products['has_new'] = $has_new;
		$products['has_sales'] = $has_sales;

		if ($products['parent']) {
				$parent = Self::getCategoryData($products['parent']['tech_name']);

				$products['parent_populars'] = Model::createProdUrl($parent['populars']);
				$products['parent_populars'] = Model::createInStock($parent['populars']);
		}


	return $products;
	}


	function getProduct($artName)
	{
		// $articul = explode("_",$artName);
		// $articul = $articul[0];
		$articul = substr($artName, 0, strpos($artName, "_"));
		$prodName = substr($artName, strpos($artName, "_")+1);
		$q = mysql_query("SELECT * FROM prod_items WHERE art='$articul' AND tech_name='$prodName' AND archived = 0 ");
		$product = mysql_fetch_assoc($q);
		if (!$product) {
			return false;
			// Route::Catch_Error('404');
		} else {
		$product['description'] = nl2br($product['description']);
		switch ($product['in_stock']) {
			case '0':
				$product['in_stock_val'] = "ожидается";
				break;

			case '1':
				$product['in_stock_val'] = "в наличии";
				break;

			case '2':
				$product['in_stock_val'] = "под заказ";
				break;

			default:
				$product['in_stock_val'] = 'ожидается';
				break;
		}
		}
		return $product;
	}


	/**
	* getCrumbs($tree, $cat)
	* генерирование данных для хлебныйх крошек
	* @param $tree дерево категорий
	* @param $cat категория
	* @return $crumbs
	*/
	function getCrumbs($tree, $cat, $sect = NULL, $item = NULL)
	{
		if (!$item) {
		$crumbs = array('Каталог' => '/catalog'); // первый элемент - каталог
		foreach ($tree as $key => $value) { // идем по категориям
			if ($key == $cat['tech_name']) { // если нашли категорию - записываем
				$crumbs[$value['name']] = $value['url'];
				if ($sect) {
				switch ($sect) {
					case 'new':
						$crumbs["Новинки"] = $value['url'].'/new';
						break;

					case 'sales':
						$crumbs["Акции"] = $value['url'].'/new';
						break;

					default:
						# code...
						break;
				}
				} else {}
				break; // и выходим
			} else if ($value['child']) { // если есть дети
							foreach ($value['child'] as $child) { // идем в детей
								if ($child['tech_name'] == $cat['tech_name']) { //если нашли в детях
									$crumbs[$value['name']] = "/catalog/".$value['tech_name']; // записываем маму
									$crumbs[$child['name']] = $child['url']; // записываем дитё
								}
							}
			}
		}

		} else {
			return "item page";
		}
		return $crumbs;
	}

}