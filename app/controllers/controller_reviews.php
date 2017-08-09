<?php

class Controller_Reviews extends Controller
{

	function __construct()
	{
		$this->model = new Model_Reviews();
		$this->view = new View();
	}

	public function action_index()
	{
		$pageDataController = $this->model->getData('reviews');
		$pageDataProd = $this->model->getData('prods');

		$prodReviews = $this->model->getComments('reviews','');
		$revUsersIds = [];
		if ($prodReviews) {
			foreach ($prodReviews as $review) {
				if (!isset($revUsersIds[$review['uid']])) {
					$revUsersIds[$review['uid']] = $review['uid'];
				}
			}
			$revUsers = $this->model->getUsers($revUsersIds);
			foreach ($prodReviews as &$review) {
				$review['author_name'] = $revUsers[$review['uid']]['name'];
				$review['author_avatar'] = $revUsers[$review['uid']]['avatar'];
				$review['pub_time'] = Self::getGoodDate($review['pub_time']);
				$review['com_text'] = nl2br($review['com_text']);
			}
		$prodReviews['count'] = count($prodReviews);
		} else {
			$prodReviews['count'] = 0;
		}

		$sideNews = $this->model->getNews(CONFIG_SITE_LAST_NEWS_NUM);
		$pageSales = $this->model->getData('sales');
		$menuItems = $this->model->get_MainMenu('catalog');
		$breadCrumbs = array($pageDataController['crumb'] => $_SERVER['REQUEST_URI']);
		$this->view->generate(
			'reviews_view.php', // вид контента
			'template_view.php', // вид шаблона
			array( // $data
					'title'=> $pageDataController['title'],
					'style'=>'public/template.css',
					'style_content' => array(
																	'public/main_page.css',
																	'owl-carousel/sales.theme.css',
																	'owl-carousel/prod.theme.css'
																	),
					'scripts_content'=> array(
																		'/js/magic-mask/jq.magic-mask.min.js',
																		'/js/main_page.js',
																		'/js/owl-carousel/owl.carousel.min.js',
																		'/js/template.js'
																		),
					'active_menu' => 'reviews',
					'pageId' => 'reviews',
					'pageDataView' => $pageDataController,
					'sidebar' => array(
														'app/views/side_menu_view.php',
														'app/views/side_prod_of_day_view.php',
														'app/views/side_news_view.php',
														),
					'prodItems' => $pageDataProd['prodItems'],
					'reviews' => $prodReviews,
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
		return 0;
	}
}