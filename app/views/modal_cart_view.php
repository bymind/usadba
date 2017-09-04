<div class="modal fade modal-cart" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content br-2">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
				<h4 class="modal-title only">Корзина</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
						<div class="row">
							<div class="cart-body">
								<div class="nothing">Тут пока ничего нет.</div>
								<div class="nothing">Посмотрите наш <a href="/catalog" rel='nofollow'>каталог</a>.</div>
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="col-xxs-12 col-xxs-offset-0 col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2">
					<div class="col-xxs-5 col-xs-6">
						<button type="button" class="br-2 btn btn-default" disabled="disabled" data-target="goLink" data-targetindex="/user/cart">Просмотр</button>
					</div>
					<div class="col-xxs-7 col-xs-6">
						<button type="button" class="br-2 btn btn-primary" disabled="disabled" data-target="goLink" data-targetindex="/user/cart/send">Оформить заказ</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> <!-- modal cart -->

<div class="modal fade modal-order-error" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content br-2">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
				<h4 class="modal-title only">Ошибка</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
						<div class="row">
							<div class="cart-body">
								<div class="nothing mt-20">Пожалуйста, заполните все поля</div>
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="col-xxs-12 col-xxs-offset-0 col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2">
					<div class="col-xs-10 col-xs-offset-1">
						<button type="button" class="br-2 btn btn-primary" data-dismiss="modal" >Продолжить</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade modal-order-ok" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content br-2">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" data-target="goLink" data-targetindex="/"><span aria-hidden="true"></span></button>
				<h4 class="modal-title only">Ваш заказ принят</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
						<div class="row">
							<div class="cart-body">
								<div class="nothing mt-20">Спасибо! Мы Вам перезвоним для уточнения деталей заказа.</div>
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="col-xxs-12 col-xxs-offset-0 col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2">
					<div class="col-xs-10 col-xs-offset-1">
						<button type="button" class="br-2 btn btn-primary" data-dismiss="modal" data-target="goLink" data-targetindex="/">Ок</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
