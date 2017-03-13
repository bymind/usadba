$(function() {

	navScriptsInit();

});



/*
navSCriptsInit()
Инициализация скриптов для навигации
*/
function navScriptsInit()
{
	bugTicketsCounter();
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