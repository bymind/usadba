<?php

class Controller_Main extends Controller
{

	function __construct()
	{
		$this->model = new Model_Main();
		$this->view = new View();
	}

	public function action_index()
	{
		$pageDataController = $this->model->getData('main_page');
		$this->view->generate(
			'main_view.php', // вид контента
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
																		'js/magic-mask/jq.magic-mask.min.js',
																		'js/main_page.js',
																		'js/owl-carousel/owl.carousel.min.js',
																		'/js/template.js'
																		),
					'active_menu' => 'menu-item-1',
					'pageId' => 'main',
					'pageDataView' => $pageDataController,
					'actions' => 'app/views/sales_carousel_view.php',
					'sidebar' => array(
														'app/views/side_menu_view.php',
														'app/views/side_prod_of_day_view.php',
														'app/views/side_news_view.php',
														)
				),
			'navigation_view.php', // навигация
			'footer_view.php', // футер
			array( // модальные окна
					 'modal_callback_view.php',
					 'modal_profile_view.php'
					 )
			);
		return 0;
	}
}