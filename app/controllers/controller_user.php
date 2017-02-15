<?php

class Controller_User extends Controller
{


	function __construct()
	{
		$this->model = new Model_User();
		$this->view = new View();
	}


	function action_index()
	{
		Controller::jsonConsole($_SESSION);
		Controller::jsonConsole($isLogged);
		return 0;
	}

	function action_login()
	{
		$jsonLogin = json_decode($_POST['jsonLogin'], true);
		$target = Route::PrepareUrl($_POST['target']);
		if (isset($target)) {
			switch ($target) {
				case 'login':
					$userData = $this->model->getLogin($jsonLogin);
					if ( isset($userData['id']) ) {
						$jsonUser = json_encode($userData);
						if (Self::login($userData)) {
							echo "true";
						} else
						echo "false";
					} else {
						echo "false";
					}
					return true;
					break;

				default:
					Route::Catch_Error('404');
					break;
			}
		}
	return 0;
	}

	function action_profile($uid)
	{
		if (!isset($_SESSION['user']) || $_SESSION['user']['id'] != $uid ) {
			Route::Catch_Error('404');
		}
		$uid = Route::PrepareUrl($uid);
		$userData = $this->model->getUser($uid);
		if (!$userData) {
			Route::Catch_Error('404');
		}
		$pageDataController = $this->model->getUserData('profile', $userData);
		$menuItems = $this->model->get_MainMenu('catalog');
		$pageDataProd = $this->model->getData('prods');
		$crumbCurEl = array('name' => $pageDataController['title'],
												'value' => $_SERVER['REQUEST_URI'] );
		$breadCrumbs = $this->model->getSimpleCrumbs($crumbCurEl);
		$this->view->generate(
			'user_profile_view.php', // вид контента
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
																		'/js/template.js'
																		),
					'pageId' => '', // активный пункт меню
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
	}


	function action_cart($param=null)
	{
		switch ($param) {
			case 'send':
				Self::sendOrder();
				break;

			default:
				Self::showCart();
				break;
		}
	}


	function sendOrder()
	{
		$noCart = false;
		if (!isset($_SESSION['cart'])) {
			$noCart = true; // TODO: empty cart page
			$cartData = NULL;
		} else {
			$cartData = json_decode($_SESSION['cart'], true);
			$cartData['items'] = $this->model->createProdPict($cartData['items']);
		}
		if (isset($_SESSION['user'])) {
			$uid = $_SESSION['user']['id'];
			$userData = $this->model->getUser($uid);
			$pageDataController = $this->model->getUserData('sendOrder', $userData);
			$accName = 'Личный кабинет, '.$_SESSION['user']['name'];
			$accUrl = "/user/profile/".$uid;
			$breadCrumbs = array($accName => $accUrl,
													 $pageDataController['title'] => $_SERVER['REQUEST_URI']);
		} else {
			// echo $_SESSION['user'];
			Route::Catch_Error('404'); // TODO: cart page without login
		}
		$menuItems = $this->model->get_MainMenu('catalog');
		$pageDataProd = $this->model->getData('prods');

		// $breadCrumbs = $this->model->getSimpleCrumbs($crumbCurEl);
		$this->view->generate(
			'order_cart_view.php', // вид контента
			'template_view.php', // вид шаблона
			array( // $data
					'title'=> $pageDataController['title'],
					'style'=>'public/template.css',
					'style_content' => array(
																	'public/main_page.css',
																	'owl-carousel/owl.carousel.css',
																	'owl-carousel/sales.theme.css',
																	'owl-carousel/prod.theme.css',
																	'public/user_profile_page.css',
																	'public/cart_page.css'
																	),
					'scripts_content'=> array(
																		'/js/magic-mask/jq.magic-mask.min.js',
																		'/js/main_page.js',
																		'/js/owl-carousel/owl.carousel.min.js',
																		'/js/template.js',
																		'/js/cart.js'
																		),
					'pageId' => '', // активный пункт меню
					'pageData' => $cartData,
					'userData' => $userData['profile'],
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
	}

	function showCart()
	{
		$noCart = false;
		if (!isset($_SESSION['cart'])) {
			$noCart = true; // TODO: empty cart page
			$cartData = NULL;
		} else {
			$cartData = json_decode($_SESSION['cart'], true);
			$cartData['items'] = $this->model->createProdPict($cartData['items']);
		}
		if (isset($_SESSION['user'])) {
			$uid = $_SESSION['user']['id'];
			$userData = $this->model->getUser($uid);
			$pageDataController = $this->model->getUserData('cart', $userData);
			$accName = 'Личный кабинет, '.$_SESSION['user']['name'];
			$accUrl = "/user/profile/".$uid;
			$breadCrumbs = array($accName => $accUrl,
													 $pageDataController['title'] => $_SERVER['REQUEST_URI']);
		} else {
			// echo $_SESSION['user'];
			Route::Catch_Error('404'); // TODO: cart page without login
		}
		$menuItems = $this->model->get_MainMenu('catalog');
		$pageDataProd = $this->model->getData('prods');

		// $breadCrumbs = $this->model->getSimpleCrumbs($crumbCurEl);
		$this->view->generate(
			'user_cart_view.php', // вид контента
			'template_view.php', // вид шаблона
			array( // $data
					'title'=> $pageDataController['title'],
					'style'=>'public/template.css',
					'style_content' => array(
																	'public/main_page.css',
																	'owl-carousel/owl.carousel.css',
																	'owl-carousel/sales.theme.css',
																	'owl-carousel/prod.theme.css',
																	'public/user_profile_page.css',
																	'public/cart_page.css'
																	),
					'scripts_content'=> array(
																		'/js/magic-mask/jq.magic-mask.min.js',
																		'/js/main_page.js',
																		'/js/owl-carousel/owl.carousel.min.js',
																		'/js/template.js',
																		'/js/cart.js'
																		),
					'pageId' => '', // активный пункт меню
					'pageData' => $cartData,
					'userData' => $userData['profile'],
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
	}

	function login($user)
	{
		$_SESSION['user'] = $user;
		$isLogged = Self::is_logged();
		return $isLogged;
	}

	function action_logout()
	{
		if (isset($_SESSION['user'])) {
			unset($_SESSION['user']);
			header('location:/');
		} else {
			Route::Catch_Error('404');
		}
	return 0;
	}

}