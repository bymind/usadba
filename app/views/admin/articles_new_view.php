<script src="/js/tinymce/tinymce.min.js"></script>
<script src="/js/admin/main.js"></script>
<script src="/js/admin/articles.js"></script>

<div class="main-content">

<div class="container-fluid">


	<div class="row">
		<div class="col-md-9">
			<div class="row mt-0">
			<div class="col-xs-12">
				<input type="hidden" id="post-id" value="">
				<label for="post-title" class="post-label">Заголовок</label>
				<input type="text" id="post-title" placeholder="Офигенный заголовок" value="">
			</div>
			</div>

			<div class="row mt-0">
			<div class="col-xs-12">
				<label for="post-subtitle" class="post-label">Подзаголовок</label>
				<input type="text" id="post-subtitle" placeholder="Офигенный подзаголовок" value="">
			</div>
			</div>

			<div class="row mt-0">
			<div class="col-xs-12">
				<label for="post-url" class="post-label">URL <span class="really-edit">изменить</span></label>
				<input type="text" id="post-url" placeholder="url" disabled  value="">
			</div>
			</div>

			<div class="row ">
			<div class="col-xs-12">
				<label for="post-anons" class="post-label">Анонс</label>
				<textarea name="" id="post-anons" cols="30" rows="2" placeholder="Интересный анонс"></textarea>
			</div>
			</div>

			<div class="row ">
			<div class="col-xs-12">
			<form action="">
				<label for="post-body" class="post-label">Тело статьи</label>
				<textarea name="" id="post-body" cols="30" rows="10"></textarea>
			</form>
			</div>
			</div>

			<div class="row">
			<div class="col-xs-12">
				<div class="btn-after-post post-save">Сохранить</div>
				<div class="btn-after-post post-abort">Отмена</div>
			</div>
			</div>

		</div>

		<div class="col-md-3 col-xs-12 pl-0">
			<div class="col-md-9">
				<div class="settings cover">
					<div class="title ">Изображение</div>
					<div class="items">
						<div class="poster">
							<img src="/img/prod-default-cover.jpg" alt="" id="img">
						</div>
						<div class="controls">
							<input type="text" id="cover-img" value="/img/prod-default-cover.jpg">
							<a href="/js/responsive_filemanager/filemanager/dialog.php?type=2&field_id=cover-img&relative_url=1&akey=<?php echo $access_key ?>" class="upload iframe-btn">заменить</a>
							<div class="delete">удалить</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-9" style="display:none">
				<div class="settings tags">
					<div class="title ">Теги<div class='reset'></div></div>
					<div class="items">

					</div>
				</div>
			</div>
		</div>

	</div>

</div>
</div>

<script>
	articlesEditInit("<?php echo $access_key ?>");
</script>