$(function()
{
	CountMenuPxls();
	WindowListen();
	MenuClickInit();
	LikesInit();
	ImgLabels();
	// ProdDay();
	ProdImg();
	BtnClickInit();
	InitInputMask();
	ModalTabs();
	ToCart();
	ConstructCart();
	InitPopover();
});

/*
* InitPopover()
* Инициализация popover
*/
function InitPopover()
{
	$('[data-toggle="popover"]').popover();
}

/*
* CreateCart()
* Создаем наполнение корзины
* @return 0
*/
function ConstructCart()
{
	cartList = {};
	cartList.count = 0;
	cartList.sumPrice = 0;
	cartList.items = {};

	cartList.addItem = function (item)
		{
			if (!this.inItems(item)) {
				this.items[item.art] = item;
					this.count++;
				this.rePrice();
				console.log('added '+item.art);
			} else {
				this.reCountItem(item);
			}
			UpdateCart();
		};

	cartList.rePrice = function()
		{
			var priceTemp = 0;
			for (var i in this.items) {
				priceTemp += this.items[i].price * this.items[i].count;
			}
			this.sumPrice = priceTemp;
		}

	cartList.inItems = function (item)
		{
			if (item.art in this.items) {
				return true;
			} else {
				return false;
			}
		};

	cartList.reCountItem = function (item)
		{
			this.items[item.art] = item;
			this.rePrice();
			console.log('recount '+item.art+' '+item.count);
		};

	cartList.deleteItem = function (item)
		{
			delete this.items[item.art];
			this.rePrice();
			this.count--;
			console.log('deleted '+item.art);
			UpdateCart();
		};

}


/*
* WindowListen()
*/
function WindowListen()
{
	$(window).resize(function(event) {
		CountMenuPxls();
	});

	var cartMini = $('button.cart');
	// var cartMini = $('a.cart');
	var pxls = $('.main_menu').offset().top -10;
	CountCartBtn(cartMini, pxls);

	window.onscroll = function() {
		CountCartBtn(cartMini, pxls);
	}
}

/*
* CountCartBtn(btn,menu)
*/
function CountCartBtn(btn,pxls)
{
	var rightFloat = ($(window).width() - $('.cart').parents('.container').width())/2;
	var scrolled = window.pageYOffset || document.documentElement.scrollTop;
	if (scrolled >= pxls ) {
		btn.addClass('float');
		btn.css('right', rightFloat);
	} else {
		btn.removeClass('float');
		btn.css('right', 'auto');
	}
}


/*
* CountMenuPxls()
*/
function CountMenuPxls()
{
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
function MenuClickInit()
{
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
function ImgAdapt()
{
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
function ProdDay()
{
	var prodsImg = $('.prod-day-img');
	for (var i = 0; i< prodsImg.length; i++) {
		var curProdImg = prodsImg.eq(i);
		var imgName = curProdImg.data('imgname');
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
function ProdImg()
{
	var prodsImg = $('.prod-img');
	for (var i = 0; i< prodsImg.length; i++) {
		var curProdImg = prodsImg.eq(i);
		var imgName = curProdImg.data('imgname');
		curProdImg.css('background-image', 'url('+imgName+')');
	}
	return 0;
}


/*
* ImgLabels()
* Добавляем лэйблы на картинки
* @return 0
*/
function ImgLabels()
{
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
return 0;
}


/*
* LikesInit()
* Инициируем лайки
* @return 0
*/
function LikesInit()
{
	// var like = $('.heart');

	$(document).on('click', '.heart', function(event) {
		event.preventDefault();
		$(this).toggleClass('liked');
	});

return 0;
}


/*
* BtnClickInit()
* Initialisation click to the button;
* @return 0
*/
function BtnClickInit()
{
	$(document).on('click', 'button', function(event) {
		BtnClickHandler($(this));
	});
	return 0;
}


/*
* BtnClickHandler(obj)
* Handler click to the button;
* @param obj
* @return 0
*/
function BtnClickHandler(obj)
{
	var target = obj.data().target;

	switch(target) {

		case 'modal':
			var targetIndex = obj.data().targetindex;
			ModalInit(targetIndex);
			break;

		default:
			break;
	}

	return 0;
}


/*
* ModalInit(index)
* Модальное окно
* @param index
* @return 0
*/
function ModalInit(index)
{

	var modalIndex = '.modal-'+index;
	var modalBlock = $('.modal'+modalIndex);
	var modalDialog = modalBlock.children('.modal-dialog');
	var modalContent = modalDialog.children('.modal-content');

	var modalBody = modalContent.children('.modal-body');


	if ((index == "cart") && (cartList.count > 0)) {
		var cartBody = modalBody.find('.cart-body');
		cartBody.html("<table class='table table-hover'><thead></thead><tbody></tbody></table>");
		cartBody.find('.table thead').append("<tr><th>Артикул</th><th> Цена</th><th>Кол-во</th></tr>");
		for (var i in cartList.items) {
			cartBody.find('.table tbody').append("<tr><td>Артикул "+cartList.items[i].art+"</td><td> Цена "+cartList.items[i].price+" руб </td><td>Кол-во "+cartList.items[i].count+"</td></tr>");
		}
	} else
	if ((index == "cart") && (cartList.count == 0)) {
		var cartBody = modalBody.find('.cart-body');
		cartBody.html("<div class='nothing'>Тут пока ничего нет.</div>");
	}

	modalBlock.modal();

	console.log(modalIndex);

return 0;
}

/*
* InitInputMask()
* Инициализация масок на телефон
* @return 0
*/
function InitInputMask()
{
	var phoneInput = $("#callback-phone");
	phoneInput.inputmask("+7 (999) 999-99-99", {clearMaskOnLostFocus: true });

// показываем маску при ховере
	phoneInput.on('mouseover', function(event) {
		event.preventDefault();
		phoneInput.inputmask("+7 (999) 999-99-99");
	});

// скрываем после ховера
	phoneInput.on('mouseout', function(event) {
		event.preventDefault();
		phoneInput.inputmask('remove');
	});

return 0;
}

/*
* ModalTabs()
* Работа с табами в модальных окнах
* @return 0
*/
function ModalTabs()
{
	var modalHeader = $('.modal .modal-header');
	var modalTabs = $('.modal .modal-header h4');
	var modalTabsCount = modalTabs.length;
	modalHeader.on('click', 'h4.disabled', function(event) {
		event.preventDefault();
		for (var i = 0; i < modalTabsCount; i++) {
			curTab = modalTabs.eq(i);
			curData = curTab.data('target');
			curTab.toggleClass('disabled');
			$('.modal-body.'+curData+'-body').toggleClass('inactive');
			$('.modal-footer.'+curData+'-footer').toggleClass('inactive');
		}
	});

return 0;
}

/*
* ToCart()
* Работа с кнопкой КУПИТЬ
* @return 0
*/
function ToCart()
{
// клик по кнопке купить
	$(document).on('click', 'button.to-cart', function(event) {
		event.preventDefault();
		if ( !$(this).hasClass('active') ) {
			$(this).addClass('active');
			$(this).html('');
			$incs = "<div class='incs'><div class='minus'>-</div><input type='tel' value='1'><div class='plus'>+</div></div>";
			$(this).after($incs);

			$prodBtnBlock = $(this).parents('.prod-btn-block');
			$prodBtnBlock.addClass('active');

			var inpt = $prodBtnBlock.find('input');
			inpt.inputmask('9{1,4}',{ "placeholder": "-" });
			SendCountItem(inpt);

			var card = (inpt.parents('.prod-card').length > 0) ? inpt.parents('.prod-card') : inpt.parents('.prod-day');
			card.find('.prod-avail').addClass('disabled');
			card.find('.prod-rev').addClass('disabled');
		}

	});

	ClickOnPLus();
	ClickOnMinus();

	ChangeInput();

return 0;
}


/*
* ChangeInput()
* Проверяем изменение инпута с кол-вом товаров
* @return 0
*/
function ChangeInput()
{
	$(document).on('focusout', '.prod-btn-block input', function(event) {
		event.preventDefault();
			if ((parseInt($(this).val()) <= 0) || ($(this).val() == "" ) || ($(this).val() == "-" )) {
				ResetToCart($(this)); // если пусто - reset
			} else {
				SendCountItem($(this)); // если не пусто - обновляем счетчик товара в корзине
			}
	});
}


/*
* ClickOnPlus()
* Клик на +
* @return 0
*/
function ClickOnPLus()
{
	$(document).on('click', '.prod-btn-block .plus', function(event) {
		event.preventDefault();
		var inpt = $(this).parent().find('input');
		var counts = parseInt(inpt.val())+1;
		inpt.val(counts);
		SendCountItem(inpt);
	});

return 0;
}


/*
* ClickOnMinus()
* Клик на -
* @return 0
*/
function ClickOnMinus()
{
	$(document).on('click', '.prod-btn-block .minus', function(event) {
		event.preventDefault();
		var inpt = $(this).parent().find('input');
		var counts = parseInt(inpt.val())-1;
		if (counts == 0) {
			ResetToCart(inpt);
		} else {
			inpt.val(counts);
			SendCountItem(inpt);
		}
	});

return 0;
}


/*
* ResetToCart(inpt)
* Возврат кнопки и проч в иходное состояние
* @param inpt
* @return 0
*/
function ResetToCart(inpt)
{
	var card = (inpt.parents('.prod-card').length > 0) ? inpt.parents('.prod-card') : inpt.parents('.prod-day');
	card.find('.incs').remove();
	card.find('.to-cart').removeClass('active').html('Купить');
	card.find('.prod-avail').removeClass('disabled');
	card.find('.prod-rev').removeClass('disabled');

	var prodItem = {};
	prodItem.count = parseInt(inpt.val());
	prodItem.art = card.data('art');

	cartList.deleteItem(prodItem);

return 0;
}


/*
* SendCountItem(inpt)
* Апдейт инфы про заказанный товар и его количество
* @param inpt
* @return 0
*/
function SendCountItem(inpt)
{
	var card = (inpt.parents('.prod-card').length > 0) ? inpt.parents('.prod-card') : inpt.parents('.prod-day');
	var prodPrice = card.find('.prod-price .number').text();
	var prodItem = {};
	prodItem.price = parseInt(prodPrice);
	prodItem.count = parseInt(inpt.val());
	prodItem.art = card.data('art');

	cartList.addItem(prodItem);
}


/*
* UpdateCart()
* Изменение плавающей кнопки корзины
* @return 0
*/
function UpdateCart()
{
	var cartBtn = $('button.cart');
	var cartCounter = cartBtn.find('.counter');
	var cartPrice = cartBtn.find('.price-counter .num');

	cartBtn.addClass('blink');
	var timing = 400;
	setTimeout(function(){
		cartBtn.removeClass('blink');
	}, timing);

	cartCounter.text(cartList.count);
	cartPrice.text(cartList.sumPrice);
}