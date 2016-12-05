<?php

class Model_Error_404 extends Model
{
	/**
	* getData($pageName)
	* Getting data for page
	* @param $pageName
	* @return $data
	*/

	public $pageDataModel;

	public function getData($pageName)
	{
		switch ($pageName) {
			case 'error_404':
				$pageDataModel['text'] = "Error 404. Page not found.";
				$pageDataModel['title'] = "Страница не найдена.";
				break;
			default:
				$pageDataModel['text'] = "Any page text";
				break;
		}
		return $pageDataModel;
	}
}