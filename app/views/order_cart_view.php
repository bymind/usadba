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
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<div class="title-wide mb-20 mb-xs-10">
							<span>Оформление заказа</span>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 shadow br-2 bg-f pl-10 pr-20 pt-10 pb-10 order-box">
						<div class="container-fluid">
							<div class="row">
								<div class=" col-xs-12">
									<form id="order-form" action="">
										<div class="row">
										<div class="form-group mb-20 col-xs-10 col-sm-7 ">
											<label for="order-name">Имя получателя</label>
											<?php if ($userData['name']) {
												?>
													<input type="text" name="name" id="order-name" class="form-control" placeholder="На это имя будет оформлен заказ" required value="<?php echo $userData['name'];?>">
												<?php
											}  else {?>
											<input type="text" name="name" id="order-name" class="form-control" placeholder="На это имя будет оформлен заказ" required>
											<?php }?>
										</div>
										</div>
										<div class="row">
										<div class="form-group mb-20 col-xs-10 col-sm-7">
											<label for="order-phone">Контактный телефон</label>
											<?php if ($userData['phone']) {
												?>
													<input type="tel" name="phone" id="order-phone" class="form-control" placeholder="+7 (___) ___-__-__" required value="<?php echo $userData['phone'];?>">
												<?php
											}  else {?>
											<input type="tel" name="phone" id="order-phone" class="form-control" placeholder="+7 (___) ___-__-__" required>
											<?php }?>
										</div>
										</div>
										<div class="row">
										<div class="form-group mb-20 col-xs-10 col-sm-7">
											<label for="order-address">Адрес</label>
											<?php
												if ($userData['addresses']) {
													?>
													<input type="text" name="address" id="order-address" class="form-control" placeholder="Укажите адрес доставки" required value="<?php echo $userData['addresses'][0];?>">
													<?php
												} else {
											 ?>
											<input type="text" name="address" id="order-address" class="form-control suggestions-input" placeholder="Укажите адрес доставки" required>
											<?php }?>
											<link href="https://cdn.jsdelivr.net/jquery.suggestions/17.2/css/suggestions.css" type="text/css" rel="stylesheet" />
											<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
											<!--[if lt IE 10]>
											<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxtransport-xdomainrequest/1.0.1/jquery.xdomainrequest.min.js"></script>
											<![endif]-->
											<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.suggestions/17.2/js/jquery.suggestions.min.js"></script>
											<script type="text/javascript">
											    $("#order-address").suggestions({
											        token: "f11ed4cbf3102dd5f4560d48c433ba0a36903275",
											        type: "ADDRESS",
											        count: 5,
											        /* Вызывается, когда пользователь выбирает одну из подсказок */
											        onSelect: function(suggestion) {
											            console.log(suggestion);
											        }
											    });
											</script>
										</div>
										</div>
										<div class="row">
										<div class="form-group mb-20 col-xs-10 col-sm-7">
											<label for="order-comment">Комментарий к заказу</label>
											<textarea style="resize:vertical" name="comment" class="form-control" id="order-comment" cols="30" rows="4" placeholder="Если есть, что уточнить"></textarea>
										</div>
										</div>
										<div class="row">
										<div class="form-group mb-20 col-xs-12">


												<label for="">Способ оплаты</label>

											<div class="row">
												<div class="sum-item-price mb-10">
													<div>Сумма заказа <b><?php echo $pageData['sumPrice'] ?> руб</b></div>
												</div>
											</div>

											<div class="row">
												<div class="btn-group order-pay" data-toggle="buttons">
													<div class="col-xxs-6 col-xs-4">
														<label class="btn btn-order order-cash active">
															<input type="radio" name="order-paytype" id="order-cash" autocomplete="off" checked> Наличными курьеру
														</label>
													</div>
													<div class="col-xxs-6 col-xs-4">
														<label class="btn btn-order order-cardonline">
															<input type="radio" name="order-paytype" id="order-cardonline" autocomplete="off"> Картой онлайн
														</label>
													</div>
													<div class="col-xs-4" style="display:none">
														<label class="btn btn-order order-cardcurier">
															<input type="radio" name="order-paytype" id="order-cardcurier" autocomplete="off"> cardcurier
														</label>
													</div>
												</div>
											</div>

										</div>
										</div>
										<div class="row">
											<div class="col-xs-12 mb-20">
												<button type="button" class="br-2 btn btn-primary order-btn" data-target="goSendOrder" data-targetindex="#">Отправить заказ</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>

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