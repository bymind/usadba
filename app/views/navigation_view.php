<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<nav class="main_menu mb-20">
				<div class="hidden-xxs col-xs-7 col-sm-8 p-0"><!--
				 --><ul class="menu_text_units"><!--
					 --><li class="show-li item-li"><a class="arrow pclick" href="/catalog">Каталог</a><ul class="sub-menu"><!--
					 --><div class="row"><div class="col-xs-6">

<?php

	foreach ($prodCats['tree'] as $cat) {
?>

	<li class="sub-li item-li">
		<a href="<?= $cat['url']?>"><?= $cat['name']?></a>

<?php
	if ( isset($cat['child']) ) {
		?>
		<ul class="sub-sub-menu">
		<?php
		foreach ($cat['child'] as $child) {
?>
			<li class="sub-sub-li">
				<a href="<?= $child['url']?>"><?= $child['name']?></a>
			</li>
<?php
		}
	?>
		</ul>
	<?php
	}
?>

	</li>

<?php
	}

?>
		</div>
	</ul>
	</li>


				  <li class="show-li item-li"><a href="/sales">Акции</a></li><!--
					--><li class="show-li item-li"><a href="/news">Новости</a></li><!--
					--><li class="show-li item-li"><a href="/delivery">Доставка</a></li><!--
					--><li class="show-li item-li"><a href="/payment">Оплата</a></li><!--
					--><li class="show-li item-li"><a href="/about">О нас</a></li><!--
					--><li class="show-li item-li"><a href="/reviews">Отзывы</a></li><!--
					--><li class="hide-li-else else"><a class="arrow pclick" href="/#else">Еще</a><ul class="else-ul sub-menu"></ul></li><!--
					 --></ul>
				</div>
				<div class="col-xxs-12 col-xs-5 col-sm-4 p-0"><!--
					 --><ul class="menu_text_units" style="display: block;"><!--
						 --><li class="search-item"><input class="search-text" type="text" placeholder="Поиск по каталогу"><button class="btn-search hidden-xxs"></button><button class="btn-search mini-search visible-xxs"></button></li><!--
											 --></ul><!--
											 				 --></div>
			</nav>
		</div>
	</div>
</div>