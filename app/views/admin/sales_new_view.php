<script src="/js/tinymce/tinymce.min.js"></script>
<script src="/js/admin/main.js"></script>
<script src="/js/admin/sales.js"></script>

<div class="main-content">

<div class="container-fluid">


	<div class="row">
		<div class="col-md-9">
			<div class="row mt-0">
				<input type="hidden" id="post-id" value="<?php echo $post['id'] ?>">
				<label for="post-title" class="post-label">Заголовок</label>
				<input type="text" id="post-title" placeholder="Заголовок акции" value="<?php echo $post['title'] ?>">
			</div>

			<div class="row mt-0">
				<label for="post-url" class="post-label">URL <span class="really-edit">изменить</span></label>
				<input type="text" id="post-url" placeholder="url" disabled  value="<?php echo $post['url'] ?>">
			</div>

			<div class="row ">
			<form action="">
				<label for="post-body" class="post-label">Текстовое описание</label>
				<textarea name="" id="post-body" cols="30" rows="10"><?php echo $post['text'] ?></textarea>
			</form>
			</div>

			<div class="row">
				<label for="" class="post-label">Продукты по акции</label>
				<div class="col-xs-12">
				<div class="sales-select-items">
				<?php
					foreach ($prods['prodCats']['tree'] as $prod) {
						?>
						<div class="spoiler">
							<div class="spoiler-title" data-catid="<?php echo $prod['id']; ?>">
								<label for="cat-<?php echo $prod['id']; ?>"><input type="checkbox" class="cat-prod-input" id="cat-<?php echo $prod['id']; ?>" data-catid="<?php echo $prod['id']; ?>"> <span title="Выбрать категорию"><?php echo $prod['name']; ?></span></label>
							</div>
							<div class="spoiler-body">
							<div class="row m-0">
						<?php
						foreach ($prods['prodItems']['all'] as $item) {
							if ($item['cat']==$prod['id']) {
								?>
									<div class="col-xs-2 item-box" data-prodid="<?php echo $item['id']; ?>">
										<input type="checkbox" class="sales-prod-input" data-prodid="<?php echo $item['id']; ?>" value="<?php echo $item['id']; ?>">
										<div class="poster" data-poster="<?php echo $item['images']; ?>" style="background-image:url(<?php echo $item['images']; ?>)"></div>
										<div class="art"><?php echo $item['art']; ?></div>
										<div class="name"><?php echo $item['name']; ?></div>
									</div>
								<?php
							}
						}
						?>
						</div>
						<?php
						if (isset($prod['child'])) {
							?>
								<div class="row m-0">
							<?php
							foreach ($prod['child'] as $child) {
								?>
								<div class="spoiler">
									<div class="spoiler-title" data-catid="<?php echo $child['id']; ?>">
										<label for="cat-<?php echo $child['id']; ?>"><input type="checkbox" class="cat-prod-input" id="cat-<?php echo $child['id']; ?>" data-catid="<?php echo $child['id']; ?>"> <span title="Выбрать категорию"><?php echo $child['name']; ?></span></label>
									</div>
									<div class="spoiler-body">
									<div class="row m-0">
								<?php
								foreach ($prods['prodItems']['all'] as $item) {
									if ($item['cat']==$child['id']) {
										?>
											<div class="col-xs-2 item-box" data-prodid="<?php echo $item['id']; ?>">
												<input type="checkbox" class="sales-prod-input" data-prodid="<?php echo $item['id']; ?>" value="<?php echo $item['id']; ?>">
												<div class="poster" data-poster="<?php echo $item['images']; ?>" style="background-image:url(<?php echo $item['images']; ?>)"></div>
												<div class="art">Артикул: <?php echo $item['art']; ?></div>
												<div class="name"><?php echo $item['name']; ?></div>
											</div>
										<?php
									}
								}
								?>
									</div>
									</div>
								</div>
								<?php
							}
							?>
							</div>
							<?php
						}
						?>
							</div>
						</div>
						<?php
					}
				?>

				</div>
				</div>
			</div>

			<div class="row">
				<div class="btn-after-post post-save new">Сохранить</div>
				<div class="btn-after-post post-abort">Отмена</div>
			</div>

			<div class="row">
				<pre>
					<!-- <?php var_dump($prods); ?> -->
				</pre>
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
							<div class="info">изображение на белом фоне</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-9" style="">
				<div class="settings calendar" style="margin-bottom:30px;">
					<div class="title mb-10">Срок акции<div class='reset'></div></div>
					<div class="items">
						<div class="mb-20 form-group">
							<label for="start_time">Начало</label>
							<?php
							$start_time = explode(" ", date('Y-m-d h:i:s'));
							$start_time = explode("-", $start_time[0]);
							$start_day = $start_time[2];
							$start_month = $start_time[1];
							$start_year = $start_time[0];
							?>
							<div class="date-dropdowns start_time"><input type="hidden" id="start_time" class="form-control" data-toggle="datepicker" name="start_time" value="<?php echo $start_time[0].'-'.$start_time[1].'-'.$start_time[2]; ?>">
							<select class="day" name="date_[day]">
								<option value="">День</option>
								<?php
									for ($i = 1; $i<=31; $i++){
									$selected = "";
										if ($i<10) {
											$val = "0".$i;
										} else $val = $i;
										if ($val == $start_day) {
											$selected = "selected";
										}
										?>
											<option <?php echo $selected;?> value="<?php echo $val;?>"><?php echo $val;?></option>
										<?php
									}
								?>
							</select><select class="month" name="date_[month]">
								<option value="">Месяц</option>
								<?php
									$arrMonth = ['Января','Февраля','Марта','Апреля','Мая','Июня','Июля','Августа','Сентября','Октября','Ноября','Декабря'];
									for ($i = 1; $i<=12; $i++){
									$selected = "";
										if ($i<10) {
											$val = "0".$i;
										} else $val = $i;
										if ($val == $start_month) {
											$selected = "selected";
										}
										?>
											<option <?php echo $selected;?> value="<?php echo $val;?>"><?php echo $arrMonth[$i-1];?></option>
										<?php
									}
								?>
							</select><select class="year" name="date_[year]">
								<option value="">Год</option>
								<?php
									$nowYear = date('Y') - 5;
									for ($i = 0; $i<10; $i++){
										$selected ="";
										$val = $nowYear+$i;
										if ($val == $start_year) {
											$selected = "selected";
										}
										?>
											<option <?php echo $selected;?> value="<?php echo $val;?>"><?php echo $val;?></option>
										<?php
									}
								?>
							</select>
							</div>
						</div>
						<div class="mb-20 form-group">
							<label for="start_time">Конец</label>
							<?php
							$curdate = new DateTime(date('Y-m-d'));
							$curdate->modify('+1 month');
							$end_time = $curdate->format('Y-m-d');
							$end_time = $end_time." 23:59:59";
							$end_time = explode(" ", $end_time);
							$end_time = explode("-", $end_time[0]);
							$end_day = $end_time[2];
							$end_month = $end_time[1];
							$end_year = $end_time[0];
							 ?>
							<div class="date-dropdowns end_time"><input type="hidden" id="end_time" class="form-control" data-toggle="datepicker" name="start_time" value="<?php echo $end_time[0].'-'.$end_time[1].'-'.$end_time[2]; ?>">
							<select class="day" name="date_[day]">
								<option value="">День</option>
								<?php
									for ($i = 1; $i<=31; $i++){
									$selected = "";
										if ($i<10) {
											$val = "0".$i;
										} else $val = $i;
										if ($val == $end_day) {
											$selected = "selected";
										}
										?>
											<option <?php echo $selected;?> value="<?php echo $val;?>"><?php echo $val;?></option>
										<?php
									}
								?>
							</select><select class="month" name="date_[month]">
								<option value="">Месяц</option>
								<?php
									$arrMonth = ['Января','Февраля','Марта','Апреля','Мая','Июня','Июля','Августа','Сентября','Октября','Ноября','Декабря'];
									for ($i = 1; $i<=12; $i++){
									$selected = "";
										if ($i<10) {
											$val = "0".$i;
										} else $val = $i;
										if ($val == $end_month) {
											$selected = "selected";
										}
										?>
											<option <?php echo $selected;?> value="<?php echo $val;?>"><?php echo $arrMonth[$i-1];?></option>
										<?php
									}
								?>
							</select><select class="year" name="date_[year]">
								<option value="">Год</option>
								<?php
									$nowYear = date('Y') - 5;
									for ($i = 0; $i<10; $i++){
										$selected ="";
										$val = $nowYear+$i;
										if ($val == $end_year) {
											$selected = "selected";
										}
										?>
											<option <?php echo $selected;?> value="<?php echo $val;?>"><?php echo $val;?></option>
										<?php
									}
								?>
							</select>
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