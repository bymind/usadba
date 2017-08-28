<div class="modal fade new-user-modal-sm" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Создание нового аккаунта</h4>
			</div>
			<form class="form">
				<div class="modal-body">
				 <div class="form-group">
					<label for="loginInput">Логин</label>
					<input type="text" required name="login" class="form-control" id="loginInput" placeholder="Логин">
				</div>
				<div class="form-group">
					<label for="emailInput">Email</label>
					<input type="email" required name="email" class="form-control" id="emailInput" placeholder="Email">
				</div>
				<div class="form-group">
					<label for="passwInput">Пароль(не меньше 6 знаков)</label>
					<input type="password" required name="passw" class="form-control" id="passwInput" placeholder="passw">
				</div>
				<div class="form-group" style="text-align: center;margin-top: 30px;margin-bottom: 0;">
					<label for="isAdminInput" style="vertical-align: middle;margin-left: auto;margin-right: auto;position: relative;">
					<input type="checkbox" name="isadmin" class="form-control" id="isAdminInput" style="margin: 0 10px 0 0;width: 16px;box-shadow: none;display: inline-block;vertical-align: middle;height: 16px;">Администратор</label>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
				<button type="submit" class="btn btn-primary">Добавить аккаунт</button>
			</div>
		</form>
	</div>
</div>
</div>


<div class="modal fade user-modal-md" role="dialog" aria-labelledby="mySmallModalLabel2">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Аккаунт</h4>
			</div>
			<div class="modal-body">
				<button type="button" class="btn btn-warning go-edit btn-block btn-lg" data-id="">Изменить права</button>
				<button type="button" class="btn btn-danger go-delete btn-lg btn-block" data-id="">Удалить</button>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade sure-modal-md" role="dialog" aria-labelledby="mySmallModalLabel2">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Удалить аккаунт</h4>
			</div>
			<div class="modal-body">
				<button type="button" class="btn btn-primary btn-lg btn-block" data-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-danger btn-lg btn-block" data-id="">Удалить</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade sure-edit-modal-md" role="dialog" aria-labelledby="mySmallModalLabel2">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Изменение прав</h4>
			</div>
			<div class="modal-body">
				<form class="clearfix" id="editRights">
					<div class="row pl-15 pr-15 mt-0 mb-0">
					<?php
						/*if (Controller_Admin::isSuper()) {
							?>
							<div class="form-group col-xs-6">
							<label for="superRight" style="vertical-align: middle;margin-left: auto;margin-right: auto;position: relative;">
							<input type="checkbox" name="super" class="form-control superRight" id="superRight" style="margin: 0 10px 0 0;width: 16px;box-shadow: none;display: inline-block;vertical-align: middle;height: 16px;">Super User</label>
							</div>
							<?php
						}*/
					 ?>
					<div class="form-group col-xs-6">
						<label for="allRight" style="vertical-align: middle;margin-left: auto;margin-right: auto;position: relative;">
						<input type="checkbox" name="all" class="form-control allRight" id="allRight" style="margin: 0 10px 0 0;width: 16px;box-shadow: none;display: inline-block;vertical-align: middle;height: 16px;">Полные права</label>
					</div>
					</div>
					<hr class="mt-0">
					<div class="col-xs-6">
					<div class="form-group">
						<label for="ordersRight" style="vertical-align: middle;margin-left: auto;margin-right: auto;position: relative;">
						<input type="checkbox" name="orders" class="form-control second" id="ordersRight" style="margin: 0 10px 0 0;width: 16px;box-shadow: none;display: inline-block;vertical-align: middle;height: 16px;">Заказы</label>
					</div>
					</div>
					<div class="col-xs-6">
					<div class="form-group">
						<label for="goodsRight" style="vertical-align: middle;margin-left: auto;margin-right: auto;position: relative;">
						<input type="checkbox" name="goods" class="form-control second" id="goodsRight" style="margin: 0 10px 0 0;width: 16px;box-shadow: none;display: inline-block;vertical-align: middle;height: 16px;">Товары и акции</label>
					</div>
					</div>
					<div class="col-xs-6">
					<div class="form-group">
						<label for="newsRight" style="vertical-align: middle;margin-left: auto;margin-right: auto;position: relative;">
						<input type="checkbox" name="news" class="form-control second" id="newsRight" style="margin: 0 10px 0 0;width: 16px;box-shadow: none;display: inline-block;vertical-align: middle;height: 16px;">Новости</label>
					</div>
					</div>
					<div class="col-xs-6">
					<div class="form-group">
						<label for="pagesRight" style="vertical-align: middle;margin-left: auto;margin-right: auto;position: relative;">
						<input type="checkbox" name="pages" class="form-control second" id="pagesRight" style="margin: 0 10px 0 0;width: 16px;box-shadow: none;display: inline-block;vertical-align: middle;height: 16px;">Страницы</label>
					</div>
					</div>
					<div class="col-xs-12">
					<div class="form-group">
						<label for="usersRight" style="vertical-align: middle;margin-left: auto;margin-right: auto;position: relative;">
						<input type="checkbox" name="users" class="form-control second" id="usersRight" style="margin: 0 10px 0 0;width: 16px;box-shadow: none;display: inline-block;vertical-align: middle;height: 16px;">Аккаунты и комментарии</label>
					</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-primary" data-id="">Сохранить</button>
			</div>
		</div>
	</div>
</div>