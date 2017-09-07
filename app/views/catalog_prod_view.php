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

			<div class="col-xxs-12 col-xs-12 col-sm-9">
				<div class="product-layer shadow br-2 clearfix">

					<!-- TITLE -->
					<h1 class="title-wide" data-prodname="true"><?php
							echo $currentProduct['name'];
						?></h1>
					<?php
					$arr = array('right'=>'goods', 'uid'=>$_SESSION['user']['id']);
					if (($_SESSION['user']['is_admin']==1)&&(Model::isHasRight($arr))) {
						?>
						<a class="admin_edit_link" href="/admin/goods/edit/<?=$currentProduct['art']?>" target="_blank">изменить</a>
						<?php
					}
					?>
					<div class="mini-desc mb-10">
						<?php
							echo $currentProduct['mini_desc'].", артикул ".$currentProduct['art'];
						?>
					</div>

					<div class="hidden-xxs sidecat prevcat mb-20">
						<span class="prevcat">
							<a href="/catalog/<?php echo $backToCatalog; ?>" >
							<?php
								echo "Назад в каталог";
							?>
							</a>
						</span>
					</div>

					<div class="col-xs-12 mb-20">
					<div class="row">

					<!-- IMAGE -->
						<div class="col-xxs-12 col-xas-6 col-xs-7 col-md-8 mb-xxs-20">
							<div class="poster">
								<img class="main" src="<?php echo $currentProduct['images']; ?>" alt="<?php echo $currentProduct['name']; ?>">
							</div>
						</div>

						<!-- CARD -->
						<div class="col-xxs-12 col-xas-6 col-xs-5 col-md-4">
							<div data-art="<?= $currentProduct['art'] ?>" data-prodid="<?= $currentProduct['id'] ?>" class="card-details prod-card bordered br-2">
								<div class="details-inner box-img labeled" data-label="<?= $currentProduct['labels'] ?>">
									<span class="measure">
										цена за <?php echo $currentProduct['count_measure'] ?> <?php echo $currentProduct['measure'] ?>
									</span>
									<span class="prod-price">
											<span class="number"><?php echo $currentProduct['price'] ?></span>
											<span class="money-name">руб</span>
									</span>
									<div class="prod-btn-block">
										<button class="to-cart">Купить</button>
									</div>
									<div class="card-prod-avail"><?php echo $currentProduct['in_stock_val']?></div>
									<div class="card-prod-avail">Уже купили <?php echo $currentProduct['count_buy']?></div>
								</div>
							</div>
							<div class="card-like mt-10">
							<?php
							if ($_SESSION['user']['id']) {
								if (strripos($_SESSION['user']['favs'], $currentProduct['id'])===false) {
									?>
									<span class="like-this" title="Добавить в избранное" >Добавить в избранное</span>
								<?php
								} else {
									?>
									<span class="like-this liked" title="Удалить из избранного" >Удалить из избранного</span>
									<?php
									}
							} else {
									?>
								<span class="like-this fake-like" title="Добавить в избранное" >Добавить в избранное</span>
								<?php
								}
							?>
							</div>
						</div>

					</div>
					</div>


					<!-- DATA -->
					<div class="col-xs-12">
						<div class="tabs-box">
							<div class="tab active" data-content="spec">
								<span>Характеристики</span>
							</div>
							<div class="tab" data-content="reviews">
								<span>Отзывы (<span class="comment_count"><?php echo $prodReviews['count']?></span>)</span>
							</div>
						</div>

						<div class="col-xs-12">
						<div class="row">
							<div class="tab-content-box spec active">
<?php
		if ($currentProduct['weight']!="") {
		?>
								<div class="params clearfix">
									<div class="param col-xxs-12 col-xs-4">
									<div class="row">
										<span>Вес</span>
										</div>
									</div>
									<div class="value col-xs-8 col-xxs-12">
										<span>
										<?php echo $currentProduct['weight']; ?>
										</span>
									</div>
								</div>
		<?php
		}
		if ($currentProduct['country']!="") {
		?>
								<div class="params clearfix">
									<div class="param col-xxs-12 col-xs-4">
									<div class="row">
										<span>Страна</span>
										</div>
									</div>
									<div class="value col-xs-8 col-xxs-12">
										<span>
										<?php echo $currentProduct['country']; ?>
										</span>
									</div>
								</div>
		<?php
		}
		if ($currentProduct['stor_cond']!="") {
		?>
								<div class="params clearfix">
									<div class="param col-xxs-12 col-xs-4">
									<div class="row">
										<span>Условия хранения</span>
										</div>
									</div>
									<div class="value col-xs-8 col-xxs-12">
										<span>
										<?php echo $currentProduct['stor_cond']; ?>
										</span>
									</div>
								</div>
		<?php
		}
		if ($currentProduct['nut_val']!="") {
		?>
								<div class="params clearfix">
									<div class="param col-xxs-12 col-xs-4">
									<div class="row">
										<span>Пищевая ценность</span>
										</div>
									</div>
									<div class="value col-xs-8 col-xxs-12">
										<span>
										<?php echo $currentProduct['nut_val']; ?>
										</span>
									</div>
								</div>
		<?php
		}
		if ($currentProduct['energy_val']!="") {
		?>
								<div class="params clearfix">
									<div class="param col-xxs-12 col-xs-4">
									<div class="row">
										<span>Энергетическая ценность</span>
										</div>
									</div>
									<div class="value col-xs-8 col-xxs-12">
										<span>
										<?php echo $currentProduct['energy_val']; ?>
										</span>
									</div>
								</div>
		<?php
		}
		if ($currentProduct['consist']!="") {
		?>
								<div class="params clearfix">
									<div class="param col-xxs-12 col-xs-4">
									<div class="row">
										<span>Состав</span>
										</div>
									</div>
									<div class="value col-xs-8 col-xxs-12">
										<span>
										<?php echo $currentProduct['consist']; ?>
										</span>
									</div>
								</div>
		<?php
		}
		?>
								<div class="description mt-20">
									<?php
										echo $currentProduct['description'];
									?>
								</div>

							</div>

							<div class="tab-content-box reviews">
								<a href="#" class="give-review<?php if (!$isLogged) {?> fake-like<?php }?>">Оставить отзыв</a>
				<?php
					if ($isLogged) {
						?>
									<div class="comment-over comm-form clearfix">
										<div class="avatar mini">
											<img src="<?php echo $_SESSION['user']['avatar']; ?>" alt="<?php echo $_SESSION['user']['name'];?>">
										</div>
										<div class="com-details">
											<span class="author"><?php echo $_SESSION['user']['name'];?></span>
											<div class="com-text">
												<textarea name="comment" id="comment-text" maxlength="2000" rows="3"></textarea>
												<div class="row mt-10 mb-10">
													<div class="col-xxs-12 col-xs-6 col-sm-3 pr-10">
														<button type="button" class="btn btn-primary comm-send" data-type="product" data-prodid="<?=$currentProduct['id']?>">Отправить</button>
													</div>
													<div class="col-sm-9 hidden-xs note-wrapper pl-10">
														<span class="note">Не более 2 000 символов. <span id="count_letters">Осталось <span id="count_num"></span>.</span><br>Отзыв, превышающий это ограничение, будет укорочен.</span>
													</div>
												</div>
											</div>
										</div>
									</div>
						<?php
					}
				?>
								<div class="comments-box" data-prodid="<?=$currentProduct['id']?>">
<?php
	if (!$prodReviews) {
		echo "<span>Отзывов пока нет</span>";
	} else {
	unset($prodReviews['count']);
	foreach ($prodReviews as $review) {
		?>
									<div class="comment-over clearfix" id="comm-<?=$review['id']?>">
										<div class="avatar mini">
											<img src="<?php echo $review['author_avatar']?>" alt="<?php echo $review['author_name']?>">
										</div>
										<div class="com-details">
											<span class="author"><?php echo $review['author_name']?></span><span class="pub-time"><?php echo $review['pub_time']?></span>
											<div class="com-text">
												<?php echo $review['com_text']?>
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
						</div>
					</div>

				</div>
			</div>

			<div class="col-xxs-12 col-xs-12 col-sm-9 mb-20">

			</div>

<?php

	if ($popularCat['show_popular']==1) {
			if (!$prodCatPopulars){
				echo "<div class='col-xxs-12 col-xs-12 col-sm-9'><h3>Нет популярных товаров в этой категории</h3></div>";
			} else {
	?>
			<div class=" col-xxs-12 col-xs-12 col-sm-9">
				<div class="title-wide mb-10">
					<?php echo $popularCat['popular_name'] ?>
				</div>
			</div>

			<div class="col-xxs-12 col-xs-12 col-sm-9 mb-20">
				<div class="prod-line-outer prod-popular prod-theme">

				<?php
					foreach ($prodCatPopulars as $prod) {
				?>

									<div data-art="<?= $prod['art'] ?>" data-prodid="<?= $prod['id'] ?>" class="prod-card shadow br-2">
										<div class="prod-img box-img display labeled" data-imgname="<?= $prod['images'] ?>" data-label="<?= $prod['labels'] ?>"><a href="<?= $prod['url'] ?>" class="prod-link" title="<?= $prod['name'] ?>"></a>
											<?php
														if ($_SESSION['user']['id']) {
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



	<?php
	}
}

?>


<div class="col-xxs-12 col-xs-12 visible-xs">

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

				<!-- <div class="hidden-xxs col-xs-12 col-sm-9 mb-20 mt-20">
					<div class="seo-text mb-20">
						<h3 class="title">
							Интернет магазин полуфабрикатов – это быстро, просто и удобно
						</h3>
						<span class="text">
							Магазин кулинарии онлайн - отличное решение для любого человека в наше время. Постоянная нехватка времени порой не дает москвичам достаточной возможности прийти в магазин, чтобы купить полезные и вкусные продукты.
						</span>
					</div>
					<div class="seo-text mb-20">
						<h3 class="title">
							Доставка вкусной и полезной домашней еды
						</h3>
						<span class="text">
							Купить продукты с доставкой на дом в Москве очень просто. При помощи копмьютера и интернета вы выбираете продукты и блюда, не тратя время на поход по магазинам. Оформление заказа занимает всего пару минут - и останется только получить ваш заказ от курьера.
							<br>Кулинария «Усадьба-Центр» также порадует вас акциями, скидками и бонусными предложениями.
						</span>
					</div>
					<div class="seo-text">
						<h3 class="title">
							Интернет-магазин Усадьба-Кулинария.ру – ваш лучший поставщик блюд и продуктов
						</h3>
						<span class="text">
							Продажа блюд и продуктов производится онлайн и по телефону. Кулинария заботится о своих клиентах, и поэтому мы делаем все, чтобы вы получали только качественные и полезные полуфабрикаты и блюда в кратчайшие сроки.
						</span>
					</div>

				</div> -->
	</div>
</div>