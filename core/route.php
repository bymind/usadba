<?php

	/**
	* TODO rewrite route.php
	*/

	/**
	* Route
	*
	* Класс-маршрутизатор для определения запрашиваемой страницы.
	* > цепляет классы контроллеров и моделей;
	* > создает экземпляры контролеров страниц и вызывает действия этих контроллеров.
	*/

	/**
	*
	*
	*
	*	Бум! коммент!
	*/

class Route
{
	/**
	* Константы
	*/

	// Адрес папки с контроллерами
	const CONTROLLERS_PATH = "app/controllers/";

	// Адрес папки с моделями
	const MODELS_PATH = "app/models/";


	/**
	* loadController()
	* Loading class
	* @param $class
	*/
	private function loadController($class)
	{
		$class_file = strtolower($class).'.php';
		$class_path = self::CONTROLLERS_PATH.$class_file;

		if (file_exists($class_path)) {
			include $class_path;
		} else {
			// echo "Контроллер $class_path не найден!";
		}
	}


	/**
	* loadModel()
	* Loading model
	* @param $model
	*/
	private function loadModel($model)
	{
		$model_file = strtolower($model).'.php';
		$model_path = self::MODELS_PATH.$model_file;

		if ( file_exists($model_path) )
		{
			// echo "Модуль $model_path подключен!";
			include $model_path;
		} else {
			// echo "Модуль $model_path не найден!";
		}

	}

	/**
	* start()
	* Starting routing.
	*/
	public static function start()
	{
		session_start();

		/**
		*	дербаним url на куски
		*/
		$routes = explode('/', $_SERVER['REQUEST_URI']);

		// if first part empty - go main page
		if ($routes[1]==null)
		{
			self::goMainPage();
		}
		else // else - go mining page
		{
			foreach ($routes as &$route) {
				$route = Self::PrepareUrl($route);
			}
			// var_dump($routes);
			self::pageMiner($routes);
		}
	}


	/**
	* pageMiner()
	* собираем страницу
	* @param $routes
	*/
	public function pageMiner($routes)
	{
		if (strtolower($routes[1])=='main') {
			Self::Catch_Error('404');
		}

		$controller_name = $routes[1];
		$controller_name = Self::PrepareUrl($controller_name);

		// второй кусок - это экшен
		if ( !empty($routes[2]) && !($routes[2]==NULL))
		{
			$action_name = $routes[2];
			$action_name = Self::PrepareUrl($action_name);
		}

		// третий кусок - это параметр
		if ( !empty($routes[3]) && !($routes[3]==NULL))
		{
			$param= $routes[3];
			$param = Self::PrepareUrl($param);
		}

		// четвертый кусок - это значение параметра
		if ( !empty($routes[4]) && !($routes[4]==NULL))
		{
			$params['name'] = $routes[3];
			$params['name'] = Self::PrepareUrl($params['name']);
			$params['value'] = $routes[4];
			$params['value'] = Self::PrepareUrl($params['value']);
		}

		// префиксы для имен
		$model_name = 'Model_'.$controller_name;
		$controller_name = 'Controller_'.$controller_name;
		if (isset($action_name)) {
			$action_param = $action_name;
			$action_name = 'action_'.$action_name;
		} else {
			$action_name = 'action_index';
		}

		// врубаем модель, если есть
		// TODO: перенести подключение модели в контроллер
		if ($model_name=="Model_admin") {
			self::loadModel('admin/'.$model_name);
		} else
			self::loadModel($model_name);

		// та же херня с контроллером
		// плюс ищем и выполняем экшен, если он есть
		// если что-то не нашли - ебашим 404

		if ($controller_name=="Controller_admin") {
			$controller_path = self::CONTROLLERS_PATH. strtolower('admin/'.$controller_name).'.php';
		} else
		$controller_path = self::CONTROLLERS_PATH. strtolower($controller_name).'.php';
		if ( file_exists($controller_path) )
		{
			if ($controller_name=="Controller_admin") {
				Self::loadController('admin/'.$controller_name);
			} else
			Self::loadController($controller_name);
			// создаем экземпляр класса контроллера
			$controller = new $controller_name;
			// создадим-ка еще одну переменную для имени экшена, старая переменная может нам еще пригодиться
			$action = $action_name;

			if ( (isset($params['value'])) && ($params['value']!=="") && ($controller_name=="Controller_admin"))
				{ // если админка
					// Route::Debug($controller_name, $action, $params);
					$controller->$action($params);
				} else// проверяем наличие такого экшена в контроллере
			 if (method_exists($controller, $action))
					{
						// нашли - ебашим
						if ($param==='') {
							$controller->$action();
						} else {
							$controller->$action($param);
						}
					} else
						{ // если не нашли экшен и нет параметра, пробуем пропихнуть в специальный экшен с параметром, равном имени экшена
								if ($param == "") {
									$param = $action_param;
									$action_first = $action_name;
									$action = 'action_param';
									// echo "$action($param);";
									if (method_exists($controller, $action))
										{
											$controller->$action($param);
										}else {
											self::Catch_Error('404');
										}
								} else
									if (isset($param)) {
										if (!isset($params)) {
											$params = [];
											$params['name'] = $action_param;
											$params['value'] = $param;
											$action = 'action_param';
											if (method_exists($controller, $action))
												{
													$controller->$action($params);
												}else {

													self::Catch_Error('404');
												}
										} else {
												switch ($routes[1]) {
													case 'catalog':
														$parentCat = explode("_", $action_name);
														$parentCat = $parentCat[1];
														$action = 'action_param';
														$prodParams['parentCat'] = $parentCat;
														$prodParams['curCat'] = $params['name'];
														$prodParams['curProd'] = $params['value'];
														$controller->$action($prodParams);
														break;

													default:
														self::Catch_Error('404');
														break;
												}
											}
									}
								 else {
									self::Catch_Error('404');
								}
						}
		}
		else {
			$param = Self::PrepareUrl($routes[1]);
			$controller_name= "Controller_Pages";
			$controller_path = self::CONTROLLERS_PATH.strtolower($controller_name).'.php';
			// var_dump($controller_path);
			if ( file_exists($controller_path) )
			{
				Self::loadModel('model_pages');
				Self::loadController($controller_name);
				// создаем экземпляр класса контроллера
				$controller = new $controller_name;
				// создадим-ка еще одну переменную для имени экшена, старая переменная может нам еще пригодиться
				$action = "action_index";
				$controller->$action($param);
			} else
				self::Catch_Error('404');
		}
	}


	/**
	* goMainPage()
	* Подрубаем главную страницу
	*/
	public function goMainPage()
	{
		self::loadModel('Model_Main');

		$controller_name = "Controller_main";

		self::loadController($controller_name);
		$controller = new $controller_name;

		$action = "action_index";
		$controller->$action();

	}


	/**
	*	PrepareUrl()
	*	Экранируем url
	*/
	function PrepareUrl($u)
	{
		$u = addslashes(urlencode($u));
		return $u;
	}


	/**
	*	Catch_Error($code)
	*	Показываем страницу ошибки
	*	@param $code
	*/
	function Catch_Error($code = null)
	{
		// создаем контроллер ошибки
		$controller_error_name = 'controller_error_'.$code;
		self::loadController($controller_error_name);

		// подключаем модель
		$model_error_name = 'model_error_'.$code;
		self::loadModel($model_error_name);

		$error_controller = new $controller_error_name;
		$error_action = 'action_index';
		$error_code = $code;

		$error_controller->$error_action($error_code);
		exit();
	}


	/**
	*	Catch_301_Redirect($to)
	*	Обработка 301-редиректа
	*	@param $to
	*/
	function Catch_301_Redirect($to = "")
	{
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: http://'.$_SERVER['HTTP_HOST'].$to);
		exit();
	}


	/**
	*	Debug($controller, $action, $params)
	* Выводит имена контроллера, экшена и параметров
	*	@param $controller, $action, $params
	*/
	function Debug($controller, $action, $params)
	{
		// тип отладка
		// просто выводит имена контроллера, экшена и параметров
		echo '$controller = ';
		var_dump($controller);
		echo '<br>';
		echo '$action = ';
		var_dump($action);
		echo '<br>';
		echo '$params = ';
		var_dump($params);
		echo '<br>';
	}

}