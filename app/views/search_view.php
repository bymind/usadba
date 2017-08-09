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

	<div class="col-xs-12 col-sm-2 col-sm-push-7" style="display:none">
		<div class="profile-menu shadow br-2 mb-xs-10">
			<ul>
				<li><a class="" href="/user/profile/<?php echo $_SESSION['user']['id'] ?>">Личный кабинет</a></li>
				<li><a class="" href="/user/cart">Корзина</a></li>
				<li><a class="" href="/user/history/<?=$_SESSION['user']['id']?>">Заказы</a></li>
				<li class="hidden-xxs"><a class="" href="/user/logout">Выйти</a></li>
			</ul>
		</div>
	</div>

	<div class="col-xs-12 col-sm-9">
<?php
	} else {
?>
	<div class="col-xs-12 col-sm-9">
<?php
	}
 ?>




		<div class="cart-box mb-20 mb-xxs-10">
			<div class="container-fluid user-orders-box">
				<div class="row">

				<div class="col-xs-12">
					<div class="title-wide mb-20 mb-xs-10">
						<span><?=$pageData['title_header']; ?></span>
					</div>
				</div>
				</div>
				<div class="row">

	<?php
		if ((!$search['by_name'] || count($search['by_name'])==0)&&(!$search['by_art'] || count($search['by_art'])==0)&&(!$search['by_desc'] || count($search['by_desc'])==0)){
			echo "<div><h3>Ничего не найдено</h3></div>";
		} else {
			if ($search['by_name']) {
				echo "<div><h3>Поиск по названию</h3></div>";
			foreach ($search['by_name'] as $res) {
	?>
						<div class="col-xxs-6 col-xs-3">
						<div class="row">
						<div data-art="<?= $res['art'] ?>" data-prodid="<?= $res['id'] ?>" class="prod-card shadow br-2 mb-20 mb-xxs-10">
							<div class="prod-img box-img display labeled" data-imgname="<?= $res['images'] ?>" data-label="<?= $res['labels'] ?>"><a href="<?= $res['url'] ?>" class="prod-link" title="<?= $res['name'] ?>"></a>
								<?php
											if ($_SESSION['user']['id']) {
												if (strripos($_SESSION['user']['favs'], $res['id'])===false) {
													?>
												<div class="heart" title="Добавить в избранное" data-toggle="tooltip" data-placement="right"></div>
												<?php
												} else {
													?>
													<div class="heart liked" title="Удалить из избранного" data-toggle="tooltip" data-placement="right"></div>
													<?php
													}
											} else {
													?>
												<div class="heart fake-like" title="Добавить в избранное" data-toggle="tooltip" data-placement="right"></div>
												<?php
												}
											?>
							</div>
							<div class="prod-name"><a href="<?= $res['url']?>" data-prodname="true"><?= $res['name']?></a></div>
							<div class="prod-details"><?= $res['mini_desc']?></div>
							<div class="prod-price"><span class="number"><?= $res['price']?></span> руб</div>
							<div class="prod-counts">(<span class="number"><?= $res['count_measure']?></span> <span class="measure"><?= $res['measure']?></span>)</div>
							<div class="prod-btn-block">
								<div class="prod-avail"><?= $res['in_stock_val']?></div>
								<div class="prod-rev zero"><span class="rev" style="display:none"><?= $res['count_reviews']?></span><?= $res['count_reviews_text']?></div>
								<button class="to-cart">Купить</button>
							</div>
						</div>
						</div>
						</div>

	<?php
			}
			?>
			<?php
			}
					if ($search['by_art']) {
						echo "</div><div class='row'><div><h3>Поиск по артикулу</h3></div>";
					foreach ($search['by_art'] as $res) {
			?>
								<div class="col-xxs-6 col-xs-3">
								<div class="row">
								<div data-art="<?= $res['art'] ?>" data-prodid="<?= $res['id'] ?>" class="prod-card shadow br-2 mb-20 mb-xxs-10">
									<div class="prod-img box-img display labeled" data-imgname="<?= $res['images'] ?>" data-label="<?= $res['labels'] ?>"><a href="<?= $res['url'] ?>" class="prod-link" title="<?= $res['name'] ?>"></a>
										<?php
													if ($_SESSION['user']['id']) {
														if (strripos($_SESSION['user']['favs'], $res['id'])===false) {
															?>
														<div class="heart" title="Добавить в избранное" data-toggle="tooltip" data-placement="right"></div>
														<?php
														} else {
															?>
															<div class="heart liked" title="Удалить из избранного" data-toggle="tooltip" data-placement="right"></div>
															<?php
															}
													} else {
															?>
														<div class="heart fake-like" title="Добавить в избранное" data-toggle="tooltip" data-placement="right"></div>
														<?php
														}
													?>
									</div>
									<div class="prod-name"><a href="<?= $res['url']?>" data-prodname="true"><?= $res['name']?></a></div>
									<div class="prod-details"><?= $res['mini_desc']?></div>
									<div class="prod-price"><span class="number"><?= $res['price']?></span> руб</div>
									<div class="prod-counts">(<span class="number"><?= $res['count_measure']?></span> <span class="measure"><?= $res['measure']?></span>)</div>
									<div class="prod-btn-block">
										<div class="prod-avail"><?= $res['in_stock_val']?></div>
										<div class="prod-rev zero"><span class="rev" style="display:none"><?= $res['count_reviews']?></span><?= $res['count_reviews_text']?></div>
										<button class="to-cart">Купить</button>
									</div>
								</div>
								</div>
								</div>

			<?php
					}
					}
							if ($search['by_desc']) {
								echo "</div><div class='row'><div><h3>Поиск в описании</h3></div>";
							foreach ($search['by_desc'] as $res) {
					?>
										<div class="col-xxs-6 col-xs-3">
										<div class="row">
										<div data-art="<?= $res['art'] ?>" data-prodid="<?= $res['id'] ?>" class="prod-card shadow br-2 mb-20 mb-xxs-10">
											<div class="prod-img box-img display labeled" data-imgname="<?= $res['images'] ?>" data-label="<?= $res['labels'] ?>"><a href="<?= $res['url'] ?>" class="prod-link" title="<?= $res['name'] ?>"></a>
												<?php
															if ($_SESSION['user']['id']) {
																if (strripos($_SESSION['user']['favs'], $res['id'])===false) {
																	?>
																<div class="heart" title="Добавить в избранное" data-toggle="tooltip" data-placement="right"></div>
																<?php
																} else {
																	?>
																	<div class="heart liked" title="Удалить из избранного" data-toggle="tooltip" data-placement="right"></div>
																	<?php
																	}
															} else {
																	?>
																<div class="heart fake-like" title="Добавить в избранное" data-toggle="tooltip" data-placement="right"></div>
																<?php
																}
															?>
											</div>
											<div class="prod-name"><a href="<?= $res['url']?>" data-prodname="true"><?= $res['name']?></a></div>
											<div class="prod-details"><?= $res['mini_desc']?></div>
											<div class="prod-price"><span class="number"><?= $res['price']?></span> руб</div>
											<div class="prod-counts">(<span class="number"><?= $res['count_measure']?></span> <span class="measure"><?= $res['measure']?></span>)</div>
											<div class="prod-btn-block">
												<div class="prod-avail"><?= $res['in_stock_val']?></div>
												<div class="prod-rev zero"><span class="rev" style="display:none"><?= $res['count_reviews']?></span><?= $res['count_reviews_text']?></div>
												<button class="to-cart">Купить</button>
											</div>
										</div>
										</div>
										</div>

					<?php
							}
							}
		}
	?>
					</div>

				</div>
			</div>
		</div>

		<div class=" col-xxs-12 col-xs-12 col-sm-9">
			<div class="title-wide mb-10">
				Самые популярные
			</div>
		</div>
		<div class="col-xxs-12 col-xs-12 col-sm-9 mb-20">
			<div class="prod-line-outer prod-popular prod-theme">

			<?php
				foreach ($prodItems['popular'] as $prod) {
			?>



								<div data-art="<?= $prod['art'] ?>" data-prodid="<?= $prod['id'] ?>" class="prod-card shadow br-2">
									<div class="prod-img box-img display labeled" data-imgname="<?= $prod['images'] ?>" data-label="<?= $prod['labels'] ?>"><a href="<?= $prod['url'] ?>" class="prod-link" title="<?= $prod['name'] ?>"></a>
										<?php
													if ($_SESSION['user']['favs'] || $_SESSION['user']['id']) {
														if (strripos($_SESSION['user']['favs'], $prod['id'])===false) {
															?>
														<div class="heart" title="Добавить в избранное" data-toggle="tooltip" data-placement="right"></div>
														<?php
														} else {
															?>
															<div class="heart liked" title="Удалить из избранного" data-toggle="tooltip" data-placement="right"></div>
															<?php
															}
													} else {
															?>
														<div class="heart fake-like" title="Добавить в избранное" data-toggle="tooltip" data-placement="right"></div>
														<?php
														}
													?>
									</div>
									<div class="prod-name"><a href="<?= $prod['url']?>" data-prodname="true" ><?= $prod['name']?></a></div>
									<div class="prod-details"><?= $prod['mini_desc']?></div>
									<div class="prod-price"><span class="number"><?= $prod['price']?></span> руб</div>
									<div class="prod-counts">(<span class="number"><?= $prod['count_measure']?></span> <span class="measure"><?= $prod['measure']?></span>)</div>
									<div class="prod-btn-block">
										<div class="prod-avail"><?= $prod['in_stock_val']?></div>
										<div class="prod-rev zero"><span class="rev" style="display:none"><?= $prod['count_reviews']?></span><?= $prod['count_reviews_text']?></div>
										<button class="to-cart">Купить</button>
									</div>
								</div>

			<?php
				}
			?>

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