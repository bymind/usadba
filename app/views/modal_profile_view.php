<div class="modal fade modal-profile" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content br-2">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="display:none;"><span aria-hidden="true"></span></button>
				<h4 class="modal-title left-part col-xs-6" data-target="login">Вход<span class="hidden-xs"> в личный кабинет</span></h4>
				<h4 class="modal-title disable right-part col-xs-6" data-target="reg">Регистрация</h4>
			</div>

			<div class="modal-body login-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xxs-10 col-xxs-offset-1 col-xs-8 col-xs-offset-2">
							<form id="profile-form-login" action="">
								<div class="mb-20 form-group">
									<label for="login-email">Адрес электронной почты</label>
									<input type="email" name="email" id="login-email" class="form-control" placeholder="Введите email" required>
								</div>
								<div class="form-group">
									<label for="login-passw">Пароль</label>
									<input type="password" name="password" id="login-passw" placeholder="Введите пароль" class=" form-control" required minlength="6">
								</div>
								<div class="forgot"><a href="/user/forgot">Я не помню пароль</a></div>
								<div class="error"></div>
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
						<button type="button" class="br-2 btn btn-primary" data-target="login">Войти</button>
					</div>
				</div>
			</div>

			<div class="modal-body reg-body inactive">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xxs-10 col-xxs-offset-1 col-xs-8 col-xs-offset-2">
							<form id="profile-form-reg" action="">
								<div class="form-group mb-20">
									<label for="reg-name">Ваши имя и фамилия</label>
									<input type="text" name="name" id="reg-name" class="form-control" placeholder="Представьтесь, пожалуйста" required>
								</div>
								<div class="form-group">
									<label for="reg-email">Адрес электронной почты</label>
									<input type="email" name="email" id="reg-email" placeholder="Введите email" class=" form-control" required>
									<span class="substring">
										Мы отправим Вам письмо с персональной ссылкой на страницу задания пароля.
									</span>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer reg-footer inactive pb-20">
				<div class="col-xxs-12 col-xxs-offset-0 col-xs-8 col-xs-offset-2">
					<div class="col-xs-4">
						<button type="button" class="br-2 btn btn-default" data-dismiss="modal">Отмена</button>
					</div>
					<div class="col-xs-8">
						<button type="button" class="br-2 btn btn-primary" data-target="registration">Зарегистрироваться</button>
					</div>
				</div>
				<span class="substring mt-20" style="
									  font-size: 11px;
									  text-align: center;
									  display: inline-block;
									  line-height: 10px;
									  padding: 2px 5px;
									  width: 100%;
									">Регистрируясь на сайте, Вы принимаете условия <a href="/terms" target="_blank">Пользовательского соглашения</a>
									</span>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal-reg-ok" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content br-2">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
				<h4 class="modal-title only">Письмо отправлено</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
						<div class="row">
							<div class="cart-body">
								<div class="nothing mt-20 pl-20 pr-20" style="max-width:400px; margin: auto;">На указанный Вами адрес отправлено письмо со ссылкой для задания пароля Вашего аккаунта.<br>
								Проверьте почтовый ящик.</div>
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="col-xxs-12 col-xxs-offset-0 col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2">
				<form>
					<div class="col-xs-10 col-xs-offset-1">
						<button type="button" class="br-2 btn btn-primary" data-dismiss="modal" >Хорошо</button>
					</div>
					<div class="col-xs-12">
						<span class="substring" style="text-align:center">
							Если письмо не приходит — попробуйте поискать его в папке "Спам".
						</span>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>
</div>