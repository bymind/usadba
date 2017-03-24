<?php

class Controller_Admin extends Controller
{

	function __construct()
	{
		$this->model = new Model_Admin();
		$this->view = new View();
		$this->userId = "";

		// подключаем Telegram
		// include 'app/controllers/telegram/controller_telegram.php';
		// $this->tlgrm = new Telegram();
	}

	function isSuper()
	{
		if ($_SESSION['user']['is_super']==1) {
			return true;
		} else return false;
	}


/*																		 LOGIN
*************************************************************************************/
	function adminNoLogin()
	{

	}

	function adminLogin($redirect = "")
	{
		$this->view->generate(
					'admin/login_view.php',
					'template_admin_view.php',
					array(
							'title'=>'Login',
							'style'=>'admin/template.css',
							'style_content'=>'admin/login.css',
							//'posts'=>$ds,
							'Favicon' => 'app/views/admin-favicon.php',
							'redirect' => $redirect,
						),
					'admin/navigation_login_view.php',
					'admin/footer_view.php'
					);
	}



/*																			 MAIN
*************************************************************************************/
	function adminMain()
	{
		$this->view->generate(
					'admin/main_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Рабочий стол',
							'style'=>'admin/template.css',
							'style_content'=>'admin/main.css',
							//'posts'=>$ds,
							'active_menu_item' => 'home',
							'actual_title' => 'Рабочий стол',
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					'admin/modals_main_view.php'
					);
	}



/*																	 WORKTABLE
*************************************************************************************/
	function adminWorktable()
	{
		$this->view->generate(
					'admin/worktable_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Рабочий стол',
							'style'=>'admin/template.css',
							'style_content'=>'admin/main.css',
							'active_menu_item' => 'home',
							'actual_title' => 'Рабочий стол',
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					'admin/modals_main_view.php'
					);
	}


/*																		 PRODUCTS
*************************************************************************************/
/*	 PRODUCTS INIT
**********************************/
	function adminGoods($params = '')
	{
		if ($params == '') {
			$params['name'] = 'default';
		}
		if (is_array($params))
					{
						extract($params);
					}
				else
					$name = $params;

		switch ($name) {
			case 'edit':
				self::adminProductEdit($value);
				break;

			case 'delete':
				self::adminProductsDelete($value);
				break;

			case 'archived':
				self::adminProductsArchived();
				break;

			case 'archive':
				self::adminProductsArchive($value);
				break;

			case 'unarchive':
				self::adminProductsUnArchive($value);
				break;

			case 'new':
				self::adminProductsNew();
				break;

			case 'save':
				self::adminProductsSave();
				break;

			case 'cat':
			case 'default':
				include '/app/models/model_catalog.php';
				$actual_title = 'Товары';
				$title= ' - Товары';
				if (isset($value)) {
					$ds = $this->model->getGoodsLists($value);
					// $ds = Model_Catalog::getCategoryData($value);
					$actual_title = "<a href='/admin/goods'>Товары (все)</a>";
					$second_title = $ds[0]['cat_title'];
					$title .= " - ".$second_title;
				} else
				$ds = $this->model->getGoodsLists();
				$ds = Model::createProdUrl($ds);
				$q = mysql_query("SELECT * FROM prod_cat ORDER BY id") or die(mysql_error());
				while ( $buf = mysql_fetch_assoc($q)) {
					$prod_cats[$buf['id']] = $buf;
				}
				unset($q);
				$cat_tree = $this->model->createCatTree($prod_cats);
				unset($prod_cats);
				$this->view->generate(
							'admin/products_view.php',
							'admin/template_admin_view.php',
							array(
									'title'=>$title,
									'style'=>'admin/template.css',
									'style_content'=>'admin/goods.css',

									'goods'=>$ds,
									'cat_tree'=>$cat_tree,
									'active_menu_item' => 'goods',
									'actual_title' => $actual_title,
									'second_title' => $second_title,
									'btns' => array(
																	'new-post' => 'Добавить товар',
																	),
							'Favicon' => 'app/views/admin-favicon.php',
								),
							'admin/navigation_view.php',
							'admin/footer_view.php',
							'admin/modals_main_view.php'
							);
				break;

			default :
				$ds = $this->model->getGoodsLists();
				$ds = Model::createProdUrl($ds);
				$this->view->generate(
							'admin/products_view.php',
							'admin/template_admin_view.php',
							array(
									'title'=>' - Товар',
									'style'=>'admin/template.css',
									'style_content'=>'admin/goods.css',

									'goods'=>$ds,
									'active_menu_item' => 'goods',
									'actual_title' => 'Товары',
									//'second_title' => 'Записи статей',
									'btns' => array(
																	'new-post' => 'Добавить товар',
																	),
							'Favicon' => 'app/views/admin-favicon.php',
								),
							'admin/navigation_view.php',
							'admin/footer_view.php',
							'admin/modals_main_view.php'
							);
				break;
		}

	}



/*	 PRODUCTS ARCHIVED LIST
**********************************/
	function adminProductsArchived()
	{
		$prods = $this->model->getGoodsPostsArchived();
		$this->view->generate(
					'admin/products_archived_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Скрытые товары',
							'style'=>'admin/template.css',
							'style_content'=>'admin/goods.css',
							'products'=>$prods,
							'active_menu_item' => 'goods',
							'actual_title' => 'Товары (скрытые)',
							//'second_title' => 'Записи в блоге',
							'btns' => array(
															'new-post' => 'Добавить товар',
															),
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					'admin/modals_main_view.php'
					);
	}



/*	 PRODUCTS -> to archive
**********************************/
	function adminProductsArchive($value)
	{
		$postArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->archivePost($postArrive);
	}



/*	 PRODUCTS -> unarchive
**********************************/
	function adminProductsUnArchive($value)
	{
		$postArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->unarchivePost($postArrive);
	}



/*	 PRODUCT NEW
**********************************/
	function adminProductsNew()
	{
		$this->view->generate(
					'admin/products_new_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Добавление товара',
							'style'=>'admin/template.css',
							'style_content'=>'admin/goods.css',
							/*'post'=>array (
														 'id' => $post['id'],
														 'url' => $post['url'],
														 'title' => htmlspecialchars($post['title']) ,
														 'poster' => $post['poster'],
														 'date' => $post['date'],
														 'anons' => htmlspecialchars($post['anons']),
														 'text' => htmlspecialchars($post['text']),
														 'tags' => $post['tags'],
														),*/
							'access_key' => $_SESSION['user']['access_key'],
							'active_menu_item' => 'goods',
							'actual_title' => '<a href="/admin/goods">Товары</a>',
							'second_title' => 'Добавление товара',
							'btns' => array(
															'post-save new' => 'Сохранить',
															'post-abort' => 'Отмена',
															//'post-delete' => 'Удалить',
															'post-archive btn-archive-new' => 'Сохранить как скрытый',
															),
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					'admin/modals_main_view.php'
					);
	}



/*	 PRODUCT EDIT
**********************************/
	function adminProductEdit($art)
	{
		$product = $this->model->getProductItem($art);
		if ($product['archived']==0) {
			$archive_class = 'btn-archive';
			$archive_text = 'Скрыть товар';
			$actual_title = '<a href="/admin/goods">Товары</a>';
		} else
		{
			$archive_class = 'btn-unarchive';
			$archive_text = 'Опубликовать';
			$actual_title = '<a href="/admin/goods/archived">Товары (скрытые)</a>';
		}

		$this->view->generate(
					'admin/product_edit_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Редактирование товара - '.htmlspecialchars($product['title']),
							'style'=>'admin/template.css',
							'style_content'=>'admin/goods.css',
							'post'=>array (
														 'id' => $product['id'],
														 'url' => $product['url'],
														 'art' => $product['art'],
														 'title' => htmlspecialchars($product['name']) ,
														 'subtitle' => htmlspecialchars($product['mini_desc']) ,
														 'poster' => $product['images'],
														 'date' => $product['added_time'],
														 'anons' => htmlspecialchars($product['mini_desc']),
														 'text' => htmlspecialchars($product['description']),
														 //'tags' => $post['tags'],
														),
							'access_key' => $_SESSION['user']['access_key'],
							'active_menu_item' => 'goods',
							'actual_title' => $actual_title,
							'second_title' => 'Редактирование товара',
							'btns' => array(
															'post-save edit' => 'Сохранить',
															'post-abort' => 'Отмена',
															'post-delete btn-delete' => 'Удалить',
															'post-archive '.$archive_class => $archive_text,
															),
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					'admin/modals_main_view.php'
					);
	}



/*	 PRODUCT SAVE
**********************************/
	function adminProductsSave()
	{
		if (!isset($_POST['action'])) {
			return "Не указан параметр action";
		}
		if (!isset($_POST['jsonPost'])) {
			return "Параметры не переданы";
		}
		switch ($_POST['action']) {
			case 'edit':
					$postArrive = json_decode($_POST['jsonPost'], true);
					return $this->model->updatePost($postArrive);
				break;

			case 'new':
					$postArrive = json_decode($_POST['jsonPost'], true);
					return $this->model->newPost($postArrive);
				break;

			default:
				# code...
				break;
		}
	}



/*	 PRODUCT DELETE
**********************************/
	function adminProductsDelete($value)
	{
		$postArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->deletePost($postArrive);
	}




/*																		 ARTICLES
*************************************************************************************/
/*	 ARTICLES INIT
**********************************/

	function adminArticles($params = '')
	{
		if ($params == '') {
			$params['name'] = 'default';
		}
		if (is_array($params))
					{
						extract($params);
					}
				else
					$name = $params;

		switch ($name) {
			case 'edit':
				self::adminArticlesEdit($value);
				break;

			case 'delete':
				self::adminArticlesDelete($value);
				break;

			case 'archived':
				self::adminArticlesArchived();
				break;

			case 'archive':
				self::adminArticlesArchive($value);
				break;

			case 'unarchive':
				self::adminArticlesUnArchive($value);
				break;

			case 'new':
				self::adminArticlesNew();
				break;

			case 'save':
				self::adminArticlesSave();
				break;

			default :
				$ds = $this->model->getArticlesLists();
				$this->view->generate(
							'admin/articles_view.php',
							'admin/template_admin_view.php',
							array(
									'title'=>' - Статьи',
									'style'=>'admin/template.css',
									'style_content'=>'admin/articles.css',

									'posts'=>$ds,
									'active_menu_item' => 'articles',
									'actual_title' => 'Статьи',
									//'second_title' => 'Записи статей',
									'btns' => array(
																	'new-post' => 'Новая запись',
																	),
							'Favicon' => 'app/views/admin-favicon.php',
								),
							'admin/navigation_view.php',
							'admin/footer_view.php',
							'admin/modals_main_view.php'
							);
				break;
		}

	}



/*	 ARTICLES ARCHIVED LIST
**********************************/
	function adminArticlesArchived()
	{
		$posts = $this->model->getArticlesPostsArchived();
		$this->view->generate(
					'admin/articles_archived_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Архив статей',
							'style'=>'admin/template.css',
							'style_content'=>'admin/articles.css',
							'posts'=>$posts,
							'active_menu_item' => 'articles',
							'actual_title' => 'Статьи (архив)',
							//'second_title' => 'Записи в блоге',
							'btns' => array(
															'new-post' => 'Новый пост',
															),
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					'admin/modals_main_view.php'
					);
	}



/*	 ARTICLES -> to archive
**********************************/
	function adminArticlesArchive($value)
	{
		$postArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->archivePost($postArrive);
	}



/*	 ARTICLES -> unarchive
**********************************/
	function adminArticlesUnArchive($value)
	{
		$postArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->unarchivePost($postArrive);
	}



/*	 ARTICLES NEW
**********************************/
	function adminArticlesNew()
	{
		$this->view->generate(
					'admin/articles_new_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Новая статья',
							'style'=>'admin/template.css',
							'style_content'=>'admin/articles.css',
							/*'post'=>array (
														 'id' => $post['id'],
														 'url' => $post['url'],
														 'title' => htmlspecialchars($post['title']) ,
														 'poster' => $post['poster'],
														 'date' => $post['date'],
														 'anons' => htmlspecialchars($post['anons']),
														 'text' => htmlspecialchars($post['text']),
														 'tags' => $post['tags'],
														),*/
							'access_key' => $_SESSION['user']['access_key'],
							'active_menu_item' => 'articles',
							'actual_title' => '<a href="/admin/articles">Статьи</a>',
							'second_title' => 'Новая статья',
							'btns' => array(
															'post-save new' => 'Сохранить',
															'post-abort' => 'Отмена',
															//'post-delete' => 'Удалить',
															'post-archive btn-archive-new' => 'Сохранить в архив',
															),
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					'admin/modals_main_view.php'
					);
	}



/*	 ARTICLES EDIT
**********************************/
	function adminArticlesEdit($post_url)
	{
		$post = $this->model->getArticlesPost($post_url);
		if ($post['archived']==0) {
			$archive_class = 'btn-archive';
			$archive_text = 'В архив';
			$actual_title = '<a href="/admin/articles">Статьи</a>';
		} else
		{
			$archive_class = 'btn-unarchive';
			$archive_text = 'Опубликовать';
			$actual_title = '<a href="/admin/articles/archived">Статьи(архив)</a>';
		}

		$this->view->generate(
					'admin/articles_edit_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Редактирование статьи - '.htmlspecialchars($post['title']),
							'style'=>'admin/template.css',
							'style_content'=>'admin/articles.css',
							'post'=>array (
														 'id' => $post['id'],
														 'url' => $post['url'],
														 'title' => htmlspecialchars($post['title']) ,
														 'subtitle' => htmlspecialchars($post['subtitle']) ,
														 'poster' => $post['poster'],
														 'date' => $post['datetime'],
														 'anons' => htmlspecialchars($post['anons']),
														 'text' => htmlspecialchars($post['body']),
														 //'tags' => $post['tags'],
														),
							'access_key' => $_SESSION['user']['access_key'],
							'active_menu_item' => 'articles',
							'actual_title' => $actual_title,
							'second_title' => 'Правка статьи',
							'btns' => array(
															'post-save edit' => 'Сохранить',
															'post-abort' => 'Отмена',
															'post-delete btn-delete' => 'Удалить',
															'post-archive '.$archive_class => $archive_text,
															),
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					'admin/modals_main_view.php'
					);
	}



/*	 ARTICLES SAVE
**********************************/
	function adminArticlesSave()
	{
		if (!isset($_POST['action'])) {
			return "Не указан параметр action";
		}
		if (!isset($_POST['jsonPost'])) {
			return "Параметры не переданы";
		}
		switch ($_POST['action']) {
			case 'edit':
					$postArrive = json_decode($_POST['jsonPost'], true);
					return $this->model->updatePost($postArrive);
				break;

			case 'new':
					$postArrive = json_decode($_POST['jsonPost'], true);
					return $this->model->newPost($postArrive);
				break;

			default:
				# code...
				break;
		}
	}



/*	 ARTICLES DELETE
**********************************/
	function adminArticlesDelete($value)
	{
		$postArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->deletePost($postArrive);
	}



/*																		 PAGES
*************************************************************************************/
	function adminPages()
	{
		$this->view->generate(
					'admin/main_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Страницы',
							'style'=>'admin/template.css',
							'style_content'=>'admin/main.css',
							//'posts'=>$ds,
							'active_menu_item' => 'pages',
							'actual_title' => 'Страницы',
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php'
					);
	}



/*																		 FILES
*************************************************************************************/
	function adminFiles()
	{
		$this->view->generate(
					'admin/main_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Файловый менеджер',
							'style'=>'admin/template.css',
							'style_content'=>'admin/main.css',
							//'posts'=>$ds,
							'active_menu_item' => 'files',
							'actual_title' => 'Файловый менеджер',
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php'
					);
	}



/*																		 USERS
*************************************************************************************/
/*	 USERS INIT
**********************************/
	function adminUsers($params = "")
	{
		if ($params == '') {
			$params['name'] = 'default';
		}
		if (is_array($params))
			extract($params);
		else
			$name = $params;

		switch ($name) {
			case 'addnew':
				self::adminUsersNew();
				break;

			case 'delete':
				self::adminUsersDelete();
				break;

			case 'issuper':
				self::adminUserIssuper();
				break;

			case 'default':
				$users = $this->model->getUsers();
				$this->view->generate(
							'admin/users_view.php',
							'admin/template_admin_view.php',
							array(
									'title'=>' - Аккаунты',
									'style'=>'admin/template.css',
									'style_content'=>'admin/users.css',
									'users'=>$users,
									'active_menu_item' => 'users',
									'actual_title' => 'Аккаунты',
									'btns' => array(
																	'btn-primary btn-new-user' => 'Создать аккаунт',
																	),
							'Favicon' => 'app/views/admin-favicon.php',
								),
							'admin/navigation_view.php',
							'admin/footer_view.php',
							'admin/modals_users_view.php'
							);
				break;

				default:
					$user = Model_Admin::getUserByName($name);
					if (!$user) {

						$user = $this->model->getUser($name);
					}
					$this->view->generate(
								'admin/users_user_view.php',
								'admin/template_admin_view.php',
								array(
										'title'=>' - Аккаунты - '.$user['login'],
										'style'=>'admin/template.css',
										'style_content'=>'admin/users.css',
										'user'=>$user,
										'active_menu_item' => 'users',
										'actual_title' => '<a href="/admin/users">Аккаунты</a>',
										'second_title' => $user['login'],
								'Favicon' => 'app/views/admin-favicon.php',
									),
								'admin/navigation_view.php',
								'admin/footer_view.php',
								'admin/modals_users_view.php'
								);
					break;
		}

	}


/*	 USERS ISSUPER
**********************************/
	function adminUserIssuper()
	{
		$user = $this->model->userIsSuper();
		echo $user['is_super'];
	}



/*	 USERS NEW
**********************************/
	function adminUsersNew()
	{
		$newUser = json_decode($_POST['newUserData'], true);
		return $this->model->addNewUser($newUser);
	}



/*	 USERS DELETE
**********************************/
	function adminUsersDelete()
	{
		$deleteUser = $_POST['id'];
		return $this->model->deleteUser($deleteUser);
	}



/*																		 CONFIG
*************************************************************************************/
	function adminConfig()
	{
		$this->view->generate(
					'admin/main_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Настройки',
							'style'=>'admin/template.css',
							'style_content'=>'admin/main.css',
							//'posts'=>$ds,
							'active_menu_item' => 'config',
							'actual_title' => 'Настройки',
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php'
					);
	}



/*																		 BUGTRACKER
*************************************************************************************/
/*	 BUGTRACKER INIT
**********************************/
	function adminBugtracker($params = '')
	{
		if ($params == '') {
			$params['name'] = 'all';
		}

		if (is_array($params))
					{
						extract($params);
					}
				else
					$name = $params;

		if ($name == "setdoer") {
			self::adminBugtrackerSetDoer();
		} else
		if ($name == 'count')
		{
			if ((isset($value))&&($value != "")) {
				self::adminBugtrackerCount($value);
			} else
			if ((isset($value))&&($value == "")||(!isset($value))) {
				self::adminBugtrackerCount('all');
			} else {
				Route::Catch_Error('404');
			}
		} else
		if ($name=="createTicket")
		{
				self::adminBugtrackerCreateTicket();
		} else
		if ($name=="ticket")
		{
			if ((isset($value))&&($value != "")) {
				self::adminBugtrackerShowTicket($value);
			} else
			if ((isset($value))&&($value == "")||(!isset($value))) {
				self::adminBugtrackerShowTicket('last');
			} else {
				Route::Catch_Error('404');
			}
		} else
		if ($name=="deleteTicket")
		{
			self::adminBugtrackerDelete();
		} else
		if ($name=="restoreTicket")
		{
			self::adminBugtrackerRestore();
		} else
		if ($name=="getLastTicket")
		{
			self::adminBugtrackerTicketForList();
		} else
		if ($name=="ajaxTickets")
		{
			self::adminBugtrackerAjaxTickets($value, $personal, $personalMy);
		} else
			self::adminBugtrackerGetTickets($name);

	}

	/**
	**
	**
	*/
	function adminBugtrackerSetDoer()
	{
		if (isset($_POST['action']) && ($_POST['action'] == "setDoer") && isset($_POST['jsonData']) ) {
			$ticketDoer = json_decode($_POST['jsonData'], true);
			echo $this->model->setDoerTicket($ticketDoer);
			// echo $ticketDoer['uid'];
			return true;
		} else {
			echo "nope!";
			return false;
		}
	}


/*	 BUGTRACKER ONE TICKET PAGE
**********************************/
	function adminBugtrackerShowTicket($value="last")
	{
		$ticket = $this->model->getOneTicket($value);

		if ($ticket == 'no ticket') {
			$ticket1['id']=$value;
			$ticket1['status']='null';
			self::adminBugtrackerShowTicketU($ticket1);
		} else
		if ($ticket['archived']==1) {
			self::adminBugtrackerShowArchivedTicket($ticket);
		} else
		self::adminBugtrackerShowTicketU($ticket);
	}



/*	 BUGTRACKER ONE TICKET - FOR LIST
**********************************/
	function adminBugtrackerTicketForList()
	{
		$ticket = $this->model->getOneTicket();
		$api = $this->tlgrm;
		self::sendMeTelegram("Хэй, Кеша 😊\r\nНовый тикет😱 от ".$ticket['author']."!\r\nВот -> ".$_SERVER['HTTP_HOST']."/admin/bugtracker/ticket/".$ticket['id']);
		$this->view->simpleGet(
						'/admin/bugtracker_ticket_for_list_view.php',
						array(
									'ticket'=>$ticket,
									)
		);
	}



/*	 BUGTRACKER ONE TICKET PAGE - UNIVERSAL METHOD
**********************************/
	function adminBugtrackerShowTicketU($ticket)
	{
				$this->view->generate(
							'admin/bugtracker_ticket_'.$ticket['status'].'_view.php',
							'admin/template_admin_view.php',
							array(
									'title'=>' - Баг-трекер | #'.$ticket['id'],
									'style'=>'admin/template.css',
									'style_content'=>'admin/bugtracker.css',
									//'posts'=>$ds,
									'active_menu_item' => 'bugtracker',
									'actual_title' => '<a href="/admin/bugtracker">Баг-трекер</a>',
									'second_title' => 'Тикет #'.$ticket['id'],
									'Favicon' => 'app/views/admin-favicon.php',
									'btns' => array(
																			),
									'ticket' => $ticket,
								),
							'admin/navigation_view.php',
							'admin/footer_view.php'
							);
	}



/*	 BUGTRACKER ONE TICKET PAGE - ARCHIVED TICKET
**********************************/
	function adminBugtrackerShowArchivedTicket($ticket)
	{
				$this->view->generate(
							'admin/bugtracker_ticket_archived_view.php',
							'admin/template_admin_view.php',
							array(
									'title'=>' - Баг-трекер | #'.$ticket['id'],
									'style'=>'admin/template.css',
									'style_content'=>'admin/bugtracker.css',
									//'posts'=>$ds,
									'active_menu_item' => 'bugtracker',
									'actual_title' => '<a href="/admin/bugtracker">Баг-трекер</a>',
									'second_title' => 'Тикет #'.$ticket['id'],
									'Favicon' => 'app/views/admin-favicon.php',
									'btns' => array(
																			),
									'ticket' => $ticket,
								),
							'admin/navigation_view.php',
							'admin/footer_view.php'
							);
	}



/*	 BUGTRACKER DELETE TICKET
**********************************/
	function adminBugtrackerDelete()
	{
		$id = $_POST['id'];
		$reason = $_POST['reason'];
		$action = $_POST['action'];
		if ((!isset($action))||(!$action == 'deleteTicket')) {
			echo "Fatal Error: action not arrived.";
			return false;
		}
		$answ = $this->model->deleteTicket($id, $reason);
		echo $answ;
	}



/*	 BUGTRACKER RESTORE TICKET
**********************************/
	function adminBugtrackerRestore()
	{
		$id = $_POST['id'];
		$action = $_POST['action'];
		if ((!isset($action))||(!$action == 'restoreTicket')) {
			echo "Fatal Error: action not arrived.";
			return false;
		}
		$answ = $this->model->restoreTicket($id);
		echo $answ;
	}



/*	 BUGTRACKER COUNT
**********************************/
	function adminBugtrackerCount($type="all")
	{
		$counted = $this->model->countTickets($type);
		echo $counted;
	}


	function adminBugtrackerAjaxTickets($type='all', $personal='0', $personalMy='0')
	{
		$tickets = $this->model->getBugTicketsAjax($type, $personal, $personalMy);
		$this->view->simpleGet(
									'admin/bugtracker_cards_list_view.php',
									array(
												'tickets' => $tickets,
												)
		);
	}


/*	 BUGTRACKER CREATE PAGE
**********************************/
	function adminBugtrackerGetTickets($type="all")
	{
		$tickets = $this->model->getBugTickets($type);
		$this->view->generate(
							'admin/bugtracker_view.php',
							'admin/template_admin_view.php',
							array(
									'title'=>' - Баг-трекер',
									'style'=>'admin/template.css',
									'style_content'=>'admin/bugtracker.css',
									//'posts'=>$ds,
									'active_menu_item' => 'bugtracker',
									'actual_title' => 'Баг-трекер',
									'Favicon' => 'app/views/admin-favicon.php',
									'btns' => array(
																			),
									'tickets' => $tickets,
								),
							'admin/navigation_view.php',
							'admin/footer_view.php'
							);
	}



/*	 BUGTRACKER CREATE NEW TICKET
		@todo НЕДОПИСАНО
**********************************/
	function adminBugtrackerCreateTicket()
	{

		if (!isset($_POST['action'])) {
			echo "Не указан параметр action";
			// return false;
		}
		if (!isset($_POST['jsonTicket'])) {
			echo "Параметры не переданы";
			return false;
		}
		echo $_POST['action'];
		switch ($_POST['action']) {
			case 'addNewTicket':
					$ticketArrive = json_decode($_POST['jsonTicket'], true);
					return $this->model->newTicket($ticketArrive);
				break;

			default:
				# code...
				break;
		}
	}



/*
** ------------- Session actions --------------------
*/


	function adminSession()
	{
		if (!isset($_POST['action'])) {
			return Route::Catch_Error('404');
		} else {
			$action = $this->model->prepareToDB($_POST['action']);
			switch ($action) {
				case 'setSession':
					$name = $this->model->prepareToDB($_POST['name']);
					$value = $this->model->prepareToDB($_POST['value']);
					Self::setSession($name, $value);
					break;

				case 'getSession':
					$name = $this->model->prepareToDB($_POST['name']);
					Self::getSession($name);
					break;

				case 'deleteSession':
					$name = $this->model->prepareToDB($_POST['name']);
					Self::deleteSession($name);
					break;

				default:
					# code...
					break;
			}
		}
	}


	function setSession($name=NULL, $value=NULL)
	{
		if (isset($name) && isset($value)) {
			$_SESSION[$name] = $value;
			echo 'установлено $_SESSION["'.$name.'"] = '.$value;
		} else {
			Route::Catch_Error('404');
		}
	}


	function getSession($name=NULL)
	{
		if (isset($name)) {
			if (isset($_SESSION[$name])) {
				echo "$_SESSION[$name]";
			} else Route::Catch_Error('404');
		} else Route::Catch_Error('404');
	}


	function deleteSession($name=NULL)
	{
		if (isset($name)) {
			if (isset($_SESSION[$name])) {
				unset($_SESSION[$name]);
				echo 'удалено $_SESSION["'.$name.'"]';
			} else Route::Catch_Error('404');
		} else Route::Catch_Error('404');
	}

/*
** ------------- ACTIONS --------------------
*/
	function action_index($param = null)
	{
		switch ($param) {
			case 'login':
				self::adminLogin();
				break;
			case 'main':
				self::adminWorktable();
				break;
			case '':
			if (Controller::is_admin())
				{
					self::adminWorktable();
				} else
						{
							self::adminLogin();
						}
				break;
			default:
				Route::Catch_Error('404');
				//self::action_main($param);
				break;
		}

	}



	function action_login()
	{
		if (Controller::is_admin())
		{
			self::action_main();
		} else
				{
					self::adminLogin();
				}
	}



	function action_showtime()
	{
		echo date("H-1")."<span class='poink'>:</span>".date("i");
	}



	function action_gologin()
	{
		$urlR = $_SERVER['REQUEST_URI'];
		if (Controller::goLogin($urlR))
		{
			//self::adminMain();
		} else
			{
				self::adminNoLogin();
			}
	}



	function action_logout()
	{
		Controller::logout();
	}



	function action_main()
	{
		if (Controller::is_admin())
		{
			self::adminWorktable();
		} else
		{
			self::adminLogin();
		}
	}


	function action_session()
	{
		if (Controller::is_admin())
		{
			self::adminSession();
		} else
		{
			self::adminLogin();
		}
	}


	function action_blog($params = '')
	{
		if (Controller::is_admin())
		{
			self::adminBlog($params);
		} else
		{
			self::adminLogin();
		}
	}



	function action_goods($params = '')
	{
		if (Controller::is_admin())
		{
			self::adminGoods($params);
		} else
		{
			self::adminLogin();
		}
	}


	function action_articles($params = '')
	{
		if (Controller::is_admin())
		{
			self::adminArticles($params);
		} else
		{
			self::adminLogin();
		}
	}



	function action_pages()
	{
		if (Controller::is_admin())
		{
			self::adminPages();
		} else
		{
			self::adminLogin();
		}
	}



	function action_files()
	{
		if (Controller::is_admin())
		{
			self::adminFiles();
		} else
		{
			self::adminLogin();
		}
	}



	function action_users($params = '')
	{
		if (Controller::is_admin())
		{
			self::adminUsers($params);
		} else
		{
			self::adminLogin();
		}
	}


	function action_getuserbyname()
	{
		$param = $_POST['username'];
		if (Controller::is_admin()) {
			return Model_Admin::getUserByName($param);
		} else {
			return false;
		}
	}


	function action_config()
	{
		if (Controller::is_admin())
		{
			self::adminConfig();
		} else
		{
			self::adminLogin();
		}
	}



	function action_bugtracker($params = '')
	{
		if (Controller::is_admin())
		{
			self::adminBugtracker($params);
		} else
		{
			self::adminLogin();
		}
	}


	function action_ajaxTickets($params = '')
	{
		if (Controller::is_admin())
		{
			if (isset($_POST['action']) && ($_POST['action']=='ajaxTickets')) {
				$params['name'] = 'ajaxTickets';
				$params['target'] = $_POST['path'];
				$params['personal'] = $_POST['personal'];
				$params['personalMy'] = $_POST['personalMy'];
			self::adminBugtracker($params);
			}
		} else
		{
			self::adminLogin();
		}
	}



/*																		 TELEGRAM
**********************************************************************************
*/



	function action_telegramcall($params="")
	{
		$content = file_get_contents('php://input');
		$upd = json_decode($content, true);
		$api = $this->tlgrm;
		if ($params==$api->token){ // @macfix_eve_bot
			if (!isset($upd['message'])) {
				// return false;
			} else{
				extract($upd);
					// self::sendMsg($message);
					$api->sendMsgToBot($message);
			}
		}
	}

	function action_telegram()
	{
		if (Controller::is_admin())
		{
			self::adminTelegram();
		} else
		{
			self::adminLogin();
		}
	}

	function adminTelegram()
	{
		if (self::isSuper()) {
			$this->tlgrm->sendMessage(89691650, "Привет, Kesha! \r\nАдминка https://".$_SERVER['HTTP_HOST']."/admin");
			echo "Ева(@macfix_eve_bot) отправила привет Кеше(@keshapudelev)";
		} else {
			echo "Access denied!";
		}
	}

	function sendMeTelegram($msg = "")
	{
		$this->tlgrm->sendMessage(89691650, $msg);
	}

}
