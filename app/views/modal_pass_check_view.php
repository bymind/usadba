<div class="modal fade modal-passw_check" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content br-2">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
				<h4 class="modal-title only">Подтверждение действия</h4>
			</div>

			<div class="modal-body login-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xxs-10 col-xxs-offset-1 col-xs-8 col-xs-offset-2">
							<form id="passw-form-check" action="">
								<div class="mb-20 form-group">
									<label for="passw-input">Для подтверждения действия введите текущий пароль</label>
									<input type="password" name="pass" id="passw-input" class="form-control" placeholder="Введите пароль" required>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer login-footer">
				<div class="col-xxs-12 col-xxs-offset-0 col-xs-8 col-xs-offset-2">
					<div class="col-xs-5">
						<button type="button" class="br-2 btn btn-default" data-dismiss="modal" data-target="reopen-prev">Отмена</button>
					</div>
					<div class="col-xs-7">
						<button type="button" class="br-2 btn btn-primary" data-target="send-passw-check">Продолжить</button>
					</div>
				</div>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->