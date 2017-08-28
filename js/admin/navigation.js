$(function() {

	navScriptsInit();

});



/*
navSCriptsInit()
Инициализация скриптов для навигации
*/
function navScriptsInit()
{
	newOrdersCounter();
	newCommentsCounter();
	waitForNewOrder();
	// waitForNewComments();
	switchSound();
}

/*
newOrdersCounter()
Подсчет и рендер кол-ва новых заказов
@todo генерить токен для каждого админа
*/
function newOrdersCounter()
{
	var $countOrders = 0;

	$.ajax({
		url: '/admin/orders/countNew',
		type: 'POST',
		data: {token:'ajaxCount'},
	})
	.done(function(answ) {
		$countOrders = answ;
		console.log("it's "+$countOrders+" new order(s)");
		if ($countOrders > 0) {
			$('li.home .alert').addClass('on').text($countOrders);
		}
	})
	.fail(function(answ) {
		console.log("error - "+answ);
	});
}

function newCommentsCounter()
{
	var $countComms = 0;

	$.ajax({
		url: '/admin/comm/countNew',
		type: 'POST',
		data: {token:'ajaxCount'},
	})
	.done(function(answ) {
		$countComms = answ;
		console.log("it's "+$countComms+" new comment(s)");
		if ($countComms > 0) {
			$('li.comm .alert').addClass('on').text($countComms);
		}
	})
	.fail(function(answ) {
		console.log("error - "+answ);
	});
}


/*
bugTicketsCounter()
Подсчет и рендер кол-ва новых тикетов
@todo генерить токен для каждого админа
*/
function bugTicketsCounter()
{
	var $countTickets = 0;

	$.ajax({
		url: '/admin/bugtracker/count/new',
		type: 'POST',
		data: {token:'ajaxCount'},
	})
	.done(function(answ) {
		$countTickets = answ;
		console.log("it's "+$countTickets+" new ticket(s)");
		if ($countTickets > 0) {
			$('li.bugtracker .alert').addClass('on').text($countTickets);
		}
	})
	.fail(function(answ) {
		console.log("error - "+answ);
	});

}


function waitForNewOrder()
{
	var time = 10000;
	tiktakTimer = setInterval(checkLastOrder, time);
}

function switchSound()
{
	$('li.sound a').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		$toOn = $(this).closest('li').find('.off');
		if ($toOn.data('soundstate')=="on") {
			$('li.sound .glyphicon[data-soundstate=off]').addClass('off');
			$toOn.removeClass('off');
			$('li.sound .sound-state').text('включён');
			setCookie('sound','1', {expires: (new Date(new Date().getTime() + 365*24*60*60*1000)), path: "/admin"} );
			saveSoundSettings('1');
		}
		if ($toOn.data('soundstate')=="off") {
			$('li.sound .glyphicon[data-soundstate=on]').addClass('off');
			$toOn.removeClass('off');
			$('li.sound .sound-state').text('выключен');
			setCookie('sound','0', {expires: (new Date(new Date().getTime() + 365*24*60*60*1000)), path: "/admin"} );
			saveSoundSettings('0');
		}
	});
}

function saveSoundSettings($param)
{
	$.ajax({
			url: '/admin/setSoundSetting',
			type: 'POST',
			data: {token:'setSound', param: $param}
		})
		.done(function(id) {
			console.log('set sound to '+ $param +': '+ id);
			return true;
		})
		.fail(function(answ) {
			console.log("error - "+answ);
			return false;
		});
}

function checkLastOrder()
{
	var lastOrder = "";
	$.when(getLastOrder()).then(function(res){
		lastOrder = res;
		if (getCookie('lastOrder') && (getCookie('lastOrder')==lastOrder)) {
		} else {
			lastVisibleOrder = getCookie('lastOrder');
			setCookie('lastOrder', lastOrder, {expires: (new Date(new Date().getTime() + 365*24*60*60*1000)), path: "/admin"});
			soundCheck = lastOrder%10;
			var sound = $('#sound'+soundCheck)[0];
			$.when(getSession('sound')).then(
				function(res){
					console.log(res);
					if (res=='1') {
						sound.play();
						newOrdersCounter();
						if ($('.side-nav').attr('attr-active') == "home") {
							newOrdersRender(lastVisibleOrder, getCookie('lastOrder'));
						}
					} else {
						console.info('sound off');
					}
				},
				function(res){
					console.log('error: '+res);
				});
		}
	}, function(){
		console.log('fail');
	});
}

function getLastOrder()
{
	var def = new $.Deferred();

	$.ajax({
			url: '/admin/orders/getLastOrderId',
			type: 'POST',
			data: {token:'getOrder'},
		})
		.done(function(id) {
			// console.log('checked order id: '+id);
			def.resolve(id);
		})
		.fail(function(answ) {
			console.log("error - "+answ);
			def.reject();
		});

	return def.promise();
}

function stopWaitingOrder()
{
	clearInterval(tiktakTimer);
	console.log('tiktak stopped');
}