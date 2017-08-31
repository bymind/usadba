<script src="/js/admin/main.js"></script>
<script src="/js/admin/articles.js"></script>
<div class="main-content">

<div class="container-fluid">

	<div class="row pt-0 pb-0">
		<div class="col-md-12">
			<h1 class="blocked">
				Опубликованные
			</h1>
			<h4 class="blocked">
				<a href="/admin/articles/archived" class="disabled">
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
				<div class="poster" style='background-image: url(<?php
					if ($post['poster']) {
						echo $post['poster'].");'";
					} else
						echo "/upload/prod-default-cover.jpg);background-color:#fff; background-size:contain; background-repeat:no-repeat;'";
					?> >
					<div class="controls hidden-xs">
						<a href="/admin/articles/edit/<?php echo $post['url'] ?>" class="btn-edit">Редактировать</a>
						<a href="/news/<?php echo $post['url'] ?>" class="look" target="_blank">Посмотреть на сайте</a>
						<a href="/admin/articles/delete/<?php echo $post['url'] ?>" data-target="<?php echo htmlspecialchars($post['title']) ?>" data-id="<?php echo $post['id'] ?>" class="btn-delete">Удалить</a>
						<a href="/admin/articles/archive/<?php echo $post['url'] ?>"  data-target="<?php echo htmlspecialchars($post['title']) ?>" data-id="<?php echo $post['id'] ?>" class="btn-archive">В архив</a>
					</div>
					<div class="controls visible-xs">
						<a href="/admin/articles/edit/<?php echo $post['url'] ?>"></a>
					</div>
				</div>
				<div class="details">
					<div class="tags" style="display:none">
						<div class="tag-item"></div>
					</div>
					<div class="title"><a href="/admin/articles/edit/<?php echo $post['url'] ?>"><?php echo $post['title'] ?><br><?php echo $post['subtitle'] ?></a></div>
					<div class="post-date"><?php echo $post['datetime'] ?></div>
					<div class="author">Автор: <span><?php echo $post['author'] ?></span></div>
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