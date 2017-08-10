<ul class="side-menu shadow mb-20 br-2">
	<li><a href="/catalog/tag/popular" class="<?php if ($catId=='popular') {echo 'active_menu';}?>"><span class="label popular">хит!</span>Популярное</a></li>
	<li><a href="/catalog/tag/new" class="<?php if ($catId=='new') {echo 'active_menu';}?>"><span class="label new">new!</span>Новинки</a></li>
	<li><a href="/sales" class="<?php if ($catId=='sales') {echo 'active_menu';}?>"><span class="label sales">акция!</span>Акции</a></li>
	<li><a href="<?php
		if ($isLogged) {
			echo "/catalog/tag/favs";
		} else {
			echo "#";
		}
	?>" class="<?php if ($catId=='favs') {echo 'active_menu';}?> <?php
		if ($isLogged) {
		} else {
			echo "fake-like";
		}
	?>"><span class="label favs"></span>Мои избранные<span class="hidden-sm"> товары</span></a></li>

<?php

	foreach ($prodCats['tree'] as $cat) {

?>
	<li class="<?php if ($cat['tech_name']==$catId) {echo 'active';}?>"><a href="<?= $cat['url']?>"

<?php
	if ( isset($cat['child']) ) {
		?>
		class="arrow arr pclick <?php if ($cat['tech_name']==$catId) {echo 'active_menu active';}?> "
		<?php
	}
?>
	><span class="just-this-cat"><?= $cat['name']?></span></a>

<?php
	if ( isset($cat['child']) ) {
	?>
	<ul class="side-sub-menu <?php if ($cat['tech_name']==$catId) {echo 'opened';}?> ">
		<?php
			foreach ($cat['child'] as $child) {
				?>
				<li class="side-sub-li <?php if ($child['tech_name']==$curCatId) {echo 'active';}?>">
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