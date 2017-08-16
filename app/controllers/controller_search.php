<?php

class Controller_Search extends Controller
{

	function __construct()
	{
		$this->model = new Model_Search();
		$this->view = new View();
	}

	function action_param($param)
	{
		if (isset($param)) {
			Self::action_index($param);
		}
	}

	function action_index($param=NULL)
	{
		if (!$param) {
			$param = $_POST['search'];
		}
		if (!isset($param) || ($param=="")) {
			Route::Catch_Error('404');
		} else {
			$pageDataController = $this->model->getSearchData($param);
			$pageDataProd = Model::getData('prods');
			$sideNews = $this->model->getNews(CONFIG_SITE_LAST_NEWS_NUM);
			$menuItems = $this->model->get_MainMenu('search');
			$breadCrumbs = array('Результаты поиска' => $_SERVER['REQUEST_URI']);
			$this->view->generate(
				'search_view.php', // вид контента
				'template_view.php', // вид шаблона
				array( // $data
						'title'=> $pageDataController['title'],
						'style'=>'public/template.css',
						'style_content' => array(
																		'public/main_page.css',
																		'owl-carousel/owl.carousel.css',
																		'owl-carousel/sales.theme.css',
																		'owl-carousel/prod.theme.css',
																		'public/user_profile_page.css'
																		),
						'scripts_content'=> array(
																			'/js/magic-mask/jq.magic-mask.min.js',
																			'/js/main_page.js',
																			'/js/owl-carousel/owl.carousel.min.js',
																			'/js/template.js',
																			'/js/search.js',
																			'/js/sales_page.js'
																			),
						'pageId' => 'search', // активный пункт меню
						'pageData' => $pageDataController,
						'search' => $pageDataController['search'],
						'pageDataView' => $userData['profile'],
						'sidebar' => array(
															'app/views/side_menu_view.php',
															'app/views/side_prod_of_day_view.php',
															'app/views/side_news_view.php',
															),
						'prodItems' => $pageDataProd['prodItems'], //
						'sideNews' => $sideNews,
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
		}
	}
}