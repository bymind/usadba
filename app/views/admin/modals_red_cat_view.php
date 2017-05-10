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
								$i = 0;
								$len = count($cat_tree['tree']) - 1;
								foreach ($cat_tree['tree'] as  $arr) {
									$text = $arr['name'];
									$link = '/admin/goods/cat/'.$arr['id'];
									if ($i>0 && $i<$len) {
										echo "<span class='catname' data-catid='".$arr['id']."' data-parent='".$arr['parent']."'data-cattechname='".$arr['tech_name']."' data-position='".$arr['position']."'><span>$text</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button><div class='move down' title='Переместить ниже'>&darr;</div><div class='move up' title='Переместить выше'>&uarr;</div></span>";
									} else
									if ($i == 0 && $len == 0) {
										echo "<span class='catname' data-catid='".$arr['id']."' data-parent='".$arr['parent']."'data-cattechname='".$arr['tech_name']."' data-position='".$arr['position']."'><span>$text</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button></span>";
									} else
									if ($i == 0) {
										echo "<span class='catname' data-catid='".$arr['id']."' data-parent='".$arr['parent']."'data-cattechname='".$arr['tech_name']."' data-position='".$arr['position']."'><span>$text</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button><div class='move down' title='Переместить ниже'>&darr;</div></span>";
									} else
									if ($i==$len) {
										echo "<span class='catname' data-catid='".$arr['id']."' data-parent='".$arr['parent']."'data-cattechname='".$arr['tech_name']."' data-position='".$arr['position']."'><span>$text</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button><div class='move up' title='Переместить выше'>&uarr;</div></span>";
									}
									$i++;
									if (isset($arr['child'])) {
									$ii = 0;
									$llen = count($arr['child']) - 1;
										foreach ($arr['child'] as $child) {
											$text = $child['name'];
											$link = '/admin/goods/cat/'.$child['id'];
											if ($ii>0 && $ii<$llen) {
												echo "<span class='catname subcat' data-catid='".$child['id']."' data-parent='".$child['parent']."'data-cattechname='".$child['tech_name']."' data-position='".$child['position']."'><span>$text</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button><div class='move down' title='Переместить ниже'>&darr;</div><div class='move up' title='Переместить выше'>&uarr;</div></span>";
											} else
											if ($ii == 0 && $llen == 0) {
												echo "<span class='catname subcat' data-catid='".$child['id']."' data-parent='".$child['parent']."'data-cattechname='".$child['tech_name']."' data-position='".$child['position']."'><span>$text</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button></span>";
											} else
											if ($ii == 0) {
												echo "<span class='catname subcat' data-catid='".$child['id']."' data-parent='".$child['parent']."'data-cattechname='".$child['tech_name']."' data-position='".$child['position']."'><span>$text</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button><div class='move down' title='Переместить ниже'>&darr;</div></span>";
											} else
											if ($ii==$llen) {
												echo "<span class='catname subcat' data-catid='".$child['id']."' data-parent='".$child['parent']."'data-cattechname='".$child['tech_name']."' data-position='".$child['position']."'><span>$text</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button><div class='move up' title='Переместить выше'>&uarr;</div></span>";
											}
											$ii++;
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
									<div class="form-group" style="display:none">
										<label for="tech_nameInput">Техническое имя (автозаполнение)</label>
										<input type="text" disabled name="tech_name" class="form-control" id="tech_nameInput" placeholder="заполняется автоматически">
									</div>
									<div class="form-group" style="display:none">
										<label for="tech_nameInput">ID категории</label>
										<input type="text" disabled name="id" class="form-control" id="red_idInput" placeholder="ID категории">
									</div>
									<div class="form-group" style="display:none">
										<label for="red_positionInput">Позиция категории</label>
										<input type="text" disabled name="position" class="form-control" id="red_positionInput" placeholder="Порядок показа категории">
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
									<div class="col-xs-12 col-sm-6 pl-0 pr-0">
										<div class="form-group">
										<label for="">Параметры отображения</label>
											<div class="sec_params_box" style="height:130px">
												<div class="form-group">
													<div class="checkbox">
														<label>
															<input type="checkbox" name='sec_params' id='red_show_big' value="show_big">Показывать на главной
														</label>
													</div>
												</div>
												<div class="form-group">
													<div class="checkbox">
														<label>
															<input type="checkbox" name="sec_params" id='red_show_popular' checked value="show_popular">Блок "Популярные"
														</label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xs-12 pl-0 pr-0 mt-20">
										<button type="button" class="btn btn-success btn-save-red"  style="float: right;">Сохранить изменения</button>
									</div>
								</div>
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-primary btn-submit">Сохранить</button>
			</div>
		</form>
	</div>
</div>
</div>


<div class="modal fade del-cat-modal" role="dialog" aria-labelledby="del-catsModal">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="del-catsModal">Удаление категории <span></span></h4>
			</div>
			<div class="modal-body">
				<div class="del-cat-info">
				<form class="del-cat">
					<div class="form-group">
						<div class="btn-group" data-toggle="buttons">
							<label class="btn btn-danger">
								<input type="radio" name="del_type" id="del_type1" autocomplete="off"> Удалить полностью<br>(вместе с товарами)
							</label>
							<label class="btn btn-primary active">
								<input type="radio" name="del_type" id="del_type2" autocomplete="off" checked> Удалить с переносом<br>товаров в другую категорию
							</label>
						</div>
					</div>
					<div class="form-group if-parent">
						<label for="">
							Действия производятся только над товарами именно в этой категории.<br>
							Товары во вложенных категориях не затрагиваются.<br>
							Все вложенные категории станут родительскими (основными).<br>
						</label>
					</div>
					<div class="form-group moveto-cat-box">
						<label for="moveto-cat" class="post-label">Куда переносить товары</label>
							<select class="form-control" id="moveto-cat">
							<?php
																			foreach ($cat_tree['tree'] as $parent => $arr) {
																				$addAttr = "";
																					echo "<option ".$addAttr." value='".$arr['id']."'>".$arr['name']."</option>";
																					$addAttr = "";
																				if (isset($arr['child'])) {
																					foreach ($arr['child'] as $child) {
																							echo "<option ".$addAttr." value='".$child['id']."'>—".$child['name']."</option>";
																							$addAttr = "";
																					}
																				}
																			}
																 ?>
							</select>
					</div>
				</form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-danger btn-delete-cat" data-catid="">Удалить категорию</button>
			</div>
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