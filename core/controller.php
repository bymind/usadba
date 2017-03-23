<?php


	/**
	* Controller
	*
	* Родительский класс контролллеров
	*/

class Controller
{
	public $model; // свойство для общения с моделью
	public $view; // свойство для общения с представлением

	public $isLogged;

	/**
	* __construct
	*
	* конструктор, в котором создаем привязку к модели и представлениям
	*
	* @return 0
	*/

	function __construct()
	{
		$this->view = new View();
		$this->model = new Model();
		$isLogged = Self::is_logged();
		$isAdmin = Self::is_admin();
	}

	/**
	* is_logged
	*
	* проверяет, залогинен ли посетитель
	*
	* @return bool
	*/

	public function is_logged()
	{
		if (isset($_SESSION['user']))
		{
			return $_SESSION['user'];
		} else
		{
			return false;
		}
	}

	public function is_admin()
	{
		if (isset($_SESSION['user']))
		{
			if ($_SESSION['user']['is_admin']==1) {
				return $_SESSION['user'];
			} else return false;
		} else
		{
			return false;
		}
	}

	function dump($var)
	{
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
	}

	function jsonConsole($var)
	{
		$jsonVar = json_encode($var);
		echo "<script>console.info($jsonVar);</script>";
	}

	/**
	* backDay
	*
	* меняет время на "вчера" или "сегодня", если возможно
	*
	* @param string $timeStamp метка времени
	* @param string $postDay день поста
	* @return string $postDay
	*/

	function backDay($timeStamp, $postDay)
	{
		$nowDay = (int)date('d');
		$nowUnix = (int)date('U');
		$delta = $nowUnix - $timeStamp;

		if ($delta < (60*60*24)) {
			if ( $nowDay == ($postDay + 1) ) {
				return 'вчера';
			}
			if ( $nowDay == $postDay ) {
				return 'сегодня';
			}
		} else
			return $postDay;
	}

	/**
	* getGoodDate
	*
	* меняет метку времения на "вчера" или "сегодня", или на дату
	*
	* @param string $timeStamp метка времени
	* @param string $format формат (default | compact)
	* @return string $newTimeStamp
	*/

	function getGoodDate($timeStamp, $format = 'default')
	{
		$xdate = explode(' ', $timeStamp);
		$xday = $xdate[0];
		$xtime = $xdate[1];
		$xday = explode("-", $xday);
		$xyear = (int) $xday[0];
		$xm = (int) $xday[1];
		$xday = (int) $xday[2];
		$xmonth = array ('','января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
		$xmonth = $xmonth[$xm];
		if (isset($xtime)) {
			$xtime = explode(':', $xtime);
			$xh = $xtime[0];
			$xmin = $xtime[1];
			$xs = $xtime[2];
			$xtimeunix =  mktime($xh, $xmin, $xs, $xm, $xday, $xyear);
		} else {
			$xtimeunix =  mktime($xm, $xday, $xyear);
		}

			if ($format == "default") {
				$xday = (self::backDay($xtimeunix, $xday)=='сегодня')||(self::backDay($xtimeunix, $xday)=='вчера') ? $newTimeStamp = self::backDay($xtimeunix, $xday).' в '.$xh.':'.$xmin : $newTimeStamp = $xday.' '.$xmonth.' '.$xyear.' в '.$xh.':'.$xmin;
			}
			if ($format == 'compact') {
				$xday = (self::backDay($xtimeunix, $xday)=='сегодня')||(self::backDay($xtimeunix, $xday)=='вчера') ? $newTimeStamp = self::backDay($xtimeunix, $xday) : $newTimeStamp = $xday.' '.$xmonth.' '.$xyear;
			}

		return $newTimeStamp;
	}


	/**
	* goLogin
	*
	* процедура входа
	*
	* @param string $redirect редирект после логина
	* @return bool
	*/

	function goLogin($redirect = "/admin")
	{
		$errors = array();

		if ( empty($_POST['login']) || empty($_POST['passw']) )
		{
			$errors[] = 'Ну-ну';
		} else
			{
				$login = substr(htmlspecialchars(trim($_POST['login'])), 0, 60);
				$password = md5($login.$_POST['passw'].Model::SALT);
				$ds = Model::get_login($login,'email');

				if($login == $ds['login'] && $password == $ds['pass']){
					$_SESSION['user']['id'] = $ds['id'];
					$_SESSION['user']['name'] = $ds['name'];
					$_SESSION['user']['is_admin'] = $ds['isadmin'];
					$_SESSION['user']['is_super'] = $ds['is_super'];
					$_SESSION['user']['telegram_token'] = $ds['telegram_token'];
					$_SESSION['user']['telegram_id'] = $ds['telegram_id'];
					$_SESSION['user']['access_key'] = md5($ds['pass'].Model::SALT);
					setcookie("id",$_SESSION['user']['id'], time()+60*60*24*30);
					// var_dump($redirect);
					// header('location:'.$redirect);
					echo "<script>location.reload();</script>";
					return true;
				}
				else
				{
					$errors[] = "Неверные данные";
				}
			}

			if ( $errors ) {
				# show errors
				foreach ($errors as $error) {
					echo $error."<br>";
				}
				return false;
			}
	}

	/**
	* logout
	*
	* разлогинивание
	*
	* @return 0
	*/

	function logout()
	{
		setcookie("id","");
		unset($_SESSION['user']['id']);
		header('Location:/');
		return 0;
	}


	/**
	* sessionEdit($sAction, $sParam, $sVal)
	*
	* изменение данных в сессии
	*
	* @return 0
	*/

	function sessionEdit($sAction, $sParam, $sVal)
	{

		switch ($sAction) {
			case 'set':
			case 'edit':
				$_SESSION[$sParam] = NULL;
				$_SESSION[$sParam] = $sVal;
				break;

			case 'delete':
				$_SESSION[$sParam] = NULL;
				break;

			default:
				trigger_error('No such action "'.$sAction.'" in Controller::sessionEdit()');
				break;
		}

		return 0;
	}

	function createMsg( $typeOfMsg, $data)
	{
		switch ($typeOfMsg) {
			case 'callback':
				$msg_prefix = "Перезвон \r\n".$order->name."\r\n".$order->phone."\r\n".$order->gadget;
				$title =  substr(htmlspecialchars(trim("Заявка - Перезвон")), 0, 1000);
				$title = '=?UTF-8?B?' . base64_encode($title) . '?=';
				$difTextHeader = '<strong style="font-size:1.5em">Заявка на перезвон</strong><br><table style="">';
				$difTextHeader = $difTextHeader.'<tr><td><span style="font-size:1em"><strong>Имя:</strong></span></td><td><span style="font-size:1em">'.$data['name'].'</span></td></tr><tr><td><span style="font-size:1em"><strong>Телефон:</strong></span></td><td><span style="font-size:1em">'.$data['phone'].'</span></td></tr>';
				$closing = "</table>";

				$letter = array('title' => $title,
												'msg' => $difTextHeader.$closing
												);
				break;

			default:
				# code...
				break;
		}
		return $letter;
	}

	function sendMsg($to, $msg)
	{
		switch ($to) {
			case 'admin':
				$to = 'keshapudelev@ya.ru';
				$from='Перезвон <keshapudelev@ya.ru>';
				$letter = $msg['msg'];
				$title = $msg['title'];

				mail($to, $title, $letter, "Content-type: text/html; charset=utf-8\r\nFrom:".$from);
				break;

			default:
				# code...
				break;
		}
	}

}