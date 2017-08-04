<?php

class Model_User extends Model
{
	public function getLogin($jsonLogin)
	{
		$login = $jsonLogin;
		$loginEmail = $login['email'];
		$loginPass = $login['password'];

		$loginPassword = md5($loginEmail.$loginPass.Self::SALT);
		$q = mysql_query("SELECT * FROM users WHERE email='$loginEmail'") or die(mysql_error()) ;
		$countq = mysql_num_rows($q);
		if ($countq>0) {
			$user = mysql_fetch_assoc($q);
			$user['is_admin'] = $user['isadmin'];
			if ($loginPassword == $user['pass']) {
				$_SESSION['user']['access_key'] = md5($user['pass'].Model::SALT);
				unset($user['pass']);
				return $user;
			} else return false;
		} else {
			return false;
		}

		return 0;
	}

	public function getUser($uid)
	{
		$q = mysql_query("SELECT * FROM users WHERE id='$uid'") or die(mysql_error()) ;
		$countq = mysql_num_rows($q);
		if ($countq>0) {
			$userData['profile'] = mysql_fetch_assoc($q);
			if (isset($userData['profile']['bday'])) {
				$ts = $userData['profile']['bday'];
				$userData['profile']['bday_raw'] = $ts;
				$userData['profile']['bday'] = Controller::getGoodDate($ts, 'compact');
			}
			if (isset($userData['profile']['addresses'])) {
				$ts = explode("_",$userData['profile']['addresses']);
				$userData['profile']['addresses'] = $ts;
			}
			return $userData;
		} else return false;
	}

	public function updPass($newpass)
	{
		$oldPass = $newpass['oldp'];
		$newPass = $newpass['newp'];
		$user = Self::getUser($_SESSION['user']['id']);
		$cryptOld = md5($user['profile']['email'].$newpass['oldp'].Self::SALT);
		if ($cryptOld == $user['profile']['pass']) {
			if ($newPass) {
				$u['name'] = $_SESSION['user']['name'];
				$u['email'] = $_SESSION['user']['email'];
				$u['phone'] = $_SESSION['user']['phone'];
				$u['bd'] = $_SESSION['user']['bday'];
				$u['p'] = $newPass;
				if (Self::updUser($u)) {
					return true;
				}
			}
		}
		return false;
	}

	public function addOrder($orderData)
	{
		extract($orderData);
		$prodArts = array();
		foreach ($prods['items'] as $key => $value) {
			$countBuy = $value['count'];
			$q = mysql_query("UPDATE prod_items SET count_buy = count_buy + $countBuy WHERE art = '$key'") or die(mysql_error());
		}
		// $prodArts = implode(",",$prodArts);
		// $q = mysql_query("UPDATE prod_items SET count_buy = count_buy + 1 WHERE art in ($prodArts)") or die(mysql_error());
		$orderProds = json_encode($prods, JSON_UNESCAPED_UNICODE);
		if ($logged) {
		} else
			$uid = 0;
		$comm = addslashes($comm);
		$name = addslashes($name);
		$addr = addslashes($addr);
		if ($cash) {
			$paytype = "cash";
		} else if ($payonline) {
			$paytype = "online";
		}
		$q = mysql_query("INSERT INTO orders (uid, name, phone, addr, comm, pay_type, prod_list, stat) VALUES ('$uid', '$name', '$phone', '$addr', '$comm', '$paytype', '$orderProds', 1)") or die(mysql_error());
	}

	public function updUser($uData)
	{
		$uData['name'] = addslashes($uData['name']);
		$uData['email'] = addslashes($uData['email']);
		$uData['phone'] = addslashes($uData['phone']);
		$uData['bd'] = addslashes($uData['bd']);
		$newCrypt = md5($uData['email'].$uData['p'].Self::SALT);
		$uid = $_SESSION['user']['id'];
		$q = mysql_query("UPDATE users SET name = '".$uData['name']."', email='".$uData['email']."', phone='".$uData['phone']."', bday='".$uData['bd']."', pass='".$newCrypt."' WHERE id='".$uid."'") or die(mysql_error()) ;
		return $q;
	}

	public function checkPass($pass)
	{
		$q = mysql_query("SELECT * FROM users WHERE id=".$_SESSION['user']['id']) or die(mysql_error()) ;
		$userCheck = mysql_fetch_assoc($q);

		$cryptPass = md5($userCheck['email'].$pass.self::SALT);
		if ($cryptPass == $userCheck['pass']) {
			return true;
		} else {
			return false;
		}
	}

	public function updFavs($prodId, $type)
	{
		$type = addslashes($type);
		$userId = $_SESSION['user']['id'];
		$prodIds = $_SESSION['user']['favs'];
		switch ($type) {
			case 'add':
				if ( !(strripos($prodIds, $prodId."")===false) ) {
					return "Just in favorites!";
				} else {
					$prodIds = explode(',',$prodIds);
					array_push($prodIds, $prodId);
					$prodIds = implode(',',$prodIds);
					$q = mysql_query("UPDATE users SET favs = '$prodIds' WHERE id=$userId") or die(mysql_error());
					$_SESSION['user']['favs'] = $prodIds;
					return "Value $prodId added";
				}
				break;

			case 'delete':
				if (strripos($prodIds, $prodId."")===false) {
					var_dump($prodIds);
					var_dump($prodId);
					return "No such favorite!";
				} else {
					$prodIds = explode(',',$prodIds);
					Self::rm_from_array($prodId, $prodIds);
					$prodIds = implode(',',$prodIds);
					$q = mysql_query("UPDATE users SET favs = '$prodIds' WHERE id=$userId") or die(mysql_error());
					$_SESSION['user']['favs'] = $prodIds;
					return "Value $prodId deleted";
				}
				break;

			default:
				Route::Catch_Error('404');
				break;
		}
	}

	public function getUserData($pagename, $user=NULL)
	{
		switch ($pagename) {
			case 'profile':
			$pageDataModel['title'] = "Личный кабинет";
			if (isset($user['profile'])) {
				$pageDataModel['title'] .= ", ".$user['profile']['name'];
			}
			return $pageDataModel;
			break;

			case 'cart':
			$pageDataModel['title'] = "Корзина";
			if (isset($user['profile'])) {
				$uid = $user['profile']['id'];
				$name = $user['profile']['name'];
				$url = '/user/'.$uid;
				$crumbName = 'Личный кабинет, '.$name;
				$pageDataModel['crumb'] = array( $url => $crumbName);
			}
			return $pageDataModel;
			break;

			case 'sendOrder':
			$pageDataModel['title'] = "Оформление заказа";
			if (isset($user['profile'])) {
				$uid = $user['profile']['id'];
				$name = $user['profile']['name'];
				$url = '/user/'.$uid;
				$crumbName = 'Личный кабинет, '.$name;
				$pageDataModel['crumb'] = array( $url => $crumbName);
			}
			return $pageDataModel;
			break;

			default:
				# code...
			break;
		}
	}


}