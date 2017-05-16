<?php

class Model_News extends Model
{
	/**
	* getData($pageName)
	* Getting data for page
	* @param $pageName
	* @return $data
	*/

	public $pageDataModel;

	public function getNewsPost($url)
	{
		$url = addslashes($url);
		$q = mysql_query("SELECT * FROM articles WHERE archived=0 AND url='$url' LIMIT 1")or die(mysql_error());
		$buf = mysql_fetch_assoc($q);
		if (!$buf) {
			Route::Catch_Error('404');
		}
		$buf['datetime'] = Controller::getGoodDate($buf['datetime'], 'compact');
		$dataNews = $buf;
		// echo "<pre>";
		// var_dump($dataNews);
		// echo "</pre>";
		$pageDataModel['text'] = "Main page - Welcome! Hello from Model_Main =)";
		$pageDataModel['title'] = $dataNews['title'];
		// $nowTime = time();
		// $dataSales = array();
		$pageDataModel = $dataNews;
	return $pageDataModel;
	}

	public function getData($pageName)
	{
		switch ($pageName) {
			case 'main_page':
				$pageDataModel['text'] = "Main page - Welcome! Hello from Model_Main =)";
				$pageDataModel['title'] = "Доставка продуктов и блюд на дом.";
				break;

			case 'salesPage':
				$pageDataModel['text'] = "Main page - Welcome! Hello from Model_Main =)";
				$pageDataModel['title'] = "Акции и скидки.";
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

			case 'newsPage':
				$pageDataModel['text'] = "Main page - Welcome! Hello from Model_Main =)";
				$pageDataModel['title'] = "Наши новости";
				$nowTime = time();
				$dataSales = array();
				$q = mysql_query("SELECT * FROM articles WHERE archived=0 ORDER BY `datetime` DESC");
				while ( $buf = mysql_fetch_assoc($q)) {
						$buf['datetime'] = Controller::getGoodDate($buf['datetime'], 'compact');
						$dataSales[] = $buf;
				}
				$pageDataModel['news'] = $dataSales;
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
				$q = mysql_query("SELECT * FROM prod_items WHERE labels like '%new%' ORDER BY added_time DESC ") or die(mysql_error());
				while ( $buf = mysql_fetch_assoc($q)) {
					$prod_new[] = $buf;
				}
				$q = mysql_query("SELECT * FROM prod_items WHERE labels like '%popular%' ORDER BY added_time DESC") or die(mysql_error());
				while ( $buf = mysql_fetch_assoc($q)) {
					$prod_popular[] = $buf;
				}
				$q = mysql_query("SELECT * FROM prod_items ORDER BY added_time DESC ") or die(mysql_error());
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
}