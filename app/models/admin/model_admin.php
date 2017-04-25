<?php

class Model_Admin extends Model
{
	public function get_data()
	{

	}



	/*
	getBlogPosts()
	Получение постов в блога
	*/
	public function getBlogPosts()
	{
		$select = mysql_query("SELECT * FROM blog WHERE archived = 0 ORDER BY datetime DESC")or die(mysql_error());
				$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$r['datetime'] = Controller::getGoodDate($r['datetime']);
					$ds[]=$r;
		}
		return $ds;
	}




	/*
	getGoodsLists()
	Получение списка товаров в каталоге (с учетом подкаталогов)
	*/
	public function getGoodsLists($cat_id=null)
	{
		$f = false;
		if (isset($cat_id)) {
			$f = true;
			$select = mysql_query("SELECT p.*, u.id as uid,u.name as uname,c.name as cname,c.tech_name as ctech_name, c.parent as cparent FROM prod_items p LEFT JOIN prod_cat c on p.cat=c.id LEFT JOIN users u on p.author=u.id WHERE (c.id=".$cat_id." OR c.parent=".$cat_id.") AND archived = 0 ORDER BY added_time DESC")or die(mysql_error());
		} else
		$select = mysql_query("SELECT p.*, u.id as uid, u.name as uname FROM prod_items p LEFT JOIN users u on p.author=u.id WHERE archived = 0 ORDER BY id DESC")or die(mysql_error());
				$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$r['added_time'] = Controller::getGoodDate($r['added_time']);
					$r['author'] = array('id'=>$r['uid'], 'name' => $r['uname']);
					$r['category'] = array('id'=>$r['cat'],'name'=> $r['cname'], 'tech_name'=> $r['ctech_name'],'parent'=>$r['cparent']);
					$ds[]=$r;
				}
				if ($f == true) {
					$select = mysql_query("SELECT name FROM prod_cat WHERE id=$cat_id");
					$cat_title = mysql_fetch_assoc($select);
					$ds[0]['cat_title'] = $cat_title['name'];
					$ds[0]['cat_id'] = $cat_id;
				}
		return $ds;
	}



	/*
	getGoodsPostsArchived()
	Получение списка товаров, которые не показываются
	*/
	public function getGoodsPostsArchived($cat_id=null)
	{
		$f = false;
		if (isset($cat_id)) {
			$f = true;
			$select = mysql_query("SELECT p.*, u.id as uid,u.name as uname,c.name as cname,c.tech_name as ctech_name, c.parent as cparent FROM prod_items p LEFT JOIN prod_cat c on p.cat=c.id LEFT JOIN users u on p.author=u.id WHERE (c.id=".$cat_id." OR c.parent=".$cat_id.") AND archived = 1 ORDER BY added_time DESC")or die(mysql_error());
		} else
		$select = mysql_query("SELECT p.*, u.id as uid, u.name as uname FROM prod_items p LEFT JOIN users u on p.author=u.id WHERE archived = 1 ORDER BY id DESC")or die(mysql_error());
				$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$r['added_time'] = Controller::getGoodDate($r['added_time']);
					$r['author'] = array('id'=>$r['uid'], 'name' => $r['uname']);
					$r['category'] = array('id'=>$r['cat'],'name'=> $r['cname'], 'tech_name'=> $r['ctech_name'],'parent'=>$r['cparent']);
					$ds[]=$r;
				}
				if ($f == true) {
					$select = mysql_query("SELECT name FROM prod_cat WHERE id=$cat_id");
					$cat_title = mysql_fetch_assoc($select);
					$ds[0]['cat_title'] = $cat_title['name'];
					$ds[0]['cat_id'] = $cat_id;
				}
		return $ds;
	}


	/*
	getGoodsCatsTree()
	Получение дерева каталогов
	*/
	public function getGoodsCatsTree()
	{
		$q = mysql_query("SELECT * FROM prod_cat ORDER BY id") or die(mysql_error());
		while ( $buf = mysql_fetch_assoc($q)) {
			$prod_cats[$buf['id']] = $buf;
		}
		unset($q);
		$cat_tree = Self::createCatTree($prod_cats);
		unset($prod_cats);

		return $cat_tree;
	}


	/*
	getArticlesLists()
	Получение списка статей в архиве
	*/
	public function getArticlesLists()
	{
		$select = mysql_query("SELECT * FROM articles WHERE archived = 0 ORDER BY datetime DESC")or die(mysql_error());
				$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$r['datetime'] = Controller::getGoodDate($r['datetime']);
					$ds[]=$r;

		}

		return $ds;
	}


	/*
	getProductItem($art)
	Получение товара по его артикулу
	$art [string] - артикул товара
	*/
	public function getProductItem($art)
	{
		$select = mysql_query("SELECT * FROM prod_items WHERE art = '$art'")or die(mysql_error());
		$ds = mysql_fetch_assoc($select);

		return $ds;
	}



	/*
	getArticlesPost($url)
	Получение статьи по её url
	$url [string] - url статьи
	*/
	public function getArticlesPost($post_url)
	{
		$select = mysql_query("SELECT * FROM articles WHERE url = '$post_url'")or die(mysql_error());
		$ds = mysql_fetch_assoc($select);

		return $ds;
	}



	/*
	getArticlesPostsArchived()
	Получение списка статей в архиве
	*/
	public function getArticlesPostsArchived()
	{
		$select = mysql_query("SELECT * FROM articles WHERE archived = 1 ORDER BY datetime DESC")or die(mysql_error());
				$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$r['datetime'] = Controller::getGoodDate($r['datetime']);
					$ds[]=$r;

		}

		return $ds;
	}


	/*
	updatePost($post)
	Изменение статьи
	$post [assoc array] - массив с информацией о статье
	*/
	public function updatePost($post)
	{
		extract($post);
		$author = $_SESSION['user']['name'];
		$sql = "UPDATE articles SET url = '$url', title = '$title', subtitle = '$subtitle', poster = '$poster', prev_poster = '$poster', author = '$author', anons = '$anons', body = '$text' WHERE id = '$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Статья сохранена";
	}


	/*
	updateProd($prod)
	Изменение товара
	$post [assoc array] - массив с информацией о статье
	*/
	public function updateProd($prod)
	{
		extract($prod);
		$author = $_SESSION['user']['id'];
		$sql = "UPDATE prod_items SET art = '$art', cat = '$cat', name = '$name', tech_name = '$tech_name', images = '$images', mini_desc = '$mini_desc', description = '$description', author = '$author', price = '$price', labels = '$labels', weight='$weight', country = '$country', stor_cond = '$stor_cond', nut_val = '$nut_val', energy_val='$energy_val', consist = '$consist' WHERE id = '$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Статья сохранена";
	}



	/*
	archivePost($post)
	Отправить статью в архив
	$post [assoc array] - массив с информацией о статье
	*/
	public function archiveProd($prod)
	{
		extract($prod);
		$author = $_SESSION['user']['name'];
		$sql = "UPDATE prod_items SET archived = 1 WHERE id = '$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Позиция скрыта от публикации";
	}


	/*
	archivePost($post)
	Отправить товар в архив
	$prod [assoc array] - массив с информацией о товаре
	*/
	public function archivePost($post)
	{
		extract($post);
		$author = $_SESSION['user']['name'];
		$sql = "UPDATE articles SET archived = 1 WHERE id = '$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Статья отправлена в архив";
	}



	/*
	unarchiveProd($prod)
	Опубликовать товар из архива
	$prod [assoc array] - массив с информацией о товаре
	*/
	public function unarchiveProd($prod)
	{
		extract($prod);
		$author = $_SESSION['user']['name'];
		$sql = "UPDATE prod_items SET archived = 0 WHERE id = '$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Позиция опубликована";
	}

	/*
	unarchivePost($post)
	Опубликовать статью из архива
	$post [assoc array] - массив с информацией о статье
	*/
	public function unarchivePost($post)
	{
		extract($post);
		$author = $_SESSION['user']['name'];
		$sql = "UPDATE articles SET archived = 0 WHERE id = '$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Статья опубликована";
	}



	/*
	deletePost($post)
	Удалить статью
	$post [assoc array] - массив с информацией о статье
	*/
	public function deletePost($post)
	{
		extract($post);
		$author = $_SESSION['user']['name'];
		$sql = "DELETE FROM articles WHERE id='$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Статья полностью удалена!";
	}


	/*
	deleteProd($prod)
	Удалить статью
	$post [assoc array] - массив с информацией о статье
	*/
	public function deleteProd($prod)
	{
		extract($prod);
		$author = $_SESSION['user']['id'];
		$sql = "DELETE FROM prod_items WHERE id='$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Позиция полностью удалена!";
	}



	/*
	newPost($post)
	Добавить новую статью
	$post [assoc array] - массив с информацией о статье
	*/
	public function newPost($post)
	{
		extract($post);
		$author = $_SESSION['user']['name'];
		$url = htmlspecialchars($url);
		if ((isset($archived)) && ($archived == 1)) {
			$sql = "INSERT INTO articles (url, title, subtitle, poster, prev_poster, author, anons, body, archived) VALUES ('$url','$title','$subtitle','$poster','$poster','$author','$anons','$text', 1)";
		} else
		$sql = "INSERT INTO articles (url, title, subtitle, poster, prev_poster, author, anons, body) VALUES ('$url','$title','$subtitle','$poster','$poster','$author','$anons','$text')";
		mysql_query($sql) or die(mysql_error());
		echo "Статья добавлена";
	}


	/*
	newProd($prod)
	Добавить новый товар
	$prod [assoc array] - массив с информацией о товаре
	*/
	public function newProd($prod)
	{
		extract($prod);
		$author = $_SESSION['user']['id'];
		// $url = htmlspecialchars($url);
		if ((isset($archived)) && ($archived == 1)) {
			$sql = "INSERT INTO prod_items (art, name, tech_name, images, mini_desc, description, cat, author, price, archived, weight, country, stor_cond, nut_val, energy_val, consist, labels) VALUES ('$art','$name','$tech_name','$images','$mini_desc','$description','$cat','$author','$price', 1, '$weight', '$country', '$stor_cond', '$nut_val', '$energy_val', '$consist', '$labels')";
		} else
		$sql = "INSERT INTO prod_items (art, name, tech_name, images, mini_desc, description, cat, author, price, weight, country, stor_cond, nut_val, energy_val, consist, labels) VALUES ('$art','$name','$tech_name','$images','$mini_desc','$description','$cat','$author', '$price', '$weight', '$country', '$stor_cond', '$nut_val', '$energy_val', '$consist', '$labels')";
		// TODO: edit inserting products with price
		mysql_query($sql) or die(mysql_error());
		echo "Позиция добавлена";
	}


	/*
	addCat($cat)
	Добавить новую категорию
	$cat [assoc array] - массив с информацией о категории
	*/
	public function addCat($cat)
	{
		extract($cat);
		// $author = $_SESSION['user']['id'];
		$name = htmlspecialchars($name);
		$tech_name = htmlspecialchars($tech_name);
		$checkName = "SELECT * FROM prod_cat WHERE name = '$name' OR tech_name='$tech_name'";
		if (mysql_num_rows(mysql_query($checkName)) > 0) {
			echo "Такая категория уже существует!";
		} else {
			if ($parent == "0") {
				$sql = 'SELECT position FROM prod_cat WHERE parent=0 ORDER BY position DESC LIMIT 1';
				$result = mysql_query($sql) or die(mysql_error());
				if ($result) {
					while ($row = mysql_fetch_array($result)) {
						$lastPosition = (int)$row['position'];
					}
				}
				$lastPosition++;
				$sql = "INSERT INTO prod_cat (name, tech_name, parent, poster, show_big, show_popular, position) VALUES ('$name','$tech_name','$parent','$poster','$show_big','$show_popular','$lastPosition')";
			} else
			$sql = "INSERT INTO prod_cat (name, tech_name, parent, poster, show_big, show_popular) VALUES ('$name','$tech_name','$parent','$poster','$show_big','$show_popular')";
			mysql_query($sql) or die(mysql_error());
			echo "Категория добавлена";
		}
	}


	/*
	deleteUser($id)
	Удаление пользователя-админа
	$id [int] - id пользователя
	*/
	public function deleteUser($user_id)
	{
		$sql = "DELETE FROM users WHERE id='$user_id'";
		mysql_query($sql) or die(mysql_error());
		echo "Аккаунт удален";
	}



	/*
	userIsSuper()
	Проверка пользователя на супер-юзера
	*/
	public function userIsSuper()
	{
		$account = $_SESSION['user']['id'];
		$sql = mysql_query("SELECT * FROM users WHERE id='$account'") or die(mysql_error());
		return mysql_fetch_assoc($sql);
	}



/*
	addNewUser($user)
	Создание пользователя-админа
	$user [assoc array] - массив с данными пользователя
	*/
	public function addNewUser($user)
	{
		extract($user);
		$passw = md5($email.$passw.Self::SALT);
		$users = self::getUsers();
		foreach ($users as $haveUser) {
			if ($login == $haveUser['login']) {
				echo 'Логин уже занят';
				return false;
			}
			if ($email == $haveUser['email']) {
				echo 'Адрес email уже занят';
				return false;
			}
		}
		$sql = "INSERT INTO users (name, login, pass, email) VALUES ('$login','$login','$passw','$email')";
		mysql_query($sql) or die(mysql_error());
		echo "Аккаун добавлен";
	}



	/*
	getUsers()
	Получение всех юзеров
	*/
	public function getUsers()
	{
		$select = mysql_query("SELECT * FROM users ORDER BY id ASC")or die(mysql_error());
		$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$ds[]=$r;
		}
		return $ds;
	}



	/**
	* @param string $userId - id юзера, которого тащим
	* @return array|null - инфа о юзере либо NULL, если юзер не найден
	*/
	public function getUser($userId)
	{
		$result = false;
		$goId = htmlspecialchars($userId);
		$select = mysql_query("SELECT * FROM users WHERE id=$goId")or die(mysql_error());
		$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$ds[]=$r;
		}
		$result = $ds[0];
		return $result;
	}


	/**
	* @param string $userName - username юзера, которого тащим
	* @return array|null - инфа о юзере либо NULL, если юзер не найден
	*/
	public function getUserByName($userName)
	{
		$result = false;
		$goId = htmlspecialchars($userName);
		$select = mysql_query("SELECT * FROM users WHERE login='$goId'")or die(mysql_error());
		if (mysql_num_rows($select) == 0) {
			return false;
		} else {
			$ds = array();
					while ($r = mysql_fetch_assoc($select)) {
						$ds[]=$r;
			}
			return $ds[0];
		}
	}

	/*
	getBugTicketsAjax($type, $personal, $personalMy)
	Получение тикетов баг-трекера для вывода через ajax
	$type [string] - какие тикеты выбирать
	$personal [string] - все или свои
	$personalMy [string] - все свои, где автор или где исполнитель
	*/
	public function getBugTicketsAjax($type, $personal, $personalMy)
	{
		switch ($type) {
			case 'all':
				$selectType = "";
				$orderBy = " ORDER BY id DESC ";
				break;

			case 'done':
				$selectType = " AND status = 'done'";
				$orderBy = " ORDER BY answer_time DESC";
				break;

			default:
				$selectType = " AND status = '".$type."' ";
				$orderBy = " ORDER BY id DESC ";
				break;
		}

		if ($personal=='1') {
				$userIdForTickets = $_SESSION['user']['id'];
				$userNameForTickets = $_SESSION['user']['name'];
				if ( $personalMy != '0' ) {
					$userMyCat = NULL;
					if ($personalMy=='1') {
						$userMyCat = 'author';
						$select = mysql_query("SELECT b.*, u.login as doer_name FROM `bugs` b LEFT JOIN `users` u ON b.doer = u.id WHERE ( archived = 0 AND ( author='".$userNameForTickets."')) ".$selectType.$orderBy)or die(mysql_error());
					} else if ($personalMy=='2') {
						$userMyCat = 'doer';
						$select = mysql_query("SELECT b.*, u.login as doer_name FROM `bugs` b LEFT JOIN `users` u ON b.doer = u.id WHERE ( archived = 0 AND (b.doer = '".$userIdForTickets."' )) ".$selectType.$orderBy)or die(mysql_error());
					}
					if ($userMyCat == NULL) {
						$userMyCat = 'all';
						$select = mysql_query("SELECT b.*, u.login as doer_name FROM `bugs` b LEFT JOIN `users` u ON b.doer = u.id WHERE ( archived = 0 AND ( author='".$userNameForTickets."' OR b.doer = '".$userIdForTickets."' )) ".$selectType.$orderBy)or die(mysql_error());
					}
				} else {
						$select = mysql_query("SELECT b.*, u.login as doer_name FROM `bugs` b LEFT JOIN `users` u ON b.doer = u.id WHERE ( archived = 0 AND ( author='".$userNameForTickets."' OR b.doer = '".$userIdForTickets."' )) ".$selectType.$orderBy)or die(mysql_error());
				}
		} else
		$select = mysql_query("SELECT b.*, u.login as doer_name FROM `bugs` b LEFT JOIN `users` u ON b.doer = u.id WHERE archived = 0 ".$selectType.$orderBy)or die(mysql_error());

				$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$r['time'] = Controller::getGoodDate($r['time']);
					switch ($r['tag']) {
						case 'site':
							$r['tag'] = 'Сайт';
							break;
						case 'admin':
							$r['tag'] = 'Админка';
							break;
						case 'noname':
							$r['tag'] = 'хзчо';
							break;

						default:
							$r['tag'] = 'notag';
							break;
					}
					$r['answer_time'] = Controller::getGoodDate($r['answer_time']);
					$r['doer'] = ($r['doer'] == NULL) ? "не назначен" : $r['doer_name'];
					$ds[]=$r;
				}
		array_push($ds, count($ds));
		return $ds;
	}



	/*
	getBugTickets($type)
	Получение тикетов баг-трекера
	$type [string] - какие тикеты выбирать
	*/
	public function getBugTickets($type="all")
	{
		switch ($type) {
			case 'all':
				$selectType = "";
				$orderBy = " ORDER BY id DESC ";
				break;

			case 'done':
				$selectType = " AND status = 'done'";
				$orderBy = " ORDER BY answer_time DESC";
				break;

			default:
				$selectType = " AND status = '".$type."' ";
				$orderBy = " ORDER BY id DESC ";
				break;
		}

		if (isset($_SESSION['user']['personalTickets'])) {
			if ($_SESSION['user']['personalTickets']=='1') {
				$userIdForTickets = $_SESSION['user']['id'];
				$userNameForTickets = $_SESSION['user']['name'];
				if (isset($_SESSION['user']['personalTicketsMy'])) {
					$userMyCat = NULL;
					if ($_SESSION['user']['personalTicketsMy']=='1') {
						$userMyCat = 'author';
						$select = mysql_query("SELECT b.*, u.login as doer_name FROM `bugs` b LEFT JOIN `users` u ON b.doer = u.id WHERE ( archived = 0 AND ( author='".$userNameForTickets."')) ".$selectType.$orderBy)or die(mysql_error());
					} else if ($_SESSION['user']['personalTicketsMy']=='2') {
						$userMyCat = 'doer';
						$select = mysql_query("SELECT b.*, u.login as doer_name FROM `bugs` b LEFT JOIN `users` u ON b.doer = u.id WHERE ( archived = 0 AND (b.doer = '".$userIdForTickets."' )) ".$selectType.$orderBy)or die(mysql_error());
					}
					if ($userMyCat == NULL) {
						$userMyCat = 'all';
						$select = mysql_query("SELECT b.*, u.login as doer_name FROM `bugs` b LEFT JOIN `users` u ON b.doer = u.id WHERE ( archived = 0 AND ( author='".$userNameForTickets."' OR b.doer = '".$userIdForTickets."' )) ".$selectType.$orderBy)or die(mysql_error());
					}
				} else {
						$select = mysql_query("SELECT b.*, u.login as doer_name FROM `bugs` b LEFT JOIN `users` u ON b.doer = u.id WHERE ( archived = 0 AND ( author='".$userNameForTickets."' OR b.doer = '".$userIdForTickets."' )) ".$selectType.$orderBy)or die(mysql_error());
				}
			} else
			$select = mysql_query("SELECT b.*, u.login as doer_name FROM `bugs` b LEFT JOIN `users` u ON b.doer = u.id WHERE archived = 0 ".$selectType.$orderBy)or die(mysql_error());
		} else
		$select = mysql_query("SELECT b.*, u.login as doer_name FROM `bugs` b LEFT JOIN `users` u ON b.doer = u.id WHERE archived = 0 ".$selectType.$orderBy)or die(mysql_error());
		// $select = mysql_query("SELECT * FROM bugs LEFT OUTER JOIN users ON bugs.doer = users.id WHERE bugs.archived = 0 ".$selectType." ORDER BY id DESC")or die(mysql_error());

				$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$r['time'] = Controller::getGoodDate($r['time']);
					switch ($r['tag']) {
						case 'site':
							$r['tag'] = 'Сайт';
							break;
						case 'admin':
							$r['tag'] = 'Админка';
							break;
						case 'noname':
							$r['tag'] = 'хзчо';
							break;

						default:
							$r['tag'] = 'notag';
							break;
					}
					$r['answer_time'] = Controller::getGoodDate($r['answer_time']);
					$r['doer'] = ($r['doer'] == NULL) ? "не назначен" : $r['doer_name'];
					$ds[]=$r;
				}
		array_push($ds, count($ds));
		return $ds;
	}



	/*
	newTicket($ticket)
	Добавить новый тикет
	$ticket [assoc array] - массив с информацией о тикете
	*/
	public function newTicket($ticket)
	{
		extract($ticket);
		$author = $_SESSION['user']['name'];
		$title = Self::prepareToDB($title);
		$body = Self::prepareToDB($body);
		$author = Self::prepareToDB($author);
		echo "public function newTicket()";
		$sql = "INSERT INTO bugs (tag, title, body, author) VALUES ('$tag','$title','$body','$author')";
		mysql_query($sql) or die(mysql_error());
		echo "Тикет добавлен!";
	}



	/*
	setDoerTicket($ticket)
	Назначение исполнителя
	$ticket [assoc array] - массив с информацией о тикете и исполнителе
	*/
	public function setDoerTicket($ticket)
	{
		$ticket_id = $ticket['ticket'];
		$user_id = $ticket['uid'];
		$user_id = Self::prepareToDB($user_id);
		$ticket_id = Self::prepareToDB($ticket_id);
		echo "public function setDoerTicket()\r\n";
		$sql = "UPDATE bugs SET doer = $user_id WHERE id = $ticket_id ";
		mysql_query($sql) or die(mysql_error());
		echo "Исполнитель добавлен!";
	}



	/*
	getOneTicket($ticket)
	Получить информацию о тикете по id либо 'last'
	$ticket [int|string] - id тикета или 'last'
	*/
	public function getOneTicket($ticket="last")
	{
		if ($ticket == 'last') {
			$sql = mysql_query("SELECT b.*, u.login as doer_name FROM `bugs` b LEFT JOIN `users` u ON b.doer = u.id ORDER BY b.id DESC LIMIT 1") or die(mysql_error());
			$answ = mysql_fetch_assoc($sql);
			$answ['time'] = Controller::getGoodDate($answ['time']);
			$answ['answer_time'] = Controller::getGoodDate($answ['answer_time']);
			$answ['doer'] = ($answ['doer']==NULL) ? 'не назначен' : $answ['doer_name'];
			switch ($answ['tag']) {
				case 'site':
					$answ['tag'] = 'Сайт';
					break;
				case 'admin':
					$answ['tag'] = 'Админка';
					break;
				case 'noname':
					$answ['tag'] = 'хзчо';
					break;

				default:
					$answ['tag'] = 'notag';
					break;
			}
			return $answ;
		} else {
			$sql = mysql_query("SELECT b.*, u.login as doer_name FROM `bugs` b LEFT JOIN `users` u ON b.doer = u.id WHERE b.id = '$ticket' LIMIT 1") or die(mysql_error());
			$answ = mysql_fetch_assoc($sql);
			if (!$answ){
				return "no ticket";
			} else
			{
				$answ['time'] = Controller::getGoodDate($answ['time']);
				$answ['answer_time'] = Controller::getGoodDate($answ['answer_time']);
				$answ['doer'] = ($answ['doer']==NULL) ? 'не назначен' : $answ['doer_name'];
				switch ($answ['tag']) {
					case 'site':
						$answ['tag'] = 'Сайт';
						break;
					case 'admin':
						$answ['tag'] = 'Админка';
						break;
					case 'noname':
						$answ['tag'] = 'хзчо';
						break;

					default:
						$answ['tag'] = 'notag';
						break;
				}
				return $answ;
			}
		}
	}



	/*
	deleteTicket($id, $reason)
	Удаление (архивирование) тикета
	$id [int|string] - id тикета
	$reason [string] - причина удаления
	*/
	public function deleteTicket($id, $reason = "Without explaning the reason")
	{
		$doer = $_SESSION['user']['id'];
		$date = date('Y-m-d h:i:s');
			$sql = mysql_query("UPDATE bugs SET doer='$doer', archived=1, answer='$reason', answer_time='$date' WHERE id='$id'") or die(mysql_error());
			echo 'Model_Admin::deleteTicket() - Ok!';
	}



	/*
	restoreTicket($id)
	Восстановление (разархивирование) тикета
	$id [int|string] - id тикета
	*/
	public function restoreTicket($id)
	{
		$doer = $_SESSION['user']['id'];
		$date = date('Y-m-d h:i:s');
			$sql = mysql_query("UPDATE bugs SET doer='$doer', archived=0, answer='', answer_time='$date' WHERE id='$id'") or die(mysql_error());
			echo 'Model_Admin::restoreTicket() - Ok!';
	}



	/*
	countTickets($type)
	Получение кол-ва тикетов баг-трекера
	$type [string] - какие тикеты выбирать
	*/
	public function countTickets($type="all", $token = "token missed")
	{

		if (isset($_POST['token'])) {
			$token = $_POST['token'];
		}

		if ($token!="ajaxCount"){
			return "Failed!<br><small><b>Notice:</b><i> ".$token."</i></small>";
		}

		switch ($type) {
			case 'all':
				$selectType = "";
				break;

			default:
				$selectType = " AND status = '".$type."' ";
				break;
		}

		$select = mysql_query("SELECT * FROM bugs WHERE archived = 0 ".$selectType." ORDER BY id DESC")or die(mysql_error());
		$r = mysql_num_rows($select);
		return $r;
	}

	/*
	prepareToDB($val)
	подготовка данных для безопасной вставки
	$val [string] - строка для обработки
	*/
	function prepareToDB($val)
	{
		return addslashes($val);
	}


	/*
	resetRows($table, $key)
	Пересчет Id в таблице
	$table [string] - имя таблицы
	$key [string] - имя столбца с PRIMARY KEY
	*/
	function resetRows($table, $key)
	{
		mysql_query("ALTER TABLE $table MODIFY $key INT(11)");
		mysql_query("ALTER TABLE $table DROP PRIMARY KEY");
		mysql_query("UPDATE $table SET $key='0';");
		mysql_query("ALTER TABLE $table AUTO_INCREMENT=0");
		mysql_query("ALTER TABLE $table MODIFY $key INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY");
	}

}