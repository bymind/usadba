<div class="container mb-20">
	<div class="row">

	<div class="hidden-xs col-sm-3">

		<?php
			if (is_array($sidebar)) {
				foreach ($sidebar as $sidebar_item) {
					include $sidebar_item;
				}
			} else {
				include $sidebar;
			}
		?>

	</div>

<?php
	if (isset($_SESSION['user'])) {
?>

	<div class="col-xs-12 col-sm-2 col-sm-push-7">
		<div class="profile-menu shadow br-2 mb-xs-10">
			<ul>
				<li><a class="" href="/user/profile/<?php echo $userData['id'] ?>">Личный кабинет</a></li>
				<li><a class="active" href="/user/cart">Корзина</a></li>
				<li><a class="" href="/user/history">Заказы</a></li>
				<li class="hidden-xxs"><a class="" href="/user/logout">Выйти</a></li>
			</ul>
		</div>
	</div>

	<div class="col-xs-12 col-sm-7 col-sm-pull-2">
<?php
	} else {
?>
	<div class="col-xs-12 col-sm-9">
<?php
	}
 ?>




		<div class="cart-box mb-20 mb-xxs-10">
			<div class="container-fluid cart-cards-reload">
				<div class="row">

				<div class="col-xs-12">
					<div class="title-wide mb-20 mb-xs-10">
						<span>Сейчас в корзине</span>
					</div>
				</div>
			</div>

<?php

					 // Controller::dump($pageData);
					$cartItems = $pageData['items'];
					$cartCount = 1;
					// var_dump($pageData);
					if (count($cartItems) == 0) {
						?>
						<div class="row">
							<div class="shadow br-2 prod-card cart-item mb-10" >
								<div class="row">

									<div class="col-xxs-12">
										<h3 style="text-align:center">
											<div class="cart-body"><div class="nothing">Тут пока ничего нет.</div><div class="nothing">Посмотрите наш <a href="/catalog" rel="nofollow">каталог</a>.</div></div>
										</h3>
									</div>
								</div>
							</div>
						</div>
						<?php
					} else
					{
					foreach ($cartItems as $cartItem) {
?>
					<div class="row">
						<div class="shadow br-2 prod-card cart-item mb-10" data-art="<?php echo $cartItem['art'] ?>">
							<div class="row">
								<div class="col-xxs-5 col-xs-4 col-md-4 col-lg-3">
									<div class="product-poster br-2 o-h">
										<a href="<?php echo $cartItem['url'] ?>"><img src="<?php echo $cartItem['poster'] ?>" alt="<?php echo $cartItem['name'] ?>"></a>
									</div>
								</div>

								<div class="col-xxs-7 col-xs-4 col-md-8 col-lg-9">
									<div class="details-wrapper">
										<div class="name" data-prodname='true'>
											<a href="<?php echo $cartItem['url'] ?>"><?php echo $cartItem['name'] ?></a>
										</div>
										<div class="counts-wrapper">
											<div class="incs">
												<div class="minus" data-toggle="tooltip" data-placement="top" title="" data-original-title="Уменьшить">-</div>
												<input type="tel" value="<?php echo $cartItem['count'] ?>">
												<div class="plus" data-toggle="tooltip" data-placement="top" title="" data-original-title="Добавить">+</div>
											</div>
											<div class="one-item-price">
												<div class="prod-price">

													х <span class="number"><?php echo $cartItem['price'] ?></span> руб

												</div>
											</div>
											<div class="sum-item-price">
												<span class="sum-number-price">
													<?php echo $cartItem['price']*$cartItem['count']; ?>
												</span> руб
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
<?php
					}
?>
					<div class="row">
						<div class="col-xs-12 shadow br-2 bg-f pl-10 pr-20 pt-10 pb-10">
							<div class="sum-item-price mt-10">
								<div>Сумма заказа
								<span class="sum-number-price"><?php echo $pageData['sumPrice'] ?></span> руб
								</div>
								<div class="mt-20">
									<button type="button" class="br-2 btn btn-primary order-btn" data-target="goLink" data-targetindex="/user/cart/send">Оформить заказ</button>
								</div>
							</div>
						</div>
					</div>
<?php
}
?>
				</div>
			</div>
		</div>

	<div class="col-xs-12 visible-xs">

		<?php
			if (is_array($sidebar)) {
				foreach ($sidebar as $sidebar_item) {
					include $sidebar_item;
				}
			} else {
				include $sidebar;
			}
		?>

	</div>

	</div>
</div>