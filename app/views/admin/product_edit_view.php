<script src="/js/tinymce/tinymce.min.js"></script>
<script src="/js/admin/main.js"></script>
<script src="/js/admin/goods.js"></script>

<div class="main-content">

<div class="container-fluid">


	<div class="row">
		<div class="col-md-9">
			<div class="row mt-0">
			<div class="col-xs-12">
				<input type="hidden" id="post-id" value="<?php echo $post['id'] ?>">
				<div class="col-xs-8 pl-0">
					<label for="post-title" class="post-label">Название товара</label>
					<input type="text" id="post-title" placeholder="Например, очень вкусный пирожок" value="<?php echo $post['title'] ?>">
				</div>
				<div class="col-xs-12 mb-xs-10 pl-0 col-sm-4">
					<label for="prod-cat" class="post-label">Категория товара</label>
					<div class="col-xs-10 pl-0">
					<select class="form-control" id="prod-cat">
					<?php
																	foreach ($cat_tree['tree'] as $parent => $arr) {
																			if ($arr['id']==$post['cat']) {
																				echo "<option selected value='".$arr['id']."'>".$arr['name']."</a>";
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
					<div class="col-xs-2 pl-0">
						<button class="add_cat" data-page="prod_item" title="Новая категория">+</button>
					</div>
				</div>
			</div>
			</div>

			<div class="row mt-0">
			<div class="col-xs-12">
				<label for="post-subtitle" class="post-label">Мини-описание</label>
				<input type="text" id="post-subtitle" placeholder="Например, производитель" value="<?php echo $post['subtitle'] ?>">
			</div>
			</div>

			<div class="row mt-0">
			<div class="col-xs-12">
				<label for="post-url" class="post-label">Артикул <span class="really-edit">изменить</span></label>
				<input type="text" id="post-url" placeholder="art001" disabled  value="<?php echo $post['art'] ?>">
				<input type="hidden" id="post-tech_name" value="<?php echo $post['tech_name'] ?>">
			</div>
			</div>

			<div class="row ">
			<div class="col-xs-12">
			<form action="">
				<label for="post-body" class="post-label">Описание</label>
				<textarea name="" id="post-body" cols="30" rows="10"><?php echo $post['text'] ?></textarea>
			</form>
			</div>
			</div>

			<div class="row mt-0">
			<div class="col-xs-12">
				<label for="post-weight" class="post-label">Вес</label>
				<input type="text" class="prod-input" id="post-weight" placeholder="Например, 1 кг" value="<?php echo $post['weight'] ?>">
			</div>
			</div>

			<div class="row mt-0">
			<div class="col-xs-12">
				<label for="post-country" class="post-label">Страна</label>
				<input type="text" class="prod-input" id="post-country" placeholder="Например, Россия, Москва" value="<?php echo $post['country'] ?>">
			</div>
			</div>

			<div class="row mt-0">
			<div class="col-xs-12">
				<label for="post-stor_cond" class="post-label">Условия хранения</label>
				<input type="text" class="prod-input" id="post-stor_cond" placeholder="Например, 2 мес при -18С" value="<?php echo $post['stor_cond'] ?>">
			</div>
			</div>

			<div class="row mt-0">
			<div class="col-xs-12">
				<label for="post-nut_val" class="post-label">Пищевая ценность</label>
				<input type="text" class="prod-input" id="post-nut_val" placeholder="Белки, жиры и углеводы" value="<?php echo $post['nut_val'] ?>">
			</div>
			</div>

			<div class="row mt-0">
			<div class="col-xs-12">
				<label for="post-energy_val" class="post-label">Энергетическая ценность</label>
				<input type="text" class="prod-input" id="post-energy_val" placeholder="Например, 100 Ккал" value="<?php echo $post['energy_val'] ?>">
			</div>
			</div>

			<div class="row mt-0">
			<div class="col-xs-12">
				<label for="post-consist" class="post-label">Состав</label>
				<textarea name="" class="prod-input" id="post-consist" cols="30" rows="4"><?php echo $post['consist'] ?></textarea>
			</div>
			</div>

			<div class="row">
			<div class="col-xs-12">
				<div class="btn-after-post post-save edit">Сохранить</div>
				<div class="btn-after-post post-abort">Отмена</div>
			</div>
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
							<img src="<?php echo $post['poster'] ?>" alt="" id="img-cover-img">
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
					<div class="items tags-items">
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name='labels' id='new'
									<?php
										if (strpos($post['tags'], 'new')!==false) {
											echo 'checked';
										}
									 ?>
									><span class="label new" style="">New!</span>
								</label>
							</div>
						</div>
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="labels" id='popular' <?php
										if (strpos($post['tags'], 'popular')!==false) {
											echo 'checked';
										}
									 ?> ><span class="label popular" style="">Хит!</span>
								</label>
							</div>
						</div>
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="labels" id='sales' <?php
										if (strpos($post['tags'], 'sales')!==false) {
											echo 'checked';
										}
									 ?> ><span class="label sales" style="">Акция!</span>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="settings tags" style="margin-bottom:30px;">
					<div class="title ">Товар дня<div class='reset'></div></div>
					<div class="items tags-items">
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name='labels' id='pod'
									<?php
										if ($post['pod']=='1') {
											echo 'checked';
										}
									 ?>
									><span class="label" style="">Товар дня</span>
								</label>
							</div>
						</div>
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