<?php

class Controller_User extends Controller
{


	function __construct()
	{
		$this->model = new Model_User();
		$this->view = new View();
		if (isset($_SESSION['user']['id'])) {
			$is_banned = $this->model->getUser($_SESSION['user']['id']);
			// var_dump($is_banned);
			if ($is_banned['profile']['banned']=='1') {
				// echo $_SERVER['REQUEST_URI'];
				session_destroy();
				header('location:'.$_SERVER['REQUEST_URI']);
			}
		}
	}


	function action_index()
	{
		if (isset($_SESSION['user']['id'])) {
			Route::Catch_301_Redirect("/user/profile/".$_SESSION['user']['id']);
		} else {
			Route::Catch_Error("404");
		}
		return 0;
	}

	function action_order($orderid=NULL)
	{
		if (!$orderid && isset($_SESSION['user']['id'])) {
			Route::Catch_301_Redirect("/user/history/".$_SESSION['user']['id']);
		} else
		if (!$orderid) {
			Route::Catch_Error("404");
		}
		$clean_orderid = preg_replace("/[^0-9]/", '', $orderid);
		if ($clean_orderid != $orderid) {
			Route::Catch_301_Redirect("/user/order/".$clean_orderid);
		} else {
			$orderid = $clean_orderid;
		}
		$q = mysql_query("SELECT uid FROM orders WHERE id = $orderid") or die(mysql_error());
		$trueUId = mysql_fetch_assoc($q);
		$trueUId = $trueUId['uid'];
		$uid = $_SESSION['user']['id'];
		if (!$uid || $uid == "" || $uid != $trueUId) {
			Route::Catch_Error("404");
		} else {
			if (!isset($_SESSION['user']) || $_SESSION['user']['id'] != $uid ) {
				Route::Catch_Error('404');
			}
			if (!$orderid || $orderid == "") {
				Route::Catch_301_Redirect("/user/history/".$_SESSION['user']['id']);
			}
			$userData = $this->model->getUser($uid);
			if (!$userData) {
				Route::Catch_Error('404');
			}
			$pageDataController['user'] = $this->model->getUserData('profile', $userData);
			$pageDataController['order'] = $this->model->getUserOrder($orderid);
			// var_dump($pageDataController['order']['order']);
			$pageDataController['order']['order']['prod_list']['items'] = $this->model->createProdPict($pageDataController['order']['order']['prod_list']['items']);
			$menuItems = $this->model->get_MainMenu('catalog');
			$sideNews = $this->model->getNews(CONFIG_SITE_LAST_NEWS_NUM);
			$pageDataProd = Model::getData('prods');
			$accName = 'Личный кабинет, '.$_SESSION['user']['name'];
			$accUrl = "/user/profile/".$uid;
			$ordersName = 'Заказы';
			$ordersUrl = "/user/history/".$uid;
			$breadCrumbs = array($accName => $accUrl,
			                     $ordersName => $ordersUrl,
													 $pageDataController['order']['title'] => $_SERVER['REQUEST_URI']);
			$this->view->generate(
				'user_order_view.php', // вид контента
				'template_view.php', // вид шаблона
				array( // $data
						'title'=> $pageDataController['order']['title'],
						'style'=>'public/template.css',
						'style_content' => array(
																		'public/main_page.css',
																		'owl-carousel/owl.carousel.css',
																		'owl-carousel/sales.theme.css',
																		'owl-carousel/prod.theme.css',
																		'public/user_profile_page.css',
																		'datepicker/datepicker.min.css'
																		),
						'scripts_content'=> array(
																			'/js/magic-mask/jq.magic-mask.min.js',
																			'/js/main_page.js',
																			'/js/owl-carousel/owl.carousel.min.js',
																			'/js/datepicker/datepicker.min.js',
																			'/js/template.js',
																			'/js/profile.js'
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
						'orders' => $pageDataController['order'],
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
						 'modal_profile_edit_view.php',
						 'modal_photo_edit_view.php',
						 'modal_address_edit_view.php',
						 'modal_cart_view.php',
						 'modal_pass_check_view.php',
						 'modal_pass_new_view.php'
						 )
				);
		}
	return 0;
	}

	function action_confirmPassw()
	{
		$data = json_decode($_POST['data'], true);
		return $this->model->confirmPassw($data);
	}


	function action_forgotNew()
	{
		$data = json_decode($_POST['data'], true);
		return $this->model->forgotNew($data);
	}

	function action_history($uid=NULL)
	{
		$clean_uid = preg_replace("/[^0-9]/", '', $uid);
		if ($clean_uid != $uid) {
			Route::Catch_301_Redirect("/user/history/".$clean_uid);
		} else {
			$uid = $clean_uid;
		}
		if (!$uid && isset($_SESSION['user']['id'])) {
			Route::Catch_301_Redirect("/user/history/".$_SESSION['user']['id']);
		} else
		if (!$uid) {
				Route::Catch_Error('404');
		} else  {
			if (!isset($_SESSION['user']) || $_SESSION['user']['id'] != $uid ) {
				Route::Catch_Error('404');
			}
			$uid = Route::PrepareUrl($uid);
			$userData = $this->model->getUser($uid);
			if (!$userData) {
				Route::Catch_Error('404');
			}
			$pageDataController['user'] = $this->model->getUserData('profile', $userData);
			$pageDataController['orders'] = $this->model->getUserOrders($uid);
			$menuItems = $this->model->get_MainMenu('catalog');
			$sideNews = $this->model->getNews(CONFIG_SITE_LAST_NEWS_NUM);
			$pageDataProd = Model::getData('prods');
			$accName = 'Личный кабинет, '.$_SESSION['user']['name'];
			$accUrl = "/user/profile/".$uid;
			$breadCrumbs = array($accName => $accUrl,
													 $pageDataController['orders']['title'] => $_SERVER['REQUEST_URI']);
			$this->view->generate(
				'user_orders_view.php', // вид контента
				'template_view.php', // вид шаблона
				array( // $data
						'title'=> $pageDataController['orders']['title'],
						'style'=>'public/template.css',
						'style_content' => array(
																		'public/main_page.css',
																		'owl-carousel/owl.carousel.css',
																		'owl-carousel/sales.theme.css',
																		'owl-carousel/prod.theme.css',
																		'public/user_profile_page.css',
																		'datepicker/datepicker.min.css'
																		),
						'scripts_content'=> array(
																			'/js/magic-mask/jq.magic-mask.min.js',
																			'/js/main_page.js',
																			'/js/owl-carousel/owl.carousel.min.js',
																			'/js/datepicker/datepicker.min.js',
																			'/js/template.js',
																			'/js/profile.js'
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
						'orders' => $pageDataController['orders'],
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
						 'modal_profile_edit_view.php',
						 'modal_photo_edit_view.php',
						 'modal_address_edit_view.php',
						 'modal_cart_view.php',
						 'modal_pass_check_view.php',
						 'modal_pass_new_view.php'
						 )
				);
		}
	return 0;
	}

	function action_checkemail()
	{
		$checkemail = addslashes($_POST['data']);
		$target = Route::PrepareUrl($_POST['target']);
		if (isset($target)) {
			switch ($target) {
				case 'checkemail':
					$checkRes = false;
					$checkRes = $this->model->checkEmail($checkemail);
					if ($checkRes) {
						echo 'true';
					} else {
						echo 'false';
					}
					break;

				default:
					Route::Catch_Error('404');
					break;
			}
		}
	}

	function action_checkpass()
	{
		$checkpass = $_POST['data'];
		$target = Route::PrepareUrl($_POST['target']);
		if (isset($target)) {
			switch ($target) {
				case 'checkpass':
					$checkRes = false;
					$checkRes = $this->model->checkPass($checkpass);
					if ($checkRes) {
						echo 'true';
					} else {
						echo 'false';
					}
					break;

				default:
					Route::Catch_Error('404');
					break;
			}
		}
	}


	function action_upduser()
	{
		$target = Route::PrepareUrl($_POST['target']);
		if (isset($target)) {
			switch ($target) {
				case 'upduser':
					$upduser = json_decode($_POST['data'], true);
					$upduser['name'] = strip_tags($upduser['name']);
					$_SESSION['user']['name'] = $upduser['name'];
					$updRes = false;
					$updRes = $this->model->updUser($upduser);
					if ($updRes) {
						echo 'true';
					} else {
						echo 'false';
					}
					break;

				default:
					Route::Catch_Error('404');
					break;
			}
		}
	}

	function action_updatefavs()
	{
		$prodId = (int) $_POST['prodId'];
		$type = $_POST['type'];
		$resp = $this->model->updFavs($prodId, $type);
		echo $resp;
		return true;
	}

	function action_newpass()
	{
		$target = Route::PrepareUrl($_POST['target']);
		if (isset($target)) {
			switch ($target) {
				case 'newpass':
					$newpass = json_decode($_POST['data'], true);
					$newpassRes = false;
					$newpassRes = $this->model->updPass($newpass);
					if ($newpassRes) {
						echo 'true';
					} else {
						echo 'false';
					}
					break;

				default:
					Route::Catch_Error('404');
					break;
			}
		}
	}

	function action_reg()
	{
		Self::action_login();
	}

	function action_sendRegEmail()
	{
		$target = Route::PrepareUrl($_POST['target']);
		if (isset($target)) {
			switch ($target) {
				case 'sendRegEmail':
					$jsonData = json_decode($_POST['data'], true);
					$name = $jsonData['name'];
					$email = $jsonData['email'];
					Self::sendEmail($name,$email,'userReg');
					echo "true";
					return true;
					break;

				default:
					Route::Catch_Error('404');
					break;
			}
		}
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
						$userData['access_key'] = md5($userData['pass'].Model::SALT);
						$userData['admin_rights'] = explode(',',$userData['admin_rights']);
						if (Self::login($userData)) {
							echo "true";
						} else
						echo "false";
					} else {
						echo "false";
					}
					return true;
					break;

				case 'loginHash':
					$userData = $this->model->getLoginHash($jsonLogin);
					if ( isset($userData['id']) ) {
						$jsonUser = json_encode($userData);
						$userData['access_key'] = md5($userData['pass'].Model::SALT);
						$userData['admin_rights'] = explode(',',$userData['admin_rights']);
						if (Self::login($userData)) {
							echo '{"true": true , "uid":'.$userData['id'].'}';
						} else
						echo "false";
					} else {
						echo "false";
					}
					return true;
					break;

				case 'registration':
					$jsonReg = $jsonLogin;
					$tryReg = $this->model->tryReg($jsonReg);
					if ($tryReg === true) {
						$this->model->userReg($jsonReg);
						echo "true";
						return true;
					} else {
						echo $tryReg;
						return false;
					}
					break;

				default:
					Route::Catch_Error('404');
					break;
			}
		}
	return 0;
	}

	function action_uploadphoto()
	{
		if(isset($_FILES) && isset($_FILES['image_file'])) {

			//Переданный массив сохраняем в переменной
			$image = $_FILES['image_file'];

			// Проверяем размер файла и если он превышает заданный размер
			// завершаем выполнение скрипта и выводим ошибку
			if ($image['size'] > 20*1024*1024) {
				die('error: too large file
try less size file');
			}

			// Достаем формат изображения
			$imageFormat = explode('.', $image['name']);
			$imageFormat = $imageFormat[count($imageFormat)-1];
			// Генерируем новое имя для изображения. Можно сохранить и со старым
			// но это не рекомендуется делать
			// var_dump($_SERVER);
			$path =  $_SERVER["DOCUMENT_ROOT"]."/u_uploads/";
			$uid = $_SESSION['user']['id'];
			$u_path = $path.$uid."/";
			if (!opendir($u_path)) {
				mkdir($u_path);
			}
			$imageSelfName = md5(hash('crc32',time())) . '.' . $imageFormat;
			$imageFullName = $u_path.$imageSelfName;

			// Сохраняем тип изображения в переменную
			$imageType = $image['type'];

			// Сверяем доступные форматы изображений, если изображение соответствует,
			// копируем изображение в папку images
			$public_path = "/u_uploads/".$uid."/".$imageSelfName;
			if ($imageType == 'image/jpeg' || $imageType == 'image/png' || $imageType == 'image/gif') {
				if (move_uploaded_file($image['tmp_name'],$imageFullName)) {
					$user = $this->model->getUser($uid);
					$user = $user['profile'];
					$user['avatar'] = $public_path;
					Self::cropSqr($_SERVER["DOCUMENT_ROOT"].$public_path);
					Self::backUserUpdate($user);
					echo $public_path;
					return $public_path;
				} else {
					echo 'error2';
					return false;
				}
			} else {
					echo 'error1';
					return false;
			}
		}
	}

	function cropSqr($image)
	{
			$img = AcImage::createImage($image);
			if ($img->getWidth() > $img->getHeight()) {
				$sqrSize = $img->getHeight();
				$pointLeftTop = ceil(($img->getWidth()-$img->getHeight())/2);
				$rect = new Rectangle((int)$pointLeftTop, 0, (int)$sqrSize, (int)$sqrSize);
			} else {
				$sqrSize = $img->getWidth();
				$pointLeftTop = ceil(($img->getHeight()-$img->getWidth())/2);
				$rect = new Rectangle(0, (int)$pointLeftTop, (int)$sqrSize, (int)$sqrSize);
			}
				$img->crop($rect)->resize(200, 200);
				AcImage::setTransparency(true);
				AcImage::setBackgroundColor(255, 255, 255);
				AcImage::setRewrite(true);
				$img->save($image);
				return true;
	}

	function backUserUpdate($data)
	{
		return $this->model->updUserAva($data);
	}

	function action_addcomment()
	{
		if (!isset($_POST['comment'])) {
			Route::Catch_Error('404');
		}
		$comment = $_POST['comment'];
		$comment = json_decode($comment, true);
		$comment['text'] = addslashes($comment['text']);
		return $this->model->addComment($comment);
	}

	function action_forgot($hash)
	{
		if (Self::is_logged()) {
			Route::Catch_Error("404");
		} else {
		if ($hash != null) {
			$hash = addslashes($hash);
			$timestamp = date("Y-m-d H:i:s", strtotime('now') - 60*60*24);
			$q = mysql_query("SELECT id FROM users WHERE forgot_hash='$hash' AND forgot_timestamp > '$timestamp' LIMIT 1");
			if (mysql_num_rows($q)>0) {
				$ds = mysql_fetch_assoc($q);
				$userData = $this->model->getUser($ds['id']);
				$pageDataController = $this->model->getUserData('profile', $userData);
				$menuItems = $this->model->get_MainMenu('catalog');
				$sideNews = $this->model->getNews(CONFIG_SITE_LAST_NEWS_NUM);
				$pageDataProd = Model::getData('prods');
				$this->view->generate(
					'user_forgot_confirm_view.php', // вид контента
					'template_view.php', // вид шаблона
					array( // $data
							'title'=> 'Установка пароля',
							'style'=>'public/template.css',
							'style_content' => array(
																			'public/main_page.css',
																			'owl-carousel/owl.carousel.css',
																			'owl-carousel/sales.theme.css',
																			'owl-carousel/prod.theme.css',
																			'public/user_profile_page.css',
																			'datepicker/datepicker.min.css'
																			),
							'scripts_content'=> array(
																				'/js/magic-mask/jq.magic-mask.min.js',
																				'/js/main_page.js',
																				'/js/owl-carousel/owl.carousel.min.js',
																				'/js/datepicker/datepicker.min.js',
																				'/js/template.js',
																				'/js/profile.js'
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
							'sideNews' => $sideNews,
							'pageSales' => $pageSales['sales'],
							'menuItems' => $menuItems,
							'breads' => false,
						),
					'navigation_view.php', // навигация
					'footer_view.php', // футер
					array( // модальные окна
							 'modal_callback_view.php',
							 'modal_profile_view.php',
							 'modal_profile_edit_view.php',
							 'modal_photo_edit_view.php',
							 'modal_address_edit_view.php',
							 'modal_cart_view.php',
							 'modal_pass_check_view.php',
							 'modal_pass_new_view.php'
							 )
					);
				return true;
			} else {
				Route::Catch_Error("404");
			}
		} else {
				$menuItems = $this->model->get_MainMenu('catalog');
				$sideNews = $this->model->getNews(CONFIG_SITE_LAST_NEWS_NUM);
				$pageDataProd = Model::getData('prods');
				$this->view->generate(
					'user_forgot_new_view.php', // вид контента
					'template_view.php', // вид шаблона
					array( // $data
							'title'=> 'Восстановление аккаунта',
							'style'=>'public/template.css',
							'style_content' => array(
																			'public/main_page.css',
																			'owl-carousel/owl.carousel.css',
																			'owl-carousel/sales.theme.css',
																			'owl-carousel/prod.theme.css',
																			'public/user_profile_page.css',
																			'datepicker/datepicker.min.css'
																			),
							'scripts_content'=> array(
																				'/js/magic-mask/jq.magic-mask.min.js',
																				'/js/main_page.js',
																				'/js/owl-carousel/owl.carousel.min.js',
																				'/js/datepicker/datepicker.min.js',
																				'/js/template.js',
																				'/js/profile.js'
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
							'sideNews' => $sideNews,
							'pageSales' => $pageSales['sales'],
							'menuItems' => $menuItems,
							'breads' => false,
							// 'breadsData' => $breadCrumbs,
						),
					'navigation_view.php', // навигация
					'footer_view.php', // футер
					array( // модальные окна
							 'modal_callback_view.php',
							 'modal_profile_view.php',
							 'modal_profile_edit_view.php',
							 'modal_photo_edit_view.php',
							 'modal_address_edit_view.php',
							 'modal_cart_view.php',
							 'modal_pass_check_view.php',
							 'modal_pass_new_view.php'
							 )
					);
				return true;
		}
		}
	}

	function action_confirm($hash)
	{
		$hash = addslashes($hash);
		$timestamp = date("Y-m-d H:i:s", strtotime('now') - 60*60*24);
		$q = mysql_query("SELECT id FROM users WHERE reg_hash='$hash' AND reg_datetime > '$timestamp' LIMIT 1");
		if (mysql_num_rows($q)>0) {
			$ds = mysql_fetch_assoc($q);
			$userData = $this->model->getUser($ds['id']);
			$pageDataController = $this->model->getUserData('profile', $userData);
			$menuItems = $this->model->get_MainMenu('catalog');
			$sideNews = $this->model->getNews(CONFIG_SITE_LAST_NEWS_NUM);
			$pageDataProd = Model::getData('prods');
			$this->view->generate(
				'user_confirm_view.php', // вид контента
				'template_view.php', // вид шаблона
				array( // $data
						'title'=> 'Установка пароля',
						'style'=>'public/template.css',
						'style_content' => array(
																		'public/main_page.css',
																		'owl-carousel/owl.carousel.css',
																		'owl-carousel/sales.theme.css',
																		'owl-carousel/prod.theme.css',
																		'public/user_profile_page.css',
																		'datepicker/datepicker.min.css'
																		),
						'scripts_content'=> array(
																			'/js/magic-mask/jq.magic-mask.min.js',
																			'/js/main_page.js',
																			'/js/owl-carousel/owl.carousel.min.js',
																			'/js/datepicker/datepicker.min.js',
																			'/js/template.js',
																			'/js/profile.js'
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
						'sideNews' => $sideNews,
						'pageSales' => $pageSales['sales'],
						'menuItems' => $menuItems,
						'breads' => false,
						// 'breadsData' => $breadCrumbs,
					),
				'navigation_view.php', // навигация
				'footer_view.php', // футер
				array( // модальные окна
						 'modal_callback_view.php',
						 'modal_profile_view.php',
						 'modal_profile_edit_view.php',
						 'modal_photo_edit_view.php',
						 'modal_address_edit_view.php',
						 'modal_cart_view.php',
						 'modal_pass_check_view.php',
						 'modal_pass_new_view.php'
						 )
				);
			return true;
		} else {
			Route::Catch_Error("404");
		}
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
		$sideNews = $this->model->getNews(CONFIG_SITE_LAST_NEWS_NUM);
		$pageDataProd = Model::getData('prods');
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
																	'public/user_profile_page.css',
																	'datepicker/datepicker.min.css'
																	),
					'scripts_content'=> array(
																		'/js/magic-mask/jq.magic-mask.min.js',
																		'/js/main_page.js',
																		'/js/owl-carousel/owl.carousel.min.js',
																		'/js/datepicker/datepicker.min.js',
																		'/js/template.js',
																		'/js/profile.js'
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
					 'modal_profile_edit_view.php',
					 'modal_photo_edit_view.php',
					 'modal_address_edit_view.php',
					 'modal_cart_view.php',
					 'modal_pass_check_view.php',
					 'modal_pass_new_view.php'
					 )
			);
	return 0;
	}


	function action_cart($param=null)
	{
		switch ($param) {
			case 'send':
				Self::sendOrder();
				break;

			case 'sendOrder':
				Self::sendOrderPost();
				break;

			default:
				Self::showCart();
				break;
		}
	}


	function sendOrderPost()
	{
		$order = $_POST['order'];
		$order = json_decode($order, true);
		$this->model->addOrder($order);
		return 0;
	}

	function action_recall()
	{
		if (isset($_POST['target'])) {
			if ($_POST['target'] == 'recall') {
				$sendData = json_decode($_POST['data'], true);
				$uid = $_SESSION['user']['id'];
				$name = addslashes($sendData['name']);
				$phone = addslashes($sendData['phone']);
				$q = mysql_query("INSERT INTO recall (name, phone, uid) VALUES ('$name', '$phone', '$uid')") or die(mysql_error());
				Controller::sendEmail("","","newRecall", $sendData);
				// $msg = Controller::createMsg('callback',$sendData);
				// Controller::sendMsg('admin', $msg);
				return true;
			}
		} else {
			return false;
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
			$pageDataController = $this->model->getUserData('sendOrder');
			$breadCrumbs = array('Корзина' => '/user/cart', $pageDataController['title'] => $_SERVER['REQUEST_URI']);
		}
		$menuItems = $this->model->get_MainMenu('catalog');
		$pageDataProd = Model::getData('prods');
		$sideNews = $this->model->getNews(CONFIG_SITE_LAST_NEWS_NUM);

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
			$pageDataController = $this->model->getUserData('cart');
			$breadCrumbs = array($pageDataController['title'] => $_SERVER['REQUEST_URI']);
		}
		$menuItems = $this->model->get_MainMenu('catalog');
		$sideNews = $this->model->getNews(CONFIG_SITE_LAST_NEWS_NUM);
		$pageDataProd = Model::getData('prods');

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
					'sideNews' => $sideNews,
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
		if (isset($_SESSION['user']['id'])) {
			$is_banned = $this->model->getUser($_SESSION['user']['id']);
			if ($is_banned['profile']['banned']=='1') {
				echo "banned ";
				session_destroy();
				return false;
			}
		}
		$isLogged = Self::is_logged();
		return $isLogged;
	}

	function action_logout()
	{
		if (isset($_SESSION['user'])) {
			unset($_SESSION['user']);
			session_destroy();
			header('location:/');
		} else {
			Route::Catch_Error('404');
		}
	return 0;
	}

}