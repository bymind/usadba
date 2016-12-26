<?php

class Controller_Cart extends Controller
{

	function __construct()
	{
		$this->model = new Model_Cart();
	}

	function action_index()
	{
		if (isset($_POST['target'])) {
			switch ($_POST['target']) {
				case 'updCart':
					if (isset($_POST['jsonCart'])) {
						Self::updCart();
					} else {
						Route::Catch_Error('404');
					};
					break;

				case 'checkCart':
					if (isset($_SESSION['cart'])) {
						Self::checkCart();
					} else {
						return false;
					}
					break;

				default:
					# code...
					break;
			}
		}
	return 0;
	}

	function updCart()
	{
		$jsonCart = $_POST['jsonCart'];
		Controller::sessionEdit('edit', 'cart', $jsonCart);
		return 0;
	}

	function checkCart()
	{
		// $_SESSION['cart'] = NULL;
		$jsonCart = $_SESSION['cart'];
		echo "$jsonCart";
		return $jsonCart;
	}

}