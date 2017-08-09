<?php

class Controller_Sales extends Controller
{

	function __construct()
	{
		$this->model = new Model_Sales();
		$this->view = new View();
	}


	public function action_param($url)
	{
		$pageDataController = $this->model->getSalesPost($url);
		$sideNews = $this->model->getNews(CONFIG_SITE_LAST_NEWS_NUM);
		$pageDataProd = $this->model->getData('prods');
		$menuItems = $this->model->get_MainMenu('catalog');
		$breadCrumbs = array('Акции' => '/sales', $pageDataController['title'] => $_SERVER['REQUEST_URI']);
		$this->view->generate(
			'sales_post_view.php', // вид контента
			'template_view.php', // вид шаблона
			array( // $data
					'title'=> $pageDataController['title'],
					'style'=>'public/template.css',
					'style_content' => array(
																	'public/main_page.css',
																	'owl-carousel/owl.carousel.css',
																	'owl-carousel/sales.theme.css',
																	'owl-carousel/prod.theme.css',
																	'public/sales_page.css'
																	),
					'scripts_content'=> array(
																		'/js/magic-mask/jq.magic-mask.min.js',
																		'/js/main_page.js',
																		'/js/owl-carousel/owl.carousel.min.js',
																		'/js/template.js',
																		'/js/sales_page.js'
																		),
					'pageId' => 'sales', // активный пункт меню
					'catId' => 'sales', // активный пункт меню
					'pageData' => $pageDataController,
					'pageDataView' => $userData['profile'],
					'sidebar' => array(
														'app/views/side_menu_view.php',
														'app/views/side_prod_of_day_view.php',
														'app/views/side_news_view.php',
														),
					'prodItems' => $pageDataProd['prodItems'], //
					'prodCats' => $pageDataProd['prodCats'],
					'pageSales' => $pageSales['sales'],
					'prodSales' => $pageDataController['prods'],
					'sideNews' => $sideNews,
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


	public function action_index()
	{
		$pageDataController = $this->model->getData('salesPage');
		$pageDataProd = $this->model->getData('prods');
		$sideNews = $this->model->getNews(CONFIG_SITE_LAST_NEWS_NUM);
		$pageSales = $this->model->getData('sales');
		$menuItems = $this->model->get_MainMenu('catalog');
		$breadCrumbs = array('Акции' => $_SERVER['REQUEST_URI']);
		$this->view->generate(
			'sales_view.php', // вид контента
			'template_view.php', // вид шаблона
			array( // $data
					'title'=> $pageDataController['title'],
					'style'=>'public/template.css',
					'style_content' => array(
																	'public/main_page.css',
																	'owl-carousel/owl.carousel.css',
																	'owl-carousel/sales.theme.css',
																	'owl-carousel/prod.theme.css',
																	'public/sales_page.css'
																	),
					'scripts_content'=> array(
																		'/js/magic-mask/jq.magic-mask.min.js',
																		'/js/main_page.js',
																		'/js/owl-carousel/owl.carousel.min.js',
																		'/js/template.js',
																		'/js/sales_page.js'
																		),
					'pageId' => 'sales', // активный пункт меню
					'catId' => 'sales', // активный пункт меню
					'pageData' => $pageDataController['sales'],
					'pageDataView' => $userData['profile'],
					'sidebar' => array(
														'app/views/side_menu_view.php',
														'app/views/side_prod_of_day_view.php',
														'app/views/side_news_view.php',
														),
					'prodItems' => $pageDataProd['prodItems'], //
					'prodCats' => $pageDataProd['prodCats'],
					'sideNews' => $sideNews,
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