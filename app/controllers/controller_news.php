<?php

class Controller_News extends Controller
{

	function __construct()
	{
		$this->model = new Model_News();
		$this->view = new View();
	}

	public function actionShowPost($url)
	{
		echo "Show post ".$url;
		return 0;
	}

	public function action_param($url)
	{
		// $pageDataController = $this->model->getData('newsPage');
		// $pageDataController = $this->model->getData('newsPost');
		$pageDataController = $this->model->getNewsPost($url);
		$pageDataProd = $this->model->getData('prods');
		// $pageData = $this->model->getData('news');
		// $pageSales = $this->model->getData('sales');
		$menuItems = $this->model->get_MainMenu('catalog');
		$breadCrumbs = array('Новости' => '/news', $pageDataController['title'] => $_SERVER['REQUEST_URI'],);
		$this->view->generate(
			'news_post_view.php', // вид контента
			'template_view.php', // вид шаблона
			array( // $data
					'title'=> $pageDataController['title'],
					'style'=>'public/template.css',
					'style_content' => array(
																	'public/main_page.css',
																	'owl-carousel/owl.carousel.css',
																	'owl-carousel/sales.theme.css',
																	'owl-carousel/prod.theme.css',
																	'public/news_page.css'
																	),
					'scripts_content'=> array(
																		'/js/magic-mask/jq.magic-mask.min.js',
																		'/js/main_page.js',
																		'/js/owl-carousel/owl.carousel.min.js',
																		'/js/template.js',
																		'/js/sales_page.js'
																		),
					'pageId' => 'news', // активный пункт меню
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


	public function action_index($param=NULL)
	{
		$pageDataController = $this->model->getData('newsPage');
		$pageDataProd = $this->model->getData('prods');
		// $pageData = $this->model->getData('news');
		// $pageSales = $this->model->getData('sales');
		$menuItems = $this->model->get_MainMenu('catalog');
		$breadCrumbs = array('Новости' => $_SERVER['REQUEST_URI']);
		$this->view->generate(
			'news_view.php', // вид контента
			'template_view.php', // вид шаблона
			array( // $data
					'title'=> $pageDataController['title'],
					'style'=>'public/template.css',
					'style_content' => array(
																	'public/main_page.css',
																	'owl-carousel/owl.carousel.css',
																	'owl-carousel/sales.theme.css',
																	'owl-carousel/prod.theme.css',
																	'public/news_page.css'
																	),
					'scripts_content'=> array(
																		'/js/magic-mask/jq.magic-mask.min.js',
																		'/js/main_page.js',
																		'/js/owl-carousel/owl.carousel.min.js',
																		'/js/template.js',
																		'/js/sales_page.js'
																		),
					'pageId' => 'news', // активный пункт меню
					'pageData' => $pageDataController['news'],
					'pageDataView' => $userData['profile'],
					'sidebar' => array(
														'app/views/side_menu_view.php',
														'app/views/side_prod_of_day_view.php',
														'app/views/side_news_view.php',
														),
					'prodItems' => $pageDataProd['prodItems'], //
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