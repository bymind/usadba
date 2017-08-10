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
				<div class="title-wide mb-20">
					<div class="sidecat prevcat hidden-xs">
						<span class="prevcat">
							<a href="/catalog/<?php echo $prodCat['prev']['tech_name']; ?>" >
							<?php
								echo $prodCat['prev']['name'];
							?>
							</a>
						</span>
					</div>
					<h1><?php
						echo $prodCat['name'];
					?></h1>
					<div class="sidecat nextcat hidden-xs">
						<span class="nextcat">
							<a href="/catalog/<?php echo $prodCat['next']['tech_name']; ?>">
							<?php
								echo $prodCat['next']['name'];
							?>
							</a>
						</span>
					</div>
				</div>
				</div>
				<div class="col-xxs-12 col-xs-12 col-sm-9">
				<div class="subtitle subcats mb-20">

					<a href="/catalog/<?php if (!$curCatId) { echo $prodCat['tech_name']; } else {echo $catId; }?>" class='subcat all <?php if ((!$curCatId)&&(!$section)) { echo "active"; }?>'>Все</a>
					<?php
						if (!$curCatId) { $cat = $prodCat['tech_name']; } else {$cat = $catId; }
						if ($prodCats['tree'][$cat]['child']) {
						foreach ($prodCats['tree'][$cat]['child'] as $child) {
							?>
							<a href="<?php echo $child['url'] ?>" class="subcat <?php if (($curCatId)&&($curCatId == $child['tech_name'])) echo 'active'; ?> "><?php echo $child['name'] ?></a>
							<?php
						}
						}

						if ($prodCatItemsHasNew) {

						?>
						<a href="/catalog/<?php if (!$curCatId) { echo $prodCat['tech_name']; } else {echo $catId; }?>/new" class="subcat <?php if ($section=='new') echo 'active'; ?> new">Новинки</a>
						<?php
						}
						if ($prodCatItemsHasSales) {
						?>
						<a href="/catalog/<?php if (!$curCatId) { echo $prodCat['tech_name']; } else {echo $catId; }?>/sales" class="subcat <?php if ($section=='sales') echo 'active'; ?> sales">Акции</a>
						<?php
						}
					?>
				</div>
			</div>
			<div class="col-xxs-12 col-xs-12 col-sm-9 mb-20 mb-xxs-10">
				<div class="row">

<?php
	if (!$prodCatItems){
		echo "<div><h3 class='ta-c'>Категория пуста</h3></div>";
	} else {
		foreach ($prodCatItems as $prod) {
?>


					<div class="col-xxs-6 col-xs-3">
					<div class="row">
					<div data-art="<?= $prod['art'] ?>" data-prodid="<?= $prod['id'] ?>" class="prod-card shadow br-2 mb-20 mb-xxs-10">
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
						<div class="prod-name"><a href="<?= $prod['url']?>" data-prodname="true"><?= $prod['name']?></a></div>
						<div class="prod-details"><?= $prod['mini_desc']?></div>
						<div class="prod-price"><span class="number"><?= $prod['price']?></span> руб</div>
						<div class="prod-counts">(<span class="number"><?= $prod['count_measure']?></span> <span class="measure"><?= $prod['measure']?></span>)</div>
						<div class="prod-btn-block">
							<div class="prod-avail"><?= $prod['in_stock_val']?></div>
							<div class="prod-rev zero"><span class="rev" style="display:none"><?= $prod['count_reviews']?></span><?= $prod['count_reviews_text']?></div>
							<button class="to-cart">Купить</button>
						</div>
					</div>
					</div>
					</div>


<?php
			}

			if ($pagination) {
				?>
				<div class="row">
				<div class="col-xs-12 ta-c">
					<ul class="pagination catalog-pages mt-10 mb-10" data-curpage="<?=$curPage;?>">
					<?php if ($pagination['cur_page']==1) {} else { ?>
						<li <?php if ($pagination['cur_page']==1) {echo "class='disabled'";} ?> >
							<a rel="nofollow" href="<?=$_SERVER['REDIRECT_URL']."?page=".($pagination['cur_page']-1)?>" aria-label="previous" title="предыдущая">
								<span aria-hidden="true">&lsaquo;</span>
							</a>
						</li>
						<?php }
						for ($i=1; $i <= $pagination['count_pages']; $i++) {
							if ($pagination['cur_page']==$i) $addClass = "class='active'";
							?>
							<li <?=$addClass;?> ><a rel="nofollow" href="<?=$_SERVER['REDIRECT_URL']."?page=".$i?>"><?=$i;?></a></li>
							<?php
							$addClass = "";
						}
						if ($pagination['cur_page']==$pagination['count_pages']) {} else { ?>
						<li <?php if ($pagination['cur_page']==$pagination['count_pages']) {echo "class='disabled'";} ?> >
							<a rel="nofollow" href="<?=$_SERVER['REDIRECT_URL']."?page=".($pagination['cur_page']+1)?>" aria-label="next" title="следующая">
								<span aria-hidden="true">&rsaquo;</span>
							</a>
						</li>
						<?php
						}
						?>
					</ul>
				</div>
				</div>
				<?php
			}
		}
?>
				</div>

<?php
	if ($popularCat['show_popular']==1) {
			if (!$prodCatPopulars){
				echo "<div class='col-xxs-12 col-xs-12 col-sm-9'><h3 class='ta-c'>Нет популярных товаров в этой категории</h3></div>";
			} else {
	?>
	<div class="row">
			<div class=" col-xxs-12 col-xs-12">
				<div class="title-wide mb-10">
					<?php
						// echo $popularCat['popular_name'];
						echo "Популярное из категории";
					?>
				</div>
			</div>

			<div class="col-xxs-12 col-xs-12 mb-20">
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
										<div class="prod-name"><a href="<?= $prod['url']?>" data-prodname="true"><?= $prod['name']?></a></div>
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
	</div>


	<?php
	}
}

?>
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
				<!-- <div class="hidden-xxs col-xs-12 col-sm-12 mb-20 mt-20">
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

