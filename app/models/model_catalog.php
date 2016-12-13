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

	public function getData($pageName)
	{
		switch ($pageName) {
			case 'catalog':
				$pageDataModel['text'] = "Catalog Model";
				$pageDataModel['title'] = "Каталог продукции.";
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

				/*echo "<pre>";
				var_dump($prod_all);
				echo "</pre>";*/

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

	/**
	* getCategoryData($catName)
	* Инфа про категорию
	* @param $catName
	* @return $products
	*/
	function getCategoryData($catName)
	{
		$category = $catName;
		$q = mysql_query("SELECT * FROM prod_cat WHERE tech_name='$category'");
		$categoryData = mysql_fetch_assoc($q);
		unset($q);
		$products = [];
		$products['cat'] = $categoryData;

		$categoryId = $categoryData['id'];
		$q = mysql_query("SELECT * FROM prod_items WHERE cat='$categoryId' ORDER BY added_time DESC");
		while ( $buf = mysql_fetch_assoc($q)) {
			$products['items'][$buf['id']] = $buf;
			if (strstr($buf['labels'], 'popular') ) {
				$products['populars'][$buf['id']] = $buf;
			}
		}

		$products['items'] = Model::createProdUrl($products['items']);
		$products['items'] = Model::createInStock($products['items']);
		$products['populars'] = Model::createProdUrl($products['populars']);
		$products['populars'] = Model::createInStock($products['populars']);

	return $products;
	}



	/**
	* getCrumbs($tree, $cat)
	* генерирование данных для хлебныйх крошек
	* @param $tree дерево категорий
	* @param $cat категория
	* @return $crumbs
	*/
	function getCrumbs($tree, $cat)
	{
		$crumbs = array('Каталог' => '/catalog'); // первый элемент - каталог
		foreach ($tree as $key => $value) { // идем по категориям
			if ($key == $cat['tech_name']) { // если нашли категорию - записываем
				$crumbs[$value['name']] = $value['url'];
				break; // и выходим
			} else if ($value['child']) { // если есть дети
							foreach ($value['child'] as $child) { // идем в детей
								if ($child['tech_name'] == $cat['tech_name']) { //если нашли в детях
									$crumbs[$value['name']] = $value['tech_name']; // записываем маму
									$crumbs[$child['name']] = $child['url']; // записываем дитё
								}
							}
			}
		}
		return $crumbs;
	}

}