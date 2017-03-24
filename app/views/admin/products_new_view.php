<script src="/js/tinymce/tinymce.min.js"></script>
<script src="/js/admin/main.js"></script>
<script src="/js/admin/goods.js"></script>

<div class="main-content">

<div class="container-fluid">


	<div class="row">
		<div class="col-md-9">
			<div class="row mt-0">
				<input type="hidden" id="post-id" value="">
				<label for="post-title" class="post-label">Название товара</label>
				<input type="text" id="post-title" placeholder="Например, очень вкусный пирожок" value="">
			</div>

			<div class="row mt-0">
				<label for="post-subtitle" class="post-label">Мини-описание</label>
				<input type="text" id="post-subtitle" placeholder="Например, производитель" value="">
			</div>

			<div class="row mt-0">
				<label for="post-url" class="post-label">Артикул <span class="really-edit">изменить</span></label>
				<input type="text" id="post-url" placeholder="art001" disabled  value="">
			</div>

	<!-- 		<div class="row ">
		<label for="post-anons" class="post-label">Анонс</label>
		<textarea name="" id="post-anons" cols="30" rows="2" placeholder="Интересный анонс"></textarea>
	</div> -->

			<div class="row ">
			<form action="">
				<label for="post-body" class="post-label">Описание</label>
				<textarea name="" id="post-body" cols="30" rows="10"></textarea>
			</form>
			</div>

			<div class="row">
				<div class="btn-after-post post-save">Сохранить</div>
				<div class="btn-after-post post-abort">Отмена</div>
			</div>

		</div>

		<div class="col-md-3 col-xs-12 pl-0">
			<div class="col-md-9">
				<div class="settings cover">
					<div class="title ">Главная картинка</div>
					<div class="items">
						<div class="poster">
							<img src="/img/macfix-articles-default-cover.jpg" alt="" id="img">
						</div>
						<div class="controls">
							<input type="text" id="cover-img" value="/img/macfix-articles-default-cover.jpg">
							<a href="/js/responsive_filemanager/filemanager/dialog.php?type=2&field_id=cover-img&relative_url=1&akey=<?php echo $access_key ?>" class="upload iframe-btn">заменить</a>
							<div class="delete">удалить</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-9">
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