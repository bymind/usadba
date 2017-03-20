<div class="modal fade modal-passw_new" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content br-2">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
				<h4 class="modal-title only">Смена пароля</h4>
			</div>

			<div class="modal-body login-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xxs-10 col-xxs-offset-1 col-xs-8 col-xs-offset-2">
							<form id="passw-form-new" action="">
								<div class="mb-20 form-group">
									<label for="passw-input">Введите текущий пароль</label>
									<input type="password" name="pass" id="passw-input" class="form-control" placeholder="Введите текущий пароль" required>
								</div>
								<div class="mb-20 form-group">
									<label for="passw-input-new">Введите новый пароль</label>
									<input type="password" name="pass-new" id="passw-input-new" class="form-control" placeholder="Введите новый пароль" required>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer login-footer">
				<div class="col-xxs-12 col-xxs-offset-0 col-xs-8 col-xs-offset-2">
					<div class="col-xs-5">
						<button type="button" class="br-2 btn btn-default dismiss-button" data-dismiss="modal" data-target="">Отмена</button>
					</div>
					<div class="col-xs-7">
						<button type="button" class="br-2 btn btn-primary" data-target="send-passw-new" data-targetindex="">Сохранить</button>
					</div>
				</div>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->