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
		$sideNews = $this->model->getNews('5');
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
					'sideNews' => $sideNews,
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
			if (count($param)==2) {
				// var_dump($param);
				if ($param['name']=='tag') {
					Self::action_prodTag($param);
				} else
				Self::action_prodPage($param, 'parentcat');
				// Self::action_subCatPage($param);
			} else if (count($param)==3) {
				Self::action_prodPage($param);
			}
		} else {
			Self::action_catPage($param);
		}
	return 0;
	}


	public function action_prodTag($param)
	{
		$pageDataController = $this->model->getData('catalog');
		$pageDataProd = $this->model->getData('prods');
		$sideNews = $this->model->getNews('5');
		$pageDataProdTag = $this->model->getProdsTag($param['value']);
		$pageSales = $this->model->getData('sales');
		$menuItems = $this->model->get_MainMenu('catalog');
		if ($param['value']=='new') {
			$tagName = 'Новинки';
		} else
		if ($param['value']=='popular') {
			$tagName = 'Популярное';
		}
		$breadCrumbs = array($tagName => $_SERVER['REQUEST_URI']);
		// echo "<pre>";
		// var_dump($menuItems);
		// echo "</pre>";
		$this->view->generate(
			'catalog_tag_view.php', // вид контента
			'template_view.php', // вид шаблона
			array( // $data
					'title'=> $pageDataProdTag['title_tag'],
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
					'catId' => $param['value'],
					'sidebar' => array(
														'app/views/side_menu_view.php',
														'app/views/side_prod_of_day_view.php',
														'app/views/side_news_view.php',
														),
					'prodTag' => $pageDataProdTag['prodItems']['tag'],
					'tagTitle' => $pageDataProdTag['title_tag'],
					'prodItems' => $pageDataProd['prodItems'],
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

	public function action_prodPage($param, $isparent = NULL)
	{
		if ($isparent=='parentcat') {
			$parentCat = $param['name'];
			$curCat = $param['name'];
			$curProd = $param['value'];
		} else {
			$parentCat = $param['parentCat'];
			$curCat = $param['curCat'];
			$curProd = $param['curProd'];
		}

		$currentProduct = $this->model->getProduct($curProd);
		// var_dump($curProd);
		if (!$currentProduct) {
			Self::action_subCatPage($param);
			exit();
		}
		$pageDataController = $this->model->getData('catalog');
		$pageDataProd = $this->model->getData('prods');
		$pageDataCat = $this->model->getCategoryData($curCat);
		$breadCrumbs = $this->model->getCrumbs($pageDataProd['prodCats']['tree'],$pageDataCat['cat']);
		$breadCrumbs[$currentProduct['name']] = "";

		$pageSales = $this->model->getData('sales');
		$menuItems = $this->model->get_MainMenu('catalog');
		$catsTree = $pageDataProd['prodCats']['tree'];

		$prodReviews = $this->model->getComments('product',$currentProduct['id']);
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
				// $review['pub_time'] = strftime("%d %m %Y %H:%M", strtotime($review['pub_time']));
				$review['pub_time'] = Self::getGoodDate($review['pub_time']);
				$review['com_text'] = nl2br($review['com_text']);
			}
		$prodReviews['count'] = count($prodReviews);
		} else {
			$prodReviews['count'] = 0;
		}

		$prodCatPopulars = $pageDataCat['populars'];
		$sideNews = $this->model->getNews('5');
		if ($pageDataCat['cat']['parent']!=0) {
			$parentCatId = $pageDataCat['cat']['parent'];
			$parentCatBuf = $pageDataProd['prodCats'][$parentCatId];
			$popularCat = $parentCatBuf;
			$prodCatPopulars = $pageDataCat['parent_populars'];
		} else {
			$popularCat = $pageDataCat['cat'];
		}

		$backToCatalog = $parentCat."/".$curCat;
		$currentProduct['count_buy'] = Model::plural_form($currentProduct['count_buy'],array('раз','раза','раз'));
		$this->view->generate(
			'catalog_prod_view.php', // вид контента
			'template_view.php', // вид шаблона
			array( // $data
					'title'=> $currentProduct['name']." доставка на дом - ".$pageDataController['title'],
					'style'=>'public/template.css',
					'style_content' => array(
																	'public/main_page.css',
																	'public/prod_page.css',
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
					'catId' => $parentCat, // активный пункт меню родитель
					'curCatId' => $curCat, // активный пункт меню ребенок
					'pageDataView' => $pageDataController,
					'sidebar' => array(
														'app/views/side_menu_view.php',
														'app/views/side_prod_of_day_view.php',
														'app/views/side_news_view.php',
														),
					'prodItems' => $pageDataProd['prodItems'], //
					'prodCatItems' => $pageDataCat['items'], //
					'prodCatItemsHasNew' => $pageDataCat['has_new'], //
					'prodCatItemsHasSales' => $pageDataCat['has_sales'], //
					'currentProduct' => $currentProduct,
					'prodCatPopulars' => $prodCatPopulars, //
					'popularCat' => $popularCat, //
					'prodCat' => $pageDataCat['cat'], //
					'prodCats' => $pageDataProd['prodCats'],
					'sideNews' => $sideNews,
					'pageSales' => $pageSales['sales'],
					'menuItems' => $menuItems,
					'breads' => true,
					'breadsData' => $breadCrumbs,
					'backToCatalog' => $backToCatalog,
					'prodReviews' => $prodReviews,
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


	public function action_catPage($param, $sect = NULL)
	{
		$pageDataController = $this->model->getData('catalog');
		$pageDataProd = $this->model->getData('prods');
		$sideNews = $this->model->getNews('5');
		if ($sect) {
			$pageDataCat = $this->model->getCategoryData($param, $sect);
		} else
		$pageDataCat = $this->model->getCategoryData($param);
		if ($sect) {
			$breadCrumbs = $this->model->getCrumbs($pageDataProd['prodCats']['tree'],$pageDataCat['cat'], $sect);
		} else
		$breadCrumbs = $this->model->getCrumbs($pageDataProd['prodCats']['tree'],$pageDataCat['cat']);
		$pageSales = $this->model->getData('sales');
		$menuItems = $this->model->get_MainMenu('catalog');

		$this->view->generate(
			'catalog_cat_view.php', // вид контента
			'template_view.php', // вид шаблона
			array( // $data
					'title'=> $pageDataCat['cat']['name']." - ".$pageDataController['title'],
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
					'prodCatItemsHasNew' => $pageDataCat['has_new'], //
					'prodCatItemsHasSales' => $pageDataCat['has_sales'], //
					'prodCatPopulars' => $pageDataCat['populars'], //
					'prodCat' => $pageDataCat['cat'], //
					'popularCat' => $pageDataCat['cat'], //
					'prodCats' => $pageDataProd['prodCats'],
					'sideNews' => $sideNews,
					'pageSales' => $pageSales['sales'],
					'menuItems' => $menuItems,
					'breads' => true,
					'breadsData' => $breadCrumbs,
					'section' => $sect,
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


	public function action_subCatPage($param)
	{
		$parentCat = $param['name'];
		$curCat = $param['value'];
		$sideNews = $this->model->getNews('5');
		$buf = array(
		             'parentCat' => $parentCat,
		             'curCat' => $parentCat,
		             'curProd' => $curCat,
		             );
		if ($curCat == "new") {
			Self::action_catPage($parentCat, 'new');
		} else if ($curCat == "sales") {
			Self::action_catPage($parentCat, 'sales');
		} else {
			$pageDataController = $this->model->getData('catalog');
			$pageDataProd = $this->model->getData('prods');
			$pageDataCat = $this->model->getCategoryData($curCat);
			$breadCrumbs = $this->model->getCrumbs($pageDataProd['prodCats']['tree'],$pageDataCat['cat']);
			$pageSales = $this->model->getData('sales');
			$menuItems = $this->model->get_MainMenu('catalog');
			$catsTree = $pageDataProd['prodCats']['tree'];

			$prodCatPopulars = $pageDataCat['populars'];
			if ($pageDataCat['cat']['parent']!=0) {
				$parentCatId = $pageDataCat['cat']['parent'];
				$parentCatBuf = $pageDataProd['prodCats'][$parentCatId];
				$popularCat = $parentCatBuf;
				$prodCatPopulars = $pageDataCat['parent_populars'];
			} else {
				$popularCat = $pageDataCat['cat'];
			}

			$this->view->generate(
				'catalog_cat_view.php', // вид контента
				'template_view.php', // вид шаблона
				array( // $data
						'title'=> $pageDataCat['cat']['name']." - ".$pageDataController['title'],
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
						'catId' => $parentCat, // активный пункт меню родитель
						'curCatId' => $curCat, // активный пункт меню ребенок
						'pageDataView' => $pageDataController,
						'sidebar' => array(
															'app/views/side_menu_view.php',
															'app/views/side_prod_of_day_view.php',
															'app/views/side_news_view.php',
															),
						'prodItems' => $pageDataProd['prodItems'], //
						'prodCatItems' => $pageDataCat['items'], //
						'prodCatItemsHasNew' => $pageDataCat['has_new'], //
						'prodCatItemsHasSales' => $pageDataCat['has_sales'], //
						// 'prodCatPopulars' => $pageDataCat['populars'], //
						// 'prodParentCatPopulars' => $pageDataCat['parent_populars'], //
						'prodCatPopulars' => $prodCatPopulars, //
						'popularCat' => $popularCat, //
						'prodCat' => $pageDataCat['cat'], //
						'prodCats' => $pageDataProd['prodCats'],
						'pageSales' => $pageSales['sales'],
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
	}

}