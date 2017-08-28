$(function() {
	loginPageInit();

});

function loginPageInit()
{
	setLoginListener();
	focusListener();
	btnLoginListener();
	isTime();
	showTime();
}

/*---------------------------------------------*/

function showTime()
{
	/*
	@ ajax version
	/*setInterval(function(){
		$.ajax({
			url: '/admin/showtime',
			type: 'GET'
		})
		.done(function(response) {
			console.log("update time - success");
			$('.time-date .time').html(response);
		})
		.fail(function() {
			console.log("update time - error");
		})
		.always(function() {
			console.log("update time - complete");
		});
	},10000);*/

	setInterval(function() {
		isTime();
	} ,1000);
}

function isTime()
{
	$timeNow = new Date();
	$months = ['января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
	$days = ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'];
	$('.time-date .time').html(($timeNow.getHours()>9?$timeNow.getHours():("0"+$timeNow.getHours()))+"<span class='poink'>:</span>"+($timeNow.getMinutes()>9?$timeNow.getMinutes():("0"+$timeNow.getMinutes())));
	$nowDay = $timeNow.getDate();
	$nowDayW = $days[$timeNow.getDay()];
	$nowYear = $timeNow.getFullYear();
	$nowMonth = $months[$timeNow.getMonth()];
	// $('.time-date .date').html($nowDay + " " + $nowMonth + " " + $nowYear + "<span class='dow'>" + $nowDayW + "</span>");
	$('.time-date .date').html($nowDay + " " + $nowMonth + " " + $nowYear + ", " + $nowDayW );
	//console.log("update time - success");
}

function btnLoginListener()
{
	$('button.login-btn').click(function(event) {
		actionLogin();
	});
}

function focusListener()
{
	$('input').focus(function(event) {
		$(this).parent().find('.before').addClass('up');
	});
	$('input').blur(function(event) {
		if ($(this).val()=="")
			$(this).parent().find('.before').removeClass('up');
	});
}

function setLoginListener()
{
	$('input[type=text]').keyup(function(e)
		{
			if (e.keyCode == 13)
			{
				$('input[type=password]').focus();
			}
		}
	);

	$('input[type=password]').keyup(function(e)
		{
			if (e.keyCode == 13)
			{
				$('.login-btn').addClass('hover');
				actionLogin();
			}
		}
	);
}

function actionLogin()
{
	$login = $('input[type=text]').val();
	$passw = $('input[type=password]').val();
	tryLogin($login, $passw);
}

/*---------------------------------------------*/

function tryLogin(login, passw)
{
	$.post(
		'/admin/gologin',
		{ login: login, passw: passw },
		//$('.item-content').serialize(),
		function(response)
			{
				$('.ans').addClass('filled').html(response);
				$('.login-btn').removeClass('hover')
			}
		);
}