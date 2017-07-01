<script src="/js/tinymce/tinymce.min.js"></script>
<script src="/js/admin/main.js"></script>
<script src="/js/admin/pages.js"></script>

<div class="main-content">

<div class="container-fluid">


	<div class="row">
		<div class="col-md-9">
			<div class="row mt-0">
				<input type="hidden" id="post-id" value="<?php echo $post['id'] ?>">
				<label for="post-title" class="post-label">Заголовок</label>
				<input type="text" id="post-title" placeholder="Офигенный заголовок" value="<?php echo $post['title'] ?>">
			</div>

			<div class="row mt-0">
				<label for="post-subtitle" class="post-label">Подзаголовок</label>
				<input type="text" id="post-subtitle" placeholder="Офигенный подзаголовок" value="<?php echo $post['subtitle'] ?>">
			</div>

			<div class="row mt-0">
				<label for="post-url" class="post-label">URL <span class="really-edit">изменить</span></label>
				<input type="text" id="post-url" placeholder="url" disabled  value="<?php echo $post['url'] ?>">
			</div>

			<div class="row ">
			<form action="">
				<label for="post-body" class="post-label">Тело поста</label>
				<textarea name="" id="post-body" cols="30" rows="10"><?php echo $post['text'] ?></textarea>
			</form>
			</div>

			<div class="row">
				<div class="btn-after-post post-save edit">Сохранить</div>
				<div class="btn-after-post post-abort">Отмена</div>
			</div>

		</div>

		<div class="col-md-3 col-xs-12 pl-0">
			<div class="col-md-9">
				<div class="settings cover">
					<div class="title ">Обложка</div>
					<div class="items">
						<div class="poster">
						<?php
							if ($post['poster']) {
							?>
							<img src="<?php echo $post['poster'] ?>" alt="" id="img">
							<?php
							} else {
									?>
							<img src="/upload/prod-default-cover.jpg" alt="" id="img">
							<?php
							} ?>
						</div>
						<div class="controls">
							<input type="text" id="cover-img" value="<?php echo $post['poster'] ?>">
							<a href="/js/responsive_filemanager/filemanager/dialog.php?type=2&field_id=cover-img&relative_url=1&akey=<?php echo $access_key ?>" class="upload iframe-btn">заменить</a>
							<div class="delete">удалить</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-9" style="display:none">
				<div class="settings tags" style="margin-bottom:30px;">
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