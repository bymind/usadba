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

<!--

		<div class="col-md-3 col-xs-12 pl-0">
			<div class="col-md-9">
				<div class="settings sort">
					<div class="title ">Сортировка</div>
					<div class="items">
						<a href="#" class="selected"><span class="hidden-xs">сначала </span>самые новые</a>
						<a href="#"><span class="hidden-xs">сначала </span>самые старые</a>
						<a href="#"><span class="hidden-xs">сначала </span>популярные</a>
						<a href="#"><span class="hidden-xs">сначала </span>непопулярные</a>
						<a href="#">по авторам</a>
						<a href="#">по площадкам</a>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="settings select">
					<div class="title ">Выборка <div class='reset'></div></div>
					<div class="items">
						<ul>
							<li>
								<span data-attr="автор">автор</span>
								<div class="pins-list">
									<div class="pin">author</div>
									<div class="pin">author</div>
									<div class="pin">author author author author author</div>
									<div class="pin">author</div>
									<div class="pin">author</div>
									<div class="pin">author</div>
								</div>
								<div class="unpin"></div>
							</li>
							<li>
								<span data-attr="место">место</span>
								<div class="pins-list">
									<div class="pin">place</div>
									<div class="pin">place</div>
									<div class="pin">place</div>
									<div class="pin">place</div>
								</div>
								<div class="unpin"></div>
							</li>
							<li>
								<span data-attr="время">время</span>
								<div class="pins-list">
									<div class="pin">time</div>
									<div class="pin">time</div>
									<div class="pin">time</div>
									<div class="pin">time</div>
								</div>
								<div class="unpin"></div>
							</li>
							<li>
								<span data-attr="тэг">тэг</span>
								<div class="pins-list">
									<div class="pin">tag</div>
									<div class="pin">tag</div>
									<div class="pin">tag</div>
									<div class="pin">tag</div>
								</div>
								<div class="unpin"></div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

-->

	</div>

</div>
</div>

<script>
	lookNew();
	lookDelete();
	lookUnArchive();
</script>