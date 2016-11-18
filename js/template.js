$(function() {
	CountMenuPxls();
	WindowListen();
	MenuClickInit();
	ImgLabels();
	ProdDay();
	ProdImg();
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
		// построим ссылки в два столбца
		// посчитаем, сколько ссылок в первый столбец
		countFirstCell = Math.ceil( menu.children('li.hide-li').length/3 );
		// копируем скрытые пункты в подменю "еще"
		for (var i = 0; i < menu.children('li.hide-li').length; i++) {
			liHidden = menu.children('li.hide-li:eq('+i+')').clone().removeClass('hide-li').addClass('else-li').addClass('sub-li');
			liHidden.appendTo('.else-ul');
		}
	}

	setTimeout(function(){
		menu.css('display', 'table');
		menu.find('li.show-li').css({
			'display': 'table-cell',
			'text-align': 'center'
		});
		// menu.css('overflow', 'initial');
		for (var i = $('.sub-menu').length - 1; i >= 0; i--) {
			subMenu = $('.sub-menu:eq('+i+')');
			subMenu.css('min-width', subMenu.parent().width() );
		}
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

/*
* ImgAdapt()
* Адаптируем img как cover
* @return 0
*/
function ImgAdapt(){
	$('.box-img').each(function() {
			//set size
			var th = $(this).height(),//box height
					tw = $(this).width(),//box width
					im = $(this).children('img'),//image
					ih = im.height(),//inital image height
					iw = im.width();//initial image width
			if (ih>iw) {//if portrait
					im.addClass('ww').removeClass('wh');//set width 100%
			} else {//if landscape
					im.addClass('wh').removeClass('ww');//set height 100%
			}
			//set offset
			var nh = im.height(),//new image height
					nw = im.width(),//new image width
					hd = (nh-th)/2,//half dif img/box height
					wd = (nw-tw)/2;//half dif img/box width
			if (nh<nw) {//if portrait
					im.css({marginLeft: '-'+wd+'px', marginTop: 0});//offset left
			} else {//if landscape
					im.css({marginTop: '-'+hd+'px', marginLeft: 0});//offset top
			}
	});
	return 0;
}

/*
* ProdDay()
* Инициализируем картинки для товаров дня
* @return 0
*/
function ProdDay(){
	var prodsImg = $('.prod-day-img');
	for (var i = 0; i< prodsImg.length; i++) {
		var curProdImg = prodsImg.eq(i);
		var imgName = 'img/'+curProdImg.data('imgname')+'.jpg';
		curProdImg.css('background-image', 'url('+imgName+')');
	}
	// ImgAdapt();
	return 0;
}

/*
* ProdIMg()
* Инициализируем картинки для товаров дня
* @return 0
*/
function ProdImg(){
	var prodsImg = $('.prod-img');
	for (var i = 0; i< prodsImg.length; i++) {
		var curProdImg = prodsImg.eq(i);
		var imgName = 'img/'+curProdImg.data('imgname')+'.jpg';
		curProdImg.css('background-image', 'url('+imgName+')');
	}
	return 0;
}


/*
* ImgLabels()
* Добавляем лэйблы на картинки
* @return 0
*/
function ImgLabels(){
	var labeledBlocks = $('.labeled');
	var label = {};
	label['new'] = "<span class='label new'>new!</span>";
	label['popular'] = "<span class='label popular'>хит!</span>";
	label['sales'] = "<span class='label sales'>акция!</span>";
	for (var i = 0; i < labeledBlocks.length; i++) {
		var curLabeled = labeledBlocks.eq(i);
		var labels = curLabeled.data('label').split(',');
		var plusTop = -5;
		for (var j = 0; j < labels.length; j++) {
			curLabeled.append(label[labels[j]]);
			curLabeled.find('.label').eq(j).css('top', plusTop+'px');
			console.log(plusTop);
			plusTop+=20; // изменить отступ сверху для следующих лэйблов
		}
	}
}