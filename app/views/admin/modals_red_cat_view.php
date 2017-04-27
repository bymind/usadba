<div class="modal fade red-cat-modal" role="dialog" aria-labelledby="newCatModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="newCatModal">Редактирование категорий</h4>
			</div>
			<form class="form">
				<div class="modal-body clearfix">
					<div class="row mb-0 mt-0">
						<div class="col-xs-6">
							<div class="items cat_links pb-20">
								<?php
								foreach ($cat_tree['tree'] as $parent => $arr) {
									$text = $arr['name'];
									$link = '/admin/goods/cat/'.$arr['id'];
									/*if ($arr['id']==$goods[0]['cat_id']) {
										echo "<a class='active' href='$link'>$text</a>";
									} else*/
									echo "<span class='catname' data-catid='".$arr['id']."' data-cattechname='".$arr['tech_name']."'>$text</span>";
									if (isset($arr['child'])) {
										foreach ($arr['child'] as $child) {
											$text = $child['name'];
											$link = '/admin/goods/cat/'.$child['id'];
											/*if ($child['id']==$goods[0]['cat_id']) {
												echo "<a class='active' href='$link'>—$text</a>";
											} else*/
											echo "<span class='catname subcat' data-catid='".$child['id']."' data-cattechname='".$child['tech_name']."'>$text</span>";
										}
									}
								}
								?>
							</div>
						</div>
						<div class="col-xs-6 pl-0">
							<div class="red_cat_over">
								<div class="red_cat_title mb-10">
									<i>&larr; выберите категорию</i>
								</div>
								<div class="red_cat_body clearfix">
									<div class="form-group">
										<label for="nameInput">Название категории</label>
										<input type="text" required name="name" class="form-control" id="red_nameInput" placeholder="Название">
									</div>
									<div class="form-group">
										<label for="parentInput">Родительская категория</label>
										<select class="form-control" id="red_parentInput" name="parent">
											<option value='0' >+ Без родителя (новая главная категория)</option>
											<?php
												foreach ($cat_tree['tree'] as $parent => $arr) {
														echo "<option value='".$arr['id']."'>".$arr['name']."</option>";
												}
											?>
											</select>
									</div>
									<div class="form-group col-xs-12 col-sm-6 pl-0 cat_poster">
										<label for="cat_posterInput">Картинка категории</label>
											<input type="text" id="red_cat_posterInput" name="poster" value="/img/prod-default-cover.jpg" style="display:none">
										<div class="poster">
											<img src="/img/prod-default-cover.jpg" alt="" id="img-red_cat_posterInput" >
										</div>
										<div class="controls">
											<a href="javascript:open_popup('/js/responsive_filemanager/filemanager/dialog.php?popup=1&type=2&field_id=cat_posterInput&relative_url=1&akey=<?php echo $access_key ?>')" data-akey="<?php echo $access_key ?>" class="upload cat-iframe-btn" style="display:none	">заменить</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<div class="form-group col-xs-12 col-sm-6 pl-0">
					<label for="tech_nameInput">Техническое имя</label>
					<input type="text" disabled name="tech_name" class="form-control" id="tech_nameInput" placeholder="заполняется автоматически">
				</div>
				<div class="col-xs-12 col-sm-6 pl-0">
					<div class="form-group col-xs-12 pl-0">
					<label for="">Параметры отображения категории</label>
						<div class="sec_params_box">
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name='sec_params' id='show_big' value="show_big">Показывать на главной
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="sec_params" id='show_popular' checked value="show_popular">Блок "Популярные"
									</label>
								</div>
							</div><!--
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="sec_params" id='show_new'>Блок "Новинки"
									</label>
								</div>
							</div> -->
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-primary btn-submit">Добавить категорию</button>
			</div>
		</form>
	</div>
</div>
</div>


<div class="modal fade cats-modal-md" role="dialog" aria-labelledby="catsModal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="catsModal">Категории</h4>
			</div>
			<div class="modal-body">
				<button type="button" class="btn btn-warning btn-block btn-lg" disabled="disabled">Редактировать</button>
				<button type="button" class="btn btn-danger go-delete  btn-lg btn-block" data-id="">Удалить</button>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade shure-modal-md" role="dialog" aria-labelledby="shureCatsModal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="shureCatsModal">Удалить аккаунт</h4>
			</div>
			<div class="modal-body">
				<button type="button" class="btn btn-primary btn-lg btn-block" data-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-danger btn-lg btn-block" data-id="">Удалить</button>
			</div>
		</div>
	</div>
</div>