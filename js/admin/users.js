$(function() {

	usersScriptsInit();

});

function usersScriptsInit()
{
	btnsInit();
}

function btnsInit()
{
	newUserBtn();
	banUserBtn();
	rowClick();
}

function banUserBtn()
{
	$(document).on('click',"td.banned-col span", function(event) {
		event.stopPropagation();
		var status = $(this).parents('td').data('status');
		var user = {
			id: $(this).parents('tr').find('th').text(),
			banned: !status+.0
		}
		var thisTd = $(this).parent();
		var notBannedHtml = "нет<span>забанить</span>";
		var bannedHtml = "да<span>разбанить</span>";
		var toggledHtml = "";
		var newData = 0;
		var newClass = "";
		if (thisTd.data('status')=='1') {
			toggledHtml = notBannedHtml;
			newData = 0;
			newClass = "is-banned-0";
		} else if (thisTd.data('status')=='0'){
			toggledHtml = bannedHtml;
			newData = 1;
			newClass = "is-banned-1";
		}
		var jsonUser =  JSON.stringify(user);
		$.when(setBan(jsonUser)).done(function(res){
			if (res=="true") {
				thisTd.html(toggledHtml);
				thisTd.data('status', newData);
				thisTd.removeClass('is-banned-0').removeClass('is-banned-1').addClass(newClass);
			}
		});

	});
}

function setBan(userdata)
{
	return $.Deferred(function(def){
		$.ajax({
				url: '/admin/users/editUser',
				type: 'POST',
				data: {userData:userdata},
				success: function(res){
					def.resolve(res);
				},
				fail: function(err){
					def.reject(err);
				}
			});
	}).promise();
}

function rowClick()
{
	rowUser = $('.users-table tbody tr.admin-users td.data');
	var modalBox = $('.user-modal-md');
	rowUser.click(function(event) {
		event.stopPropagation();
		$id = $(this).parent().find('th').text();
		$login = $(this).parent().find('td:eq(0)').text();
		$email = $(this).parent().find('td:eq(1)').text();
		modalBox.on('show.bs.modal', function(event) {
			var modal = $(this);
			  modal.find('.modal-title').html('Аккаунт <strong>'+$login+'</strong>');
			  modal.find('.modal-body .go-delete').attr('data-id', $id);
			  modal.find('.modal-body .go-edit').attr('data-id', $id);
		});
		modalBox.modal();
		$('.go-delete').unbind('click').on('click',function(event) {
			isSuper('delete');
		});
		$('.go-edit').unbind('click').on('click',function(event) {
			isSuper('edit');
		});
	});

	$('.users-table tbody tr.all-users td.data').click(function(event) {
		var $userId = $(this).parent().find('th').text();
		location.href = "/admin/users/"+$userId;
	});
}

function isSuper(type)
{
	var $answ;
	if (!type) {
		type = 'edit';
	}
	switch (type) {
		case "delete":
			console.log('delete');
			isSnotS(function(){
				confirm = $('.shure-modal-md');
				confirm.on('show.bs.modal', function(event) {
					var modal = $(this);
					  modal.find('.modal-title').html('Удалить аккаунт <strong>'+$login+'</strong>?');
					  modal.find('.modal-body .btn-danger').attr('data-id', $id);
				});
				confirm.modal();
				confirm.find('.btn-danger').click(function(event) {
					$id = $(this).data('id');
					$.ajax({
						url: '/admin/users/delete',
						type: 'POST',
						data: {id: $id},
					})
					.done(function(response) {
						console.log("success");
						confirm.modal('hide');
						modalBox.find('.modal-body').html(response);
						modalBox.find('.modal-footer .btn-primary').text('Ок');
						console.log(response);
						location.reload();
					})
					.fail(function(response) {
						console.log("error");
						confirm.modal('hide');
						modalBox.find('.modal-body').html(response);
						modalBox.find('.modal-footer .btn-primary').text('Ок');
						console.log(response);
					})
					.always(function() {
						console.log("complete");
						modalBox.on('hidden.bs.modal', function(event) {
							location.reload();
						});
					});
				});
			}, function(){
				console.log('denied');
				confirm = $('.shure-modal-md');
				confirm.on('show.bs.modal', function(event) {
					var modal = $(this);
					  modal.find('.modal-title').html('<h4>Отказ</h4>');
					  modal.find('.modal-body').html('<h4>У Вас недостаточно полномочий.</h4>');
					  modal.find('.modal-footer').remove();
					  modal.find('.modal-content').append('<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button></div>');
				});
				confirm.modal();
			});
			/*$.ajax({
				url: '/admin/users/issuper',
				type: 'POST'
			})
			.done(function(answ) {
				console.log(answ);
				$answ = answ;
				if ($answ=='1') {
					confirm = $('.shure-modal-md');
					confirm.on('show.bs.modal', function(event) {
						var modal = $(this);
						  modal.find('.modal-title').html('Удалить аккаунт <strong>'+$login+'</strong>?');
						  modal.find('.modal-body .btn-danger').attr('data-id', $id);
					});
					confirm.modal();
					confirm.find('.btn-danger').click(function(event) {
						$id = $(this).data('id');
						$.ajax({
							url: '/admin/users/delete',
							type: 'POST',
							data: {id: $id},
						})
						.done(function(response) {
							console.log("success");
							confirm.modal('hide');
							modalBox.find('.modal-body').html(response);
							modalBox.find('.modal-footer .btn-primary').text('Ок');
							console.log(response);
							location.reload();
						})
						.fail(function(response) {
							console.log("error");
							confirm.modal('hide');
							modalBox.find('.modal-body').html(response);
							modalBox.find('.modal-footer .btn-primary').text('Ок');
							console.log(response);
						})
						.always(function() {
							console.log("complete");
							modalBox.on('hidden.bs.modal', function(event) {
								location.reload();
							});
						});
					});
				}
				if ($answ == '0') {
					console.log('denied');
					confirm = $('.shure-modal-md');
					confirm.on('show.bs.modal', function(event) {
						var modal = $(this);
						  modal.find('.modal-title').html('<h1>Отказ</h1>');
						  modal.find('.modal-body').html('<h2>У Вас недостаточно полномочий.</h2>');
						  modal.find('.modal-footer').remove();
						  modal.find('.modal-content').append('<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button></div>');
					});
					confirm.modal();
				}
			})
			.fail(function() {
				console.log("error");
			});*/
			break;

		case "edit":
			console.log('edit');
			break;
	}
}

function newUserBtn()
{
	btnNewUser = $('.btn-new-user');
	modalBox = $('.new-user-modal-sm');
	btnNewUser.click(function(event) {
		modalBox.modal();
		modalBox.find('.form').submit(function(event) {
			event.preventDefault();
			event.stopPropagation();
			var $login = modalBox.find('#loginInput').val();
			var $email = modalBox.find('#emailInput').val();
			var $passw = modalBox.find('#passwInput').val();
			var $isadmin = modalBox.find('#isAdminInput').prop('checked');
			var dataArray = {
				login: $login,
				email: $email,
				passw: $passw,
				isadmin: $isadmin
			}
			console.info(dataArray);
			dataPost = JSON.stringify(dataArray);
			$.ajax({
				url: '/admin/users/addnew',
				type: 'POST',
				data: {newUserData: dataPost},
			})
			.done(function(response) {
				console.log("success");
				console.log(dataPost);
				modalBox.find('.modal-body').html(response);
				modalBox.find('.modal-footer .btn-primary').css('display','none');
				modalBox.find('.modal-footer .btn-default').addClass('btn-primary').removeClass('btn-default').text('Ок');
			})
			.fail(function(response) {
				console.log("error");
				modalBox.find('.modal-body').html(response);
			})
			.always(function() {
				console.log("complete");
				modalBox.on('hidden.bs.modal', function(event) {
					// location.href="/admin/users";
					location.reload();
				});
			});

		});
	});
}

