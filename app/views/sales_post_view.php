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


	<div class="col-xxs-12 col-xs-12 col-sm-9 clearfix">
		<div class="shadow br-2 bg-f mb-20">
			<div class="sale-card" data-saleid="<?php echo $pageData['poster']?>">
				<div class="details">
					<div class="start">Акция запущена <?php echo $pageData['start_time']?></div>
					<?php
						$today = date('Y-m-d H:i:s');
						// echo $today."<br>".$pageData['raw_end_time'];
						if ($pageData['raw_end_time']<=$today) { ?>
						<div class="end">Завершилась <?php echo $pageData['end_time']?></div>
						<?php } else {
					?>
					<div class="end">Завершится <?php echo $pageData['end_time']?></div>
					<?php }
					?>
				</div>
			<?php
			if ($_SESSION['user']['is_admin']==1) {
				?>
				<a class="admin_edit_link" href="/admin/sales/edit/<?=$pageData['tech_name']?>" target="_blank">изменить</a>
				<?php
			}
			?>
			</div>
			<div class="description">
				<p>
					<?php echo $pageData['description'] ?>
				</p>
			</div>
		</div>

		<div class=" col-xxs-12 col-xs-12">
			<div class="title-wide mb-20 mt-20">
				<?php
					// echo $popularCat['popular_name'];
					echo "Товары по акции";
				?>
			</div>
		</div>

					<div class="col-xxs-12 col-xs-12 mb-20 mb-xxs-10">
						<div class="row">

		<?php
			if (!$prodSales){
				echo "<div><h3>Категория пуста</h3></div>";
			} else {

				foreach ($prodSales as $prod) {
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