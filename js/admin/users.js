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
	rowClick();
}

function rowClick()
{
	rowUser = $('.users-table tbody tr');
	var modalBox = $('.user-modal-md');
	rowUser.click(function(event) {
		$id = $(this).find('th').text();
		$login = $(this).find('td:eq(0)').text();
		$email = $(this).find('td:eq(1)').text();
		modalBox.on('show.bs.modal', function(event) {
			var modal = $(this);
			  modal.find('.modal-title').html('Аккаунт <strong>'+$login+'</strong>');
			  modal.find('.modal-body .go-delete').attr('data-id', $id);
		});
		modalBox.modal();
		$('.go-delete').click(function(event) {
			isSuper();
		});
	});
}

function isSuper()
{
	var $answ;
	$.ajax({
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
				  modal.find('.modal-title').html('<h1>Лососни тунца!</h1>');
				  modal.find('.modal-body').html('<h2>У Вас недостаточно полномочий.</h2>');
				  modal.find('.modal-footer').remove();
				  modal.find('.modal-content').append('<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button></div>');
			});
			confirm.modal();
		}
	})
	.fail(function() {
		console.log("error");
	});
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
			var dataArray = {
				login: $login,
				email: $email,
				passw: $passw
			}
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
					location.href="/admin/users";
				});
			});

		});
	});
}

