<link href="/css/owl-carousel/pod.theme.css" rel="stylesheet">

<div class="prod-day shadow mb-20 br-2 hidden-xs">
	<div class="prod-day-title">
		Товар дня
	</div>

<div id="carousel-pod" class="owl-carousel pod-theme prod-pod">
<?php

	foreach ($prodItems['all'] as $prod) {
		if ($prod['pod'] == 1) {
			?>
<div class="item">
	<div data-art="<?= $prod['art'] ?>" class="prod-card">
		<div class="prod-day-img box-img display labeled" data-label="<?= $prod['labels'] ?>" style='background-image: url("<?= $prod['images'] ?>");'><a href="<?= $prod['url'] ?>" class="prod-link" title="<?= $prod['name'] ?>"></a>
			<div class="heart" title="Добавить в избранное" data-toggle="tooltip" data-placement="right"></div>
		</div>
		<div class="prod-name"><a href="<?= $prod['url'] ?>" data-prodname="true" ><?= $prod['name'] ?></a></div>
		<div class="prod-details"><?= $prod['mini_desc'] ?></div>
		<div class="prod-price"><span class="number"><?= $prod['price'] ?></span> руб</div>
		<div class="prod-counts">(<span class="number"><?= $prod['count_measure'] ?></span> <span class="measure"><?= $prod['measure'] ?></span>)</div>
		<div class="prod-btn-block">
			<div class="prod-avail"><?= $prod['in_stock_val'] ?></div>
			<div class="prod-rev">отзывов - <span class="rev">5</span></div>
			<button class="to-cart">Купить</button>
		</div>
	</div>
</div>
<?php
		}
	}

?>
</div>
</div>