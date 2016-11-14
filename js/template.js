$(function() {
	CountMenuPxls();
	WindowListen();
	MenuClickInit();
});

/*
* WindowListen()
*/
function WindowListen(){
	$(window).resize(function(event) {
		CountMenuPxls();
	});
}

/*
* CountMenuPxls()
*/
function CountMenuPxls() {
	menu = $('nav.main_menu ul.menu_text_units:eq(0)');

// отображаем как блок чтобы посмотреть длину
	menu.css('display', 'inline-block');
	menu.children('li.show-li').css({
		'display': 'inline-block',
		'overflow': 'hidden'
	});
// длина видимого меню
	allW = menu.width();

// переменная для вычисления длины пунктов, забиваем первый пункт
	countW = menu.children('li.item-li:eq(0)').width();
// флаг надо ли скрывать текущий пункт
	goHide = 0;
// длина пункта "еще"
	elseW = Math.round(menu.children('li.else').width() + 0.5);
// считаем кол-во пунктов
	countLi = menu.children('li.item-li').length;
// считаем кол-во пуктов в "еще"
	countElse = 0;

// запускаем цикл по пунктам
	for (var i = 0; i < countLi; i++) {
		// если надо скрыть текущий пункт
		if (goHide) {
			// скрываем текущий пункт
			menu.children('li.item-li:eq('+(i+1)+')').removeClass('show-li').addClass('hide-li').removeAttr('style');
		} else
			// если скрывать не надо, и длина пунктов больше длины меню
			if ((!goHide)&&(countW + menu.children('li.item-li:eq('+(i+1)+')').width() + elseW > allW )) {
				// флаг - скрываем остальные
				goHide = 1;
				// скрываем след пункт
				menu.children('li.item-li:eq('+(i+1)+')').removeClass('show-li').addClass('hide-li').removeAttr('style');
				// countW -= menu.children('li:eq('+(i+1)+')').width();
		} else
			{
				// если не надо скрывать - не скрываем, а показываем
				menu.children('li.item-li:eq('+(i+1)+')').removeClass('hide-li').addClass('show-li');
				// добавляем длину пункта
				countW += menu.children('li.item-li:eq('+(i+1)+')').width();
				// обнуляем флаг
				goHide = 0;
			}
	}

// считаем кол-во пунктов в "еще"
countElse = $('.else-li').length;

// если есть скрытые пункты
	if ((menu.children('li.hide-li').length > 0)&&(menu.children('li.hide-li').length != countElse)) {
		// показываем пункт "еще"
		menu.children('li.hide-li-else').removeClass('hide-li-else').addClass('show-li-else').css({
			'display': 'table-cell',
			'text-align': 'center'
		});
		$('ul.else-ul').html('');
		// копируем скрытые пункты в подменю "еще"
		for (var i = 0; i < menu.children('li.hide-li').length; i++) {
			liHidden = menu.children('li.hide-li:eq('+i+')').clone().removeClass('hide-li').addClass('else-li');
			liHidden.appendTo('.else-ul');
		}
	}

	setTimeout(function(){
		menu.css('display', 'table');
		menu.find('li.show-li').css({
			'display': 'table-cell',
			'text-align': 'center'
		});
		menu.css('overflow', 'initial');
	},100);

	console.log("countW = "+countW+"; allW = "+allW);


}

/*
* MenuClickInit()
* Click menu-item
* @return 0
*/
function MenuClickInit() {
	// обработка нажатия на раскрывающийся пункт
	$(document).on('click', '.pclick', function(event) {
		event.preventDefault();
		return false;
	});
	// триггерирование стрелки
	$(document).on('click', '.arrow', function(event) {
		event.preventDefault();
		$(this).toggleClass('active');
		$(this).parent('li').toggleClass('active');
		$(this).children('ul.else-ul').toggleClass('hide-else-ul');
	});


	return 0;
}