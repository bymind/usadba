<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<nav class="main_menu mb-20">
				<div class="hidden-xxs col-xs-7 col-sm-8 p-0"><!--
				 --><ul class="menu_text_units"><!--
					 --><li class="show-li item-li <?php if ($pageId=='catalog') {echo 'active_menu';}?> "><a class="arrow pclick <?php if ($pageId=='catalog') {echo 'active_menu';}?>" href="/catalog" data-tname="catalog">Каталог</a><ul class="sub-menu"><!--
					 -->
					<div class="container-fluid">
						<div class="row">
							<div class="menu-box">

<?php
	$i = 1;
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
		if ($i % 3 == 0) {
			?>
			</div><div class="menu-box">
			<?php
		}
		$i++;
	}

?>
							</div>
						</div>
					</div>
	</ul>
	</li>

<?php

	foreach ($menuItems as $menuItem) {
		$classItem = "";
		if ($menuItem['tech_name']==$pageId) {
			$classItem = "active_menu";
		}
		?><li class="show-li item-li <?php echo $classItem ?>"><a data-tname="<?php echo $menuItem['tech_name'] ?>" href="<?php echo $menuItem['url'] ?>" class="<?php echo $classItem ?>"><?php echo $menuItem['name'] ?></a></li><!--
				--><?php
	}

?><li class="hide-li-else else"><a class="arrow pclick" href="/#else">Еще</a><ul class="else-ul sub-menu"></ul></li></ul>
				</div>
				<div class="col-xxs-12 col-xs-5 col-sm-4 p-0"><!--
					 --><ul class="menu_text_units" style="display: block;"><!--
						 --><li class="search-item"><input class="search-text" type="text" placeholder="Поиск по товарам"><button class="btn-search hidden-xxs"></button><button class="btn-search mini-search visible-xxs"></button></li><!--
											 --></ul><!--
											 				 --></div>
			</nav>
		</div>
	</div>
</div>