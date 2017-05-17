<script src="/js/tinymce/tinymce.min.js"></script>
<script src="/js/admin/main.js"></script>
<!-- <script src="/js/admin/articles.js"></script> -->
<script src="/js/admin/sales.js"></script>

<div class="main-content">

<div class="container-fluid">


	<div class="row">
		<div class="col-md-9">
			<div class="row mt-0">
				<input type="hidden" id="post-id" value="<?php echo $post['id'] ?>">
				<label for="post-title" class="post-label">Заголовок</label>
				<input type="text" id="post-title" placeholder="Заголовок" value="<?php echo $post['title'] ?>">
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
				<div class="btn-after-post post-save edit">Сохранить</div>
				<div class="btn-after-post post-abort">Отмена</div>
			</div>

		</div>

		<div class="col-md-3 col-xs-12 pl-0">
			<div class="col-md-9">
				<div class="settings cover">
					<div class="title ">Изображение</div>
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
			<div class="col-md-9" style="">
				<div class="settings calendar" style="margin-bottom:30px;">
					<div class="title mb-10">Срок акции<div class='reset'></div></div>
					<div class="items">
						<div class="mb-20 form-group">
							<label for="start_time">Начало</label>
							<?php
							$start_time = explode(" ", $post['start_time']);
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
							$end_time = explode(" ", $post['end_time']);
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