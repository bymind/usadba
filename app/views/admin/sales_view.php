<script src="/js/admin/main.js"></script>
<script src="/js/admin/articles.js"></script>
<div class="main-content">

<div class="container-fluid">

	<div class="row pt-0 pb-0">
		<div class="col-md-12">
			<h1 class="blocked">
				Текущие
			</h1>
			<h4 class="blocked">
				<a href="/admin/sales/archived" class="disabled">
					Архив
				</a>
			</h4>
		</div>
	</div>

	<div class="row">
		<div class="col-md-9 pl-0">

			<?php
				foreach ($posts as $post) {
			?>

			<div class="col-xs-12 col-sm-4 col-md-3">
			<div class="blog-item">
				<div class="poster" style="background-image: url(<?php echo $post['poster'] ?>);">
					<div class="controls hidden-xs">
						<a href="/admin/sales/edit/<?php echo $post['url'] ?>" class="btn-edit">Редактировать</a>
						<a href="/sales/<?php echo $post['url'] ?>" class="look" target="_blank">Посмотреть на сайте</a>
						<a href="/admin/sales/delete/<?php echo $post['url'] ?>" data-target="<?php echo htmlspecialchars($post['title']) ?>" data-id="<?php echo $post['id'] ?>" class="btn-delete">Удалить</a>
						<a href="/admin/sales/archive/<?php echo $post['url'] ?>"  data-target="<?php echo htmlspecialchars($post['title']) ?>" data-id="<?php echo $post['id'] ?>" class="btn-archive">В архив</a>
					</div>
					<div class="controls visible-xs">
						<a href="/admin/sales/edit/<?php echo $post['url'] ?>"></a>
					</div>
				</div>
				<div class="details">
					<div class="tags" style="display:none">
						<div class="tag-item"></div>
					</div>
					<div class="title"><a href="/admin/sales/edit/<?php echo $post['url'] ?>"><?php echo $post['name'] ?></a></div>
					<div class="post-date">Нач. <?php echo $post['start_time'] ?><br>Кон. <?php echo $post['end_time'] ?></div>
					<!-- <div class="author">Автор: <span><?php echo $post['author'] ?></span></div> -->
				</div>
			</div>
			</div>

			<?php
			}
			?>


		</div>
	</div>

</div>
</div>

<script>
	lookNew();
	lookDelete();
	lookArchive();
</script>