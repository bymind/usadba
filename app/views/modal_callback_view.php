<div class="modal fade modal-callback" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content br-2">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
				<h4 class="modal-title only">Закажите обратный звонок</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-10 col-xs-offset-1">
							<form id="callback-form" action="">
								<div class="form-group mb-20">
								<?php
								if (isset($_SESSION['user']['id'])) {
									$userCallback = Model::getUserCallback($_SESSION['user']['id']);
								}
								 ?>
									<label for="callback-name">Как к Вам обращаться?</label>
									<input type="text" id="callback-name" name="name" class="form-control" placeholder="Представьтесь, пожалуйста" <?php
								if (isset($userCallback)) {
									echo "value='".$userCallback['name']."'";
								}
								 ?> >
								</div>
								<div class="form-group mb-20">
									<label for="callback-phone">Ваш номер телефона</label>
									<input type="tel" id="callback-phone" class="form-control" name="phone" placeholder="+7 (___) ___-__-__"  <?php
								if (isset($userCallback)) {
									echo "value='".$userCallback['phone']."'";
								}
								 ?> >
									<span class="substring">
										Мы перезвоним Вам на этот номер в течение 15 минут.
									</span>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="col-xxs-12 col-xxs-offset-0 col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2">
					<div class="col-xs-5">
						<button type="button" class="br-2 btn btn-default" data-dismiss="modal">Отмена</button>
					</div>
					<div class="col-xs-7">
						<button type="button" class="br-2 btn btn-primary" data-target="goCallback">Перезвоните мне</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>