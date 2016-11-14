$(function() {
	//CountMenuPxls();
	//WindowListen();
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
	menu = $('nav.main_menu ul.menu_text_units');
	allW = menu.width();
	countW = 0;
	elseW = Math.round(menu.find('li.else').width() + 0.5);
	countLi = menu.find('li.show-li').length;
	for (var i = 0; i < countLi; i++) {
		countW += menu.find('li.show-li:eq('+i+')').width();
	}
	searchW = allW - countW - elseW;
	console.log(searchW);

	if (searchW < 250) {
		menu.find('li.show-li:eq('+(countLi-1)+')').removeClass('show-li').addClass('hide-li');
		menu.find('li.else').removeClass('hide-li-else').addClass('show-li-else');
		CountMenuPxls();
	} else if (250 + countW + menu.find('li.hide-li:eq(0)').width() <= allW - elseW) {
				menu.find('li.hide-li:eq(0)').removeClass('hide-li').addClass('show-li');
				menu.find('li.search-item').width(250);
	}
	else {
		menu.find('li.search-item').width(searchW);
	}

	countLi = menu.find('li.show-li').length;
	if (countLi == $('li.item-li').length ) {
		$('li.else').removeClass('show-li-else').addClass('hide-li-else');
		menu.find('li.search-item').width(searchW + elseW);
	}

	if (countLi <= 1) {
		$('li.else').removeClass('show-li-else').addClass('hide-li-else');
		menu.find('li.search-item').width(searchW + elseW);
	}

	// if ( searchW > 250 ) {
	// 	countW = 0;
	// 	countLi = menu.find('li.hide-li').length;
	// 	for (var i = 0; i < countLi; i++) {
	// 		countW += menu.find('li.hide-li:eq('+i+')').width();
	// 		if (countW + 250 <= allW) {
	// 			menu.find('li.hide-li:eq('+(countLi-1)+')').removeClass('hide-li').addClass('show-li');
	// 		}
	// 	}
	// }

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
	});


	return 0;
}