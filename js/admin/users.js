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
	var $adminId = $('.user-btn').data('userid');
	switch (type) {
		case "delete":
			console.log('delete');
			ifHasRight("all", $adminId, function(){
				confirm = $('.sure-modal-md');
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
				confirm = $('.sure-modal-md');
				confirm.on('show.bs.modal', function(event) {
					var modal = $(this);
					  modal.find('.modal-title').html('<h4>Отказ</h4>');
					  modal.find('.modal-body').html('<h4>У Вас недостаточно полномочий.</h4>');
					  modal.find('.modal-footer').remove();
					  modal.find('.modal-content').append('<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button></div>');
				});
				confirm.modal();
			});
			break;

		case "edit":
			console.log('edit');
			var rights="";
			var modal = $('.sure-edit-modal-md');
			ifHasRight("all", $adminId, function(){
				confirm = $('.sure-edit-modal-md');
				confirm.unbind('show.bs.modal').on('show.bs.modal', function(event) {
					modal = $(this);
					  modal.find('.modal-title').html('Изменение прав <strong>'+$login+'</strong>');
					  modal.find('.modal-footer .btn-primary').attr('data-id', $id);
					$.when(getUserRights($id)).done(function(res){
						if (res) {
							rights = $.parseJSON(res);
							$.each(rights, function(index, val) {
								if (val=="all") {
									// console.log('all detected');
									modal.find('input[type=checkbox]').prop('checked', true);
									return false;
								} else
								modal.find('input#'+val+'Right').prop('checked', 'true');
							});
						}
					});
				});
				confirm.on('hide.bs.modal', function(event) {
					var modal = $(this);
					modal.find('input[type=checkbox]').prop('checked', false);
				});
				confirm.modal();
				confirm.find('.btn-primary').click(function(event) {
					$id = $(this).data('id');
					var rightsArr =[];
					$.each(modal.find('input[type=checkbox]'), function(index, val) {
						if (modal.find('input[type=checkbox]').eq(index).prop('checked')) {
							if (modal.find('input[type=checkbox]').eq(index).attr('name')=="all") {
								rightsArr.push(modal.find('input[type=checkbox]').eq(index).attr('name'));
								return false;
							} else
							rightsArr.push(modal.find('input[type=checkbox]').eq(index).attr('name'));
						}
					});
					// console.log(rightsArr);
					var data = {
						uid: $id,
						rights: rightsArr
					};
					var dataJson = JSON.stringify(data);
					$.ajax({
						url: '/admin/users/editRights',
						type: 'POST',
						data: {data: dataJson},
					})
					.done(function(response) {
						console.log("success");
						confirm.modal('hide');
						modalBox.find('.modal-body').html(response);
						modalBox.find('.modal-footer .btn-primary').text('Ок');
						console.log(response);
						setTimeout(function(){
							location.reload();
						},500);
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
							// location.reload();
						});
					});
				});
			}, function(){
				console.log('denied');
				confirm = $('.sure-modal-md');
				confirm.on('show.bs.modal', function(event) {
					var modal = $(this);
					  modal.find('.modal-title').html('<h4>Отказ</h4>');
					  modal.find('.modal-body').html('<h4>У Вас недостаточно полномочий.</h4>');
					  modal.find('.modal-footer').remove();
					  modal.find('.modal-content').append('<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button></div>');
				});
				confirm.modal();
			});
			modal.find('input#superRight').on('change', function(event) {
				// event.preventDefault();
				if ($(this).prop('checked')) {
				modal.find('input').prop('checked', $(this).prop('checked'));
			}
			});
			modal.find('input#allRight').on('change', function(event) {
				// event.preventDefault();
				if ($(this).prop('checked')) {
					modal.find('input.second').prop('checked', $(this).prop('checked'));
				}
				if (!$(this).prop('checked')) {
					modal.find('input#superRight').prop('checked',false);
				}
			});
			modal.find('input.second').on('change', function(event) {
				// event.preventDefault();
				// if (!$(this).hasClass('allRight')) {
					var isAll = modal.find('input#allRight').prop('checked');
					$.each(modal.find('input.second'), function(index, val) {
						if (!modal.find('input.second').eq(index).prop('checked')) {
							isAll = false;
						} else {
							// isAll = true;
						}
					});
					modal.find('input#allRight').prop('checked', isAll);
					if (!isAll) {
					modal.find('input#superRight').prop('checked', isAll);
				}
				// }
			});
			break;
	}
}

function getUserRights(uid)
{
	return $.Deferred(function(def){
		var $uid = JSON.stringify(uid);
		$.ajax({
				url: '/admin/users/getRights',
				type: 'POST',
				data: {uid:$uid},
				success: function(res){
					def.resolve(res);
				},
				fail: function(err){
					def.reject(err);
				}
			});
	}).promise();
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

