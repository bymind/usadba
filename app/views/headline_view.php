<div class="container">
	<div class="row">
		<div class="headline mt-20 mb-20 mt-xs-10 mb-xs-10 clearfix">

			<div class="col-xxs-6 col-xas-4 col-xs-3 col-sm-3">
				<div class="logo" style="background-image: url(<?php echo (CONFIG_SITE_LOGO); ?>)">
				<a href="/"></a>
				</div>
				<div class="logo-sitename">
					<a href="/"><?php echo nl2br(CONFIG_SITE_NAME); ?></a>
				</div>
			</div>

			<div class="col-xxs-6 col-xas-3 col-xs-3 col-sm-2 col-lg-2 pl-0">
				<div class="head-phone">
					<span class="phone-label">
						<?php echo nl2br(CONFIG_SITE_WORKTIME); ?>
					</span>
					<span class="phone-number">
						<a href="tel:<?php echo (CONFIG_SITE_PHONE); ?>"><?php echo (CONFIG_SITE_PHONE); ?></a>
					</span>
				</div>
			</div>

			<div class="col-xxs-12 col-xas-5 col-xs-6 col-sm-7 col-lg-7 mt-xxs-10 pl-0">
				<button class="btn-mini col-xxs-4 cart" data-target="modal" data-targetindex="cart">
					<span class="counter">0</span>
					<span class="price-counter">
						<span class="num">0</span> руб
					</span>
				</button><button class="btn-wide col-xxs-4 login <?php if ($isLogged) {echo 'logged';} ?>" data-target="<?php if ($isLogged) {echo 'goProfile';}  else echo 'modal'; ?>" data-targetindex="<?php if ($isLogged) {echo $isLogged['id'];}  else echo 'profile'; ?>">
				<?php
					if ($isLogged) {
						echo $isLogged['name'];
					} else {
				?>
					Личный <br class="visible-xs">кабинет
				<?php
					}
				?>
				</button><button class="btn-wide col-xxs-4 call-me" data-target="modal" data-targetindex="callback">
					<span class="hidden-xs">Заказать<br></span>обратный <br class="visible-xs">звонок
				</button>
			</div>
		</div>
	</div>
</div>