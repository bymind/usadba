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
		<div class="profile-box shadow br-2 mb-20 mb-xxs-10">
			<div class="container-fluid">
				<div class="row">

				<div class="col-xs-12">
					<div class="cart-title mb-20">
						<span>Сейчас в корзине</span>
					</div>
				</div>
			</div>

<?php

					 // Controller::dump($pageData);
					$cartItems = $pageData['items'];
					$cartCount = 1;
					foreach ($cartItems as $cartItem) {
?>
					<div class="row br-bt-gray">
					<div class="cart-item clearfix">
						<div class="col-xxs-6 col-xs-4 col-md-4 col-lg-3">
							<div class="count-items">
								<?php echo $cartCount++."."; ?>
							</div>
							<div class="product-poster br-2">
								<a href="<?php echo $cartItem['url'] ?>"><img src="<?php echo $cartItem['poster'] ?>" alt="<?php echo $cartItem['name'] ?>"></a>
							</div>
						</div>

						<div class="col-xxs-6 col-xs-4 col-md-8 col-lg-9">
							<div class="details-wrapper">
								<div class="name">
									<a href="<?php echo $cartItem['url'] ?>"><?php echo $cartItem['name'] ?></a>
								</div>
								<div class="incs">
									<div class="minus" data-toggle="tooltip" data-placement="top" title="" data-original-title="Уменьшить">-</div>
									<input type="tel" value="<?php echo $cartItem['count'] ?>">
									<div class="plus" data-toggle="tooltip" data-placement="top" title="" data-original-title="Добавить">+</div>
								</div>
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