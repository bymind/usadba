<ul class="side-menu shadow mb-20 br-2">
	<li><a href="/catalog/popular"><span class="label popular">хит!</span>Популярное</a></li>
	<li><a href="/catalog/new"><span class="label new">new!</span>Новинки</a></li>
	<li><a href="/catalog/sales"><span class="label sales">акция!</span>Акции</a></li>

<?php

	foreach ($prodCats['tree'] as $cat) {

?>
	<li><a href="<?= $cat['url']?>"

<?php
	if ( isset($cat['child']) ) {
		?>
		class="arrow arr pclick"
		<?php
	}
?>
	><?= $cat['name']?></a>

<?php
	if ( isset($cat['child']) ) {
	?>
	<ul class="side-sub-menu">
		<?php
			foreach ($cat['child'] as $child) {
				?>
				<li class="side-sub-li">
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
</ul>