<div class="container mb-20">
	<div class="row">

	<div class="hidden-xs col-sm-3">

		<?php
			if (is_array($sidebar)) {
				foreach ($sidebar as $sidebar_item) {
					include $sidebar_item;
				}
			} else {
				include $sidebar;
			}
		?>

	</div>


	<div class="col-xs-12 col-sm-2 col-sm-push-7">
		<div class="profile-menu shadow br-2 mb-xs-10">
			<ul>
				<li><a class="active" href="/user/profile/<?php echo $pageDataView['id'] ?>">Личный кабинет</a></li>
				<li><a class="" href="/user/cart">Корзина</a></li>
				<li><a class="" href="/user/history/<?=$pageDataView['id']?>">Заказы</a></li>
				<li class=""><a class="" href="/user/logout">Выйти</a></li>
			</ul>
		</div>
		<?php
		if ($_SESSION['user']['is_admin']==1) {
			?>
			<div class="profile-menu shadow br-2 mt-10 mb-10 pb-10 pt-10">
			<ul>
				<li><a href="/admin">Админка</a></li>
			</ul>
			</div>
			<?php
		}
		?>
	</div>


	<div class="col-xs-12 col-sm-7 col-sm-pull-2">
		<div class="profile-box shadow br-2 mb-20 mb-xxs-10">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-3">
						<div class="profile-avatar br-4">
							<img src="<?php echo $pageDataView['avatar'] ?>" alt="<?php echo $pageDataView['name'] ?>">
						</div>
						<div class="avatar-edit">
							<a href="#" class="edit-photo">изменить фото</a>
						</div>
					</div>
					<div class="col-xs-9">
						<div class="details-wrapper">
							<div class="details-section clearfix  mb-20">
								<div class="details-section-title">
									Личные данные
									<span class='edit like_button' data-target="modal" data-targetindex="edit_profile">изменить</span>
								</div>
								<div class="details-section-data">

									<div class="params clearfix">
										<div class="param col-xxs-12 col-xs-4">
										<div class="row">
											<span>Имя</span>
											</div>
										</div>
										<div class="value col-xs-8 col-xxs-12">
											<span>
											<?php echo $pageDataView['name']; ?>
											</span>
										</div>
									</div>

									<div class="params clearfix">
										<div class="param col-xxs-12 col-xs-4">
										<div class="row">
											<span>E-mail</span>
											</div>
										</div>
										<div class="value col-xs-8 col-xxs-12">
											<span>
											<?php echo $pageDataView['email']; ?>
											</span>
										</div>
									</div>

									<div class="params clearfix">
										<div class="param col-xxs-12 col-xs-4">
										<div class="row">
											<span>Телефон</span>
											</div>
										</div>
										<div class="value col-xs-8 col-xxs-12">
											<span>
											<?php if ($pageDataView['phone']) { echo $pageDataView['phone'];} else { ?>
												<span class='edit like_button real' data-target="modal" data-targetindex="edit_profile">Добавить телефон</span>
												<?php } ?>
											</span>
										</div>
									</div>

									<div class="params clearfix">
										<div class="param col-xxs-12 col-xs-4">
										<div class="row">
											<span>День рождения</span>
											</div>
										</div>
										<div class="value col-xs-8 col-xxs-12">
											<span>
											<?php if (($pageDataView['bday']) && ($pageDataView['bday']>"0 0")) { echo $pageDataView['bday'];} else { ?>
												<span class='edit like_button real' data-target="modal" data-targetindex="edit_profile">Указать день рождения</span>
												<?php } ?>
											</span>
										</div>
									</div>

									<div class="col-xs-12">
										<div class="new-passw">
											<span class="edit like_button" data-target="modal" data-targetindex="passw_new" data-targetcallback="new_passw">Сменить пароль</span>
										</div>
									</div>

								</div>
							</div>

							<div class="details-section clearfix  mb-20">
								<div class="details-section-title">
									Адреса доставки
									<span class='edit like_button' data-target="modal" data-targetindex="edit_address">изменить</span>
								</div>
								<div class="details-section-data">
								<div class="col-xs-12">
								<div class="addresses">
								<?php if (isset($pageDataView['addresses'])) {
									$addrcount = 1;
									foreach ($pageDataView['addresses'] as $address) {
									?>
										<span class="addr"><span class="addr-count"><?php echo $addrcount?>.</span><?php echo $address ?></span>
									<?php
									$addrcount++;
									}
								} else { ?>
										<div class="new-addr">
											<span class="edit like_button" data-target="modal" data-targetindex="edit_address" >Добавить адрес</span>
										</div>
									<?php } ?>
								</div>
								</div>

								</div>
							</div>

						</div>
					</div>
				</div>



			</div>
		</div>
	</div>

	<div class="col-xs-12 visible-xs">

		<?php
			if (is_array($sidebar)) {
				foreach ($sidebar as $sidebar_item) {
					include $sidebar_item;
				}
			} else {
				include $sidebar;
			}
		?>

	</div>

	</div>
</div>