<script src="/js/tinymce/tinymce.min.js"></script>
<script src="/js/admin/main.js"></script>
<script src="/js/admin/goods.js"></script>

<div class="main-content">

<div class="container-fluid">


	<div class="row">
		<div class="col-md-9">
			<div class="row mt-0">
				<input type="hidden" id="post-id" value="<?php echo $post['id'] ?>">
				<div class="col-xs-8 pl-0">
					<label for="post-title" class="post-label">Название товара</label>
					<input type="text" id="post-title" placeholder="Например, очень вкусный пирожок" value="<?php echo $post['title'] ?>">
				</div>
				<div class="col-xs-4">
					<label for="prod-cat" class="post-label">Категория товара</label>
					<select class="form-control" id="prod-cat">
					<?php
																	foreach ($cat_tree['tree'] as $parent => $arr) {
																			if ($arr['id']==$post['cat']) {
																				echo "<option selected alue='".$arr['id']."'>".$arr['name']."</a>";
																			} else
																			echo "<option value='".$arr['id']."'>".$arr['name']."</a>";
																		if (isset($arr['child'])) {
																			foreach ($arr['child'] as $child) {
																				if ($child['id']==$post['cat']) {
																					echo "<option selected value='".$child['id']."'>—".$child['name']."</a>";
																				} else
																					echo "<option value='".$child['id']."'>—".$child['name']."</a>";
																			}
																		}
																	}
														 ?>
					</select>
				</div>
			</div>

			<div class="row mt-0">
				<label for="post-subtitle" class="post-label">Мини-описание</label>
				<input type="text" id="post-subtitle" placeholder="Например, производитель" value="<?php echo $post['subtitle'] ?>">
			</div>

			<div class="row mt-0">
				<label for="post-url" class="post-label">Артикул <span class="really-edit">изменить</span></label>
				<input type="text" id="post-url" placeholder="art001" disabled  value="<?php echo $post['art'] ?>">
				<input type="hidden" id="post-tech_name" value="<?php echo $post['tech_name'] ?>">
			</div>
<!--
			<div class="row ">
				<label for="post-anons" class="post-label">Анонс</label>
				<textarea name="" id="post-anons" cols="30" rows="2" placeholder="Интересный анонс"><?php echo $post['anons'] ?></textarea>
			</div> -->

			<div class="row ">
			<form action="">
				<label for="post-body" class="post-label">Описание</label>
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
					<div class="settings price">
						<div class="title ">Цена</div>
						<div class="items">
							<div class="form-group mt-10">
								<div class="input-group">
									<input type="text" id="price" placeholder="0" value="<?php echo $post['price'] ?>">
									 <span class="input-group-addon">руб</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			<div class="col-md-9">
				<div class="settings cover">
					<div class="title ">Главная картинка</div>
					<div class="items">
						<div class="poster">
							<img src="<?php echo $post['poster'] ?>" alt="" id="img">
						</div>
						<div class="controls">
							<input type="text" id="cover-img" value="<?php echo $post['poster'] ?>">
							<a href="/js/responsive_filemanager/filemanager/dialog.php?type=2&field_id=cover-img&relative_url=1&akey=<?php echo $access_key ?>" class="upload iframe-btn">заменить</a>
							<div class="delete">удалить</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-9">
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