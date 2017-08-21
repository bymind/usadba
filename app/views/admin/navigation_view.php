<!-- nav -->

<script src="/js/admin/navigation.js"></script>

<link rel="stylesheet" href="/css/admin/navigation.css">
<div class="nav">
	<div class="container-fluid">
		<div class="row mt-0 mb-0">
		<div class="col-md-8 col-xs-9 pl-0 pr-0">

			<ul class='col-xs-1 col-md-2 col-lg-2 pl-0 pr-0'>
				<li class="back-to-site"><a href="/" title="Вернуться на сайт" target="_blank"><span class="glyphicon glyphicon-globe"></span>Вернуться на сайт</a></li>
				<li class="open-menu"><button class="open-menu-btn"></button></li>
			</ul>

			<div class="nav-title col-xs-6 col-md-10 col-lg-10 pr-0 pl-0">
				<div class='main-title pl-0 ml-10 pr-0'><?php echo $actual_title; ?></div>
				<?php if (isset($second_title)) {
					?>
						<div class="second-title hidden-xs "><?php echo $second_title; ?></div>
					<?php
				} ?>
				<?php if (isset($btns)) {
					?>
					<div class="buttons-title hidden-xs">
						<?php
							foreach ($btns as $key => $value) {
								?>
									<div class="btn-title <?php echo $key; ?>"><?php echo $value; ?></div>
								<?php
							}
						?>
					</div>
					<?php
				} ?>
			</div>
		</div>
		<div class="col-md-4 col-xs-2 pl-0 pr-0">

			<div class="time-date hidden-xs hidden-sm col-md-7">
				<span class="time"></span>
				<span class="date hidden-xs hidden-sm">13 октября 2015</span>
			</div>
			<div class="col-xs-1 col-sm-4 col-md-5">
				<div class="usr-over<?php if (Controller_Admin::isSuper()){ echo ' bg-super'; } ?>" title="<?php echo $_SESSION['user']['name'];?>">
					<div class="user-btn btn-title">
						<span class="hidden-xs" style="white-space: nowrap;">
							<?php echo $_SESSION['user']['name'];?>
						</span>
					</div>
					<div class="list">
						<a class="list-item" href="/admin/users/<?php echo $_SESSION['user']['id']; ?>">Профиль</a>
						<a class="list-item logout" href="/user/logout">Выход</a>
					</div>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>
<div class="nav-overlay"></div>
<div class="navigation">
	<ul class='side-nav' attr-active="<?php echo $active_menu_item; ?>">
		<li class="home"><span class="alert danger orders-counter" title="Новые заказы"></span><a href="/admin" title="Рабочий стол"><span>Рабочий стол</span></a></li>
		<li class="goods"><a href="/admin/goods" title="Товары"><span>Товары</span></a></li>
		<li class="sales"><a href="/admin/sales" title="Акции"><span>Акции</span></a></li>
		<li class="articles"><a href="/admin/articles" title="Новости"><span>Новости</span></a></li>
		<li class="pages"><a href="/admin/pages"  title="Страницы сайта"><span>Страницы</span></a></li>
		<li class="files"><a href="/admin/files" title="Файлы"><span>Файлы</span></a></li>
		<li class="users"><a href="/admin/users/all" title="Аккаунты"><span>Аккаунты</span></a></li>
		<li class="config"><a href="/admin/config" title="Настройки"><span>Настройки</span></a></li>
		<li class="sound"><span data-soundstate="on" class="glyphicon glyphicon-volume-up <?php if ($_SESSION['user']['sound']=='0') {echo 'off';} ?>" aria-hidden="true"></span><span class="glyphicon glyphicon-volume-off  <?php if ($_SESSION['user']['sound']=='1') {echo 'off';} ?>" aria-hidden="true" data-soundstate="off"></span><a href="#" ><span>Звук <span class="sound-state">включён</span></span></a></li>
		<li class="bot bugtracker" style="display:none"><span class="alert danger tickets-counter"></span><a href="/admin/bugtracker" title="Баг-трекер"><span>Баг-трекер</span></a></li>
	</ul>
	<audio id="sound1" src="/sounds/fbm.mp3"></audio>
	<audio id="sound2" src="/sounds/fbm.mp3"></audio>
	<audio id="sound3" src="/sounds/fbm.mp3"></audio>
	<audio id="sound4" src="/sounds/fbm.mp3"></audio>
	<audio id="sound5" src="/sounds/fbm.mp3"></audio>
	<audio id="sound6" src="/sounds/fbm.mp3"></audio>
	<audio id="sound7" src="/sounds/fbm.mp3"></audio>
	<audio id="sound8" src="/sounds/fbm.mp3"></audio>
	<audio id="sound9" src="/sounds/fbm.mp3"></audio>
	<audio id="sound0" src="/sounds/fbm.mp3"></audio>
</div>

<div style="visibility: hidden;display: none;">
	<!-- @todo: собрать в спрайт -->
	<img src="/img/admin/dashboard-white.svg" alt="">
	<img src="/img/admin/goods-white.svg" alt="">
	<img src="/img/admin/articles-white.svg" alt="">
	<img src="/img/admin/sales-white.svg" alt="">
	<img src="/img/admin/pages-white.svg" alt="">
	<img src="/img/admin/files-white.svg" alt="">
	<img src="/img/admin/users-white.svg" alt="">
	<img src="/img/admin/config-white.svg" alt="">
	<img src="/img/admin/bugtracker-white.svg" alt="">
	<img src="/img/admin/new-post.svg" alt="">
</div>

<!-- nav -->
