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
	menu = $('nav.main_menu ul.menu_text_units');
	allW = menu.width();
	countW = 0;
	countLi = menu.find('li').length - 1;
	for (var i = 0; i < countLi; i++) {
		countW += menu.find('li:eq('+i+')').width();
		console.log(countW);
	}
	searchW = allW - countW;
	console.log(searchW);
	if (searchW < 250) {
		menu.find('li:eq('+(countLi-1)+')').remove();
		CountMenuPxls();
	} else {
		menu.find('li.search-item').width(searchW);
	}
}

/*
* MenuClickInit()
* Click menu-item
* @return 0
*/
function MenuClickInit() {
	$(document).on('click', '.pclick', function(event) {
		event.preventDefault();
		return false;
	});
	$(document).on('click', '.arrow', function(event) {
		event.preventDefault();
		$(this).toggleClass('active');
	});


	return 0;
}