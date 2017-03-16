<div class="modal fade modal-edit_profile" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content br-2">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
				<h4 class="modal-title only">Редактирование профиля</h4>
			</div>

			<div class="modal-body login-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xxs-10 col-xxs-offset-1 col-xs-8 col-xs-offset-2">
							<form id="profile-form-edit" action="">
								<div class="mb-20 form-group">
									<label for="profile-name">Имя</label>
									<input type="text" name="name" id="profile-name" class="form-control" placeholder="Имя" required value="<?php echo $pageDataView['name']; ?>">
								</div>
								<div class="mb-20 form-group">
									<label for="profile-email">E-mail</label>
									<input type="email" name="email" id="profile-email" class="form-control" placeholder="Введите email" required value="<?php echo $pageDataView['email']; ?>">
								</div>
								<div class="mb-20 form-group">
									<label for="profile-phone">Телефон</label>
									<input type="tel" id="profile-phone" class="form-control" name="phone" placeholder="+7 (___) ___-__-__" value="<?php if (isset($pageDataView['phone'])) { echo $pageDataView['phone'];}?>">
								</div>
								<div class="mb-20 form-group">
									<label for="profile-bd">День рождения</label>
									<input type="date" id="profile-bd" class="form-control" data-toggle="datepicker" name="bd" value="<?php if (isset($pageDataView['bday_raw'])) { echo $pageDataView['bday_raw'];} ?>">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer login-footer">
				<div class="col-xxs-12 col-xxs-offset-0 col-xs-8 col-xs-offset-2">
					<div class="col-xs-5">
						<button type="button" class="br-2 btn btn-default" data-dismiss="modal">Отмена</button>
					</div>
					<div class="col-xs-7">
						<button type="button" class="br-2 btn btn-primary" data-target="save_edit_profile">Сохранить</button>
					</div>
				</div>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->