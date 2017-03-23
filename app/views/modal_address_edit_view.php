<div class="modal fade modal-edit_address" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content br-2">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
				<h4 class="modal-title only">Адреса</h4>
			</div>

			<div class="modal-body login-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xxs-12 col-xxs-offset-0 col-xs-10 col-xs-offset-1">
									<form id="address-form-edit" class="mt-20">
									<table class="table table-striped table-bordered table-hover" id='addresses'>
							<?php
							if (isset($pageDataView['addresses']) && count($pageDataView['addresses']) > 0) {
								foreach ($pageDataView['addresses'] as $addr) {
									?>
									<tr class="add-obj">
										<td>
											<label for=""><?php echo $addr; ?></label>
										</td>
										<td>
											<button class="br-2 btn btn-default btn-delete" data-target="delete-addr" type="button">Удалить</button>
										</td>
									</tr>
									<?php
								}
							} else {

							}

							?>
									</table>
											<form id="new-addr">
											<div class="col-xxs-8 col-xs-9">
											<input id="address" type="text" placeholder="Введите адрес"></input>
											<link href="https://cdn.jsdelivr.net/jquery.suggestions/17.2/css/suggestions.css" type="text/css" rel="stylesheet" />
											<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
											<!--[if lt IE 10]>
											<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxtransport-xdomainrequest/1.0.1/jquery.xdomainrequest.min.js"></script>
											<![endif]-->
											<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.suggestions/17.2/js/jquery.suggestions.min.js"></script>
											<script type="text/javascript">
											    $("#address").suggestions({
											        token: "f11ed4cbf3102dd5f4560d48c433ba0a36903275",
											        type: "ADDRESS",
											        count: 5,
											        /* Вызывается, когда пользователь выбирает одну из подсказок */
											        onSelect: function(suggestion) {
											            console.log(suggestion);
											        }
											    });
											</script>
											</div>
											<div class="col-xxs-4 col-xs-3">
											<button class="br-2 btn btn-primary btn-add" data-target="add-addr" type="button">Добавить</button>
											</div>
											</form>
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
						<button type="button" class="br-2 btn btn-primary" data-target="save_edit_addr" data-targetindex="" data-targetcallback="reopen-prev">Сохранить</button>
					</div>
				</div>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->