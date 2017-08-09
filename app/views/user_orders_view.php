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

					 // Controller::dump($pageData);
					if ($orders['count'] == 0 || !$orders['orders']) {
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
					foreach ($orders['orders'] as $order) {
						// $order = $orders['orders'][0];
?>
					<div class="row">
						<div class="shadow br-2 orders-item mb-10" data-ordernum="<?php echo $order['id'] ?>">
							<div class="row">

								<div class="col-xs-12">
									<div class="col-xxs-12 col-xs-7">
										<a class="order-num-link" href="/user/order/<?=$order['id']?>">Заказ №<?=$order['id']?></a><span class="order-status ml-10 status-<?=$order['stat_label']?>"><?=$order['stat_text']?></span>
									</div>
									<div class="col-xxs-12 col-xs-5">
										<span class="timestamp"><?=$order['datetime']?></span>
									</div>
									<div class="col-xs-12 mt-10 mb-10 bb-1-lightgray"></div>
								</div>

									<?php
										$i =1;
										foreach ($order['prod_list']['items'] as $item) {
											?>
										<div class="col-xs-12 prodline">
											<div class="col-xs-6">
												<div class="prodname">
													<?=$i.". ".$item['name']?>
												</div>
											</div>
											<div class="col-xs-2">
												<div class="prodcount">
													<?=$item['count']?> шт
												</div>
											</div>
											<div class="col-xs-4">
												<div class="prodprice">
												<?php
													if ($item['count']>1) {
														?>
															<span class="small-gray"><?=$item['price']?>x<?=$item['count']?> =</span> <?=$item['price']*$item['count']?> руб
														<?php
													} else {
													?>
													<?=$item['price']?> руб
													<?php } ?>
												</div>
											</div>
										</div>
											<?
											$i++;
										}
										unset($i);
									?>
										<div class="col-xs-12 sum-price-line pr-10 mb-20">
											Итог <?=$order['prod_list']['sumPrice']?> руб
										</div>

										<div class="col-xs-12">
											<div class="col-xxs-12 col-xs-8">
												<span class="addr-note">Адрес доставки</span>
												<span class="addr-data"><?=$order['addr']?></span>
											</div>
											<div class="col-xxs-12 col-xs-4 ta-r">
											<a class="order-look-link" href="/user/order/<?=$order['id']?>"><b>Посмотреть заказ</b></a>
											</div>
										</div>

							</div>
						</div>
					</div>
<?php
	}
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