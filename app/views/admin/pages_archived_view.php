<script src="/js/admin/main.js"></script>
<script src="/js/admin/pages.js"></script>
<div class="main-content">

<div class="container-fluid">

	<div class="row pt-0 pb-0">
		<div class="col-md-12">
			<h4 class="blocked">
				<a href="/admin/pages" class="disabled">
				Опубликованные
				</a>
			</h4>
			<h1 class="blocked">
					Архив
			</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-md-9 pl-0">

			<?php
				if (!$posts) {
					?>
					<div class="col-md-6 col-md-offset-3" style="text-align:center;">
						<h2>Нет записей в архиве</h2>
					</div>
					<?php
				} else
				foreach ($posts as $post) {
			?>

			<div class="col-xs-12 col-sm-4 col-md-3">
			<div class="blog-item">
				<div class="poster" style="background-image: url(<?php
				                                                 if ($post['poster']) {
					                                                 echo $post['poster'];
				                                                 } else
				                                                 	echo "/upload/prod-default-cover.jpg";
				                                                 	 ?>);">
					<div class="controls hidden-xs">
						<a href="/admin/pages/edit/<?php echo $post['tech_name'] ?>" class="btn-edit">Редактировать</a>
						<a href="/admin/pages/delete/<?php echo $post['tech_name'] ?>" data-target="<?php echo htmlspecialchars($post['title']) ?>" data-id="<?php echo $post['id'] ?>" class="btn-delete">Удалить</a>
						<a href="/admin/pages/archive/<?php echo $post['tech_name'] ?>"  data-target="<?php echo htmlspecialchars($post['title']) ?>" data-id="<?php echo $post['id'] ?>" class="btn-archive btn-unarchive">Опубликовать</a>
					</div>
					<div class="controls visible-xs">
						<a href="/admin/pages/edit/<?php echo $post['tech_name'] ?>"></a>
					</div>
				</div>
				<div class="details">
					<div class="tags" style="display:none">
						<div class="tag-item"></div>
					</div>
					<div class="title"><a href="/admin/pages/edit/<?php echo $post['tech_name'] ?>"><?php echo $post['title'] ?><br><?php echo $post['subtitle'] ?></a></div>
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
	lookUnArchive();
</script>