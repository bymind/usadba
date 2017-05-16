<script src="/js/admin/main.js"></script>
<script src="/js/admin/articles.js"></script>
<div class="main-content">

<div class="container-fluid">

	<div class="row pt-0 pb-0">
		<div class="col-md-12">
			<h1 class="blocked">
					Архив
			</h1>
			<h4 class="blocked">
				<a href="/admin/articles" class="disabled">
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
						<h2>Нет записей в архиве</h2>
					</div>
					<?php
				} else
				foreach ($posts as $post) {
			?>

			<div class="col-md-3">
			<div class="blog-item">
				<div class="poster" style="background-image: url(<?php echo $post['poster'] ?>);">
					<div class="controls hidden-xs">
						<a href="/admin/articles/edit/<?php echo $post['url'] ?>" class="btn-edit">Редактировать</a>
						<!-- <a href="/articles/<?php echo $post['url'] ?>" class="look" target="_blank">Посмотреть на сайте</a> -->
						<a href="/admin/articles/delete/<?php echo $post['url'] ?>" data-target="<?php echo htmlspecialchars($post['title']) ?>" data-id="<?php echo $post['id'] ?>" class="btn-delete">Удалить</a>
						<a href="/admin/articles/archive/<?php echo $post['url'] ?>"  data-target="<?php echo htmlspecialchars($post['title']) ?>" data-id="<?php echo $post['id'] ?>" class="btn-archive btn-unarchive">Опубликовать</a>
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