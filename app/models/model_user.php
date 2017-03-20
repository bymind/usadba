<?php

class Model_User extends Model
{

	// public $userData;



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
			if ($loginPassword == $user['pass']) {
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