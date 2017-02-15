<?php

class Model_User extends Model
{

	// public $userData;

	public function getLogin($jsonLogin)
	{
		$login = $jsonLogin;
		$loginEmail = $login['email'];
		$loginPassword = md5($login['password']);

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
				$userData['profile']['bday'] = Controller::getGoodDate($ts, 'compact');
			}
			if (isset($userData['profile']['addresses'])) {
				$ts = explode("_",$userData['profile']['addresses']);
				$userData['profile']['addresses'] = $ts;
			}
			return $userData;
		} else return false;
	}

	public function getUserData($pagename, $user)
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