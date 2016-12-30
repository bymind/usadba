<?php

class Controller_Login extends Controller
{

	function __construct()
	{
		$this->model = new Model();
		$this->view = new View();
	}

	function action_index()
	{
		return 0;
	}

}