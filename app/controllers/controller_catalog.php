<?php

class Controller_Catalog extends Controller
{

	function __construct()
	{
		$this->model = new Model_Catalog();
		$this->view = new View();
	}

	public function action_index()
	{
		$pageDataController = $this->model->getData('catalog');
		$pageDataProd = $this->model->getData('prods');
		$pageSales = $this->model->getData('sales');
		$menuItems = $this->model->get_MainMenu('catalog');
		// echo "<pre>";
		// var_dump($menuItems);
		// echo "</pre>";
		$this->view->generate(
			'catalog_view.php', // вид контента
			'template_view.php', // вид шаблона
			array( // $data
					'title'=> $pageDataController['title'],
					'style'=>'public/template.css',
					'style_content' => array(
																	'public/main_page.css',
																	'owl-carousel/owl.carousel.css',
																	'owl-carousel/sales.theme.css',
																	'owl-carousel/prod.theme.css'
																	),
					'scripts_content'=> array(
																		// 'js/prefixfree/prefixfree.min.js',
																		'/js/magic-mask/jq.magic-mask.min.js',
																		'/js/main_page.js',
																		'/js/owl-carousel/owl.carousel.min.js',
																		'/js/template.js'
																		),
					'pageId' => 'catalog', // активный пункт меню
					'pageDataView' => $pageDataController,
					'sidebar' => array(
														'app/views/side_menu_view.php',
														'app/views/side_prod_of_day_view.php',
														'app/views/side_news_view.php',
														),
					'prodItems' => $pageDataProd['prodItems'], //
					'prodCats' => $pageDataProd['prodCats'],
					'pageSales' => $pageSales['sales'],
					'menuItems' => $menuItems,
				),
			'navigation_view.php', // навигация
			'footer_view.php', // футер
			array( // модальные окна
					 'modal_callback_view.php',
					 'modal_profile_view.php',
					 'modal_cart_view.php'
					 )
			);
		return 0;
	}


	public function action_param($param)
	{
		if (is_array($param)) {
			Self::action_subCatPage($param);
		} else {
			Self::action_catPage($param);
		}
	return 0;
	}

	public function action_catPage($param)
	{
		$pageDataController = $this->model->getData('catalog');
		$pageDataProd = $this->model->getData('prods');
		$pageDataCat = $this->model->getCategoryData($param);
		$breadCrumbs = $this->model->getCrumbs($pageDataProd['prodCats']['tree'],$pageDataCat['cat']);
		$pageSales = $this->model->getData('sales');
		$menuItems = $this->model->get_MainMenu('catalog');
		// echo "<pre>";
		// var_dump($menuItems);
		// echo "</pre>";
		$this->view->generate(
			'catalog_cat_view.php', // вид контента
			'template_view.php', // вид шаблона
			array( // $data
					'title'=> $pageDataController['title'],
					'style'=>'public/template.css',
					'style_content' => array(
																	'public/main_page.css',
																	'owl-carousel/owl.carousel.css',
																	'owl-carousel/sales.theme.css',
																	'owl-carousel/prod.theme.css'
																	),
					'scripts_content'=> array(
																		// 'js/prefixfree/prefixfree.min.js',
																		'/js/magic-mask/jq.magic-mask.min.js',
																		'/js/main_page.js',
																		'/js/owl-carousel/owl.carousel.min.js',
																		'/js/template.js'
																		),
					'pageId' => 'catalog', // активный пункт меню
					'catId' => $param, // активный пункт меню
					'pageDataView' => $pageDataController,
					'sidebar' => array(
														'app/views/side_menu_view.php',
														'app/views/side_prod_of_day_view.php',
														'app/views/side_news_view.php',
														),
					'prodItems' => $pageDataProd['prodItems'], //
					'prodCatItems' => $pageDataCat['items'], //
					'prodCatPopulars' => $pageDataCat['populars'], //
					'prodCat' => $pageDataCat['cat'], //
					'prodCats' => $pageDataProd['prodCats'],
					'pageSales' => $pageSales['sales'],
					'menuItems' => $menuItems,
					'breads' => true,
					'breadsData' => $breadCrumbs,
				),
			'navigation_view.php', // навигация
			'footer_view.php', // футер
			array( // модальные окна
					 'modal_callback_view.php',
					 'modal_profile_view.php',
					 'modal_cart_view.php'
					 )
			);
		return 0;
	}

}