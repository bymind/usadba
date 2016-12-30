<?php

class Controller_User extends Controller
{


	function __construct()
	{
		$this->model = new Model_User();
		$this->view = new View();
	}


	function action_index()
	{
		Controller::jsonConsole($_SESSION);
		Controller::jsonConsole($isLogged);
		return 0;
	}

	function action_login()
	{
		$jsonLogin = json_decode($_POST['jsonLogin'], true);
		$target = Route::PrepareUrl($_POST['target']);
		if (isset($target)) {
			switch ($target) {
				case 'login':
					$userData = $this->model->getLogin($jsonLogin);
					if ( isset($userData['id']) ) {
						$jsonUser = json_encode($userData);
						if (Self::login($userData)) {
							echo "true";
						} else
						echo "false";
					} else {
						echo "false";
					}
					return true;
					break;

				default:
					Route::Catch_Error('404');
					break;
			}
		}
	return 0;
	}

	function login($user)
	{
		$_SESSION['user'] = $user;
		$isLogged = Self::is_logged();
		return $isLogged;
	}

	function action_logout()
	{
		if (isset($_SESSION['user'])) {
			unset($_SESSION['user']);
		} else {
			echo "No user";
		}
	return 0;
	}

}