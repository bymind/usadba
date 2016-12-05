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
				$q = mysql_query("SELECT * FROM prod_items  ORDER BY added_time DESC ") or die(mysql_error());
				while ( $buf = mysql_fetch_assoc($q)) {
					$prod_items[] = $buf;
				}
				$prod_items = Model::createProdUrl($prod_items);
				$prod_items = Model::createInStock($prod_items);
				$pageDataModel['prodItems'] = $prod_items;
				$pageDataModel['text'] = "Catalog Model";
				$pageDataModel['title'] = "Каталог продукции.";
				break;

			default:
				$pageDataModel['text'] = "Any page text";
				break;
		}
		return $pageDataModel;
	}

}