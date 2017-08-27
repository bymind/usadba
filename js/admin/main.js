$(function() {

	$is_Super = false;

	mainPageInit();

	$('.details .title').dotdotdot({
		ellipsis:'...',
	});

	$('.details .tags').dotdotdot({
		ellipsis:'...',
		watch		: true,
	});

	$('.tags.is-truncated').click(function(event) {
		$(this).addClass('open');
		$h = $(this).height();
		$mb = $h - 22;
		$(this).css('margin-bottom', '-'+$mb+'px');
	});

	spoilersInit();

});

function spoilersInit()
{
	$('.spoiler-title').unbind('click').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		$(this).parent('.spoiler').toggleClass('open');
	});
}

function mainPageInit()
{
	setActiveMenu();
	setExitListener();
	isTime();
	showTime();
	sandvich($('.open-menu-btn'), "#000", "#fff", 22, 15, 1, false);
}

/*--------------------------------*/

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
	$('.time-date .date').html($nowDay + " " + $nowMonth + " " + $nowYear + "<br>" + $nowDayW );
	//console.log("update time - success");
}

function setExitListener()
{
	$('button.logout').click(function(event) {
		$.post('/admin/logout', function(response) {
			$('body').html(response);
		});
	});
}

function setActiveMenu()
{
	$active_name = $('.side-nav').attr('attr-active');
	console.log($('.side-nav').attr('attr-active'));
	$('.side-nav li.'+$active_name).addClass('active');

	userBtn = $('.user-btn');
	userOver = userBtn.parent('.usr-over');
	userBtn.click(function(event) {
		userOver.toggleClass('opened');
	});

	selectItem = $('.select .items li');
	openedItem = $('li.opened');
	selectItem.click(function(event) {
		$(this).toggleClass('opened');
		$(this).find('.pin-list');
	});

	for (var i = $('.pins-list').length - 1; i >= 0; i--) {
		$('.pins-list:eq('+i+')').css('z-index', 5-i);
	};

	$('.pins-list')

	$(document).click(function(event) {
		if ($(event.target).closest(".usr-over").length)
			{
				return;
			}
		else {
					userOver.removeClass('opened');
					event.stopPropagation();
		}
	});

}

function sandvich($object, $color1, $color2, $width, $height, $strokeWidth, $isRounded) {
	btn = $object;
	initSandvich(btn,$color1, $color2, $width, $height, $strokeWidth, $isRounded);
	btn.parent().click(function(event) {
		btn.toggleClass('selected');
		toggleMenu($object.hasClass('selected'));
});
	$('.nav-overlay').click(function(event) {
		btn.toggleClass('selected');
		toggleMenu($object.hasClass('selected'));
	});
}

function toggleMenu($isSet)
{
	content = $('#content');
	menuObj = $('.navigation');
	menuOverlay = $('.nav-overlay');
	!$isSet ? closeMenu() : openMenu();
}

function closeMenu()
{
	console.log('closeMenu()');
	menuObj.removeClass('swiped');
	menuOverlay.removeClass('on');
	setTimeout(function () {
		btn.parent().removeClass('swiped').parent().removeClass('swiped').parent().find('.nav-title').removeClass('swiped').addClass('col-lg-10').removeClass('col-lg-9');
		content.removeClass('swiped');
	},200);
}

function openMenu()
{
	console.log('openMenu()');
	menuObj.addClass('swiped');
	menuOverlay.addClass('on');
	setTimeout(function () {
		content.addClass('swiped');
		btn.parent().addClass('swiped').parent().addClass('swiped').parent().find('.nav-title').addClass('swiped').addClass('col-lg-9').removeClass('col-lg-10');
	},200);

}

function initSandvich($object, $color, $color2, $w, $h, $l, $radius)
{
	$btnBox = $object;
	btnClassName = $btnBox.attr('class');
	$round = $radius ? " border-radius: "+$l/2+"px;" : "";
	$bthShape = $btnBox.append('<span class="sandvich-shape"></span>');
	$('body').append('<style> .'+btnClassName+' {display:block; position: relative; width: '+$w+'px; height: '+$h+'px; background-color: transparent; transition: all .2s ease-out; cursor: pointer;box-sizing: border-box} .'+btnClassName+'.selected .sandvich-shape {color: transparent; } .'+btnClassName+'.selected .sandvich-shape::before, .'+btnClassName+'.selected .sandvich-shape::after {transform-origin: center; background-color:'+$color2+'} .'+btnClassName+'.selected .sandvich-shape::before {transform: translateY('+(($h-$l)/2)+'px) rotate(45deg); } .'+btnClassName+'.selected .sandvich-shape::after {transform: translateY(-'+(($h-$l)/2)+'px) rotate(-45deg); } .sandvich-shape {position: absolute; top: 0; bottom: 0; right: 0; left: 0; margin: auto; display: block; width: 100%; height:0; border-bottom: '+$l+'px solid; transition: all .2s ease-in-out;'+$round+' } .sandvich-shape::before, .sandvich-shape::after {position: relative; display: block;'+$round+' content: ""; width: 100%; height: 0; border-bottom: '+$l+'px solid; background-color: '+$color+'; transition: all .2s ease-in; } .sandvich-shape::before {top: -'+(($h-$l)/2)+'px; } .sandvich-shape::after {top: '+(($h-3*$l)/2)+'px; } </style>');
}

/*-------------------COMMON FUNCTIONS-----------------------*/

function checkSuper()
{
isSnotS(
	function(){
		log('You are super! ;)');
	},
	function(){
		log('You are not super. =(');
	});
}



/*
sup()
Функция, возвращающая promise() о
принадлежности пользователя к СуперЮзерам
1 - true
0 - false
*/
function sup()
{
		return $.Deferred(function(def){
			$.ajax({
					url: '/admin/users/issuper',
					type: 'GET',
					success: function(res){
						def.resolve(res);
					},
					fail: function(err){
						def.reject(err);
					}
				});
		}).promise();
};

/*
hasRight(right,uid)
Функция, возвращающая promise() о
наличии прав у админа
1 - true
0 - false
*/
function hasRight(right, uid)
{
		var data = {
			right: right,
			uid: uid
		}
		dataJson = JSON.stringify(data);
		return $.Deferred(function(def){
			$.ajax({
					url: '/admin/users/hasRight',
					type: 'POST',
					data: {data:dataJson},
					success: function(res){
						def.resolve(res);
					},
					fail: function(err){
						def.reject(err);
					}
				});
		}).promise();
};

function returnHasRight(right, uid)
{
	hasRight(right,uid).done(function(res){
		switch (res){
			case "1":
				console.info('this user has right "'+right+'"');
				break;
			case "0":
				console.warn('this user has NOT right "'+right+'"');
				break;

			default:
				console.log(res);
				break;
		}
	})
}

/*
isSnotS(yes,no)
Выполнит первую функцию, если СуперЮзер, или вторую - если нет
yes [function] - функция для true
no [function] - функция для false
*/
function isSnotS(yes,no)
{
			sup().done(function(res){
				if (res == 1){
					yes();
				} else {
					no();
				}
			});
}


/*
log(a)
Обертка для concole.log()
a [str|int|obj|...] - что выводим
*/
function log(a){
	console.log(a);
}


function getCookie(name) {
	var matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

function setCookie(name, value, options) {
	options = options || {};

	var expires = options.expires;

	if (typeof expires == "number" && expires) {
		var d = new Date();
		d.setTime(d.getTime() + expires * 1000);
		expires = options.expires = d;
	}
	if (expires && expires.toUTCString) {
		options.expires = expires.toUTCString();
	}

	value = encodeURIComponent(value);

	var updatedCookie = name + "=" + value;

	for (var propName in options) {
		updatedCookie += "; " + propName;
		var propValue = options[propName];
		if (propValue !== true) {
			updatedCookie += "=" + propValue;
		}
	}

	document.cookie = updatedCookie;
}


function deleteCookie(name) {
	setCookie(name, "", {
		expires: -1
	})
}

function getSession(name)
{
	var def = new $.Deferred();

	$.ajax({
		url: '/admin/session',
		type: 'POST',
		data: {action: 'getSession', name: name},
	})
	.done(function(answer) {
		// console.log(answer);
		def.resolve(answer);
	})
	.fail(function(answer) {
		// console.error(answer);
		def.reject(answer);
	})
	.always(function() {
		// console.log("getSession() complete");
	});
	return def.promise();
}

setSession = function(name, value, secondName) {
	var def = new $.Deferred();
	if (secondName=='undefined') {
	$.ajax({
		url: '/admin/session',
		type: 'POST',
		data: {action: 'setSession', name: name, value: value},
	})
	.done(function(answer) {
		// console.log(answer);
		def.resolve(answer);
	})
	.fail(function(answer) {
		console.error(answer);
		def.reject(answer);
	})
	.always(function(answer) {
		console.log("setSession() complete");
	});
} else {
	$.ajax({
		url: '/admin/session',
		type: 'POST',
		data: {action: 'setSession', name: name, value: value, secondName: secondName},
	})
	.done(function(answer) {
		// console.log(answer);
		def.resolve(answer);
	})
	.fail(function(answer) {
		console.error(answer);
		def.reject(answer);
	})
	.always(function(answer) {
		console.log("setSession() complete");
	});
}
	return def.promise();
}


function deleteSession(name) {
	$.ajax({
		url: '/admin/session',
		type: 'POST',
		data: {action: 'deleteSession', name: name},
	})
	.done(function(answer) {
		console.log(answer);
	})
	.fail(function(answer) {
		console.error(answer);
	})
	.always(function() {
		console.log("deleteSession() complete");
	});
}

function open_popup(url)
{
		var w = 880;
		var h = 570;
		var l = Math.floor((screen.width - w) / 2);
		var t = Math.floor((screen.height - h) / 2);
		var win = window.open(url, 'ResponsiveFilemanager', "scrollbars=1,width=" + w + ",height=" + h + ",top=" + t + ",left=" + l);
}


function responsive_filemanager_callback(field_id){
	$('#'+field_id).val('/upload/'+$('#'+field_id).val());
	var url=jQuery('#'+field_id).val();
	$("#img-"+field_id).attr('src', url).css('opacity', '1');
	console.log('filemanager callback');
}