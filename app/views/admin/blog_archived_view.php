<script src="/js/admin/main.js"></script>
<script src="/js/admin/blog.js"></script>
<div class="main-content">

<div class="container-fluid">

	<div class="row pt-0 pb-0">
		<div class="col-md-12">
			<h1 class="blocked">
					Архив
			</h1>
			<h4 class="blocked">
				<a href="/admin/blog" class="disabled">
				Опубликованные
				</a>
			</h4>
		</div>
	</div>

	<div class="row">
		<div class="col-md-9 pl-0">

			<?php
				if (!$posts) {
					?>
					<div class="col-md-6 col-md-offset-3" style="text-align:center;">
						<h2>Нет неопубликованных постов</h2>
					</div>
					<?php
				} else
				foreach ($posts as $post) {
			?>

			<div class="col-md-3">
			<div class="blog-item">
				<div class="poster" style='background-image: url(<?php
					if ($post['poster']) {
						echo $post['poster'].");'";
					} else
						echo "/upload/prod-default-cover.jpg);background-color:#fff; background-size:contain; background-repeat:no-repeat;'";
					?> >
					<div class="controls hidden-xs">
						<a href="/admin/blog/edit/<?php echo $post['url'] ?>" class="btn-edit">Редактировать</a>
						<!-- <a href="/blog/post/<?php echo $post['url'] ?>" class="look" target="_blank">Посмотреть на сайте</a> -->
						<a href="/admin/blog/delete/<?php echo $post['url'] ?>" data-target="<?php echo htmlspecialchars($post['title']) ?>" data-id="<?php echo $post['id'] ?>" class="btn-delete">Удалить</a>
						<a href="/admin/blog/archive/<?php echo $post['url'] ?>"  data-target="<?php echo htmlspecialchars($post['title']) ?>" data-id="<?php echo $post['id'] ?>" class="btn-archive btn-unarchive">Опубликовать</a>
					</div>
					<div class="controls visible-xs">
						<a href="/admin/blog/edit/<?php echo $post['url'] ?>"></a>
					</div>
				</div>
				<div class="details">
					<div class="tags">
						<div class="tag-item">прошедшие</div>
					</div>
					<div class="title"><a href="/admin/blog/edit/<?php echo $post['url'] ?>"><?php echo $post['title'] ?></a></div>
					<div class="post-date"><?php echo $post['date'] ?></div>
					<div class="author">Автор: <span><?php echo $post['author'] ?></span></div>
					<div class="place">Место: <span><?php echo "place"; ?></span></div>
				</div>
			</div>
			</div>

			<?php
			}
			?>


		</div>

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

	</div>

</div>
</div>

<script>
	lookNew();
	lookDelete();
	lookUnArchive();
</script>