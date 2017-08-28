<?php

class Model_Sales extends Model
{
	/**
	* getData($pageName)
	* Getting data for page
	* @param $pageName
	* @return $data
	*/

	public $pageDataModel;


	public function getSalesPost($url)
	{
		$url = addslashes($url);
		$nowDate = time();
		$q = mysql_query("SELECT * FROM sales WHERE  end_time > '$nowDate' AND tech_name='$url' LIMIT 1")or die(mysql_error());
		$buf = mysql_fetch_assoc($q);
		if (!$buf) {
			Route::Catch_Error('404');
		}
		$buf['start_time'] = Controller::getGoodDate($buf['start_time'], 'compact');
		$buf['raw_end_time'] = $buf['end_time'];
		$buf['end_time'] = Controller::getGoodDate($buf['end_time']);
		$dataSales = $buf;
		$pageDataModel = $dataSales;
		$pageDataModel['title'] = $dataSales['name'];
		$pageDataModel['prods'] = Self::getProdsByIds($buf['prods']);
	return $pageDataModel;
	}


	public function getProdsByIds($arrIds)
	{
		$ds = array();
		$q = mysql_query("SELECT * FROM prod_items WHERE id in ($arrIds) ORDER BY added_time DESC") or die(mysql_error());
		while ($buf = mysql_fetch_assoc($q)) {
			$ds[] = $buf;
		}
		return $ds;
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
				$pageDataModel['title'] = "Акции и скидки";
				$nowTime = time();
				$dataSales = array();
				$today = date('Y-m-d H:i:s');
				$q = mysql_query("SELECT * FROM sales WHERE end_time > '$today'");
				while ( $buf = mysql_fetch_assoc($q)) {
					$endTime = strtotime($buf['end_time']);
					if ($endTime > $nowTime) {
						$buf['start_time'] = Controller::getGoodDate($buf['start_time'], 'compact');
						$buf['raw_end_time'] = $buf['end_time'];
						$buf['end_time'] = Controller::getGoodDate($buf['end_time']);
						$dataSales[] = $buf;
					}
				}
				$pageDataModel['sales'] = $dataSales;
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