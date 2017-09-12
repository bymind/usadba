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
		Route::Catch_Error("404");

		// header('location:/');

		// $this->view->generate(
		// 			'admin/login_view.php',
		// 			'template_admin_view.php',
		// 			array(
		// 					'php_header' => "Location:/",
		// 					'title'=>'Login',
		// 					'style'=>'admin/template.css',
		// 					'style_content'=>'admin/login.css',
		// 					//'posts'=>$ds,
		// 					'Favicon' => 'app/views/admin-favicon.php',
		// 					'redirect' => $redirect,
		// 				),
		// 			'admin/navigation_login_view.php',
		// 			'admin/footer_view.php'
		// 			);
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
							'active_menu_item' => 'home',
							'actual_title' => 'Рабочий стол',
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					'admin/modals_main_view.php'
					);
	}



/*																	 NOT ENOUTH RIGHTS
*************************************************************************************/
	function notEnouthRights()
	{
		$user = $this->model->getAdminUser($_SESSION['user']['id']);
		$this->view->generate(
					'admin/not_enouth_rights_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - нет доступа',
							'style'=>'admin/template.css',
							'style_content'=>'admin/main.css',
							'active_menu_item' => 'home',
							'user' => $user,
							'actual_title' => 'Нет доступа',
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
		$orders = $this->model->getOrders('last', 20);
		$curPage = 1;
		$recalls = $this->model->getNewRecalls();
		$ordersCounter = $this->model->countOrders('actual');
		$pagesCount = ceil( $ordersCounter['all'] / 20);
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
							'orders' => $orders,
							'ordersCounter' => $ordersCounter,
							'recalls' => $recalls,
							'curPage' => $curPage,
							'pagesCount' => $pagesCount
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					'admin/modals_main_view.php'
					);
	}

	function action_recallOk()
	{
		$recall = json_decode($_POST['data'],true);
		$recallId = $recall['id'];
		$q = mysql_query("UPDATE recall SET new = 0 WHERE id = '$recallId' ") or die(mysql_error());
		return $q;
	}

	function action_getrecall()
	{
		if (Controller::is_admin())
		{
			$q = mysql_query("SELECT * FROM recall WHERE new=1 ORDER BY datetime DESC") or die(mysql_error());
			while ($r = mysql_fetch_assoc($q)) {
				$r['good_time'] = Controller::getGoodDate($r['datetime']);
				$ds[] = $r;
			}
			if (count($ds) > 0) {
			$recallHtml = '<table class="table table-striped table-bordered">';
			foreach ($ds as $recall) {
				$recallHtml .= '<tr>
				<td style="padding: 8px 0">
					<div class="col-xs-12" style="font-size: .8em">
						'.$recall['good_time'].'
					</div>
					<div class="col-xs-6 col-md-5 recall-line-div">
						<b>'.$recall['name'].'</b>
					</div>
					<div class="col-xs-6 col-md-5 recall-line-div">
						<nobr><b>'.$recall['phone'].'</b></nobr>
					</div>
					<div class="col-xs-4 col-xs-offset-8 col-md-2 col-md-offset-0">
						<button style="float:right; margin:10px 0" class="btn btn-primary btn-sm recall-done" data-recallid="'.$recall['id'].'">Ок</button>
					</div>
				</td>
				</tr>';
			}
			$recallHtml .= '</table>';
			} else {
				$recallHtml = "Нет новых заявок";
			}
			echo $recallHtml;
			return $recallHtml;
		} else
		{
			Self::adminLogin();
		}
	}

/*																		 ORDERS
*************************************************************************************/
	function adminOrders($params)
	{
		if ($params == '') {
			$params['name'] = 'default';
		}
		if (is_array($params))
			{
				extract($params);
			}
		else
			$param = $params;

		switch ($param) {
			case 'countNew':
				Self::adminOrdersCount('new');
				break;

			case 'getStatData':
				Self::adminGetStatData();
				break;

			case 'newStat':
				Self::adminOrderNewStat();
				break;

			case 'getOrderHeader':
				Self::adminGetOrderHeader();
				break;

			case 'getOrderBody':
				Self::adminGetOrderBody();
				break;

			case 'getFirstPage':
				Self::adminGetOrderFirstPage();
				break;

			case 'getPage':
				Self::adminGetOrderPage();
				break;

			case 'getOrderTitle':
				Self::adminGetOrderTitle();
				break;

			case 'getLastOrderId':
				Self::adminGetLastOrderId();
				break;

			default:
				# code...
				break;
		}
}

function adminGetStatData()
{
	$data = json_decode($_POST['data'], true);
	extract($data);
	echo $this->model->getStatData($type, $period);
}

function adminGetLastOrderId()
{
	$token = $this->model->prepareToDB($_POST['token']);
	if ($token != 'getOrder') {
		echo "-1";
		return false;
	} else {
		$lastOrderId = $this->model->getLastOrderId();
		echo $lastOrderId;
		return true;
	}
}

function adminGetOrderTitle()
{
	$token = $this->model->prepareToDB($_POST['token']);
	if ($token != 'getOrder') {
		return false;
	} else {
		$ordersCounter = $this->model->countOrders('actual');
		if ($ordersCounter) {
			$this->view->simpleGet(
				"admin/order_title_view.php",
				array('ordersCounter'=>$ordersCounter)
			);
		}
		return true;
	}
}

function adminGetOrderFirstPage()
{
	$token = $this->model->prepareToDB($_POST['token']);
	if ($token != 'getPage') {
		return false;
	} else {
		$orders = $this->model->getOrders('last');
		$curPage = 1;
		$ordersCounter = $this->model->countOrders('actual');
		$pagesCount = ceil( $ordersCounter['all'] / 20);
		$this->view->simpleGet(
			'admin/worktable_reload_view.php',
			array('orders'=>$orders,
						'curPage' => $curPage,
						'ordersCounter'=>$ordersCounter,
						'pagesCount'=>$pagesCount
						)
		);
		return true;
	}
}

function adminGetOrderPage()
{
	$token = $this->model->prepareToDB($_POST['token']);
	if ($token != 'getPage') {
		return false;
	} else {
		$startId = $this->model->prepareToDB($_POST['startId']);
		$startId = (int) $startId;
		$orders = $this->model->getOrders('mid',$startId);
		$curPage = ceil($startId/20 +1);
		$ordersCounter = $this->model->countOrders('actual');
		$pagesCount = ceil( $ordersCounter['all'] / 20);
		$this->view->simpleGet(
			'admin/worktable_reload_view.php',
			array('orders'=>$orders,
						'curPage' => $curPage,
						'ordersCounter'=>$ordersCounter,
						'pagesCount'=>$pagesCount
						)
		);
		return true;
	}
}

function adminGetOrderBody()
{
	$token = $this->model->prepareToDB($_POST['token']);
	if ($token != 'getOrder') {
		return false;
	} else {
		$orderId = $this->model->prepareToDB($_POST['orderId']);
		$orderId = (int) $orderId;
		$order = $this->model->getOrder($orderId);
		if ($order) {
			$this->view->simpleGet(
				"admin/order_body_view.php",
				array('order'=>$order)
			);
		}
		return true;
	}
}

function adminGetOrderHeader()
{
	$token = $this->model->prepareToDB($_POST['token']);
	if ($token != 'getOrder') {
		return false;
	} else {
		$orderId = $this->model->prepareToDB($_POST['orderId']);
		$orderId = (int) $orderId;
		$order = $this->model->getOrder($orderId);
		if ($order) {
			$this->view->simpleGet(
				"admin/order_header_view.php",
				array('order'=>$order)
			);
		}
		return true;
	}
}

function adminOrderNewStat()
{
	$token = $this->model->prepareToDB($_POST['token']);
	if ($token != 'ajaxCount') {
		return false;
	} else {
		$newStat = json_decode($_POST['newStat'], true);
		$this->model->setOrderNewStat($newStat['id'], $newStat['status']);
		return true;
	}
}

function adminOrdersCount($param)
{
	$token = $this->model->prepareToDB($_POST['token']);
	if ($token != 'ajaxCount') {
		return false;
	} else {

			switch ($param) {
				case 'new':
					$ordersCounter = $this->model->countOrders('new');
					break;

				default:
					# code...
					break;
			}
		echo $ordersCounter;
		return true;
	}
}

/*																		 PRODUCTS
*************************************************************************************/

function adminCats($params = '')
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
		case 'childcatstoparent':
			Self::adminMoveCatsToParent();
			break;

		case 'deleteprodsformcat':
			Self::adminDeleteProdsFromCat();
			break;

		case 'deletecategory':
			Self::adminDeleteCategory();
			break;

		case 'movefromcattocat':
			Self::adminMoveFromCatToCat();
			break;

		case 'movecat':
			Self::adminMoveCat();
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
								'access_key' => $_SESSION['user']['access_key'],
								'btns' => array(
																'new-post' => 'Добавить товар',
																),
						'Favicon' => 'app/views/admin-favicon.php',
							),
						'admin/navigation_view.php',
						'admin/footer_view.php',
						array(
								'admin/modals_main_view.php',
								'admin/modals_add_cat_view.php'
									)
						);
			break;
	}

}


function adminMoveCat()
{
	if ($_POST['action'] == 'movecat') {
		$catData = json_decode($_POST['catJSON'], true);
		if ($this->model->moveCat($catData)) {
			echo "true";
		} else {
			echo "false action";
		}
	} else {
		echo "false action";
	}
}

function adminDeleteCategory()
{
	if ($_POST['action'] == 'deletecategory') {
		$parentCatId = $_POST['catid'];
		$catPosition = $_POST['position'];
		$catParent = $_POST['parent'];
		if ($this->model->deleteCategory($parentCatId,$catPosition,$catParent)) {
			echo "true";
		} else {
			echo "false action";
		}
	} else {
		echo "false action";
	}
}

function adminMoveCatsToParent()
{
	if ($_POST['action'] == 'childcatstoparent') {
		$parentCatId = $_POST['catid'];
		if ($this->model->moveCatsToParent($parentCatId)) {
			echo "true";
		} else {
			echo "false action";
		}
	} else {
		echo "false action";
	}
}


function adminMoveFromCatToCat()
{
	if ($_POST['action'] == 'movefromcattocat') {
		$fromCatId = $_POST['fromcatid'];
		$toCatId = $_POST['tocatid'];
		if ($this->model->moveFromCatToCat($fromCatId,$toCatId)) {
			echo "true";
		} else {
			echo "false action";
		}
	} else {
		echo "false action";
	}
}


function adminDeleteProdsFromCat()
{
	if ($_POST['action'] == 'deleteprodsformcat') {
		$parentCatId = $_POST['catid'];
		if ($this->model->deleteProdsFromCat($parentCatId)) {
			echo "true";
		} else {
			echo "false action";
		}
	} else {
		echo "false action";
	}
}


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
				Self::adminProductEdit($value);
				break;

			case 'delete':
				Self::adminProductsDelete($value);
				break;

			case 'archived':
				if (isset($value)) {
					Self::adminProductsArchived($value);
				} else Self::adminProductsArchived();
				break;

			case 'archive':
				Self::adminProductsArchive($value);
				break;

			case 'unarchive':
				Self::adminProductsUnArchive($value);
				break;

			case 'new':
				Self::adminProductsNew();
				break;

			case 'newcat':
				Self::adminProductsNewCat();
				break;

			case 'redcat':
				Self::adminProductsRedCat();
				break;

			case 'getcattree':
				Self::adminGetCatTree();
				break;


			case 'getcat':
				if (!isset($value)) {
					echo "No category name arrived!";
				} else
				Self::adminGetCat($value);
				break;

			case 'getcattree_prodlist':
				Self::adminGetCatTreeProdlist($value);
				break;

			case 'save':
				Self::adminProductsSave();
				break;

			case 'cat':
			case 'default':
				include 'app/models/model_catalog.php';
				$actual_title = 'Товары';
				$title= ' - Товары';
				$archivedLink = "/admin/goods/archived";
				if (isset($value)) {
					$ds = $this->model->getGoodsLists($value);
					$actual_title = "<a href='/admin/goods'>Товары (все)</a>";
					$second_title = $ds[0]['cat_title'];
					$title .= " - ".$second_title;
					$archivedLink = "/admin/goods/archived/".$value;
				} else
				$ds = $this->model->getGoodsLists();
				$ds = Model::createProdUrl($ds);
				$q = mysql_query("SELECT * FROM prod_cat ORDER BY position") or die(mysql_error());
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
									'archivedLink' => $archivedLink,
									'title'=>$title,
									'style'=>'admin/template.css',
									'style_content'=>'admin/goods.css',
									'goods'=>$ds,
									'cat_tree'=>$cat_tree,
									'active_menu_item' => 'goods',
									'actual_title' => $actual_title,
									'second_title' => $second_title,
									'access_key' => $_SESSION['user']['access_key'],
									'btns' => array(
																	'new-post' => 'Добавить товар',
																	),
							'Favicon' => 'app/views/admin-favicon.php',
								),
							'admin/navigation_view.php',
							'admin/footer_view.php',
							array(
									'admin/modals_main_view.php',
									'admin/modals_add_cat_view.php',
									'admin/modals_red_cat_view.php'
										)
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
									'access_key' => $_SESSION['user']['access_key'],
									'btns' => array(
																	'new-post' => 'Добавить товар',
																	),
							'Favicon' => 'app/views/admin-favicon.php',
								),
							'admin/navigation_view.php',
							'admin/footer_view.php',
							array(
									'admin/modals_main_view.php',
									'admin/modals_add_cat_view.php'
										)
							);
				break;
		}

	}




/*	 PRODUCTS ARCHIVED LIST
**********************************/
	function adminProductsArchived($cat_id=null)
	{
		$publicLink = "/admin/goods";
		$title = " - Скрытые товары";
		$actual_title = "<a href='/admin/goods/'>Товары (все)</a>";
		$second_title = "Все скрытые";
		if (isset($cat_id)) {
			$prods = $this->model->getGoodsPostsArchived($cat_id);
			$publicLink .= "/cat/".$cat_id;
			// $actual_title = "<a href='/admin/goods/'>Товары (все)</a>";
			$second_title = $prods[0]['cat_title'];
			$title .= " - ".$second_title;
		} else
		$prods = $this->model->getGoodsPostsArchived();
		$prods = Model::createProdUrl($prods);
		$cat_tree = $this->model->getGoodsCatsTree();
		$this->view->generate(
					'admin/products_archived_view.php',
					'admin/template_admin_view.php',
					array(
							'publicLink' => $publicLink,
							'title'=>$title,
							'second_title'=>$second_title,
							'style'=>'admin/template.css',
							'style_content'=>'admin/goods.css',
							'products'=>$prods,
							'active_menu_item' => 'goods',
							'actual_title' => $actual_title,
							'access_key' => $_SESSION['user']['access_key'],
							'cat_tree' => $cat_tree,
							'btns' => array(
															'new-post' => 'Добавить товар',
															),
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					array(
								'admin/modals_main_view.php',
								'admin/modals_add_cat_view.php',
								'admin/modals_red_cat_view.php'
								)
					);
	}



/*	 PRODUCTS -> to archive
**********************************/
	function adminProductsArchive($value)
	{
		$prodArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->archiveProd($prodArrive);
	}



/*	 PRODUCTS -> unarchive
**********************************/
	function adminProductsUnArchive($value)
	{
		$prodArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->unarchiveProd($prodArrive);
	}


/*	 Get categoties (and products) tree
**********************************/
	function adminGetCatTree()
	{
		$cat_tree = $this->model->getGoodsCatsTree();
		$jsonCatTree = json_encode($cat_tree);
		echo $jsonCatTree;
		return $jsonCatTree;
	}


/*	 Get categoties (and products) data
**********************************/
	function adminGetCat($value)
	{
		include 'app/models/model_catalog.php';
		$value = htmlspecialchars($value);
		$q = mysql_query("SELECT * FROM prod_cat WHERE tech_name='$value'");
		$categoryData = mysql_fetch_assoc($q);
		if (!$categoryData) {
			echo "No such category!";
			return false;
		}
		$catData = Model_Catalog::getCategoryData($value);
		$jsonCatData = json_encode($catData);
		echo $jsonCatData;
		return $jsonCatData;
	}

/*	 Get categoties (and products) tree for page "prod list"
**********************************/
	function adminGetCatTreeProdlist($cat_id=null)
	{
		include 'app/models/model_catalog.php';
		if (isset($cat_id)) {
			$ds = $this->model->getGoodsLists($cat_id);
		} else
		$ds = $this->model->getGoodsLists();
		$ds = Model::createProdUrl($ds);
		$q = mysql_query("SELECT * FROM prod_cat ORDER BY position") or die(mysql_error());
		while ( $buf = mysql_fetch_assoc($q)) {
			$prod_cats[$buf['id']] = $buf;
		}
		unset($q);
		$cat_tree = $this->model->createCatTree($prod_cats);
		unset($prod_cats);
		$result[0] = $ds;
		$result[1] = $cat_tree;
		$jsonResult = json_encode($result);
		echo $jsonResult;
		return $result;
		/*
		$cat_tree = $this->model->getGoodsCatsTree();
		$jsonCatTree = json_encode($cat_tree);
		echo $jsonCatTree;
		return $jsonCatTree;
		*/
	}

/*	 PRODUCT NEW
**********************************/
	function adminProductsNew()
	{
		$ref = $this->model->getRef();
		$ref = $ref[1];
		$ref = split('/', $ref);
		$addLink = "";
		if ($ref[3]=="cat" && isset($ref[4])) {
			$actualCat = $ref[4];
		}
		if (isset($actualCat)) {
			$addLink = "/cat/".$actualCat;
		}
		$cat_tree = $this->model->getGoodsCatsTree();
		$this->view->generate(
					'admin/products_new_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Добавление товара',
							'style'=>'admin/template.css',
							'style_content'=>'admin/goods.css',
							'cat_tree'=>$cat_tree,
							'ref' => $ref,
							'actualCat' => $actualCat,
							'access_key' => $_SESSION['user']['access_key'],
							'active_menu_item' => 'goods',
							'actual_title' => '<a href="/admin/goods'.$addLink.'">Товары</a>',
							'second_title' => 'Добавление',
							'btns' => array(
															'post-save new' => 'Сохранить',
															'post-abort' => 'Отмена',
															'post-archive btn-archive-new' => 'Сохранить как скрытый',
															),
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					array(
							'admin/modals_main_view.php',
							'admin/modals_add_cat_view.php'
								)
					);
		unset($addLink);
	}


/*	 PRODUCT edit CATEGORY
**********************************/
	function adminProductsRedCat()
	{
		if (!isset($_POST['action'])) {
			return "Не указан параметр action";
		}
		if (!isset($_POST['jsonPost'])) {
			return "Параметры не переданы";
		}
		switch ($_POST['action']) {
			case 'redcat':
					$catArrive = json_decode($_POST['jsonPost'], true);
					return $this->model->redCat($catArrive);
				break;

			default:
				return false;
				break;
		}

	}



/*	 PRODUCT NEW CATEGORY
**********************************/
	function adminProductsNewCat()
	{
		if (!isset($_POST['action'])) {
			return "Не указан параметр action";
		}
		if (!isset($_POST['jsonPost'])) {
			return "Параметры не переданы";
		}
		switch ($_POST['action']) {
			case 'newcat':
					$catArrive = json_decode($_POST['jsonPost'], true);
					return $this->model->addCat($catArrive);
				break;

			default:
				return false;
				break;
		}

	}



/*	 PRODUCT EDIT
**********************************/
	function adminProductEdit($art)
	{
		$product = $this->model->getProductItem($art);
		if ($product['archived']==0) {
			$archive_class = 'btn-archive';
			$archive_text = 'Скрыть товар';
			$catName = $this->model->getCatName($product['cat']);
			// var_dump($catName);
			$actual_title = '<a href="/admin/goods/cat/'.$product['cat'].'">'.$catName['name'].'</a>';
		} else
		{
			$archive_class = 'btn-unarchive';
			$archive_text = 'Опубликовать';
			$actual_title = '<a href="/admin/goods/archived">Товары (скрытые)</a>';
		}

		$cat_tree = $this->model->getGoodsCatsTree();
		// var_dump($cat_tree);

		$this->view->generate(
					'admin/product_edit_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Редактирование товара - '.htmlspecialchars($product['title']),
							'style'=>'admin/template.css',
							'style_content'=>'admin/goods.css',
							'cat_tree' => $cat_tree,
							'post'=>array (
														 'id' => $product['id'],
														 'url' => $product['url'],
														 'art' => $product['art'],
														 'tech_name' => $product['tech_name'],
														 'title' => htmlspecialchars($product['name']) ,
														 'subtitle' => htmlspecialchars($product['mini_desc']) ,
														 'poster' => $product['images'],
														 'price' => $product['price'],
														 'pod' => $product['pod'],
														 'cat' => $product['cat'],
														 'date' => $product['added_time'],
														 'text' => htmlspecialchars($product['description']),
														 'tags' => htmlspecialchars($product['labels']),
														 'consist' => htmlspecialchars($product['consist']),
														 'weight' => htmlspecialchars($product['weight']),
														 'weight' => htmlspecialchars($product['weight']),
														 'country' => htmlspecialchars($product['country']),
														 'stor_cond' => htmlspecialchars($product['stor_cond']),
														 'nut_val' => htmlspecialchars($product['nut_val']),
														 'energy_val' => htmlspecialchars($product['energy_val'])
														),
							'access_key' => $_SESSION['user']['access_key'],
							'active_menu_item' => 'goods',
							'actual_title' => $actual_title,
							'second_title' => 'Редактирование',
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
					array(
							'admin/modals_main_view.php',
							'admin/modals_add_cat_view.php'
								)
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
					$prodArrive = json_decode($_POST['jsonPost'], true);
					return $this->model->updateProd($prodArrive);
				break;

			case 'new':
					$prodArrive = json_decode($_POST['jsonPost'], true);
					return $this->model->newProd($prodArrive);
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
		$prodArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->deleteProd($prodArrive);
	}




/*																		 SALES
*************************************************************************************/
/*	 SALES INIT
**********************************/

	function adminSales($params = '')
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
				Self::adminSalesEdit($value);
				break;

			case 'delete':
				Self::adminSalesDelete($value);
				break;

			case 'archived':
				Self::adminSalesArchived();
				break;

			case 'archive':
				Self::adminSalesArchive($value);
				break;

			case 'unarchive':
				Self::adminSalesUnArchive($value);
				break;

			case 'new':
				Self::adminSalesNew();
				break;

			case 'save':
				Self::adminSalesSave();
				break;

			default :
				$ds = $this->model->getSalesLists();
				$this->view->generate(
							'admin/sales_view.php',
							'admin/template_admin_view.php',
							array(
									'title'=>' - Акции',
									'style'=>'admin/template.css',
									// 'style_content'=>'admin/articles.css',
									'style_content'=>'admin/sales.css',

									'posts'=>$ds,
									'active_menu_item' => 'sales',
									'actual_title' => 'Акции',
									//'second_title' => 'Записи статей',
									'btns' => array(
																	'new-post' => 'Новая акция',
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


	/*	 SALES EDIT
	**********************************/
		function adminSalesEdit($post_url)
		{
			$post = $this->model->getSalesPost($post_url);
			$prods = $this->model->getData('prods');
			if ($post['archived']==0) {
				$archive_class = 'btn-archive';
				$archive_text = 'Закончить акцию';
				$actual_title = '<a href="/admin/sales">Акции</a>';
			} else
			{
				$archive_class = 'btn-unarchive';
				$archive_text = 'Опубликовать';
				$actual_title = '<a href="/admin/sales/archived">Акции(архив)</a>';
			}

			$this->view->generate(
						'admin/sales_edit_view.php',
						'admin/template_admin_view.php',
						array(
								'title'=>' - Редактирование акции - '.htmlspecialchars($post['name']),
								'style'=>'admin/template.css',
								'style_content'=>'admin/goods.css',
								'post'=>array (
															 'id' => $post['id'],
															 'url' => $post['tech_name'],
															 'title' => htmlspecialchars($post['name']) ,
															 // 'subtitle' => htmlspecialchars($post['subtitle']) ,
															 'poster' => $post['poster'],
															 'start_time' => $post['start_time'],
															 'end_time' => $post['end_time'],
															 // 'anons' => htmlspecialchars($post['anons']),
															 'text' => htmlspecialchars($post['description']),
															 'sales_prods' => htmlspecialchars($post['prods']),
															 'sales_cats' => htmlspecialchars($post['cats'])
															 //'tags' => $post['tags'],
															),
								'access_key' => $_SESSION['user']['access_key'],
								'active_menu_item' => 'sales',
								'prods' => $prods,
								'actual_title' => $actual_title,
								'second_title' => 'Правка акции',
								'btns' => array(
																'post-save edit' => 'Сохранить',
																'post-abort' => 'Отмена',
																'post-delete btn-delete' => 'Удалить',
																// 'post-archive '.$archive_class => $archive_text,
																),
								'Favicon' => 'app/views/admin-favicon.php',
							),
						'admin/navigation_view.php',
						'admin/footer_view.php',
						'admin/modals_main_view.php'
						);
		}


		/*	 SALES SAVE
		**********************************/
			function adminSalesSave()
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
							return $this->model->updateSale($postArrive);
						break;

					case 'new':
							$postArrive = json_decode($_POST['jsonPost'], true);
							return $this->model->newSale($postArrive);
						break;

					default:
						# code...
						break;
				}
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
				Self::adminArticlesEdit($value);
				break;

			case 'delete':
				Self::adminArticlesDelete($value);
				break;

			case 'archived':
				Self::adminArticlesArchived();
				break;

			case 'archive':
				Self::adminArticlesArchive($value);
				break;

			case 'unarchive':
				Self::adminArticlesUnArchive($value);
				break;

			case 'new':
				Self::adminArticlesNew();
				break;

			case 'save':
				Self::adminArticlesSave();
				break;

			default :
				$ds = $this->model->getArticlesLists();
				$this->view->generate(
							'admin/articles_view.php',
							'admin/template_admin_view.php',
							array(
									'title'=>' - Новости',
									'style'=>'admin/template.css',
									'style_content'=>'admin/articles.css',

									'posts'=>$ds,
									'active_menu_item' => 'articles',
									'actual_title' => 'Новости',
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



/*	 SALES ARCHIVED LIST
**********************************/
	function adminSalesArchived()
	{
		$posts = $this->model->getSalesArchived();
		$this->view->generate(
					'admin/sales_archived_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Архив акций',
							'style'=>'admin/template.css',
							'style_content'=>'admin/sales.css',
							'posts'=>$posts,
							'active_menu_item' => 'sales',
							'actual_title' => 'Акции (архив)',
							//'second_title' => 'Записи в блоге',
							'btns' => array(
															'new-post' => 'Новая акция',
															),
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					'admin/modals_main_view.php'
					);
	}



/*	 PAGES ARCHIVED LIST
**********************************/
	function adminPagesArchived()
	{
		$posts = $this->model->getPagesPostsArchived();
		$this->view->generate(
					'admin/pages_archived_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Архив страниц',
							'style'=>'admin/template.css',
							'style_content'=>'admin/articles.css',
							'posts'=>$posts,
							'active_menu_item' => 'pages',
							'actual_title' => 'Страницы (архив)',
							//'second_title' => 'Записи в блоге',
							'btns' => array(
															'new-post' => 'Новая страница',
															),
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					'admin/modals_main_view.php'
					);
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
							'actual_title' => 'Новости (архив)',
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



/*	 SALES -> to archive
**********************************/
	function adminSalesArchive($value)
	{
		$postArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->archiveSale($postArrive);
	}

/*	PAGES -> to archive
**********************************/
	function adminPagesArchive($value)
	{
		$postArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->archivePage($postArrive);
	}

/*	 ARTICLES -> to archive
**********************************/
	function adminArticlesArchive($value)
	{
		$postArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->archivePost($postArrive);
	}

/*	 PAGES -> unarchive
**********************************/
	function adminPagesUnArchive($value)
	{
		$postArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->unarchivePage($postArrive);
	}

/*	 ARTICLES -> unarchive
**********************************/
	function adminArticlesUnArchive($value)
	{
		$postArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->unarchivePost($postArrive);
	}



/*	 SALES NEW
**********************************/
	function adminSalesNew()
	{
		$prods = $this->model->getData('prods');
		$this->view->generate(
					'admin/sales_new_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Новая акция',
							'style'=>'admin/template.css',
							// 'style_content'=>'admin/articles.css',
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
							'prods' => $prods,
							'active_menu_item' => 'sales',
							'actual_title' => '<a href="/admin/sales">Акции</a>',
							'second_title' => 'Новая акция',
							'btns' => array(
															'post-save new' => 'Сохранить',
															'post-abort' => 'Отмена',
															//'post-delete' => 'Удалить',
															// 'post-archive btn-archive-new' => 'Сохранить в архив',
															),
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php',
					'admin/modals_main_view.php'
					);
	}




/*	 PAGES NEW
**********************************/
	function adminPagesNew()
	{
		$this->view->generate(
					'admin/pages_new_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Новая страница',
							'style'=>'admin/template.css',
							// 'style_content'=>'admin/articles.css',
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
							'active_menu_item' => 'pages',
							'actual_title' => '<a href="/admin/pages">Страницы</a>',
							'second_title' => 'Новая страница',
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



/*	 ARTICLES NEW
**********************************/
	function adminArticlesNew()
	{
		$this->view->generate(
					'admin/articles_new_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Новая новость',
							'style'=>'admin/template.css',
							// 'style_content'=>'admin/articles.css',
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
							'active_menu_item' => 'articles',
							'actual_title' => '<a href="/admin/articles">Новости</a>',
							'second_title' => 'Новая новость',
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



/*	 PAGES EDIT
**********************************/
	function adminPagesEdit($post_url)
	{
		$post = $this->model->getPagesPost($post_url);
		if ($post['archived']==0) {
			$archive_class = 'btn-archive';
			$archive_text = 'В архив';
			$actual_title = '<a href="/admin/pages">Страницы</a>';
		} else
		{
			$archive_class = 'btn-unarchive';
			$archive_text = 'Опубликовать';
			$actual_title = '<a href="/admin/pages/archived">Страницы(архив)</a>';
		}

		$this->view->generate(
					'admin/pages_edit_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Редактирование страницы - '.htmlspecialchars($post['title']),
							'style'=>'admin/template.css',
							'style_content'=>'admin/goods.css',
							'post'=>array (
														 'id' => $post['id'],
														 'url' => $post['tech_name'],
														 'title' => htmlspecialchars($post['title']) ,
														 'subtitle' => htmlspecialchars($post['subtitle']) ,
														 'poster' => $post['poster'],
														 'date' => $post['datetime'],
														 // 'anons' => htmlspecialchars($post['anons']),
														 'text' => htmlspecialchars($post['body']),
														 //'tags' => $post['tags'],
														),
							'access_key' => $_SESSION['user']['access_key'],
							'active_menu_item' => 'pages',
							'actual_title' => $actual_title,
							'second_title' => 'Правка страницы',
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



/*	 ARTICLES EDIT
**********************************/
	function adminArticlesEdit($post_url)
	{
		$post = $this->model->getArticlesPost($post_url);
		if ($post['archived']==0) {
			$archive_class = 'btn-archive';
			$archive_text = 'В архив';
			$actual_title = '<a href="/admin/articles">Новости</a>';
		} else
		{
			$archive_class = 'btn-unarchive';
			$archive_text = 'Опубликовать';
			$actual_title = '<a href="/admin/articles/archived">Новости(архив)</a>';
		}

		$this->view->generate(
					'admin/articles_edit_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Редактирование новости - '.htmlspecialchars($post['title']),
							'style'=>'admin/template.css',
							'style_content'=>'admin/goods.css',
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
							'second_title' => 'Правка новости',
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



/*	 PAGES SAVE
**********************************/
	function adminPagesSave()
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
					return $this->model->updatePage($postArrive);
				break;

			case 'new':
					$postArrive = json_decode($_POST['jsonPost'], true);
					return $this->model->newPage($postArrive);
				break;

			default:
				# code...
				break;
		}
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



/*	 SALES DELETE
**********************************/
	function adminSalesDelete($value)
	{
		$postArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->deleteSale($postArrive);
	}


/*	 ARTICLES DELETE
**********************************/
	function adminArticlesDelete($value)
	{
		$postArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->deletePost($postArrive);
	}



/*	 PAGES DELETE
**********************************/
	function adminPagesDelete($value)
	{
		$postArrive = json_decode($_POST['jsonPost'], true);
		return $this->model->deletePage($postArrive);
	}



/*																		 PAGES
*************************************************************************************/


function adminPages($params = '')
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
			Self::adminPagesEdit($value);
			break;

		case 'delete':
			Self::adminPagesDelete($value);
			break;

		case 'archived':
			Self::adminPagesArchived();
			break;

		case 'archive':
			Self::adminPagesArchive($value);
			break;

		case 'unarchive':
			Self::adminPagesUnArchive($value);
			break;

		case 'new':
			Self::adminPagesNew();
			break;

		case 'save':
			Self::adminPagesSave();
			break;

		default :
			$ds = $this->model->getPagesLists();
			$this->view->generate(
						'admin/pages_view.php',
						'admin/template_admin_view.php',
						array(
								'title'=>' - Страницы',
								'style'=>'admin/template.css',
								'style_content'=>'admin/pages.css',
								'active_menu_item' => 'pages',
								'actual_title' => 'Страницы',
								'posts'=>$ds,
								'btns' => array(
																'new-post' => 'Новая страница',
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




/*																		 DOCS / MANUALS
*************************************************************************************/
	function adminReadDocs($params="")
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

			case 'save':
				// Self::adminPagesSave();
				break;

			default :
				$docsPosts = $this->model->getDocsUrls();
				if ($name=="") {
					$name = "default";
				}
				$curPage = $this->model->getDocPage($name);
				if (!$curPage) {
					Route::Catch_Error("404");
				}
				// echo "<pre>";
				// var_dump($docsPosts);
				// var_dump($curPage);
				// echo "</pre>";
				$this->view->generate(
							'admin/docs_view.php',
							'admin/template_admin_view.php',
							array(
									'title'=>' - '.$curPage['name'].' - Инструкции для модераторов и администраторов',
									'style'=>'admin/template.css',
									'style_content'=>'admin/docs.css',
									'active_menu_item' => 'docs',
									'actual_title' => '<small>Как пользоваться админкой</small>',
									'posts'=>$ds,
									'btns' => array(
																	// 'new-post' => 'Новая страница',
																	),
									'docsPosts' => $docsPosts,
									'curPage' => $curPage,
									'Favicon' => 'app/views/admin-favicon.php',
								),
							'admin/navigation_view.php',
							'admin/footer_view.php',
							'admin/modals_main_view.php'
							);
				break;
		}

	}


/*																		 FILES
*************************************************************************************/
	function adminFiles()
	{
		$this->view->generate(
					'admin/files_view.php',
					'admin/template_admin_view.php',
					array(
							'title'=>' - Файловый менеджер',
							'style'=>'admin/template.css',
							'style_content'=>'admin/main.css',
							'access_key' => $_SESSION['user']['access_key'],
							'active_menu_item' => 'files',
							'actual_title' => 'Файловый менеджер',
							'Favicon' => 'app/views/admin-favicon.php',
						),
					'admin/navigation_view.php',
					'admin/footer_view.php'
					);
	}



/*																		 COMMENTS
*************************************************************************************/
	function adminComments($params = "")
	{
		if ($params == '') {
			$params['name'] = 'default';
		}
		if (is_array($params))
			extract($params);
		else
			$name = $params;

		switch ($name) {
			case 'new':
				Self::adminCommentsNew();
				break;

			case 'approved':
				Self::adminCommentsApproved();
				break;

			case 'banned':
				Self::adminCommentsBanned();
				break;

			case 'countNew':
				Self::adminCommentsCountNew();
				break;

			case 'statusChange':
				Self::adminCommentStatusChange();
				break;

			case 'all':
				Self::adminShowAll();
				break;

			case 'default':
				Self::adminShowAll();
				break;

				default:
					Self::adminShowAll();
					break;
		}

	}


	function adminCommentStatusChange(){
		$data = json_decode($_POST['data'], true);
		$this->model->commentStatusChange($data);
	}

	function adminCommentsCountNew()
	{
		if (isset($_POST['token'])&&($_POST['token']=='ajaxCount')) {
			$comments = $this->model->getComments('all', NULL, 'new');
			if ($comments) {
				echo count($comments);
			} else echo "0";
			return true;
		} else {
			return false;
		}
	}

	function adminCommentsBanned()
	{
		$commentsProduct = $this->model->getComments('all', NULL, 'banned');
		if ($commentsProduct) {
		foreach ($commentsProduct as $key => $value) {
			$userData = $this->model->getUser($value['uid']);
			if ($value['target_id']==0) {
				$commentsProduct[$key]['type'] = "review";
			} else {
				$commentsProduct[$key]['type'] = "prod";
				$productData = $this->model->getProductById($value['target_id']);
				$productData = $this->model->createProdUrl(array($productData));
				$commentsProduct[$key]['prod'] = $productData[0];
			}
			$commentsProduct[$key]['user'] = $userData;
			$commentsProduct[$key]['pub_time_text'] = Self::getGoodDate($commentsProduct[$key]['pub_time']);
		}
		}
		$this->view->generate(
								'admin/comm_banned_view.php',
								'admin/template_admin_view.php',
								array(
										'title'=>' - Комментарии',
										'style'=>'admin/template.css',
										'style_content'=>'admin/users.css',
										'comments'=>$commentsProduct,
										'active_menu_item' => 'comm',
										'actual_title' => 'Комментарии',
										'Favicon' => 'app/views/admin-favicon.php',
									),
								'admin/navigation_view.php',
								'admin/footer_view.php',
								'admin/modals_users_view.php'
								);
	}


	function adminCommentsApproved()
	{
		$commentsProduct = $this->model->getComments('all', NULL, 'approved');
		if ($commentsProduct) {
		foreach ($commentsProduct as $key => $value) {
			$userData = $this->model->getUser($value['uid']);
			if ($value['target_id']==0) {
				$commentsProduct[$key]['type'] = "review";
			} else {
				$commentsProduct[$key]['type'] = "prod";
				$productData = $this->model->getProductById($value['target_id']);
				$productData = $this->model->createProdUrl(array($productData));
				$commentsProduct[$key]['prod'] = $productData[0];
			}
			$commentsProduct[$key]['user'] = $userData;
			$commentsProduct[$key]['pub_time_text'] = Self::getGoodDate($commentsProduct[$key]['pub_time']);
		}
		}
		$this->view->generate(
								'admin/comm_approved_view.php',
								'admin/template_admin_view.php',
								array(
										'title'=>' - Комментарии',
										'style'=>'admin/template.css',
										'style_content'=>'admin/users.css',
										'comments'=>$commentsProduct,
										'active_menu_item' => 'comm',
										'actual_title' => 'Комментарии',
										'Favicon' => 'app/views/admin-favicon.php',
									),
								'admin/navigation_view.php',
								'admin/footer_view.php',
								'admin/modals_users_view.php'
								);
	}

	function adminCommentsNew()
	{
		$commentsProduct = $this->model->getComments('all', NULL, 'new');
		if ($commentsProduct) {
		foreach ($commentsProduct as $key => $value) {
			$userData = $this->model->getUser($value['uid']);
			if ($value['target_id']==0) {
				$commentsProduct[$key]['type'] = "review";
			} else {
				$commentsProduct[$key]['type'] = "prod";
				$productData = $this->model->getProductById($value['target_id']);
				$productData = $this->model->createProdUrl(array($productData));
				$commentsProduct[$key]['prod'] = $productData[0];
			}
			$commentsProduct[$key]['user'] = $userData;
			$commentsProduct[$key]['pub_time_text'] = Self::getGoodDate($commentsProduct[$key]['pub_time']);
		}
		}
		$this->view->generate(
								'admin/comm_new_view.php',
								'admin/template_admin_view.php',
								array(
										'title'=>' - Комментарии',
										'style'=>'admin/template.css',
										'style_content'=>'admin/users.css',
										'comments'=>$commentsProduct,
										'active_menu_item' => 'comm',
										'actual_title' => 'Комментарии',
										'Favicon' => 'app/views/admin-favicon.php',
									),
								'admin/navigation_view.php',
								'admin/footer_view.php',
								'admin/modals_users_view.php'
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
				Self::adminUsersNew();
				break;

			case 'delete':
				Self::adminUsersDelete();
				break;

			case 'issuper':
				Self::adminUserIssuper();
				break;

			case 'setBanStatus':
				Self::adminUserSetBanStatus();
				break;

			case 'editUser':
				Self::adminUserEdit();
				break;

			case "hasRight":
				Self::adminHasRight();
				break;

			case "getRights":
				Self::adminGetRights();
				break;

			case "editRights":
				Self::adminEditRights();
				break;

			case 'all':
				Self::adminShowAll();
				break;

			case 'default':
				$users = $this->model->getAdminUsers();
				$this->view->generate(
							'admin/users_view.php',
							'admin/template_admin_view.php',
							array(
									'title'=>' - Администраторы',
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
					$user['admin_rights'] = explode(',',$user['admin_rights']);
					$user['admin_rights_texts'] = $this->model->getRightsText($user['admin_rights']);
					$this->view->generate(
								'admin/users_user_view.php',
								'admin/template_admin_view.php',
								array(
										'title'=>' - Аккаунты - '.$user['login'],
										'style'=>'admin/template.css',
										'style_content'=>'admin/users.css',
										'user'=>$user,
										'active_menu_item' => 'users',
										'actual_title' => '<a href="/admin/users/all">Аккаунты</a>',
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

function adminGetRights()
{
	$data = json_decode($_POST['uid'], true);
	$echo = $this->model->getRights($data);
	echo $echo;
}

function adminEditRights(){
	$data = json_decode($_POST['data'], true);
	$this->model->editRights($data);
}

function adminHasRight(){
	$data = json_decode($_POST['data'], true);
	$this->model->isHasRight($data);
}

function adminUserEdit()
{
	$userdata = json_decode($_POST['userData'], true);
	$this->model->editUser($userdata);
}

public function adminShowAll()
{
	$users = $this->model->getSimpleUsers();
	$this->view->generate(
				'admin/users_all_view.php',
				'admin/template_admin_view.php',
				array(
						'title'=>' - Пользователи',
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
	function adminConfig($params)
	{
		if ($params == '') {
			$params['name'] = 'default';
		}
		if (is_array($params))
			extract($params);
		else
			$name = $params;

		switch ($name) {
			case 'update':
				Self::adminConfigUpdate();
				break;

			case 'default':
				$configs = $this->model->getConfigs();
				$this->view->generate(
							'admin/config_view.php',
							'admin/template_admin_view.php',
							array(
									'title'=>' - Настройки',
									'style'=>'admin/template.css',
									'style_content'=>'admin/main.css',
									'active_menu_item' => 'config',
									'actual_title' => 'Настройки',
									'configs' => $configs,
									'access_key' => $_SESSION['user']['access_key'],
									'Favicon' => 'app/views/admin-favicon.php',
								),
							'admin/navigation_view.php',
							'admin/footer_view.php'
							);
				break;
		}
	}

	function adminConfigUpdate()
	{
		$configData = $_POST['dataJSON'];
		$configData = json_decode($configData, true);
		$this->model->updateConfigs($configData);
		return true;
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
			Self::adminBugtrackerSetDoer();
		} else
		if ($name == 'count')
		{
			if ((isset($value))&&($value != "")) {
				Self::adminBugtrackerCount($value);
			} else
			if ((isset($value))&&($value == "")||(!isset($value))) {
				Self::adminBugtrackerCount('all');
			} else {
				Route::Catch_Error('404');
			}
		} else
		if ($name=="createTicket")
		{
				Self::adminBugtrackerCreateTicket();
		} else
		if ($name=="ticket")
		{
			if ((isset($value))&&($value != "")) {
				Self::adminBugtrackerShowTicket($value);
			} else
			if ((isset($value))&&($value == "")||(!isset($value))) {
				Self::adminBugtrackerShowTicket('last');
			} else {
				Route::Catch_Error('404');
			}
		} else
		if ($name=="deleteTicket")
		{
			Self::adminBugtrackerDelete();
		} else
		if ($name=="restoreTicket")
		{
			Self::adminBugtrackerRestore();
		} else
		if ($name=="getLastTicket")
		{
			Self::adminBugtrackerTicketForList();
		} else
		if ($name=="ajaxTickets")
		{
			Self::adminBugtrackerAjaxTickets($value, $personal, $personalMy);
		} else
			Self::adminBugtrackerGetTickets($name);

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
			Self::adminBugtrackerShowTicketU($ticket1);
		} else
		if ($ticket['archived']==1) {
			Self::adminBugtrackerShowArchivedTicket($ticket);
		} else
		Self::adminBugtrackerShowTicketU($ticket);
	}



/*	 BUGTRACKER ONE TICKET - FOR LIST
**********************************/
	function adminBugtrackerTicketForList()
	{
		$ticket = $this->model->getOneTicket();
		//$api = $this->tlgrm;
		//Self::sendMeTelegram("Хэй, Кеша 😊\r\nНовый тикет😱 от ".$ticket['author']."!\r\nВот -> ".$_SERVER['HTTP_HOST']."/admin/bugtracker/ticket/".$ticket['id']);
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
					if (isset($_POST['secondName'])) {
						$secondName = $this->model->prepareToDB($_POST['secondName']);
						echo "$name - $value - $secondName";
						Self::setSession($name, $value, $secondName);
					} else
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


	function setSession($name=NULL, $value=NULL, $secondName = null)
	{
		if (isset($name) && isset($value)) {
			if (isset($secondName)) {
				$_SESSION[$name][$secondName] = $value;
				echo 'установлено $_SESSION["'.$name.'"]["'.$secondName.'"] = '.$value;
			}
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
			} else if (isset($_SESSION['user'][$name])) {
				$ses = $_SESSION['user'][$name];
				echo "$ses";
			}
			else Route::Catch_Error('404');
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

	function adminSetSoundSetting()
	{
		$token = $this->model->prepareToDB($_POST['token']);
		if ($token != 'setSound') {
			echo "-1";
			return false;
		} else {
			$soundParam = (int)$this->model->prepareToDB($_POST['param']);
			$this->model->setSoundSetting($soundParam);
			$_SESSION['user']['sound'] = $soundParam;
			echo "ok";
			return true;
		}
	}

/*
** ------------- ACTIONS --------------------
*/
	function action_index($param = null)
	{
		switch ($param) {
			case 'login':
				Self::adminLogin();
				break;
			case 'main':
				Self::adminWorktable();
				break;
			case '':
				Self::action_main();
			// if (Controller::is_admin())
			// 	{
			// 		Self::adminWorktable();
			// 	} else
			// 			{
			// 				Self::adminLogin();
			// 			}
				break;
			default:
				Route::Catch_Error('404');
				//Self::action_main($param);
				break;
		}

	}


	function action_orders($param = null)
	{
		if (Controller::is_admin())
		{
			Self::adminOrders($param);
		} else
				{
					Self::adminLogin();
				}
	}


	function action_login()
	{
		if (Controller::is_admin())
		{
			Self::action_main();
		} else
				{
					Self::adminLogin();
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
			//Self::adminMain();
		} else
			{
				Self::adminNoLogin();
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
			$arr = array('right'=>'orders', 'uid'=>$_SESSION['user']['id']);
			if (($_SESSION['user']['is_admin']==1)&&(Model::isHasRight($arr))) {
				Self::adminWorktable();
			} else {
				Self::notEnouthRights();
			}
		} else
		{
			Self::adminLogin();
		}
	}


	function action_session()
	{
		if (Controller::is_admin())
		{
			Self::adminSession();
		} else
		{
			Self::adminLogin();
		}
	}

	function action_setsoundsetting()
	{
		if (Controller::is_admin())
		{
			Self::adminSetSoundSetting();
		} else
		{
			Self::adminLogin();
		}
	}


	function action_blog($params = '')
	{
		if (Controller::is_admin())
		{
			Self::adminBlog($params);
		} else
		{
			Self::adminLogin();
		}
	}



	function action_goods($params = '')
	{
		if (Controller::is_admin())
		{
			$arr = array('right'=>'goods', 'uid'=>$_SESSION['user']['id']);
			if (($_SESSION['user']['is_admin']==1)&&(Model::isHasRight($arr))) {
				Self::adminGoods($params);
			} else {
				Self::notEnouthRights();
			}
		} else
		{
			Self::adminLogin();
		}
	}



	function action_cats($params = '')
	{
		if (Controller::is_admin())
		{
			$arr = array('right'=>'goods', 'uid'=>$_SESSION['user']['id']);
			if (($_SESSION['user']['is_admin']==1)&&(Model::isHasRight($arr))) {
				Self::adminCats($params);
			} else {
				Self::notEnouthRights();
			}
		} else
		{
			Self::adminLogin();
		}
	}


	function action_articles($params = '')
	{
		if (Controller::is_admin())
		{
			$arr = array('right'=>'news', 'uid'=>$_SESSION['user']['id']);
			if (($_SESSION['user']['is_admin']==1)&&(Model::isHasRight($arr))) {
				Self::adminArticles($params);
			} else {
				Self::notEnouthRights();
			}
		} else
		{
			Self::adminLogin();
		}
	}


	function action_sales($params = '')
	{
		if (Controller::is_admin())
		{
			$arr = array('right'=>'goods', 'uid'=>$_SESSION['user']['id']);
			if (($_SESSION['user']['is_admin']==1)&&(Model::isHasRight($arr))) {
				Self::adminSales($params);
			} else {
				Self::notEnouthRights();
			}
		} else
		{
			Self::adminLogin();
		}
	}



	function action_pages($params='')
	{
		if (Controller::is_admin())
		{
			$arr = array('right'=>'pages', 'uid'=>$_SESSION['user']['id']);
			if (($_SESSION['user']['is_admin']==1)&&(Model::isHasRight($arr))) {
				Self::adminPages($params);
			} else {
				Self::notEnouthRights();
			}
		} else
		{
			Self::adminLogin();
		}
	}



	function action_files()
	{
		if (Controller::is_admin())
		{
			Self::adminFiles();
		} else
		{
			Self::adminLogin();
		}
	}

	function action_docs($params="")
	{
		if (Controller::is_admin())
		{
			Self::adminReadDocs($params);
		} else {
			Self::adminLogin();
		}
	}

	function action_users($params = '')
	{
		if (Controller::is_admin())
		{
			$arr = array('right'=>'users', 'uid'=>$_SESSION['user']['id']);
			if ( ($_SESSION['user']['is_admin']==1) && ((Model::isHasRight($arr))||($params==$_SESSION['user']['id'])) ) {
				Self::adminUsers($params);
			} else {
				Self::notEnouthRights();
			}
		} else
		{
			Self::adminLogin();
		}
	}

	function action_comm($params = '')
	{
		if (Controller::is_admin())
		{
			$arr = array('right'=>'users', 'uid'=>$_SESSION['user']['id']);
			if ( ($_SESSION['user']['is_admin']==1) && ((Model::isHasRight($arr))||($params==$_SESSION['user']['id'])) ) {
				Self::adminComments($params);
			} else {
				Self::notEnouthRights();
			}
		} else
		{
			Self::adminLogin();
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


	function action_config($params="")
	{
		if (Controller::is_admin())
		{
			$arr = array('right'=>'users', 'uid'=>$_SESSION['user']['id']);
			if (($_SESSION['user']['is_admin']==1)&&(Model::isHasRight($arr))) {
				Self::adminConfig($params);
			} else {
				Self::notEnouthRights();
			}
		} else
		{
			Self::adminLogin();
		}
	}



	function action_bugtracker($params = '')
	{
		if (Controller::is_admin())
		{
			Self::adminBugtracker($params);
		} else
		{
			Self::adminLogin();
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
			Self::adminBugtracker($params);
			}
		} else
		{
			Self::adminLogin();
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
					// Self::sendMsg($message);
					$api->sendMsgToBot($message);
			}
		}
	}

	function action_telegram()
	{
		if (Controller::is_admin())
		{
			Self::adminTelegram();
		} else
		{
			Self::adminLogin();
		}
	}

	function adminTelegram()
	{
		if (Self::isSuper()) {
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
