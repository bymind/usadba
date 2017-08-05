<div class="modal fade modal-edit_photo" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content br-2">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
				<h4 class="modal-title only">Редактирование фотографии</h4>
			</div>

			<div class="modal-body login-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xxs-10 col-xxs-offset-1 col-xs-8 col-xs-offset-2">
							<form id="upload_form" enctype="multipart/form-data">
								<div style="text-align:center">
								<a href="#" class="btn btn-primary" id="btn-load-photo" onclick="$('#image_file').click();return false;" style="width:200px;margin:auto;	">Выбрать изображение</a>
								<input type="file" data-url="/user/uploadPhoto" style="display:none" name="image_file" id="image_file" onchange="fileSelectHandler()" /></div>

								<div class="error" style="text-align:center;"></div>
								<div id="ava-preview" style="background-image:url(<?php echo $pageDataView['avatar'] ?>)">

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
						<button type="button" class="br-2 btn btn-primary" data-target="save_edit_photo" data-targetindex="" data-targetcallback="load_photo">Сохранить</button>
					</div>
				</div>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->