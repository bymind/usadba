<script src="/js/admin/main.js"></script>
<script src="/js/admin/goods.js"></script>
<div class="main-content">

<div class="container-fluid">

	<div class="row pt-0 pb-0">
		<div class="col-md-12">
			<h4 class="blocked">
				<a href="/admin/goods" class="disabled">
				Опубликованные
				</a>
			</h4>
			<h1 class="blocked">
					Скрытые
			</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-md-9 pl-0">

			<?php
				if (!$products) {
					?>
					<div class="col-md-6 col-md-offset-3" style="text-align:center;">
						<h2>Нет скрытых товаров</h2>
					</div>
					<?php
				} else
				foreach ($products as $product) {
			?>

			<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
			<div class="blog-item">
				<div class="poster" style="background-image: url(<?php echo $product['images'] ?>);">
					<div class="controls hidden-xs">
						<a href="/admin/goods/edit/<?php echo $product['art'] ?>" class="btn-edit">Редактировать</a>
						<a href="<?php echo $product['url'] ?>" class="look" target="_blank">Посмотреть на сайте</a>
						<a href="/admin/goods/delete/<?php echo $product['art'] ?>" data-target="<?php echo htmlspecialchars($product['name']) ?>" data-id="<?php echo $product['id'] ?>" class="btn-delete">Удалить</a>
						<a href="/admin/goods/archive/<?php echo $product['art'] ?>"  data-target="<?php echo htmlspecialchars($product['name']) ?>" data-id="<?php echo $product['id'] ?>" class="btn-archive">В архив</a>
					</div>
					<div class="controls visible-xs">
						<a href="/admin/goods/edit/<?php echo $product['art'] ?>"></a>
					</div>
				</div>
				<div class="details">
					<div class="tags">
						<?php
						$labels = explode(",", $product['labels']);
						foreach ($labels as $key => $label) {
							switch ($label) {
								case 'new':
									?>
									<span class='label new'>new!</span>
									<?php
									break;
								case 'popular':
									?>
									<span class='label popular'>хит!</span>
									<?php
									break;
								case 'sales':
									?>
									<span class='label sales'>акция!</span>
									<?php
									break;

								default:
									# code...
									break;
							}
						}
					?>
					</div>
					<div class="title"><a href="/admin/goods/edit/<?php echo $product['art'] ?>"><?php echo $product['name'] ?><br><?php echo $product['mini_desc'] ?></a></div>
					<div class="post-date"><?php echo $product['added_time'] ?></div>
					<div class="author">Автор: <span><?php echo $product['author'] ?></span></div>
				</div>
			</div>
			</div>

			<?php
			}
			?>


		</div>

		<!-- <div class="col-xs-3">
			<div class="col-md-12">
							<div class="settings tags" style="margin-bottom:30px;">
								<div class="title ">Категории<div class='reset'></div></div>
								<div class="items cat_links pt-10 pb-20">
									<?php
												foreach ($cat_tree['tree'] as $parent => $arr) {
													$text = $arr['name'];
													$link = '/admin/goods/cat/'.$arr['id'];
													if ($arr['id']==$goods[0]['cat_id']) {
														echo "<a class='active' href='$link'>$text</a>";
													} else
													echo "<a href='$link'>$text</a>";
													if (isset($arr['child'])) {
														foreach ($arr['child'] as $child) {
															$text = $child['name'];
															$link = '/admin/goods/cat/'.$child['id'];
															if ($child['id']==$goods[0]['cat_id']) {
																echo "<a class='active' href='$link'>—$text</a>";
															} else
															echo "<a href='$link'>—$text</a>";
														}
													}
												}
									 ?>
								</div>
							</div>
						</div>
		</div> -->

	</div>

</div>
</div>

<script>
	lookNew();
	lookDelete();
	lookUnArchive();
</script>