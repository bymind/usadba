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
			'main_view.php',
			'template_view.php',
			array(
					'title'=> $pageDataController['title'],
					'style'=>'public/template.css',
					'style_content'=>array(
					                       'public/main_page.css',
					                       'owl-carousel/owl.carousel.css',
					                       'owl-carousel/sales.theme.css',
					                       'owl-carousel/prod.theme.css'
					                       ),
					'scripts_content'=> array(
																		'js/main_page.js',
																		'js/owl-carousel/owl.carousel.min.js'
																		),
					'active_menu' => 'menu-item-1',
					'pageId' => 'main',
					'pageDataView' => $pageDataController,
					'actions' => 'app/views/sales_carousel_view.php'
				),
			'navigation_view.php',
			'footer_view.php',
			'modal_preorder_view.php'
			);
		return 0;
	}
}