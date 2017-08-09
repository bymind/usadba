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
				<li><a class="" href="/user/profile/<?php echo $pageDataView['id'] ?>">Личный кабинет</a></li>
				<li><a class="" href="/user/cart">Корзина</a></li>
				<li><a class="active" href="/user/history/<?=$pageDataView['id']?>">Заказы</a></li>
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
			<div class="container-fluid user-orders-box">
				<!-- <div class="row">

				<div class="col-xs-12">
					<div class="title-wide mb-20 mb-xs-10">
						<span><?=$title; ?></span>
					</div>
				</div>
							</div> -->

<?php
// var_dump($orders);
					 // Controller::dump($pageData);
					if ($orders['count'] == 0 || !$orders['order']) {
						?>
						<div class="row">
							<div class="shadow br-2 orders-item mb-10" >
								<div class="row">

									<div class="col-xxs-12">
										<h3 style="text-align:center">
											<div class="cart-body"><div class="nothing">У Вас ещё не было заказов.</div><div class="nothing">Самое время это исправить - посмотрите наш <a href="/catalog" rel="nofollow">каталог</a>.</div></div>
										</h3>
									</div>
								</div>
							</div>
						</div>
						<?php
					} else
					{
					// foreach ($orders['order'] as $order) {
						$order = $orders['order'];
?>
					<div class="row">
						<div class="shadow br-2 orders-item mb-10" data-ordernum="<?php echo $order['id'] ?>">
							<div class="row">

								<div class="col-xs-12">
									<div class="col-xxs-12 col-xs-7">
										<h4>Заказ №<?=$order['id']?></h4>
									</div>
									<div class="col-xxs-12 col-xs-5">
										<span class="timestamp"><?=$order['datetime']?></span>
									</div>
									<div class="col-xs-12 mt-10 mb-10 bb-1-lightgray"></div>
								</div>

							<div class="col-xs-12 mb-10">
								<div class="order-details">
									<table>
										<tbody>
											<tr>
												<th>Статус</th>
												<td><span class="order-status status-<?=$order['stat_label']?>"><?=$order['stat_text']?></span>
												</td>
											</tr>
											<tr>
												<th>Дата</th>
												<td><?=$order['datetime']?></td>
											</tr>
											<tr><th>Адрес</th>
												<td><?=$order['addr']?></td>
											</tr>
											<tr class="br"><th></th><td></td></tr>
											<tr>
												<th>Имя</th>
												<td><?=$order['name']?></td>
											</tr>
											<tr>
												<th>Телефон</th>
												<td><?=$order['phone']?></td>
											</tr>
											<tr class="br">
												<th></th><td></td>
											</tr>
											<tr>
												<th>Оплата</th>
												<td><?=$order['pay_type']?></td>
											</tr>
											<tr class="br">
												<th></th><td></td>
											</tr>
											<?php
												if ($order['comm']!="") {
													?>
											<tr>
												<th>
												Комментарий
												</th>
												<td class="comm">
													<?=$order['comm']?>
												</td>
											</tr>
													<?php
												}
											?>
											<tr class="br">
												<th></th><td></td>
											</tr>
											<tr class="br">
												<th>Состав заказа:</th>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>

<?php
							foreach ($order['prod_list']['items'] as $prod) {
								?>
								<div class="col-xs-12">
									<div class="orders-item orders-item-prod br-2" data-art="<?php echo $prod['art'] ?>">
										<div class="row">
											<div class="col-xxs-5 col-xs-4 col-md-4 col-lg-3">
												<div class="product-poster br-2 o-h">
													<a href="<?php echo $prod['url'] ?>"><img src="<?php echo $prod['poster'] ?>" alt="<?php echo $prod['name'] ?>"></a>
												</div>
											</div>

											<div class="col-xxs-7 col-xs-4 col-md-8 col-lg-9">
												<div class="details-wrapper">
													<div class="name" data-prodname='true'>
														<a href="<?php echo $prod['url'] ?>"><?php echo $prod['name'] ?></a>
													</div>
													<div class="counts-wrapper">
														<div class="one-item-price">
															<div class="prod-price"><?php echo $prod['count'] ?> х <span class="number"><?php echo $prod['price'] ?></span> руб
															</div>
														</div>
														<div class="sum-item-price">
															<span class="sum-number-price">
																<?php echo $prod['price']*$prod['count']; ?>
															</span> руб
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xs-12 mt-10 mb-10 bb-1-lightgray"></div>
								<?php
							}
?>
										<div class="col-xs-12 mb-20">
											<div class="sum-price-line">
											Итог <?=$order['prod_list']['sumPrice']?> руб
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