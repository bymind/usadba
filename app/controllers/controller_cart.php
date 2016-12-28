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
					Route::Catch_Error('404');
					break;
			}
		} else {
			Route::Catch_Error('404');
		}
	return 0;
	}

	function updCart()
	{
		$jsonCart = $_POST['jsonCart'];
		$cartDecoded = json_decode($jsonCart, true);
		$cartDecoded['items'] = Model::createProdUrlByArt($cartDecoded['items']);
		$jsonCart = json_encode($cartDecoded);
		Controller::sessionEdit('edit', 'cart', $jsonCart);
		echo $jsonCart;
		return 0;
	}

	function checkCart()
	{
		// $_SESSION['cart'] = NULL; // delete session cart
		$jsonCart = $_SESSION['cart'];
		$cartDecoded = json_decode($jsonCart, true);
		if ($cartDecoded['items']) {
			echo $jsonCart;
		} else {
			echo "false";
		}
		return $jsonCart;
	}

}