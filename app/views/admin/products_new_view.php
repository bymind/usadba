<script src="/js/tinymce/tinymce.min.js"></script>
<script src="/js/admin/main.js"></script>
<script src="/js/admin/goods.js"></script>

<div class="main-content">

<div class="container-fluid">


	<div class="row">
		<div class="col-md-9">
			<div class="row mt-0">
				<input type="hidden" id="post-id" value="">
				<div class="col-xs-12 col-sm-8 pl-0 mb-10">
					<label for="post-title" class="post-label">Название товара</label>
					<input type="text" id="post-title" placeholder="Например, очень вкусный пирожок" value="">
				</div>
				<div class="col-xs-12 mb-xs-10 pl-0 col-sm-4">
					<label for="prod-cat" class="post-label">Категория товара</label>
					<div class="col-xs-10 pl-0">
						<select class="form-control" id="prod-cat">
						<?php
																		foreach ($cat_tree['tree'] as $parent => $arr) {
																			$addAttr = "";
																				if (isset($actualCat)) {
																					if ($arr['id']==$actualCat) {
																						$addAttr = "selected";
																					}
																				}
																				echo "<option ".$addAttr." value='".$arr['id']."'>".$arr['name']."</option>";
																				$addAttr = "";
																			if (isset($arr['child'])) {
																				foreach ($arr['child'] as $child) {
																						if (isset($actualCat)) {
																							if ($child['id']==$actualCat) {
																								$addAttr = "selected";
																							}
																						}
																						echo "<option ".$addAttr." value='".$child['id']."'>—".$child['name']."</option>";
																						$addAttr = "";
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

			<div class="row mt-0">
				<label for="post-subtitle" class="post-label">Мини-описание</label>
				<input type="text" id="post-subtitle" placeholder="Например, производитель" value="">
			</div>

			<div class="row mt-0">
				<label for="post-url" class="post-label">Артикул <span class="really-edit">изменить</span></label>
				<input type="text" id="post-url" placeholder="art001" disabled  value="">
				<input type="hidden" id="post-tech_name" value="">
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

			<div class="row mt-0">
				<label for="post-weight" class="post-label">Вес</label>
				<input type="text"  class="prod-input" id="post-weight" placeholder="Например, 1 кг" value="">
			</div>

			<div class="row mt-0">
				<label for="post-country" class="post-label">Страна</label>
				<input type="text"  class="prod-input" id="post-country" placeholder="Например, Россия, Москва" value="">
			</div>

			<div class="row mt-0">
				<label for="post-stor_cond" class="post-label">Условия хранения</label>
				<input type="text"  class="prod-input" id="post-stor_cond" placeholder="Например, 2 мес при -18С" value="">
			</div>

			<div class="row mt-0">
				<label for="post-nut_val" class="post-label">Пищевая ценность</label>
				<input type="text"  class="prod-input" id="post-nut_val" placeholder="Белки, жиры и углеводы" value="">
			</div>

			<div class="row mt-0">
				<label for="post-energy_val" class="post-label">Энергетическая ценность</label>
				<input type="text"  class="prod-input" id="post-energy_val" placeholder="Например, 100 Ккал" value="">
			</div>

			<div class="row mt-0">
				<label for="post-consist" class="post-label">Состав</label>
				<textarea name=""  class="prod-input" id="post-consist" cols="30" rows="4"></textarea>
			</div>

			<div class="row">
				<div class="btn-after-post post-save new">Сохранить</div>
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
								<input type="text" id="price" placeholder="0" value="">
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
							<img src="/img/prod-default-cover.jpg" alt="" id="img-cover-img">
						</div>
						<div class="controls">
							<input type="text" id="cover-img" value="/img/prod-default-cover.jpg">
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
									<input type="checkbox" name='labels' id='new'><span class="label new" style="">New!</span>
								</label>
							</div>
						</div>
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="labels" id='popular'><span class="label popular" style="">Хит!</span>
								</label>
							</div>
						</div>
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="labels" id='sales'><span class="label sales" style="">Акция!</span>
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
									<input type="checkbox" name='labels' id='pod'><span class="label" style="">Товар дня</span>
								</label>
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