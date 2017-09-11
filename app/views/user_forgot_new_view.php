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

	<div class="col-xs-12 col-sm-9">
		<div class="profile-box shadow br-2 mb-20 mb-xxs-10">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<div class="details-wrapper">
							<h2 style="font-weight:400; text-align:center;" class="mt-xs-10 mt-20 mb-50">Введите E-mail, на который был оформлен аккаунт</h2>
							<div class=" col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4 mb-50">
							<form id="confirmForm">
								<div class="form-group">
									<label for="inputEmail">Введите e-mail</label>
									<input type="email" id="inputEmail" name="email" class="form-control" required="required" placeholder="E-mail Вашего аккаунта">
								</div>
								<div class="form-group">
								<div class="mt-20">
								<button type="button" class="btn btn-primary br-2" id="btnConfirmEmail" data-target="setEmail">Отправить</button>
								</div>
								</div>
							</form>
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