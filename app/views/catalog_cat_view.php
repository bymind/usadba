<div class="container mb-20">
	<div class="row">

		<div class="col-xxs-12 col-xs-6 col-sm-3">

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
				<div class="title-wide mb-10">
					<div class="sidecat prevcat">
					<?php
						echo $prodCat['prev']['name'];
					?>
					</div>
					<?php
						echo $prodCat['name'];
					?>
					<div class="sidecat nextcat">
					<?php
						echo $prodCat['next']['name'];
					?>
					</div>
				</div>
			</div>
			<div class="col-xxs-12 col-xs-12 col-sm-9 mb-20">
				<div class="prod-line-outer prod-new prod-theme">

<?php
	foreach ($prodCatItems as $prod) {
?>



					<div data-art="<?= $prod['art'] ?>" class="prod-card shadow br-2">
						<div class="prod-img box-img display labeled" data-imgname="<?= $prod['images'] ?>" data-label="<?= $prod['labels'] ?>"><a href="<?= $prod['url'] ?>" class="prod-link" title="<?= $prod['name'] ?>"></a>
							<div class="heart" data-imgname="rozan" title="Добавить в избранное" data-toggle="tooltip" data-placement="right"></div>
						</div>
						<div class="prod-name"><a href="<?= $prod['url']?>"><?= $prod['name']?></a></div>
						<div class="prod-details"><?= $prod['mini_desc']?></div>
						<div class="prod-price"><span class="number"><?= $prod['price']?></span> руб</div>
						<div class="prod-counts">(<span class="number"><?= $prod['count_measure']?></span> <span class="measure"><?= $prod['measure']?></span>)</div>
						<div class="prod-btn-block">
							<div class="prod-avail"><?= $prod['in_stock_val']?></div>
							<div class="prod-rev zero">отзывов - <span class="rev">5</span></div>
							<button class="to-cart">Купить</button>
						</div>
					</div>

<?php
	}
?>

				</div>
			</div>

<?php

	if ($prodCat['show_popular']==1) {
	?>
			<div class=" col-xxs-12 col-xs-12 col-sm-9">
				<div class="title-wide mb-10">
					<?php echo $prodCat['popular_name'] ?>
				</div>
			</div>

			<div class="col-xxs-12 col-xs-12 col-sm-9 mb-20">
				<div class="prod-line-outer prod-popular prod-theme">

				<?php
					foreach ($prodCatPopulars as $prod) {
				?>

									<div data-art="<?= $prod['art'] ?>" class="prod-card shadow br-2">
										<div class="prod-img box-img display labeled" data-imgname="<?= $prod['images'] ?>" data-label="<?= $prod['labels'] ?>"><a href="<?= $prod['url'] ?>" class="prod-link" title="<?= $prod['name'] ?>"></a>
											<div class="heart" data-imgname="rozan" title="Добавить в избранное" data-toggle="tooltip" data-placement="right"></div>
										</div>
										<div class="prod-name"><a href="<?= $prod['url']?>"><?= $prod['name']?></a></div>
										<div class="prod-details"><?= $prod['mini_desc']?></div>
										<div class="prod-price"><span class="number"><?= $prod['price']?></span> руб</div>
										<div class="prod-counts">(<span class="number"><?= $prod['count_measure']?></span> <span class="measure"><?= $prod['measure']?></span>)</div>
										<div class="prod-btn-block">
											<div class="prod-avail"><?= $prod['in_stock_val']?></div>
											<div class="prod-rev zero">отзывов - <span class="rev">5</span></div>
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

?>


				<div class="hidden-xxs col-xs-12 col-sm-9 mb-20 mt-20">
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

				</div>
	</div>
</div>

