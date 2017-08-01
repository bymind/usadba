<?php

class Controller_Error_404 extends Controller
{

	function __construct()
	{
		$this->model = new Model_Error_404();
		$this->view = new View();
	}

	public function action_index()
	{
		$pageDataController = $this->model->getData('error_404');
		$pageDataProd = $this->model->getData('prods');
		$sideNews = $this->model->getNews('5');
		$pageSales = $this->model->getData('sales');
		$menuItems = $this->model->get_MainMenu('catalog');
		$this->view->generate(
			'errors/error_404_view.php', // вид контента
			'template_view.php', // вид шаблона
			array( // $data
					'php_header' => "HTTP/1.0 404 Not Found",
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
					'active_menu' => 'menu-item-1',
					'pageId' => 'error_404',
					'pageDataView' => $pageDataController,
					'sidebar' => array(
														'app/views/side_menu_view.php',
														'app/views/side_prod_of_day_view.php',
														'app/views/side_news_view.php',
														),
					'prodItems' => $pageDataProd['prodItems'],
					'sideNews' => $sideNews,
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
}