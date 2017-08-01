<script src="/js/admin/main.js"></script>
<script src="/js/admin/config.js"></script>

<div class="main-content">

<div class="container-fluid">

	<div class="row ">
	<div class="col-xs-12 col-md-8 pt-0">

	<div class="panel panel-default">
	<div class="panel-heading">
		<h4>1. Основные настройки сайта <small>заполните все</small></h4>
	</div>
	<div class="panel-body">
	<form class="form-horizontal adminka-config">
		<div class="form-group">
			<label for="inputSiteName" class="col-sm-5 control-label">Название сайта</label>
			<div class="col-sm-7">
				<textarea cols="30" rows="2" class="form-control" id="inputSiteName" placeholder="Хороший Сайт" style="resize:vertical;"><?php echo $configs['inputSiteName']['value'] ?></textarea>
			</div>
		</div>
		<div class="form-group poster-group">
			<label for="" class="col-sm-5 control-label">Логотип</label>
			<div class="col-sm-7">
				<div class="item-logo">
					<div class="poster">
					<?php
						if ($configs['cover-img']['value']) {
						?>
						<img id="img-cover-img" src="<?php echo $configs['cover-img']['value'] ?>" alt="" id="img">
						<?php
						} else {
								?>
						<img src="/upload/prod-default-cover.jpg" alt="" id="img-cover-img">
						<?php
						} ?>
					</div>
					<div class="controls">
						<?php
							if ($configs['cover-img']['value']) {
							?>
						<input type="text" id="cover-img" value="<?php echo $configs['cover-img']['value'] ?>">
							<?php
							} else {
									?>
						<input type="text" id="cover-img" value="/upload/prod-default-cover.jpg">
							<?php
							} ?>
						<a href="/js/responsive_filemanager/filemanager/dialog.php?type=2&field_id=cover-img&relative_url=1&akey=<?php echo $access_key ?>" data-akey="<?php echo $access_key ?>" class="upload iframe-btn">заменить</a>
						<div class="delete">удалить</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="inputSitePhone" class="col-sm-5 control-label">Номер телефона</label>
			<div class="col-sm-7">
				<input type="tel" class="form-control" id="inputSitePhone" placeholder="8(___)___-__-__" value="<?php echo $configs['inputSitePhone']['value'] ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="inputWorkTime" class="col-sm-5 control-label">Рабочее время</label>
			<div class="col-sm-7">
				<input type="text" class="form-control" id="inputWorkTime" placeholder="пн-пт 8:00-18:00" value="<?php echo $configs['inputWorkTime']['value'] ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="inputAddress" class="col-sm-5 control-label">Адрес</label>
			<div class="col-sm-7">
				<textarea cols="30" rows="3" style="resize:vertical;" class="form-control" id="inputAddress" placeholder='125009, г. Москва, Вознесенский переулок, д. 20, стр. 2, ОАО "УСАДЬБА-ЦЕНТР"'><?php echo $configs['inputAddress']['value'] ?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label for="inputCopyright" class="col-sm-5 control-label">Подпись в нижней части страницы</label>
			<div class="col-sm-7">
				<textarea cols="30" rows="3" style="resize:vertical;" class="form-control" id="inputCopyright" placeholder='Усадьба-Кулинария.ру - доставка полуфабрикатов и кулинарии.' aria-describedby="helpCopyright"><?php echo $configs['inputCopyright']['value'] ?></textarea>
				<span id="helpCopyright" class="help-block">Будет отображаться в самом низу, после знака ©</span>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<button class="btn btn-primary pull-right btn-save" data-configsect="primary" type="button">Сохранить</button>
			</div>
		</div>
		<div class="panel-heading panel-heading-middle">
		<h4>2. Контакты админки <small>адреса для модераторов, поля можно оставлять пустыми, или записывать несколько адресов через запятую</small></h4>
		</div>

		<div class="form-group">
	    <label for="inputEmailAdmin" class="col-sm-5 control-label">Email главного администратора</label>
	    <div class="col-sm-7">
	      <input type="email" class="form-control" id="inputEmailAdmin" placeholder="admin@gmail.com" aria-describedby="helpEmailAdmin" value="<?php echo $configs['inputEmailAdmin']['value'] ?>">
	      <span id="helpEmailAdmin" class="help-block">Сюда будут приходить заказы, комментарии, отзывы и зявки на перезвон</span>
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="inputEmailOrders" class="col-sm-5 control-label">Email для заказов</label>
	    <div class="col-sm-7">
	      <input type="email" class="form-control" id="inputEmailOrders" placeholder="orders@gmail.com" aria-describedby="helpEmailOrders" value="<?php echo $configs['inputEmailOrders']['value'] ?>">
	      <span id="helpEmailOrders" class="help-block">Сюда будут приходить только заказы</span>
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="inputEmailComm" class="col-sm-5 control-label">Email для комментариев</label>
	    <div class="col-sm-7">
	      <input type="email" class="form-control" id="inputEmailComm" placeholder="comments@gmail.com" aria-describedby="helpEmailComm" value="<?php echo $configs['inputEmailComm']['value'] ?>">
	      <span id="helpEmailComm" class="help-block">Сюда будут приходить только комментарии</span>
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="inputEmailRev" class="col-sm-5 control-label">Email для отзывов</label>
	    <div class="col-sm-7">
	      <input type="email" class="form-control" id="inputEmailRev" placeholder="reviews@gmail.com" aria-describedby="helpEmailRev" value="<?php echo $configs['inputEmailRev']['value'] ?>">
	      <span id="helpEmailRev" class="help-block">Сюда будут приходить только отзывы</span>
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="inputEmailRecall" class="col-sm-5 control-label">Email для зявок на перезвон</label>
	    <div class="col-sm-7">
	      <input type="email" class="form-control" id="inputEmailRecall" placeholder="recalliews@gmail.com" aria-describedby="helpEmailRecall" value="<?php echo $configs['inputEmailRecall']['value'] ?>">
	      <span id="helpEmailRecall" class="help-block">Сюда будут приходить только заявки на перезвон</span>
	    </div>
	  </div>
		<div class="form-group">
			<div class="col-sm-12">
				<button class="btn btn-primary pull-right btn-save" data-configsect="contacts" type="button">Сохранить</button>
			</div>
		</div>
		<!-- <div class="form-group">
			<label for="inputPassword3" class="col-sm-2 control-label">Password</label>
			<div class="col-sm-10">
				<input type="password" class="form-control" id="inputPassword3" placeholder="Password">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="checkbox">
					<label>
						<input type="checkbox"> Remember me
					</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default">Sign in</button>
			</div>
		</div> -->
	</form>
	</div>
	</div>


	</div>
</div>

</div>
</div>