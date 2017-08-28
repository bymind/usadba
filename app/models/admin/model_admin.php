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
	moveCatsToParent()
	Перемещение категорий в корень после удаления родительской категории
	*/
	public function moveCatsToParent($parentCatId)
	{
		$parentCatId = htmlspecialchars($parentCatId);
		$countChild = mysql_num_rows(mysql_query("SELECT * FROM prod_cat WHERE parent = $parentCatId"));
		$sql = "SELECT position FROM prod_cat WHERE parent=0 ORDER BY position DESC LIMIT 1";
		$result = mysql_query($sql) or die(mysql_error());
		if ($result) {
			while ($row = mysql_fetch_array($result)) {
				$lastPosition = (int)$row['position'];
			}
		}
		$lastPosition++;
		for ($i = 0; $i < $countChild; $i++) {
			$select = mysql_query("UPDATE prod_cat SET parent = 0, position = ".$lastPosition." WHERE parent = ".$parentCatId." LIMIT 1") or die(mysql_error());
			$lastPosition++;
		}
		// $q = "UPDATE prod_cat SET parent = 0, position=".$lastPosition." WHERE parent = ".$parentCatId;
		return true;
	}


	public function moveFromCatToCat($fromid, $toid)
	{
		$fromid = htmlspecialchars($fromid);
		$toid = htmlspecialchars($toid);
		$select = mysql_query("UPDATE prod_items SET cat = '$toid' WHERE cat = '$fromid'") or die(mysql_error());
		return true;
	}

	public function moveCat($catData)
	{
		$catParent = (int)$catData['parent'];
		$catId = (int)$catData['catid'];
		$catMoveIndex = (int)$catData['direction'];
		$catMovePosition = (int)$catData['position'] + $catMoveIndex;
		$catAnotherPosition = (int)$catData['position'] - $catMoveIndex;
		$catMoveAnotherPosition = (int)$catData['position'];
		$select = mysql_query("UPDATE prod_cat SET position = '$catMoveAnotherPosition' WHERE position = '$catMovePosition' AND id !='$catId' AND parent='$catParent'") or die(mysql_error());
		$select = mysql_query("UPDATE prod_cat SET position = '$catMovePosition' WHERE id='$catId'") or die(mysql_error());
		return true;
	}

	public function deleteCategory($parentCatId, $position, $parent)
	{
		$parentCatId = htmlspecialchars($parentCatId);
		$position = htmlspecialchars($position);
		$parent = htmlspecialchars($parent);
		$select = mysql_query("UPDATE prod_cat SET `position` = `position`-1 WHERE parent='".$parent."' and position > ".$position) or die(mysql_error());
		$select = mysql_query("DELETE FROM prod_cat WHERE id = ".$parentCatId) or die(mysql_error());
	}


	/*
	moveCatsToParent()
	Перемещение категорий в корень после удаления родительской категории
	*/
	public function deleteProdsFromCat($parentCatId)
	{
		$parentCatId = htmlspecialchars($parentCatId);
		$select = mysql_query("DELETE FROM prod_items WHERE cat = ".$parentCatId) or die(mysql_error());
		return true;
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
			$select = mysql_query("SELECT p.*, u.id as uid,u.name as uname,c.name as cname,c.tech_name as ctech_name, c.parent as cparent FROM prod_items p LEFT JOIN prod_cat c on p.cat=c.id LEFT JOIN users u on p.author=u.id WHERE (c.id=".$cat_id." OR c.parent=".$cat_id.") AND p.archived = 0 ORDER BY added_time DESC")or die(mysql_error());
		} else
		$select = mysql_query("SELECT p.*, u.id as uid, u.name as uname FROM prod_items p LEFT JOIN users u on p.author=u.id WHERE p.archived = 0 ORDER BY id DESC")or die(mysql_error());
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
			$select = mysql_query("SELECT p.*, u.id as uid,u.name as uname,c.name as cname,c.tech_name as ctech_name, c.parent as cparent FROM prod_items p LEFT JOIN prod_cat c on p.cat=c.id LEFT JOIN users u on p.author=u.id WHERE (c.id=".$cat_id." OR c.parent=".$cat_id.") AND p.archived = 1 ORDER BY added_time DESC")or die(mysql_error());
		} else
		$select = mysql_query("SELECT p.*, u.id as uid, u.name as uname FROM prod_items p LEFT JOIN users u on p.author=u.id WHERE p.archived = 1 ORDER BY id DESC")or die(mysql_error());
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
		$q = mysql_query("SELECT * FROM prod_cat ORDER BY position") or die(mysql_error());
		while ( $buf = mysql_fetch_assoc($q)) {
			$prod_cats[$buf['id']] = $buf;
		}
		unset($q);
		$cat_tree = Self::createCatTree($prod_cats);
		unset($prod_cats);

		return $cat_tree;
	}

	/*
	getCatName()
	Получение имени категории
	*/
	public function getCatName($catid)
	{
		$q = mysql_query("SELECT name FROM prod_cat WHERE id = '$catid' LIMIT 1") or die(mysql_error());
		while ( $buf = mysql_fetch_assoc($q)) {
			$catName  = $buf;
		}

		return $catName;
	}


	/*
	getArticlesLists()
	Получение списка статей
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
	getPagesLists()
	Получение списка страниц
	*/
	public function getPagesLists()
	{
		$select = mysql_query("SELECT * FROM pages WHERE archived = 0 ORDER BY datetime DESC")or die(mysql_error());
				$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$r['datetime'] = Controller::getGoodDate($r['datetime']);
					$ds[]=$r;
		}

		return $ds;
	}


	/*
	getConfigs()
	Получение списка конфигов (не архивных)
	*/
	public function getConfigs()
	{
		// $nowDate = date('Y-m-d h:i:s');
		$select = mysql_query("SELECT * FROM config WHERE archived =0 ORDER BY id")or die(mysql_error());
				$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					// if ($r['section']=="links") {
					// 	$num = preg_replace("/[^0-9]/", '', $r['name']);
					// 	$vals = explode("\n",$r['value']);
					// 	$r['footerText'.$num] = trim($vals[0]);
					// 	$r['footerLink'.$num] = trim($vals[1]);
					// } else {
						$r[$r['name']] =$r['value'];
					// }
						$ds[$r['name']]=$r;
		}
		return $ds;
	}

	function updateConfigs($configs)
	{
		foreach ($configs['ids'] as $key => $value) {
			// $configs['vals'][$key] = addslashes($configs['vals'][$key]);
			$configVal = addslashes($configs['vals'][$key]);
			$upd = mysql_query("UPDATE config SET value = '$configVal' WHERE name='$value' ") or die(mysql_error());
			echo "$configVal ";
			echo "$value ";
		}
			return true;
	}

	/*
	getSalesLists()
	Получение списка акции
	*/
	public function getSalesLists()
	{
		$nowDate = date('Y-m-d h:i:s');
		$select = mysql_query("SELECT * FROM sales WHERE end_time > '$nowDate' ORDER BY id DESC")or die(mysql_error());
				$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$r['start_time'] = Controller::getGoodDate($r['start_time']);
					$r['end_time'] = Controller::getGoodDate($r['end_time']);
					$r['url'] = $r['tech_name'];
					$ds[]=$r;
		}

		return $ds;
	}

	/*
	getSalesPost($url)
	*/
	public function getSalesPost($post_url)
	{
		$select = mysql_query("SELECT * FROM sales WHERE tech_name = '$post_url'")or die(mysql_error());
		$ds = mysql_fetch_assoc($select);
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
	getPagesPost($url)
	Получение страницы по её url
	$url [string] - url страницы
	*/
	public function getPagesPost($post_url)
	{
		$select = mysql_query("SELECT * FROM pages WHERE tech_name = '$post_url'")or die(mysql_error());
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
	getSalesArchived()
	Получение списка статей в архиве
	*/
	public function getSalesArchived()
	{
		$cur_date = date('Y-m-d h:i:s');
		$select = mysql_query("SELECT * FROM sales WHERE end_time < '$cur_date' ORDER BY start_time DESC")or die(mysql_error());
				$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$r['start_time'] = Controller::getGoodDate($r['start_time']);
					$r['end_time'] = Controller::getGoodDate($r['end_time']);
					$ds[]=$r;
		}
		return $ds;
	}



	/*
	getPagesPostsArchived()
	Получение списка статей в архиве
	*/
	public function getPagesPostsArchived()
	{
		$select = mysql_query("SELECT * FROM pages WHERE archived = 1 ORDER BY datetime DESC")or die(mysql_error());
				$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$r['datetime'] = Controller::getGoodDate($r['datetime']);
					$ds[]=$r;
		}
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
	updateSale($post)
	$post [assoc array] - массив с информацией о статье
	*/
	public function updateSale($post)
	{
		extract($post);
		$sales_prod = json_decode($sales_prod, true);
		$sales_prods_ids = $sales_prod['prod'];
		$sales_cats_ids = $sales_prod['cat'];
		$sales_prods = implode(",", $sales_prods_ids);
		$sales_cats = implode(",", $sales_cats_ids);

		$end_time = $end_time." 23:59:59";
		$sql = "UPDATE sales SET tech_name = '$url', name = '$title', poster = '$poster', description = '$text', start_time='$start_time', end_time='$end_time', cats = '$sales_cats', prods = '$sales_prods' WHERE id = '$id'";
		mysql_query($sql) or die(mysql_error());
		Self::updateSaleProds($sales_prods, $id);
		echo "Акция сохранена";
	}

	function updateSaleProds($prods_ids, $sale_id)
	{
		// $sql = "UPDATE prod_items SET sales_id = NULL WHERE sales_id = '$sale_id'";
		// mysql_query($sql) or die(mysql_error());

		$sql = "SELECT id,labels FROM prod_items WHERE sales_id = '$sale_id' AND id NOT IN ($prods_ids)";
		$res = mysql_query($sql) or die(mysql_error());
		$ds = [];
		while ($row = mysql_fetch_assoc($res)) {
			// var_dump($row);
			$ds[] = $row;
		}
		$sql = "";
		foreach ($ds as $row) {
			// echo "hello \r\n";
			$labels = explode(",",$row['labels']);
			$newLabels = [];
			foreach ($labels as $label) {
				if ($label!="sales") {
					$newLabels[] = $label;
				}
				// var_dump($newLabels);
			}
			$row['labels'] = implode(",", $newLabels);
			// var_dump($row['labels']);
			$sql = "UPDATE prod_items SET labels = '".$row['labels']."', sales_id = NULL WHERE id=".$row['id'];
			// var_dump($sql);
			mysql_query($sql) or die(mysql_error());
		}
		// if ($sql!="") {
		// }

		$sql = "UPDATE prod_items SET sales_id = '$sale_id' WHERE id in ($prods_ids)";
		mysql_query($sql) or die(mysql_error());
		// $prods = explode(",", $prods_ids);
		$ds = [];
		$sql = "SELECT id,labels FROM prod_items WHERE id in ($prods_ids)";
		$res = mysql_query($sql) or die(mysql_error());
		while ($row = mysql_fetch_assoc($res)) {
			if (strripos($row['labels'], "sales") === false) {
				if ($row['labels']=="") {
					$row['labels'] = "sales";
				} else
				$row['labels'] .= ",sales";
				$ds[] = $row;
			} else {
				$ds[] = $row;
			}
		}
		$sql = "UPDATE prod_items SET `labels`= CASE ";
		foreach ($ds as $value) {
			// echo ('DS!');
			$sql .= "WHEN id='".$value['id']."' THEN '".$value['labels']."' ";
		}
		$sql .=" END WHERE id in ($prods_ids)";
		if ($sql!="") {
			// var_dump($sql);
			// echo "\r\n";
			// var_dump($prods_ids);
			// echo "\r\n";
			// var_dump($sale_id);
			// echo "\r\n";
			// var_dump($ds);
			// echo "\r\n";
			mysql_query($sql) or die(mysql_error());
		}

	}

	/*
	updatePage($post)
	Изменение страницы
	$post [assoc array] - массив с информацией о странице
	*/
	public function updatePage($post)
	{
		extract($post);
		$author = $_SESSION['user']['name'];
		$sql = "UPDATE pages SET tech_name = '$url', title = '$title', subtitle = '$subtitle', poster = '$poster', author = '$author', body = '$text' WHERE id = '$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Страница сохранена";
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
		$sql = "UPDATE prod_items SET art = '$art', cat = '$cat', name = '$name', tech_name = '$tech_name', images = '$images', mini_desc = '$mini_desc', description = '$description', author = '$author', pod = '$pod', price = '$price', labels = '$labels', weight='$weight', country = '$country', stor_cond = '$stor_cond', nut_val = '$nut_val', energy_val='$energy_val', consist = '$consist' WHERE id = '$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Позиция сохранена";
	}



	/*
	archiveSale($post)
	$post [assoc array] - массив с информацией
	*/
	public function archiveSale($prod)
	{
		extract($prod);
		$cur_date = date('Y-m-d h:i:s');
		$sql = "UPDATE sales SET end_time = '$cur_date' WHERE id = '$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Акция завершена";
	}


	/*
	archivePage($post)
	Отправить страницу в архив
	$post [assoc array] - массив с информацией о страницей
	*/
	public function archivePage($prod)
	{
		extract($prod);
		$author = $_SESSION['user']['name'];
		$sql = "UPDATE pages SET archived = 1 WHERE id = '$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Страница скрыта от публикации";
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
	unarchivePage($post)
	Опубликовать страницу из архива
	$post [assoc array] - массив с информацией о странице
	*/
	public function unarchivePage($post)
	{
		extract($post);
		$author = $_SESSION['user']['name'];
		$sql = "UPDATE pages SET archived = 0 WHERE id = '$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Страница опубликована";
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
	deleteSale($post)
	$post [assoc array] - массив с информацией о статье
	*/
	public function deleteSale($post)
	{
		extract($post);
		$sql = "DELETE FROM sales WHERE id='$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Акция полностью удалена!";
	}


	/*
	deletePost($post)
	Удалить статью
	$post [assoc array] - массив с информацией о статье
	*/
	public function deletePage($post)
	{
		extract($post);
		$author = $_SESSION['user']['name'];
		$sql = "DELETE FROM pages WHERE id='$id'";
		mysql_query($sql) or die(mysql_error());
		echo "Страница полностью удалена!";
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
	Удалить позицию
	$prod [assoc array] - массив с информацией о товаре
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
	newSale($post)
	$post [assoc array] - массив с информацией
	*/
	public function newSale($post)
	{
		extract($post);
		$sales_prod = json_decode($sales_prod, true);
		$sales_prods_ids = $sales_prod['prod'];
		$sales_cats_ids = $sales_prod['cat'];
		$sales_prods = implode(",", $sales_prods_ids);
		$sales_cats = implode(",", $sales_cats_ids);;

		$sql = "INSERT INTO sales (name, tech_name, poster, start_time, end_time, description, cats, prods) VALUES ('$title','$url','$poster','$start_time','$end_time','$text','$sales_cats','$sales_prods')";
		mysql_query($sql) or die(mysql_error());
		echo "Акция добавлена";
	}

	public function setSoundSetting($param)
	{
		$uid = $_SESSION['user']['id'];
		$q = mysql_query("UPDATE users SET sound='$param' WHERE id ='$uid' ") or die(mysql_error());
		return true;
	}

	/*
	newPage($post)
	Добавить новую страницу
	$post [assoc array] - массив с информацией о странице
	*/
	public function newPage($post)
	{
		extract($post);
		$author = $_SESSION['user']['name'];
		$url = htmlspecialchars($url);
		if ((isset($archived)) && ($archived == 1)) {
			$sql = "INSERT INTO pages (tech_name, title, subtitle, poster, author, body, archived) VALUES ('$url','$title','$subtitle','$poster','$author','$text', 1)";
		} else
		$sql = "INSERT INTO pages (tech_name, title, subtitle, poster, author, body) VALUES ('$url','$title','$subtitle','$poster','$author','$text')";
		mysql_query($sql) or die(mysql_error());
		echo "Страница добавлена";
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
			$sql = "INSERT INTO prod_items (art, name, tech_name, images, mini_desc, description, cat, author, price, archived, weight, country, stor_cond, nut_val, energy_val, consist, labels, pod) VALUES ('$art','$name','$tech_name','$images','$mini_desc','$description','$cat','$author','$price', 1, '$weight', '$country', '$stor_cond', '$nut_val', '$energy_val', '$consist', '$labels', '$pod')";
		} else
		$sql = "INSERT INTO prod_items (art, name, tech_name, images, mini_desc, description, cat, author, price, weight, country, stor_cond, nut_val, energy_val, consist, labels) VALUES ('$art','$name','$tech_name','$images','$mini_desc','$description','$cat','$author', '$price', '$weight', '$country', '$stor_cond', '$nut_val', '$energy_val', '$consist', '$labels')";
		// TODO: edit inserting products with price
		mysql_query($sql) or die(mysql_error());
		echo "Позиция добавлена";
	}


	/*
	redCat($cat)
	Редактирование категории
	$cat [assoc array] - массив с информацией о категории
	*/
	public function redCat($cat)
	{
		extract($cat);
		$name = htmlspecialchars($name);
		$tech_name = htmlspecialchars($tech_name);
		$checkName = "SELECT * FROM prod_cat WHERE (name = '$name' OR tech_name='$tech_name') AND (id != '$id')";
		if (mysql_num_rows(mysql_query($checkName)) > 0) {
			echo "Категория с таким именем уже существует!";
		} else {
				$sql = "UPDATE prod_cat SET name='$name', tech_name='$tech_name', parent='$parent', poster='$poster', show_big='$show_big', position='$position', show_popular='$show_popular', per_page='$per_page' WHERE id='$id' ";
			mysql_query($sql) or die(mysql_error());
			echo "Категория сохранена";
		}
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
			// if ($parent == "0") {
				$sql = "SELECT position FROM prod_cat WHERE parent='$parent' ORDER BY position DESC LIMIT 1";
				$result = mysql_query($sql) or die(mysql_error());
				if ($result) {
					while ($row = mysql_fetch_array($result)) {
						$lastPosition = (int)$row['position'];
					}
				}
				$lastPosition++;
				$sql = "INSERT INTO prod_cat (name, tech_name, parent, poster, show_big, show_popular, position, per_page) VALUES ('$name','$tech_name','$parent','$poster','$show_big','$show_popular','$lastPosition', '$per_page')";
			/*} else {
				$sql = "INSERT INTO prod_cat (name, tech_name, parent, poster, show_big, show_popular) VALUES ('$name','$tech_name','$parent','$poster','$show_big','$show_popular')";
			}*/
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
	editUser($user)
	Создание пользователя-админа
	$user [assoc array] - массив с данными пользователя
	*/
	public function editUser($user)
	{
		foreach ($user as $name => $value) {
			if ($name != 'id') {
			$sql = "UPDATE users SET $name = '$value' WHERE id = ".$user['id'];
			mysql_query($sql) or die(mysql_error());
			}
		}
		echo "true";
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
		if ($isadmin) {
			$isadmin = "1";
		} else $isadmin = "0";
		$sql = "INSERT INTO users (name, login, pass, email, isadmin) VALUES ('$login','$login','$passw','$email', '$isadmin')";
		mysql_query($sql) or die(mysql_error());
		echo "Аккаун добавлен";
	}



	/*
	getUsers()
	Получение всех юзеров
	*/
	public function getAdminUsers()
	{
		$select = mysql_query("SELECT * FROM users WHERE isadmin=1 ORDER BY id ASC")or die(mysql_error());
		$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$r['admin_rights'] = explode(',',$r['admin_rights']);
					$r['admin_rights_texts'] = Self::getRightsText($r['admin_rights']);
					$ds[]=$r;
		}
		return $ds;
	}

	function editRights($data)
	{
		extract($data);
		$rights = implode(",",$rights);
		$select = mysql_query("UPDATE users SET admin_rights='$rights' WHERE isadmin=1 AND id='$uid'")or die(mysql_error());
		return true;
	}

	function getRights($id)
	{
		$select = mysql_query("SELECT * FROM users WHERE id='$id' AND isadmin=1 LIMIT 1")or die(mysql_error());
		$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$r['admin_rights'] = explode(',',$r['admin_rights']);
					$r['admin_rights_texts'] = Self::getRightsText($r['admin_rights']);
					$ds[]=$r;
		}
		$ds = $ds[0];
		$res = $ds['admin_rights'];
		$res = json_encode($res, JSON_UNESCAPED_UNICODE);
		return $res;
	}

	/*
	getUser()
	*/
	public function getAdminUser($id)
	{
		$select = mysql_query("SELECT * FROM users WHERE id='$id' AND isadmin=1 ORDER BY id ASC")or die(mysql_error());
		$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$r['admin_rights'] = explode(',',$r['admin_rights']);
					$r['admin_rights_texts'] = Self::getRightsText($r['admin_rights']);
					$ds[]=$r;
		}
		return $ds[0];
	}

	public function getRightsText($names)
	{
		$rights = implode("','",$names);
		$sql = mysql_query("SELECT text FROM admin_rights WHERE name in ('$rights')") or die(mysql_error());
		$ds = array();
		while ($r = mysql_fetch_array($sql)) {
			$ds[] = $r[0];
		}
		return $ds;
	}


	function isHasRight($arr)
	{
		if ($_SESSION['user']['isadmin']==1) {
			extract($arr);
			$userInfo = Self::getUser($uid);
			if ($userInfo['isadmin']==1) {
				$rights = $userInfo['admin_rights'];
				$rights = explode(',',$rights);
				if ( in_array($right,$rights) || in_array('all',$rights) ) {
					echo "1";
				} else echo "0";
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}



	/*
	getUsers()
	Получение всех юзеров
	*/
	public function getSimpleUsers()
	{
		$select = mysql_query("SELECT * FROM users WHERE isadmin=0 ORDER BY id ASC")or die(mysql_error());
		$ds = array();
				while ($r = mysql_fetch_assoc($select)) {
					$ds[]=$r;
		}
		return $ds;
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


	public function setOrderNewStat($orderId, $status)
	{
		if (mysql_num_rows(mysql_query("SELECT * FROM orders WHERE id=$orderId")) > 0) {
			if (mysql_num_rows(mysql_query("SELECT * FROM stat_text WHERE id=($status+1)")) > 0) {
				mysql_query("UPDATE orders SET stat=($status+1) WHERE id=$orderId")or die(mysql_error());
				echo "ok";
				return true;
			}
		} else {
			echo "Error";
			return false;
		}
	}

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

	public function countOrders($param =NULL)
	{
		switch ($param) {

			case 'new':
			$ds = mysql_num_rows(mysql_query("SELECT * FROM orders WHERE archived=0 AND stat=1"));
			break;

			case 'actual':
			default:
				$statLabels = ['new','progress','done','fail'];
				$ds['new'] = mysql_num_rows(mysql_query("SELECT * FROM orders WHERE archived=0 AND stat=1"));
				$ds['progress'] = mysql_num_rows(mysql_query("SELECT * FROM orders WHERE archived=0 AND stat=2"));
				$ds['done'] = mysql_num_rows(mysql_query("SELECT * FROM orders WHERE archived=0 AND stat=3"));
				$ds['fail'] = mysql_num_rows(mysql_query("SELECT * FROM orders WHERE archived=0 AND stat=4"));
				$ds['all'] = mysql_num_rows(mysql_query("SELECT * FROM orders WHERE archived=0"));
				break;
		}
		return $ds;
	}

	public function getLastOrderId()
	{
		$q = mysql_query("SELECT id FROM orders ORDER BY id DESC LIMIT 1") or die(mysql_error());
		$q = mysql_fetch_array($q);
		return $q[0];
	}

	public function getOrder($orderId)
	{
		$statLabels = ['new','progress','done','fail'];
		$q = mysql_query("SELECT * FROM stat_text ORDER BY id") or die(mysql_error());
		$statText = array();
		while ($r = mysql_fetch_assoc($q)) {
			$statText[] = $r['text'];
		}
		$orderId = Self::prepareToDB($orderId);
		$q = mysql_query("SELECT * FROM orders WHERE id=$orderId") or die(mysql_error());
		if (!$q) {
			echo "Error: no order";
			return false;
		}
		$r = mysql_fetch_assoc($q);
		$r['timestamp'] = Controller::getGoodDate($r['datetime'],'compact');
		$r['datetime'] = Controller::getGoodDate($r['datetime']);
		$r['prod_list'] = json_decode($r['prod_list'], true);
		$r['stat_label'] = $statLabels[$r['stat']-1];
		$r['stat_text'] = $statText[$r['stat']-1];
		$r['user'] = Self::getUser($r['uid']);
		$r['stats'] = $statText;
		$r['comm'] = nl2br($r['comm']);

		return $r;
	}

	public function getOrders($param =NULL, $startNum =NULL, $endNum =NULL)
	{
		$statLabels = ['new','progress','done','fail'];
		$q = mysql_query("SELECT * FROM stat_text ORDER BY id") or die(mysql_error());
		$statText = array();
		while ($r = mysql_fetch_assoc($q)) {
			$statText[] = $r['text'];
		}

		switch ($param) {
			case 'last':
				# последние startNum штук
			if ($startNum==NULL) {
				$startNum = 20;
			}
				$q = mysql_query("SELECT * FROM orders WHERE archived=0 ORDER BY datetime DESC LIMIT $startNum") or die(mysql_error());
				$curPage = 1;
				break;

			case 'mid':
				# средние со startNum по endNum
				if ($startNum == NULL) {
					echo "$startNum error";
					return false;
				}
				if ($endNum==NULL) {
					$endNum = $startNum + 20;
				}
				$q = mysql_query("SELECT * FROM orders WHERE archived=0 ORDER BY datetime DESC LIMIT $startNum, 20") or die(mysql_error());
				break;

			default:
				$q = mysql_query("SELECT * FROM orders WHERE archived=0 ORDER BY datetime DESC LIMIT 10") or die(mysql_error());
				break;
		}
		while ($r = mysql_fetch_assoc($q)) {
			$r['timestamp'] = Controller::getGoodDate($r['datetime'],'compact');
			$r['datetime'] = Controller::getGoodDate($r['datetime']);
			$r['prod_list'] = json_decode($r['prod_list'], true);
			$r['stat_label'] = $statLabels[$r['stat']-1];
			$r['stat_text'] = $statText[$r['stat']-1];
			$r['user'] = Self::getUser($r['uid']);
			$r['stats'] = $statText;
			$r['comm'] = nl2br($r['comm']);
			$ds[] = $r;
		}
		return $ds;
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