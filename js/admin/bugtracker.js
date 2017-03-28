$(function() {
		bugTrackerScriptsInit();
});



/* Инициализация скриптов для Баг-трекера */

function bugTrackerScriptsInit() {
		// инициализация dotdotdot
		//ticketsDotDotDotInit(); перемещено в инициализацию md-разметки

		// инициализация markdown-разметки
		ticketsMDInit();

		// инициализация клика по заголовку тикета
		panelHeadingClickInit();

		// инициализация кнопок Bootstrap
		btnsInit();

		// оформление ссылок разделов
		topLinksInit();

		// клик по кнопке "Создать тикет"
		newTicketClick();

		// клик по кнопке "Послать" в форме отправки
		newTicketSend();

		// клик по кнопке "Отменить" в форме отправки
		newTicketAbort();

		// удаление тикета (перемещение в архив)
		deleteTicket();

		// восстановление тикета (из архива)
		restoreTicket();

		// Клик на кнопке "редактировать" на странице тикета
		ticketEditClickInit();


		// Назначение исполнителя
		doerClickInit();

		// обработка cookie
		initCookie();

		// обработка хэшей и клики на ссылки-якоря
		//hashInit();

}



/*******/





/*
initCookie()
инициализируем cookie
*/
function initCookie()
{
	var personalTicketsBtns = $('.ticket-personal-tag');
	personalTicketAll = personalTicketsBtns.find('a.all');
	personalTicketPersonal = personalTicketsBtns.find('a.personal');
	personalMy = $('.ticket-personal-my-tag');
	personalMyAll = personalMy.find('.my-all');
	personalMyAuthor = personalMy.find('.my-author');
	personalMyDoer = personalMy.find('.my-doer');
	if (typeof(getCookie('personalTickets'))==undefined) {
		setCookie('personalTickets','0',{
			expires: 2592000,
			path: '/'
		});
		setSession('user','0','personalTickets');
		personalMy.removeClass('active');
	} else {
		if (getCookie('personalTickets') == '1') {
			personalTicketAll.removeClass('active');
			personalTicketPersonal.addClass('active');
			personalMy.addClass('active');
			if (getCookie('personalTicketsMy') == '1') {
				personalMyAll.removeClass('active');
				personalMyAuthor.addClass('active');
				personalMyDoer.removeClass('active');
			} else if (getCookie('personalTicketsMy') == '2') {
				personalMyAll.removeClass('active');
				personalMyAuthor.removeClass('active');
				personalMyDoer.addClass('active');
			} else {
				personalMyAll.addClass('active');
				personalMyAuthor.removeClass('active');
				personalMyDoer.removeClass('active');
			}
		} else {
			personalTicketAll.addClass('active');
			personalTicketPersonal.removeClass('active');
			personalMy.removeClass('active');
		}
	}
	$(document).on('click', '.ticket-personal-tag a.btn', function(event) {
		event.preventDefault();
		if ($(this).attr('disabled') ) return false;
		allBtns = $('.personal-tickets .btn');
		filterIcon = $('.filter-icon');
		filterIconSpan = filterIcon.find('span');
		allBtns.attr('disabled', 'disabled');
		filterIconSpan.removeClass('glyphicon-filter').addClass('glyphicon-repeat');
		filterIcon.addClass('rotation');
		cardsList = $('.cards__list');
		$(this).parent().find('a').removeClass('active');
		$(this).addClass('active');
		if ($(this).hasClass('all')) {
			personalMy.removeClass('active');
			personalMyAll.addClass('active');
			personalMyAuthor.removeClass('active');
			personalMyDoer.removeClass('active');
			setCookie('personalTickets','0',{
				expires: 2592000,
				path: '/'
			});
			$.when(setSession('user','0','personalTickets')).done(function(){
				$.when(loadTickets('0','0')).done(function(answer){
					reloadCards(cardsList, answer, ticketsMDInit);
				});
			});
		} else
		if ($(this).hasClass('personal')) {
			personalMy.addClass('active');
			personalMyAll.addClass('active');
			personalTicketAll.removeClass('active');
			setCookie('personalTickets','1',{
				expires: 2592000,
				path: '/'
			});
			$.when(setSession('user','1','personalTickets')).done(function(){
				$.when(loadTickets('1','0')).done(function(answer){
					reloadCards(cardsList, answer, ticketsMDInit);
				});
			});
		}
	});
	$(document).on('click', '.ticket-personal-my-tag a.btn', function(event) {
		event.preventDefault();
		if ($(this).attr('disabled') ) return false;
		allBtns = $('.personal-tickets .btn');
		filterIcon = $('.filter-icon');
		filterIconSpan = filterIcon.find('span');
		allBtns.attr('disabled', 'disabled');
		filterIconSpan.removeClass('glyphicon-filter').addClass('glyphicon-repeat');
		filterIcon.addClass('rotation');
		cardsList = $('.cards__list');
		$(this).parent().find('a').removeClass('active');
		$(this).addClass('active');
		if ($(this).hasClass('my-all')) {
			setCookie('personalTicketsMy','0',{
				expires: 2592000,
				path: '/'
			});
			$.when(setSession('user','0','personalTicketsMy')).done(function(){
				$.when(loadTickets('1','0')).done(function(answer){
					reloadCards(cardsList, answer, ticketsMDInit);
				});

				// loadTickets('1','0');
			});
		} else
		if ($(this).hasClass('my-author')) {
			setCookie('personalTicketsMy','1',{
				expires: 2592000,
				path: '/'
			});
			$.when(setSession('user','1','personalTicketsMy')).done(function(){
				$.when(loadTickets('1','1')).done(function(answer){
					reloadCards(cardsList, answer, ticketsMDInit);
				});
			});
		} else
		if ($(this).hasClass('my-doer')) {
			setCookie('personalTicketsMy','2',{
				expires: 2592000,
				path: '/'
			});
			$.when(setSession('user','2','personalTicketsMy')).done(function(){
				$.when(loadTickets('1','2')).done(function(answer){
					reloadCards(cardsList, answer, ticketsMDInit);
				});
			});
		}
	});
}

loadTickets = function(personal='0', personalMy='0') {
	var def = new $.Deferred();
	var $path = location.pathname.split("/");
	$path = $path[$path.length - 1];
	$path = ($path == 'bugtracker') ? 'all' : $path;
	$.ajax({
		url: '/admin/ajaxTickets',
		type: 'POST',
		data: {action: 'ajaxTickets', personal: personal, personalMy: personalMy, path: $path},
	})
	.done(function(answer) {
		// console.log(answer);
		def.resolve(answer);
	})
	.fail(function(answer) {
		console.error(answer);
		def.reject(answer);
	})
	.always(function() {
		console.log("loadTickets() complete");
	});
		return def.promise();
}


function reloadCards(putHere, putThis, callback)
{
	allBtns = $('.personal-tickets .btn');
	filterIcon = $('.filter-icon');
	filterIconSpan = filterIcon.find('span');
	allBtns.attr('disabled', 'disabled');
	filterIconSpan.removeClass('glyphicon-filter').addClass('glyphicon-repeat');
	filterIcon.addClass('rotation');
	putHere.animate({
		opacity: 0
		},
		300, function() {
		$(this).html(putThis);
		callback();
		$(this).animate({opacity:1}, 250, function(){
			allBtns.removeAttr('disabled');
			filterIconSpan.removeClass('glyphicon-repeat').addClass('glyphicon-filter');
			filterIcon.removeClass('rotation');
		});
	});
}

/*
ticketsMDInit()
Разметка md на странице со списком тикетов
*/
function ticketsMDInit()
{
	$('.card__desc').addClass('markdown');
	$('.card__description:not(.deleted)').addClass('markdown');
	mdSourse = $('.markdown');

	for (var i = 0; i < mdSourse.length; i++) {
		var el = mdSourse.eq(i);
		el.html(marked(el.text()));
		// log(i);
	}

	ticketsDotDotDotInit();
}



/*
reloadModals(p)
Перезагрузга модальных окон
p [string] - адрес шаблона модальных окон
*/
function reloadModals(p)
{
	var $modal = $('.modal');
	$('.modals-box').load(p, function(){
			console.log('reloaded modals');
	});
}



/*
restoreTicket()
Подготовка к восстановлению тикета
*/
function restoreTicket()
{
	var $btn = $('.card__btn__restore');
	var $modal = $('.modal');
	var $abort = "<button class='btn btn-block btn-lg btn-primary' data-dismiss='modal'>Отменить</button>";
	var $delete = "<button class='btn btn-block btn-lg btn-success btn-modal-restore'>Восстановить</button>";
	$btn.click(function(event) {
				$modal.find('.modal-title').text('Восстановить тикет?');
				$modal.find('.modal-dialog').addClass('modal-sm');
				$modal.find('.modal-footer').remove();
				$modal.find('.modal-body').html($abort+$delete);
				$modal.modal();
				var $btnRestore = $modal.find('.btn-modal-restore');
				$btnRestore.click(function(event) {
					var $ticketId = location.pathname.split("/");
					$ticketId = $ticketId[$ticketId.length - 1];
					restoreTicketModel($ticketId, $modal);
				});

	});
	$modal.on('hidden.bs.modal', function(event) {
		reloadModals();
	});
}



/*
restoreTicketModel(id, m)
Восстановление тикета
id [int|string] - id тикета
m [DOM Object] - элемент модального окна
*/
function restoreTicketModel(id, m)
{
	$.ajax({
		url: '/admin/users/issuper',
		type: 'POST'
	})
	.done(function(answ) {
		$answ = answ;
		if ($answ=='1') {
			var $action = 'restoreTicket';
			$.ajax({
				url: '/admin/bugtracker/restoreTicket',
				type: 'POST',
				data: {id: id, action: $action},
				})
				.done(function(answer){
					console.log(answer);
					console.log('ticket #'+id+" restored!");
					m.find('.modal-title').text('Восстановлено');
					m.find('.btn-modal-delete').hide();
					m.find('.modal-dialog .modal-content').append('<div class="modal-footer"><button class="btn btn-primary" data-dismiss="modal">Ок</button></div>');
					m.find('.btn-primary').text('Ок');
					m.find('.modal-body').html('<h3>Тикет #'+id+' восстановлен.</h3>');
					location.href = location.href;
					setTimeout(function(){
						m.modal('hide');
					},3000);
				})
				.fail(function(answer){
					console.log("error");
					console.log(answer);
				});
		} else
		if ($answ=='0') {
			m.find('.modal-title').text('Lososni tunca');
			m.find('.btn-modal-delete').hide();
			m.find('.modal-dialog .modal-content').append('<div class="modal-footer"><button class="btn btn-primary" data-dismiss="modal">Ooook =(</button></div>');
			m.find('.btn-primary').text('Ooook =(');
			m.find('.modal-body').html('<h4>Sorry, you shouldn\'t do this.</h4>');
		};
	});

}



/*
deleteTicket()
Подготовка к удалению тикета
*/
function deleteTicket()
{
	var $btn = $('.card__btn__delete');
	var $modal = $('.modal');
	var $abort = "<button class='btn btn-block btn-lg btn-primary' data-dismiss='modal'>Отменить</button>";
	var $delete = "<button class='btn btn-block btn-lg btn-danger btn-modal-delete'>Удалить</button>";
	$btn.click(function(event) {
		$modal.find('.modal-title').text('Удалить тикет?');
		$modal.find('.modal-dialog').addClass('modal-sm');
		$modal.find('.modal-footer').remove();
		$modal.find('.modal-body').html($abort+$delete);
		$modal.modal();
		var $btnDelete = $modal.find('.btn-modal-delete');
		$btnDelete.click(function(event) {

			$modal.find('.modal-dialog').removeClass('modal-sm');
			$modal.find('.modal-title').text('Причина удаления');
			$modal.find('.modal-body').html('<div class="form-group mb-0"><textarea name="deleteAnsw" id="ticketDelete" placeholder="Почему тикет удален" class="form-control" rows="3" style="resize:vertical;"></textarea></div>');
			$modal.find('textarea').focus();
			$modal.find('.modal-dialog .modal-content').append('<div class="modal-footer"><button class="btn btn-primary" data-dismiss="modal">Отменить</button><button class="btn btn-danger btn-modal-delete">Удалить</button></div>');
			var $btnFinallyDelete = $modal.find('.modal-footer .btn-modal-delete');
			$btnFinallyDelete.click(function(event) {
				if (validateDel($modal.find('textarea'))) {
					var $ticketId = location.pathname.split("/");
					$ticketId = $ticketId[$ticketId.length - 1];
					removeTicket($ticketId, $modal.find('textarea').val(), $modal);
				} else {
					$modal.find('textarea').parents('.form-group').addClass('has-error');
					setTimeout(function(){
						$modal.find('textarea').parents('.form-group').removeClass('has-error');
					},3000);
				}
			});
		});
	});
	$modal.on('hidden.bs.modal', function(event) {
		reloadModals();
	});
}



/*
validateDel(selector)
Валидация заполнения "Причины удаления"
selector [jsObject] - элемент поля ввода
*/
function validateDel(a)
{
	var $a = a;
	if ($a.val()=="") {
		return false;
	} else
		return true;
}



/*
removeTicket(id, reason, m)
Удаление тикета из списка (архивирование)
id [int|string] - id тикета
reason [string] - причина удаления
m [DOM Object] - элемент модального окна
*/
function removeTicket(id, reason, m)
{
	var $action = 'deleteTicket';
	$.ajax({
		url: '/admin/bugtracker/deleteTicket',
		type: 'POST',
		data: {id: id, reason: reason, action: $action},
		})
		.done(function(answer){
			console.log(answer);
			console.log('ticket #'+id+" deleted!");
			m.find('.modal-title').text('Удалено');
			m.find('.btn-modal-delete').hide();
			m.find('.btn-primary').text('Ок');
			m.find('.modal-body').html('<h3>Тикет #'+id+' удален.</h3>');
			location.href = location.href;
			setTimeout(function(){
				m.modal('hide');
			},3000);
		})
		.fail(function(answer){
			console.log("error");
			console.log(answer);
		});
}



/*
panelHeadingClickInit()
Обработка клика по заголовку тикета
*/
function panelHeadingClickInit()
{
	var $h = $('.cards__list');
	$('.cards__list').on("click", ".card .panel-heading", function(event) {
		var $url = $(this).attr('data-url');
		location.href = 'http://'+location.hostname+$url;
	});
}



/*
btnsInit()
Инициализация кнопок Bootstrap
*/
function btnsInit()
{
	$('.btn').button();
}


/*
newTicketClick()
Клик по кнопке "Создать тикет"
*/
function newTicketClick()
{
	var $btn = $('.btn-title.new-ticket');
	var $overlay = $('.new-ticket-form-over');
	$btn.click(function(event) {
		$overlay.toggleClass('on');
	});
}


/*
validateTicket(t)
Валидация отправления тикета
t [jsObject] - информация о тикете
*/
function validateTicket(t)
{
	var errors = [];
	if (t.title == "") {
		t.title = "noname";
	}
	if (t.body == "") {
		errors.push('body');
	}
	if (t.tag == "") {
		errors.push('tag');
	}

	if (errors.length > 0) {
		showErrors(errors);
		return false;
	} else {
		return t;
	}

}



/*
showErrors(e)
Отображение ошибок в форме ввода
e [string array] - массив с именами полей с ошибками
*/
function showErrors(e)
{
	var $form = $('form#newTicketForm');
	for (var i = e.length - 1; i >= 0; i--) {
		$form.find('[name='+e[i]+']').parents('.form-group').addClass('has-error');
	}
	setTimeout(function(){
		$('.has-error').removeClass('has-error');
	}, 5000);
}



/*
newTicketSend()
Обработка клика по кнопке "Послать" в форме отправки тикета
*/
function newTicketSend()
{
	var $btn = $('.new-ticket-send');
	var $bigBtn = $('.btn.new-ticket');
	$btn.click(function(event) {
		var $action = "addNewTicket";
		var $form = $('#newTicketForm');
		var $title = $form.find('[name = title]').val();
		var $body = $form.find('[name = body]').val();
		var $tag = $form.find('.ticket-tag label.active input').attr('id');

		var ticket = {
			tag: $tag,
			title: $title,
			body: $body
		};

		if (!validateTicket(ticket)) {
			return false;
		} else {
			ticket = validateTicket(ticket);
		}

		var jsonTicket = JSON.stringify(ticket);

		$bigBtn.button('loading');

		$pBar = '<div class="progress progress-striped active"><div class="progress-bar" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" style="width: 5%"><span class="sr-only">45% Complete</span></div></div>';

		$pBarOver = '<div class="p-bar-over"><div class="p-bar-content"><h4>Отправляем</h4>';

		$pBarOver += $pBar+'</div></div>';

		$form.find('.panel').hide();

		$form.addClass('sending');

		$form.append($pBarOver);
		$('body,html').animate({'scrollTop':0},300);

		console.warn(jsonTicket);

		/*callOne = $.ajax({
		url: '/admin/bugtracker/createTicket',
		type: 'POST',
		data: {jsonTicket: jsonTicket, action: $action},
		})
		.done(function(answer){
			$form.find('.progress-bar').css('width', '20%');
			setTimeout(function(){
				$bigBtn.button('success');
				$form.find('.progress-bar').css('width', '70%');
				setTimeout(function(){
					$form.find('.p-bar-content h4').text('Готово!');
					$form.find('.progress-bar').css('width', '100%');
					setTimeout(function(){
						softHide($form.find('.p-bar-over'), 1000);
						setTimeout( function(){
							$('.btn.new-ticket').click();
							$form.find('.panel').css({'opacity':'1'}).show();
							$form.trigger('reset');
							// showNewTicket();
						},300);
					},100);
					$form.removeClass('sending');
					$bigBtn.button('reset');
				},100);
			},200);
		})
		.fail(function(answer){
			console.log("error");
			console.log(answer);
		})
		.always(function(answer){
			console.info(answer);
		});

		$.when( callOne ).then(function(){
			showNewTicket();
		});*/

		callOne = $.ajax({
		url: '/admin/bugtracker/createTicket',
		type: 'POST',
		data: {jsonTicket: jsonTicket, action: $action},
		})
		.done(function(answer){
			$form.find('.progress-bar').css('width', '20%');
			setTimeout(function(){
				$bigBtn.button('success');
				$form.find('.progress-bar').css('width', '70%');
				setTimeout(function(){
					$form.find('.p-bar-content h4').text('Готово!');
					$form.find('.progress-bar').css('width', '100%');
					setTimeout(function(){
						softHide($form.find('.p-bar-over'), 1000);
						setTimeout( function(){
							$('.btn.new-ticket').click();
							$form.find('.panel').css({'opacity':'1'}).show();
							$form.trigger('reset');
							// showNewTicket();
						},300);
					},100);
					$form.removeClass('sending');
					$bigBtn.button('reset');
				},100);
			},200);
		})
		.fail(function(answer){
			console.log("error");
			console.log(answer);
		})
		.always(function(answer){
			console.info(answer);
		});

		$.when( callOne ).then(function(){
			showNewTicket();
		});


	});
}



/*
showNewTicket()
Добавляем в ленту новый тикет
*/
function showNewTicket()
{
	var call = $.ajax({
		url: '/admin/bugtracker/getLastTicket',
		type: 'GET',
	})
	.done(function(res) {
				setTimeout(function(){
					addToList(res);
				},600);
	})
	.fail(function() {
		console.log("error");
	});

	call;
}



/*
addToList(obj)
Prepend to tickets list
*/
function addToList(obj=null)
{
	$('.cards__list').prepend(obj);
	$('.cards__list .cards__new:eq(0)').addClass('move');
	obj = "";
	// $('.card__list .card.move').removeClass('move');

	setTimeout(function(){
		var desc = $('.card__desc:eq(0)');
		desc.addClass('markdown').html(marked(desc.text()));
		ticketsDotDotDotInit(desc);
		$('.cards__list').find('.move').addClass('stop').parents('.cards__list').find('.card__poink').delay(200).addClass('on animated flipInX');
		$('.cards__new').remove();
		$('.cards__list').prepend('<div class="cards__new"></div>');
	},200);
}



/*
formatMarkdownHeaders()
Добавление ссылок на заголовки в разметке тикета
*/
function formatMarkdownHeaders(){
	var md = $('.markdown h1,.markdown h2,.markdown h3');
	var regexp = /^[а-яА-Яa-zA-Z0-9]*$/;
	if (md.length > 1){
		md.each(function(index, el) {
			var inner = md.eq(index).text().substr(0,30);
			var str1, str2;
			inner = inner.toLowerCase();

			for (var i = inner.length - 1; i >= 0; i--) {
				if (!regexp.test(inner[i])){
				str1 = inner.substr(0,i);
				str2 = inner.substr(i+1,inner.length);
				inner = str1+"-"+str2;
			} else {
			}
			}

			var hId = inner;
			md.eq(index).attr('id', hId);
			md.eq(index).prepend('<a href="#'+hId+'" class="selflink"><span class="glyphicon glyphicon-link"></span></a>');
		});
	} else {
		var inner = md.text().substr(0,30);
		var str1, str2;
		inner = inner.toLowerCase();

		for (var i = inner.length - 1; i >= 0; i--) {
			if (!regexp.test(inner[i])){
				str1 = inner.substr(0,i);
				str2 = inner.substr(i+1,inner.length);
				inner = str1+"-"+str2;
			} else {
			}
		}

		var hId = inner;
		md.attr('id', hId);
		md.prepend('<a href="#'+hId+'" class="selflink"><span class="glyphicon glyphicon-link"></span></a>');
	}
	hashInit();
}



/*
softHide(object, time, type)
Плавное сокрытие и удаление элемента
object [DOM Object] - скрываемый объект
time [DOM Object] - скрываемый объект
type [string] - тип ("delete" or "hide")
*/
function softHide(object, time = 200, type = "delete")
{
	object.animate({opacity: 0}, time);
	setTimeout(function(){
		if (type=="delete") {
			object.remove();
		}
		if (type=="hide") {
			object.hide();
		}
	}, time);
}





/*
newTicketAbort()
Обработка клика по кнопке "Отменить" в форме отправки
*/
function newTicketAbort()
{
	var $btn = $('.new-ticket-abort');
	$btn.click(function(event) {
		$('.btn-title.new-ticket').click();
	});
}



/*
ticketsDotDotDotInit()
Инициализация оформления body слишком длинных тикетов
*/
function ticketsDotDotDotInit(el = $(".card__desc")) {
		el.dotdotdot({
			ellipsis: '...',
			wrap: 'letter',
			watch: false,
		});
}



/*
topLinksInit()
Оформление верхних ссылок разделов
*/
function topLinksInit() {
		$('h4.selected').removeClass('selected');
		$('.btn-tickets-headers.active').removeClass('active');
		var urlPath = location.pathname.split("/");
		urlPath = urlPath[urlPath.length - 1];
		var classStr = 'default';
		switch (urlPath) {
				case 'bugtracker':
						break;
				default:
						classStr = urlPath;
						break;
		}
		$('h4.'+classStr).addClass('selected');
		$('.btn-tickets-headers[link-attr='+classStr+']').addClass('active');
		$('.btn-tickets-headers').click(function(event) {
			var $path = $(this).attr('link-attr');
			if ($path == "default") {
				$path = "";
			} else {
				$path = "/"+$path;
			}
			$path = "/admin/bugtracker"+$path;
			location.href = 'http://'+location.hostname+$path;
		});
}



/*
ticketEditClickInit()
Клик на кнопке "редактировать" на странице тикета
*/
function ticketEditClickInit()
{
	var $btn = $('.card__btn__edit');
	var btns = '<div class="edit-ticket-btns"><button class="btn btn-primary btn-ticket-abort">Отменить</button><button class="btn btn-success btn-ticket-save ml-10">Сохранить</button></div>';
	$btn.click(function(event) {
		$hC = $('.card__description').height()+"px";
		$('.card__description').parents('.panel-body').append('<textarea class="edit-ticket form-control" id="edit'+$tJson.id+'" style="height:'+$hC+';"></textarea>');
		enableTab('edit'+$tJson.id);
		$('.card__description').addClass('h0');
		$('textarea').val($tJson.body).focus();
		goTo('edit'+$tJson.id, 0);
		$(this).parent().hide();
		$(this).parent().parent().append(btns);
		abortEdit();
		saveEdit();
	});
}



/*
abortEdit(
Отмена редактирования
*/
function abortEdit()
{
	var $btn = $(".edit-ticket-btns .btn-ticket-abort");
	var $actBody = $btn.parents('.panel').find('.panel-body').find('.markdown');
	var $textarea = $btn.parents('.panel').find('.panel-body').find('textarea');
	var $actions = $btn.parents('.panel').find('.panel-footer').find('.card__actions');
	var $btns = $btn.parent();
	$btn.click(function(event) {
		$actBody.removeClass('h0');
		$textarea.remove();
		$actions.show();
		$btns.remove();
	});
}



/*
doerClickInit()
Назначение исполнителя
*/
function doerClickInit()
{
	$(document).on('click', 'li.doer-name', function(event) {
		event.preventDefault();
		var ticket = {};
		if ($(this).parents(".panel .card").length > 0) {
			ticket.dom = $(this).parents(".panel .card");
			ticket.id = ticket.dom.data('cardid');
		} else {
			ticket.id = $(".headers-line .ticket-id").text();
			ticket.btnDom = $(this).parents('.btn-group').find('.dropdown-toggle');
		}
		var doer ={};
		doer.id = $(this).data('userid');
		doer.name = $(this).text();
		console.warn("assign doer");
		console.warn(doer);
		log(ticket);

		if ($(this).parents(".panel .card").length > 0) {
			/*ticket.dom = $(this).parents(".panel .card");
			ticket.id = ticket.dom.data('cardid');
			console.info(ticket);*/
		} else {
			var doerplace = $('span.doer-header-block');
			doerplace.html('<a href="/admin/users/'+doer.id+'">'+doer.name+'</a>');
			doerSetHtml = '<span class="glyphicon glyphicon-pushpin mr-5"></span>Исполнитель: '+doer.name+'</div>';
			ticket.btnDom.html(doerSetHtml);
			$(this).parents(".btn-group").find('.hidden-doer').removeClass('hidden-doer').addClass('dropdown-menu');
			$(this).parent().removeClass('dropdown-menu').addClass('hidden-doer');
			setDoer(ticket.id, doer.id);
		}
	});
	$(document).on('click', 'li.set-doer', function(event) {
		event.preventDefault();
		var ticket = {};
		if ($(this).parents(".panel .card").length > 0) {
			ticket.dom = $(this).parents(".panel .card");
			ticket.id = ticket.dom.data('cardid');
		} else {
			ticket.id = $(".headers-line .ticket-id").text();
			ticket.btnDom = $(this).parents('.btn-group').find('.dropdown-toggle');
		}
		/*var doer ={};
		doer.id = $(this).data('userid');
		doer.name = $(this).text();*/
		console.warn("reset doer");
		// console.warn(doer);
		log(ticket);

		if ($(this).parents(".panel .card").length > 0) {
			/*ticket.dom = $(this).parents(".panel .card");
			ticket.id = ticket.dom.data('cardid');
			console.info(ticket);*/
		} else {
			var doerplace = $('span.doer-header-block');
			// doerplace.html('<a href="/admin/users/'+doer.id+'">'+doer.name+'</a>');
			// doerSetHtml = '<div class="btn btn-default btn-xs card__btn__link dropdown-toggle" type="button" data-toggle="dropdown"><span class="glyphicon glyphicon-pushpin mr-5"></span>Исполнитель: '+doer.name+'</div> <ul class="dropdown-menu"> <li class="set-doer">Изменить</li> </ul>';
			// ticket.btnDom.html(doerSetHtml);
			// setDoer(ticket.id, doer.id);
			$(this).parents(".btn-group").find('.hidden-doer').removeClass('hidden-doer').addClass('dropdown-menu');
			$(this).parent().removeClass('dropdown-menu').addClass('hidden-doer');
			setTimeout(function(){
				ticket.btnDom.click();
			},200);
		}
	});
}


/*
setDoer()
Назначение исполнителя - send to backend
*/
function setDoer(ticketId, doerId = 1)
{
	var doer = {};
	doer.ticket = ticketId;
	doer.uid = doerId;
	jsonData = JSON.stringify(doer);
	var $action = 'setDoer';
	$.ajax({
		url: '/admin/bugtracker/setdoer',
		type: 'POST',
		data: {jsonData: jsonData, action: $action},
	})
	.done(function(answer) {
		console.log("success");
		console.log(answer);
	})
	.fail(function(answer) {
		console.warn("error");
		console.error(answer);
	})
	.always(function() {
		console.log("complete");
	});

}


/*
enableTab(id)
Табуляция внутри textarea
id [string] - id textarea
*/
function enableTab(id) {
    var el = document.getElementById(id);
    el.onkeydown = function(e) {
        if (e.keyCode === 9) { // была нажата клавиша TAB

            // получим позицию каретки
            var val = this.value,
                start = this.selectionStart,
                end = this.selectionEnd;

            // установим значение textarea в: текст до каретки + tab + текст после каретки
            this.value = val.substring(0, start) + '\t' + val.substring(end);

            // переместим каретку
            this.selectionStart = this.selectionEnd = start + 1;

            // предотвратим потерю фокуса
            return false;

        }
    };
}



/*
goTo(id,n)
Установка курсора в textarea
id [string] - id textarea
n [int] - номер позиции курсора
*/
function goTo(id, n)
{
	var o = document.getElementById(id);

	if(!document.all)
	{
		var end = o.value.length;
		o.setSelectionRange(n,n);
		o.focus();
	}
	else
	{
		var r = o.createTextRange();
		r.collapse(true);
        r.moveStart("character", n);
        r.moveEnd("character", 0);
        r.select();
	}
}



/*
hashInit()
обработка хэшей и клики на ссылки-якоря
*/
function hashInit(){
	if ((location.hash == "")||(!$('body *').is(location.hash)))
	{
		log('no link');
	} else
	{
		$('body,html').animate({'scrollTop': $(location.hash).offset().top - 60},300);
	}
	$('a.selflink').click(function(event) {
		event.preventDefault();
		var link = $(this).attr('href');
		$('body,html').animate(
			{'scrollTop': $(link).offset().top - 60},
			300,
			function( ) {
				location.hash = link;
			}
		);
	});

}
