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
	public $newsForSidebar;

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
			if (isset($_SESSION['user']['id'])) {
				// var_dump($_SESSION['user']);
				$is_banned = Model::getUser($_SESSION['user']['id']);
				if ($is_banned['profile']['banned']=='1') {
					// echo "Ваш аккаунт забанен!";
					session_destroy();
					return false;
				} else return $_SESSION['user'];
			}
		} else
		{
			return false;
		}
	}

	public function is_admin()
	{
		if (isset($_SESSION['user']))
		{
		 if (isset($_SESSION['user']['is_admin'])) {
			if ( $_SESSION['user']['is_admin']==1 ) {
				return $_SESSION['user'];
				} else return false;
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

		if (($delta < (60*60*24))&&($delta > 0)) {
			if ( $nowDay == ($postDay + 1) ) {
				return 'вчера';
			}
			if ( $nowDay == $postDay ) {
				return 'сегодня';
			}
		} else
		if (($delta > -(60*60*24))&&($delta < 0)) {
			if ( $nowDay == ($postDay - 1) ) {
							return 'завтра';
						}
						if ( $nowDay == $postDay ) {
							return 'сегодня';
						}
		} else
		if ($delta == 0) {
			return 'сегодня';
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
		$xmonth = array ('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
		$xmonth_short = array ('янв','фев','марта','апр','мая','июня','июля','авг','сент','окт','нояб','дек');
		$xmonth = $xmonth[$xm-1];
		$xmonth_short = $xmonth_short[$xm-1];
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
			if ($format == 'short') {
				$newTimeStamp = $xday.' '.$xmonth_short;
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
					$_SESSION['user']['sound'] = $ds['sound'];
					$_SESSION['user']['name'] = $ds['name'];
					$_SESSION['user']['favs'] = $ds['favs'];
					$_SESSION['user']['is_admin'] = $ds['isadmin'];
					$_SESSION['user']['is_super'] = $ds['is_super'];
					$_SESSION['user']['admin_rights'] = explode(',',$ds['admin_rights']);
					$_SESSION['user']['telegram_token'] = $ds['telegram_token'];
					$_SESSION['user']['telegram_id'] = $ds['telegram_id'];
					$_SESSION['user']['access_key'] = md5($ds['pass'].Model::SALT);
					setcookie("id",$_SESSION['user']['id'], time()+60*60*24*30);
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
		session_destroy();
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

	function sendEmail($toName, $toEmail, $type, $data)
	{
		switch ($type) {
// ================================================================
			case 'forgot':
				$siteName = CONFIG_SITE_NAME;
				$siteName = str_replace(array("\r","\n")," ",$siteName);
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
					$protocol = 'https://';
				} else {
					$protocol = 'http://';
				}
				$siteUrl = $protocol.$_SERVER['HTTP_HOST'];
				$siteLogoUrl = CONFIG_SITE_LOGO;
				$siteLogoUrl = $siteUrl.CONFIG_SITE_LOGO;
				$siteLogoPng = explode(".", $siteLogoUrl);
				$siteLogoPng[count($siteLogoPng)-1] = "png";
				$siteLogoPng = implode('.', $siteLogoPng);
				$link = $data;
				$letterText = '<div background="'.$siteUrl.'/img/bg-pattern.png" style="margin:0;padding:5% 0;width:100%; height:100%;background-image:url('.$siteUrl.'/img/bg-pattern.png); background-position: left top; background-repeat: repeat; background-size: auto;">
				<table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;margin: auto;padding:10px;background-color:#fff;font-family:Arial,Helvetica,sans-serif;width: 90%;max-width:580px;height: 100% !important;border-radius:4px;border: 1px solid #ddd;">
				<tbody>
				<tr>
					<td>
					<div style="font-size:1.2em;padding: 15px 10px 10px 20px;display:inline-block;font-size: 16px;font-weight: normal;line-height: 30px;color: #c63838;display: inline-block;position: relative;border-bottom: 4px solid;z-index: 2;">Смена пароля на сайте</div>
					<div style="display: block;position: relative;width: 100%;height: 1px;background: #e5e5e5;top: -1px;box-sizing: content-box;z-index: 1;"></div>
					<br>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px;padding-left: 20px;font-size: 14px;line-height:1.4;">
					<br>
					Здравствуйте.<br>Этот адрес был указан для восстановления пароля на сайте <b>'.$siteName.'</b>.<br>Для перехода на страницу подтверждения и создания пароля нажмите на кнопку:<br><br>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px; text-align:center">
					<a href="'.$siteUrl.'/user/forgot/'.$link.'" style="display:inline-block;padding: 6px 12px;border-radius:4px;color:#fff;background: #c63838;margin:10px auto 2px;text-decoration:none;text-transform: uppercase;font-size: 13px;font-family: roboto, sans-serif;font-weight: 900;">Сменить пароль</a>
					<span style="display:inline-block;width:100%;font-size:12px;margin-bottom:50px;margin-top: 10px;color: #888;">Ссылка действительна в течение 24 часов.</span>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;background:#ECEFF1;padding:10px;border-radius: 0 0 4px 4px;">
					<a style="color:inherit" href="'.$siteUrl.'"><span style="display:inline-block; text-align:center; font-size:14px; color:#888;">'.date('Y').' © '.nl2br(CONFIG_SITE_COPYRIGHT).'</span></a>
					</td>
				</tr>
				</tbody>
				</table>
				</div>';
				$title = "Восстановление аккаунта";
				$title = '=?UTF-8?B?' . base64_encode($title) . '?=';
				$from = $siteName." <".CONFIG_SITE_ADMIN.">";
				mail($toEmail, $title, $letterText, "Content-type: text/html; charset=utf-8\r\nFrom:".$from);
				break;

// ================================================================
			case 'userReg':
				$siteName = CONFIG_SITE_NAME;
				$siteName = str_replace(array("\r","\n")," ",$siteName);
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
					$protocol = 'https://';
				} else {
					$protocol = 'http://';
				}
				$siteUrl = $protocol.$_SERVER['HTTP_HOST'];
				$siteLogoUrl = CONFIG_SITE_LOGO;
				$siteLogoUrl = $siteUrl.CONFIG_SITE_LOGO;
				$siteLogoPng = explode(".", $siteLogoUrl);
				$siteLogoPng[count($siteLogoPng)-1] = "png";
				$siteLogoPng = implode('.', $siteLogoPng);
				$link = md5($toEmail.$toName.Model::SALT);
				$letterText = '<div background="'.$siteUrl.'/img/bg-pattern.png" style="margin:0;padding:5% 0;width:100%; height:100%;background-image:url('.$siteUrl.'/img/bg-pattern.png); background-position: left top; background-repeat: repeat; background-size: auto;">
				<table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;margin: auto;padding:10px;background-color:#fff;font-family:Arial,Helvetica,sans-serif;width: 90%;max-width:580px;height: 100% !important;border-radius:4px;border: 1px solid #ddd;">
				<tbody>
				<tr>
					<td>
					<div style="font-size:1.2em;padding: 15px 10px 10px 20px;display:inline-block;font-size: 16px;font-weight: normal;line-height: 30px;color: #c63838;display: inline-block;position: relative;border-bottom: 4px solid;z-index: 2;">Подтверждение регистрации</div>
					<div style="display: block;position: relative;width: 100%;height: 1px;background: #e5e5e5;top: -1px;box-sizing: content-box;z-index: 1;"></div>
					<br>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px;padding-left: 20px;font-size: 14px;line-height:1.4;">
					<br>
					Здравствуйте, '.$toName.'.<br>Этот адрес был указан при регистрации на сайте <b>'.$siteName.'</b>.<br>Для перехода на страницу подтверждения и создания пароля нажмите на кнопку:<br><br>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px; text-align:center">
					<a href="'.$siteUrl.'/user/confirm/'.$link.'" style="display:inline-block;padding: 6px 12px;border-radius:4px;color:#fff;background: #c63838;margin:10px auto 2px;text-decoration:none;text-transform: uppercase;font-size: 13px;font-family: roboto, sans-serif;font-weight: 900;">Подтвердить регистрацию</a>
					<span style="display:inline-block;width:100%;font-size:12px;margin-bottom:50px;margin-top: 10px;color: #888;">Ссылка действительна в течение 24 часов.</span>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;background:#ECEFF1;padding:10px;border-radius: 0 0 4px 4px;">
					<a style="color:inherit" href="'.$siteUrl.'"><span style="display:inline-block; text-align:center; font-size:14px; color:#888;">'.date('Y').' © '.nl2br(CONFIG_SITE_COPYRIGHT).'</span></a>
					</td>
				</tr>
				</tbody>
				</table>
				</div>';
				$title = "Подтверждение регистрации на сайте";
				$title = '=?UTF-8?B?' . base64_encode($title) . '?=';
				$from = $siteName." <".CONFIG_SITE_ADMIN.">";
				mail($toEmail, $title, $letterText, "Content-type: text/html; charset=utf-8\r\nFrom:".$from);
				break;

// ================================================================
			case "newComm":
				$orderId = $data['id'];

				switch ($data['paytype']) {
					case 'cash':
						$data['paytypeRu'] = 'наличными';
						break;

					case 'online':
						$data['paytypeRu'] = 'картой онлайн';
						break;

					default:
						$data['paytypeRu'] = 'наличными';
						break;
				}
				if ($data['comm']=="") {
					$data['comm'] = "—";
				}
				$data['orderProds'] = json_decode($data['orderProds'], JSON_UNESCAPED_UNICODE);
				$siteName = CONFIG_SITE_NAME;
				$siteName = str_replace(array("\r","\n")," ",$siteName);
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
					$protocol = 'https://';
				} else {
					$protocol = 'http://';
				}
				$siteUrl = $protocol.$_SERVER['HTTP_HOST'];
				$siteLogoUrl = CONFIG_SITE_LOGO;
				$siteLogoUrl = $siteUrl.CONFIG_SITE_LOGO;
				$siteLogoPng = explode(".", $siteLogoUrl);
				$siteLogoPng[count($siteLogoPng)-1] = "png";
				$siteLogoPng = implode('.', $siteLogoPng);
				if ($data['uid']>0) {
					$uid = $data['uid'];
					$uidTxt = '<a href="'.$siteUrl.'/admin/users/'.$uid.'" target="_blank">'.$data['name'].'</a>';
				} else {
					$uidTxt = 'не зарегистрирован';
				}
				$letterText = '<div background="'.$siteUrl.'/img/bg-pattern.png" style="margin:0;padding:5% 0;width:100%; height:100%;background-image:url('.$siteUrl.'/img/bg-pattern.png); background-position: left top; background-repeat: repeat; background-size: auto;">
				<table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;margin: auto;padding:10px;background-color:#fff;font-family:Arial,Helvetica,sans-serif;width: 90%;max-width:580px;height: 100% !important;border-radius:4px;border: 1px solid #ddd;">
				<tbody>
				<tr>
					<td>
					<div style="font-size:1.2em;padding: 15px 10px 10px 20px;display:inline-block;font-size: 16px;font-weight: normal;line-height: 30px;color: #c63838;display: inline-block;position: relative;border-bottom: 4px solid;z-index: 2;">Новый заказ <a href="'.$siteUrl.'/admin">№'.$orderId.'</a></div>
					<div style="display: block;position: relative;width: 100%;height: 1px;background: #e5e5e5;top: -1px;box-sizing: content-box;z-index: 1;"></div>
					<br>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px;padding-left: 20px;font-size: 14px;line-height:1.4;">
					<br>
					<table class="order-details-table table table-hover table-bordered mb-sm-10">
							<tbody><tr>
								<th style="text-align:left; vertical-align:top">№ заказа</th>
								<td>'.$orderId.'</td>
							</tr><tr>
								<th style="text-align:left; vertical-align:top">Сумма</th>
								<td><b>'.number_format($data['orderProds']['sumPrice'],0,',',' ').' руб.</b></td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Оплата</th>
								<td>
									'.$data['paytypeRu'].'
									</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Заказ на имя</th>
								<td>
									'.$data['name'].'
									</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Аккаунт</th>
								<td>
								'.$uidTxt.'
								</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Телефон</th>
								<td>
									<nobr>'.$data['phone'].'</nobr>
								</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Адрес</th>
								<td>
									'.$data['addr'].'
									</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Комментарий</th>
								<td>
								'.$data['comm'].'
								</td>
							</tr>
						</tbody></table>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px; text-align:center">
					<a href="'.$siteUrl.'/admin" style="display:inline-block;padding: 6px 12px;border-radius:4px;color:#fff;background: #c63838;margin:10px auto 2px;text-decoration:none;text-transform: uppercase;font-size: 13px;font-family: roboto, sans-serif;font-weight: 900;">Перейти к заказу</a>
					<br>
					<br>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;background:#ECEFF1;padding:10px;border-radius: 0 0 4px 4px;">
					<a style="color:inherit" href="'.$siteUrl.'"><span style="display:inline-block; text-align:center; font-size:14px; color:#888;">'.date('Y').' © '.nl2br(CONFIG_SITE_COPYRIGHT).'</span></a>
					</td>
				</tr>
				</tbody>
				</table>
				</div>';
				$title = "Заказ №".$orderId;
				$title = '=?UTF-8?B?' . base64_encode($title) . '?=';
				$from = "Админка / ".$siteName." <".CONFIG_SITE_ADMIN.">";
				$toEmail = CONFIG_SITE_ADMIN.",".CONFIG_SITE_ADMIN_ORDERS;
				mail($toEmail, $title, $letterText, "Content-type: text/html; charset=utf-8\r\nFrom:".$from);
			break;

// ================================================================
			case "newRecall":
				$siteName = CONFIG_SITE_NAME;
				$siteName = str_replace(array("\r","\n")," ",$siteName);
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
					$protocol = 'https://';
				} else {
					$protocol = 'http://';
				}
				$siteUrl = $protocol.$_SERVER['HTTP_HOST'];
				$siteLogoUrl = CONFIG_SITE_LOGO;
				$siteLogoUrl = $siteUrl.CONFIG_SITE_LOGO;
				$siteLogoPng = explode(".", $siteLogoUrl);
				$siteLogoPng[count($siteLogoPng)-1] = "png";
				$siteLogoPng = implode('.', $siteLogoPng);
				$letterText = '<div background="'.$siteUrl.'/img/bg-pattern.png" style="margin:0;padding:5% 0;width:100%; height:100%;background-image:url('.$siteUrl.'/img/bg-pattern.png); background-position: left top; background-repeat: repeat; background-size: auto;">
				<table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;margin: auto;padding:10px;background-color:#fff;font-family:Arial,Helvetica,sans-serif;width: 90%;max-width:580px;height: 100% !important;border-radius:4px;border: 1px solid #ddd;">
				<tbody>
				<tr>
					<td>
					<div style="font-size:1.2em;padding: 15px 10px 10px 20px;display:inline-block;font-size: 16px;font-weight: normal;line-height: 30px;color: #c63838;display: inline-block;position: relative;border-bottom: 4px solid;z-index: 2;">Заявка на <a href="'.$siteUrl.'/admin">перезвон</a></div>
					<div style="display: block;position: relative;width: 100%;height: 1px;background: #e5e5e5;top: -1px;box-sizing: content-box;z-index: 1;"></div>
					<br>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px;padding-left: 20px;font-size: 14px;line-height:1.4;">
					<br>
					<table class="order-details-table table table-hover table-bordered mb-sm-10">
							<tbody><tr>
								<th style="text-align:left; vertical-align:top">Имя</th>
								<td>'.nl2br($data['name']).'</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Номер телефона</th>
								<td>
									'.$data['phone'].'
									</td>
							</tr>
						</tbody></table>
					</td>
				</tr>
				<tr>
					<td style="height:25px">
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px; text-align:center">
					<a href="'.$siteUrl.'/admin" style="display:inline-block;padding: 6px 12px;border-radius:4px;color:#fff;background: #c63838;margin:10px auto 2px;text-decoration:none;text-transform: uppercase;font-size: 13px;font-family: roboto, sans-serif;font-weight: 900;">Перейти к заявкам</a>
					<br>
					<br>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;background:#ECEFF1;padding:10px;border-radius: 0 0 4px 4px;">
					<a style="color:inherit" href="'.$siteUrl.'"><span style="display:inline-block; text-align:center; font-size:14px; color:#888;">'.date('Y').' © '.nl2br(CONFIG_SITE_COPYRIGHT).'</span></a>
					</td>
				</tr>
				</tbody>
				</table>
				</div>';
				$title = "Новая зявка на перезвон";
				$title = '=?UTF-8?B?' . base64_encode($title) . '?=';
				$from = "Админка / ".$siteName." <".CONFIG_SITE_ADMIN.">";
				$toEmail = CONFIG_SITE_ADMIN.",".CONFIG_SITE_ADMIN_RECALL;
				mail($toEmail, $title, $letterText, "Content-type: text/html; charset=utf-8\r\nFrom:".$from);
			break;


// ================================================================
			case "newReview":
				$siteName = CONFIG_SITE_NAME;
				$siteName = str_replace(array("\r","\n")," ",$siteName);
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
					$protocol = 'https://';
				} else {
					$protocol = 'http://';
				}
				$siteUrl = $protocol.$_SERVER['HTTP_HOST'];
				$siteLogoUrl = CONFIG_SITE_LOGO;
				$siteLogoUrl = $siteUrl.CONFIG_SITE_LOGO;
				$siteLogoPng = explode(".", $siteLogoUrl);
				$siteLogoPng[count($siteLogoPng)-1] = "png";
				$siteLogoPng = implode('.', $siteLogoPng);
				$letterText = '<div background="'.$siteUrl.'/img/bg-pattern.png" style="margin:0;padding:5% 0;width:100%; height:100%;background-image:url('.$siteUrl.'/img/bg-pattern.png); background-position: left top; background-repeat: repeat; background-size: auto;">
				<table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;margin: auto;padding:10px;background-color:#fff;font-family:Arial,Helvetica,sans-serif;width: 90%;max-width:580px;height: 100% !important;border-radius:4px;border: 1px solid #ddd;">
				<tbody>
				<tr>
					<td>
					<div style="font-size:1.2em;padding: 15px 10px 10px 20px;display:inline-block;font-size: 16px;font-weight: normal;line-height: 30px;color: #c63838;display: inline-block;position: relative;border-bottom: 4px solid;z-index: 2;">Новый отзыв <a href="'.$siteUrl.'/admin/comm/new">на сайте</a> о сервисе</div>
					<div style="display: block;position: relative;width: 100%;height: 1px;background: #e5e5e5;top: -1px;box-sizing: content-box;z-index: 1;"></div>
					<br>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px;padding-left: 20px;font-size: 14px;line-height:1.4;">
					<br>
					<table class="order-details-table table table-hover table-bordered mb-sm-10">
							<tbody><tr>
								<th style="text-align:left; vertical-align:top">Текст отзыва</th>
								<td>'.nl2br($data['com_text']).'</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">ID пользователя</th>
								<td>
									<a href="'.$siteUrl.'/admin/users/'.$data['uid'].'">'.$data['uid'].'</a>
									</td>
							</tr>
						</tbody></table>
					</td>
				</tr>
				<tr>
					<td style="height:25px">
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px; text-align:center">
					<a href="'.$siteUrl.'/admin/comm/new" style="display:inline-block;padding: 6px 12px;border-radius:4px;color:#fff;background: #c63838;margin:10px auto 2px;text-decoration:none;text-transform: uppercase;font-size: 13px;font-family: roboto, sans-serif;font-weight: 900;">Перейти к комментариям</a>
					<br>
					<br>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;background:#ECEFF1;padding:10px;border-radius: 0 0 4px 4px;">
					<a style="color:inherit" href="'.$siteUrl.'"><span style="display:inline-block; text-align:center; font-size:14px; color:#888;">'.date('Y').' © '.nl2br(CONFIG_SITE_COPYRIGHT).'</span></a>
					</td>
				</tr>
				</tbody>
				</table>
				</div>';
				$title = "Новый отзыв о сайте";
				$title = '=?UTF-8?B?' . base64_encode($title) . '?=';
				$from = "Админка / ".$siteName." <".CONFIG_SITE_ADMIN.">";
				$toEmail = CONFIG_SITE_ADMIN.",".CONFIG_SITE_ADMIN_REV;
				mail($toEmail, $title, $letterText, "Content-type: text/html; charset=utf-8\r\nFrom:".$from);
			break;

// ================================================================
			case "newProductComm":
				$siteName = CONFIG_SITE_NAME;
				$siteName = str_replace(array("\r","\n")," ",$siteName);
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
					$protocol = 'https://';
				} else {
					$protocol = 'http://';
				}
				$siteUrl = $protocol.$_SERVER['HTTP_HOST'];
				$siteLogoUrl = CONFIG_SITE_LOGO;
				$siteLogoUrl = $siteUrl.CONFIG_SITE_LOGO;
				$siteLogoPng = explode(".", $siteLogoUrl);
				$siteLogoPng[count($siteLogoPng)-1] = "png";
				$siteLogoPng = implode('.', $siteLogoPng);
				$letterText = '<div background="'.$siteUrl.'/img/bg-pattern.png" style="margin:0;padding:5% 0;width:100%; height:100%;background-image:url('.$siteUrl.'/img/bg-pattern.png); background-position: left top; background-repeat: repeat; background-size: auto;">
				<table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;margin: auto;padding:10px;background-color:#fff;font-family:Arial,Helvetica,sans-serif;width: 90%;max-width:580px;height: 100% !important;border-radius:4px;border: 1px solid #ddd;">
				<tbody>
				<tr>
					<td>
					<div style="font-size:1.2em;padding: 15px 10px 10px 20px;display:inline-block;font-size: 16px;font-weight: normal;line-height: 30px;color: #c63838;display: inline-block;position: relative;border-bottom: 4px solid;z-index: 2;">Новый комментарий <a href="'.$siteUrl.'/admin/comm/new">на сайте</a> о товаре</div>
					<div style="display: block;position: relative;width: 100%;height: 1px;background: #e5e5e5;top: -1px;box-sizing: content-box;z-index: 1;"></div>
					<br>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px;padding-left: 20px;font-size: 14px;line-height:1.4;">
					<br>
					<table class="order-details-table table table-hover table-bordered mb-sm-10">
							<tbody><tr>
								<th style="text-align:left; vertical-align:top">Текст комментария</th>
								<td>'.nl2br($data['com_text']).'</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">ID пользователя</th>
								<td>
									<a href="'.$siteUrl.'/admin/users/'.$data['uid'].'">'.$data['uid'].'</a>
									</td>
							</tr>
						</tbody></table>
					</td>
				</tr>
				<tr>
					<td style="height:25px">
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px; text-align:center">
					<a href="'.$siteUrl.'/admin/comm/new" style="display:inline-block;padding: 6px 12px;border-radius:4px;color:#fff;background: #c63838;margin:10px auto 2px;text-decoration:none;text-transform: uppercase;font-size: 13px;font-family: roboto, sans-serif;font-weight: 900;">Перейти к комментариям</a>
					<br>
					<br>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;background:#ECEFF1;padding:10px;border-radius: 0 0 4px 4px;">
					<a style="color:inherit" href="'.$siteUrl.'"><span style="display:inline-block; text-align:center; font-size:14px; color:#888;">'.date('Y').' © '.nl2br(CONFIG_SITE_COPYRIGHT).'</span></a>
					</td>
				</tr>
				</tbody>
				</table>
				</div>';
				$title = "Новый комментарий о товаре";
				$title = '=?UTF-8?B?' . base64_encode($title) . '?=';
				$from = "Админка / ".$siteName." <".CONFIG_SITE_ADMIN.">";
				$toEmail = CONFIG_SITE_ADMIN.",".CONFIG_SITE_ADMIN_COMM;
				mail($toEmail, $title, $letterText, "Content-type: text/html; charset=utf-8\r\nFrom:".$from);
			break;

// ================================================================
// Controller::sendEmail($order['u_name'],$order['u_email'],"newOrderStat",$order);
			case "newOrderStat":
				$orderId = $data['id'];
				switch ($data['paytype']) {
					case 'cash':
						$data['paytypeRu'] = 'наличными';
						break;

					case 'online':
						$data['paytypeRu'] = 'картой онлайн';
						break;

					default:
						$data['paytypeRu'] = 'наличными';
						break;
				}
				if ($data['comm']=="") {
					$data['comm'] = "—";
				}
				$data['orderProds'] = json_decode($data['orderProds'], JSON_UNESCAPED_UNICODE);
				$siteName = CONFIG_SITE_NAME;
				$siteName = str_replace(array("\r","\n")," ",$siteName);
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
					$protocol = 'https://';
				} else {
					$protocol = 'http://';
				}
				$siteUrl = $protocol.$_SERVER['HTTP_HOST'];
				$siteLogoUrl = CONFIG_SITE_LOGO;
				$siteLogoUrl = $siteUrl.CONFIG_SITE_LOGO;
				$siteLogoPng = explode(".", $siteLogoUrl);
				$siteLogoPng[count($siteLogoPng)-1] = "png";
				$siteLogoPng = implode('.', $siteLogoPng);
				$prodsTxt = "";
				if ($data['uid']>0) {
					$uid = $data['uid'];
					$uidTxt = 'Вы можете следить за состоянием заказа в <a href="'.$siteUrl.'/user/order/'.$orderId.'" target="_blank">личном кабинете</a>';
				} else {
					$uidTxt = '<a href="'.$siteUrl.'">Зарегистрируйтесь</a> на сайте и следите за заказами в личном кабинете.';
				}
				$letterText = '<div background="'.$siteUrl.'/img/bg-pattern.png" style="margin:0;padding:5% 0;width:100%; height:100%;background-image:url('.$siteUrl.'/img/bg-pattern.png); background-position: left top; background-repeat: repeat; background-size: auto;">
				<table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;margin: auto;padding:10px;background-color:#fff;font-family:Arial,Helvetica,sans-serif;width: 90%;max-width:580px;height: 100% !important;border-radius:4px;border: 1px solid #ddd;">
				<tbody>
				<tr>
					<td>
					<div style="font-size:1.2em;padding: 15px 10px 10px 20px;display:inline-block;font-size: 16px;font-weight: normal;line-height: 30px;color: #c63838;display: inline-block;position: relative;border-bottom: 4px solid;z-index: 2;">Статус заказа №'.$orderId.' изменён</a></div>
					<div style="display: block;position: relative;width: 100%;height: 1px;background: #e5e5e5;top: -1px;box-sizing: content-box;z-index: 1;"></div>
					<br>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px;padding-left: 20px;font-size: 14px;line-height:1.4;">
					'.$toName.', новый статус Вашего заказа — <b>'.$data['stat'].'</b>.
					<br>
					<br>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px; text-align:left">
					<br>
					<span style="display:inline-block;width:100%;font-size:12px;margin-bottom:50px;margin-top: 10px;color: #888;text-align:center;">'.$uidTxt.'</span>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;background:#ECEFF1;padding:10px;border-radius: 0 0 4px 4px;">
					<a style="color:inherit" href="'.$siteUrl.'"><span style="display:inline-block; text-align:center; font-size:14px; color:#888;">'.date('Y').' © '.nl2br(CONFIG_SITE_COPYRIGHT).'</span></a>
					</td>
				</tr>
				</tbody>
				</table>
				</div>';
				$title = "Статус заказа №".$orderId." изменён";
				$title = '=?UTF-8?B?' . base64_encode($title) . '?=';
				$from = $siteName." <".CONFIG_SITE_ADMIN.">";
				mail($toEmail, $title, $letterText, "Content-type: text/html; charset=utf-8\r\nFrom:".$from);
			break;

// ================================================================
			case "newOrder":
				$orderId = $data['id'];

				switch ($data['paytype']) {
					case 'cash':
						$data['paytypeRu'] = 'наличными';
						break;

					case 'online':
						$data['paytypeRu'] = 'картой онлайн';
						break;

					default:
						$data['paytypeRu'] = 'наличными';
						break;
				}
				if ($data['comm']=="") {
					$data['comm'] = "—";
				}
				$data['orderProds'] = json_decode($data['orderProds'], JSON_UNESCAPED_UNICODE);
				$siteName = CONFIG_SITE_NAME;
				$siteName = str_replace(array("\r","\n")," ",$siteName);
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
					$protocol = 'https://';
				} else {
					$protocol = 'http://';
				}
				$siteUrl = $protocol.$_SERVER['HTTP_HOST'];
				$siteLogoUrl = CONFIG_SITE_LOGO;
				$siteLogoUrl = $siteUrl.CONFIG_SITE_LOGO;
				$siteLogoPng = explode(".", $siteLogoUrl);
				$siteLogoPng[count($siteLogoPng)-1] = "png";
				$siteLogoPng = implode('.', $siteLogoPng);
				if ($data['uid']>0) {
					$uid = $data['uid'];
					$uidTxt = '<a href="'.$siteUrl.'/admin/users/'.$uid.'" target="_blank">'.$data['name'].'</a>';
				} else {
					$uidTxt = 'не зарегистрирован';
				}
				$letterText = '<div background="'.$siteUrl.'/img/bg-pattern.png" style="margin:0;padding:5% 0;width:100%; height:100%;background-image:url('.$siteUrl.'/img/bg-pattern.png); background-position: left top; background-repeat: repeat; background-size: auto;">
				<table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;margin: auto;padding:10px;background-color:#fff;font-family:Arial,Helvetica,sans-serif;width: 90%;max-width:580px;height: 100% !important;border-radius:4px;border: 1px solid #ddd;">
				<tbody>
				<tr>
					<td>
					<div style="font-size:1.2em;padding: 15px 10px 10px 20px;display:inline-block;font-size: 16px;font-weight: normal;line-height: 30px;color: #c63838;display: inline-block;position: relative;border-bottom: 4px solid;z-index: 2;">Новый заказ <a href="'.$siteUrl.'/admin">№'.$orderId.'</a></div>
					<div style="display: block;position: relative;width: 100%;height: 1px;background: #e5e5e5;top: -1px;box-sizing: content-box;z-index: 1;"></div>
					<br>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px;padding-left: 20px;font-size: 14px;line-height:1.4;">
					<br>
					<table class="order-details-table table table-hover table-bordered mb-sm-10">
							<tbody><tr>
								<th style="text-align:left; vertical-align:top">№ заказа</th>
								<td>'.$orderId.'</td>
							</tr><tr>
								<th style="text-align:left; vertical-align:top">Сумма</th>
								<td><b>'.number_format($data['orderProds']['sumPrice'],0,',',' ').' руб.</b></td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Оплата</th>
								<td>
									'.$data['paytypeRu'].'
									</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Заказ на имя</th>
								<td>
									'.$data['name'].'
									</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Аккаунт</th>
								<td>
								'.$uidTxt.'
								</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Телефон</th>
								<td>
									<nobr>'.$data['phone'].'</nobr>
								</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Адрес</th>
								<td>
									'.$data['addr'].'
									</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Комментарий</th>
								<td>
								'.$data['comm'].'
								</td>
							</tr>
						</tbody></table>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px; text-align:center">
					<a href="'.$siteUrl.'/admin" style="display:inline-block;padding: 6px 12px;border-radius:4px;color:#fff;background: #c63838;margin:10px auto 2px;text-decoration:none;text-transform: uppercase;font-size: 13px;font-family: roboto, sans-serif;font-weight: 900;">Перейти к заказу</a>
					<br>
					<br>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;background:#ECEFF1;padding:10px;border-radius: 0 0 4px 4px;">
					<a style="color:inherit" href="'.$siteUrl.'"><span style="display:inline-block; text-align:center; font-size:14px; color:#888;">'.date('Y').' © '.nl2br(CONFIG_SITE_COPYRIGHT).'</span></a>
					</td>
				</tr>
				</tbody>
				</table>
				</div>';
				$title = "Заказ №".$orderId;
				$title = '=?UTF-8?B?' . base64_encode($title) . '?=';
				$from = "Админка / ".$siteName." <".CONFIG_SITE_ADMIN.">";
				$toEmail = CONFIG_SITE_ADMIN.",".CONFIG_SITE_ADMIN_ORDERS;
				mail($toEmail, $title, $letterText, "Content-type: text/html; charset=utf-8\r\nFrom:".$from);
			break;

// ================================================================
			case "newOrderToUser":
				$orderId = $data['id'];
				switch ($data['paytype']) {
					case 'cash':
						$data['paytypeRu'] = 'наличными';
						break;

					case 'online':
						$data['paytypeRu'] = 'картой онлайн';
						break;

					default:
						$data['paytypeRu'] = 'наличными';
						break;
				}
				if ($data['comm']=="") {
					$data['comm'] = "—";
				}
				$data['orderProds'] = json_decode($data['orderProds'], JSON_UNESCAPED_UNICODE);
				$siteName = CONFIG_SITE_NAME;
				$siteName = str_replace(array("\r","\n")," ",$siteName);
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
					$protocol = 'https://';
				} else {
					$protocol = 'http://';
				}
				$siteUrl = $protocol.$_SERVER['HTTP_HOST'];
				$siteLogoUrl = CONFIG_SITE_LOGO;
				$siteLogoUrl = $siteUrl.CONFIG_SITE_LOGO;
				$siteLogoPng = explode(".", $siteLogoUrl);
				$siteLogoPng[count($siteLogoPng)-1] = "png";
				$siteLogoPng = implode('.', $siteLogoPng);
				$prodsTxt = "";
				foreach ($data['orderProds']['items'] as $prod) {
					$cost = $prod['price']*$prod['count'];
					$prodsTxt .= "<tr><td><a href=".$siteUrl.$prod['url'].">".$prod['name']."</a></td><td style='text-align:right; vertical-align:top;color:#888;'><nobr>".number_format($prod['price'],0,',',' ')." руб.</nobr></td><td style='text-align:center; vertical-align:top;color:#888;'>x".$prod['count']."</td><td style='text-align:right; vertical-align:top;'><nobr>".number_format($cost,0,',',' ')." руб.</nobr></td></tr>";
				}
				if ($data['uid']>0) {
					$uid = $data['uid'];
					$uidTxt = 'Вы можете следить за состоянием заказа в <a href="'.$siteUrl.'/user/history/'.$uid.'" target="_blank">личном кабинете</a>';
				} else {
					$uidTxt = '<a href="'.$siteUrl.'">Зарегистрируйтесь</a> на сайте и следите за заказами в личном кабинете.';
				}
				$letterText = '<div background="'.$siteUrl.'/img/bg-pattern.png" style="margin:0;padding:5% 0;width:100%; height:100%;background-image:url('.$siteUrl.'/img/bg-pattern.png); background-position: left top; background-repeat: repeat; background-size: auto;">
				<table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;margin: auto;padding:10px;background-color:#fff;font-family:Arial,Helvetica,sans-serif;width: 90%;max-width:580px;height: 100% !important;border-radius:4px;border: 1px solid #ddd;">
				<tbody>
				<tr>
					<td>
					<div style="font-size:1.2em;padding: 15px 10px 10px 20px;display:inline-block;font-size: 16px;font-weight: normal;line-height: 30px;color: #c63838;display: inline-block;position: relative;border-bottom: 4px solid;z-index: 2;">Заказ №'.$orderId.' принят</a></div>
					<div style="display: block;position: relative;width: 100%;height: 1px;background: #e5e5e5;top: -1px;box-sizing: content-box;z-index: 1;"></div>
					<br>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px;padding-left: 20px;font-size: 14px;line-height:1.4;">
					Здравствуйте, '.$toName.'. Ваш заказ принят в обработку.
					<br>
					<br>
					<table class="order-details-table table table-hover table-bordered mb-sm-10">
							<tbody><tr>
								<th style="text-align:left; vertical-align:top">№ заказа</th>
								<td>'.$orderId.'</td>
							</tr><tr>
								<th style="text-align:left; vertical-align:top">Сумма</th>
								<td><b>'.number_format($data['orderProds']['sumPrice'],0,',',' ').' руб.</b></td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Оплата</th>
								<td>
									'.$data['paytypeRu'].'
									</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Заказ на имя</th>
								<td>
									'.$data['name'].'
									</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Телефон</th>
								<td>
									<nobr>'.$data['phone'].'</nobr>
								</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Адрес</th>
								<td>
									'.$data['addr'].'
									</td>
							</tr>
							<tr>
								<th style="text-align:left; vertical-align:top">Комментарий</th>
								<td>
								'.$data['comm'].'
								</td>
							</tr>
						</tbody></table>
					</td>
				</tr>
				<tr>
					<td style="margin-bottom:20px; text-align:left">
					<br>
					<br>
					<table style="width:100%;border-top: 1px dashed #888; padding: 20px 0; text-align:center; font-size:1.1em;">
						<tr>
							<td>
								Состав заказа
							</td>
						</tr>
					</table>
					<table style="width:100%;font-size: .9em;padding: 0 20px;">
						<thead>
							<tr>
								<th>Наименование</th>
								<th style="text-align:right;"></th>
								<th style="text-align:center;"></th>
								<th style="text-align:right;">Стоимость</th>
							</tr>
						</thead>
						<tbody>
								'.$prodsTxt.'
								<tr>
									<td colspan="4" style="height:25px;">
									</td>
								</tr>
								<tr>
									<th colspan="3">Итоговая сумма</th>
									<th style="text-align:right; "><nobr>'.number_format($data['orderProds']['sumPrice'],0,',',' ').' руб.</nobr></th>
								</tr>
						</tbody>
					</table>
					<br>
					<span style="display:inline-block;width:100%;font-size:12px;margin-bottom:50px;margin-top: 10px;color: #888;text-align:center;">'.$uidTxt.'</span>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;background:#ECEFF1;padding:10px;border-radius: 0 0 4px 4px;">
					<a style="color:inherit" href="'.$siteUrl.'"><span style="display:inline-block; text-align:center; font-size:14px; color:#888;">'.date('Y').' © '.nl2br(CONFIG_SITE_COPYRIGHT).'</span></a>
					</td>
				</tr>
				</tbody>
				</table>
				</div>';
				$title = "Ваш заказ №".$orderId;
				$title = '=?UTF-8?B?' . base64_encode($title) . '?=';
				$from = $siteName." <".CONFIG_SITE_ADMIN.">";
				mail($toEmail, $title, $letterText, "Content-type: text/html; charset=utf-8\r\nFrom:".$from);
			break;

			default:
				# code...
				break;
		}
	}

}