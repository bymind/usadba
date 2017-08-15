$(function()
{
	winW = $(window).width();
	CheckSessionCart();
	CountMenuPxls();
	WindowListen();
	MenuClickInit();
	LikesInit();
	ImgLabels();
	// ProdDay();
	ProdImg();
	InitOwlPoD();
	$(window).resize(function(event) {
		InitOwlPoD();
	});
	BtnClickInit();
	InitInputMask($("#callback-phone"));
	InitInputMask($("#profile-phone"));
	ModalTabs();
	ToCart();
	ConstructCart();
	InitPopover();

	InitTabs();

	InitHash();

	InitTooltips();

	InitInputDatepicker($('#profile-bd'));

	InitToggle(".give-review",".comm-form","on");

	if ( $('.comm-form').get(0) ) {
		InitCommTextarea($('.comm-form'));
	}

	InitSearch();

});

function scrollAfterPagination()
{
	$(function()
	{
		$(document).scrollTop($('.title-wide').offset().top - 10);
	});
}

function InitSearch()
{
	var btn = $('button.btn-search');
	var inp = $('input.search-text');
	$(document).on('keypress', 'input.search-text', function(event) {
		// event.preventDefault();
		event.stopPropagation();
		var chr = event.which;
		if (chr == 13) {
			var val = inp.val();
			tryToSearch(val);
		}
		return true;
	});
	$(document).on('click', 'button.btn-search', function(event) {
		event.preventDefault();
		var val = inp.val();
		if (val == "") {
			return false;
		}
		tryToSearch(val);
		return true;
	});
}

function tryToSearch(val)
{
	$.ajax({
		url: '/search',
		type: 'POST',
		data: {search: val},
	})
	.done(function(res) {
		console.log("success");
		location.href = '/search/'+val;
	})
	.fail(function(err) {
		console.log("error");
		console.log(err);
	});

}

function InitToggle(selector_switcher, selector_to_switch, class_to_switch)
{
	$(document).on('click', selector_switcher, function(event) {
		event.preventDefault();
		$(selector_to_switch).toggleClass(class_to_switch);
	});
}

function InitOwlPoD()
{
	var autoPlaySpeed1 = 4500;
	var autoPlaySpeed2 = 5000;

	$("#carousel-pod").owlCarousel({
		navigation : true, // Show next and prev buttons
		navigationText: false,
		mouseDrag: false,
		slideSpeed: 300,
		paginationSpeed : 400,
		pagination: false,
		items : 1,
		itemsCustom : false,
		itemsDesktop : [1200,1],
		itemsDesktopSmall : [960,1],
		itemsTablet: [768,1],
		itemsTabletSmall: [480,1],
		itemsMobile : [320,1],
		loop:true,
		// autoHeight : true,
		// autoWidth:true,
		autoplay: autoPlaySpeed1,
		afterMove : function(elem){
					$('.prod-pod .owl-prev').addClass('show');
				}
	});


	$("#carousel-news").owlCarousel({
		navigation : true, // Show next and prev buttons
		navigationText: false,
		mouseDrag: false,
		slideSpeed: 300,
		paginationSpeed : 400,
		delay: 1000,
		pagination: false,
		items : 1,
		itemsCustom : false,
		itemsDesktop : [1200,1],
		itemsDesktopSmall : [960,1],
		itemsTablet: [768,1],
		itemsTabletSmall: [480,1],
		itemsMobile : [320,1],
		loop:true,
		// autoHeight : true,
		// autoWidth:true,
		autoplay: autoPlaySpeed2,
		afterMove : function(elem){
					$('.prod-pod .owl-prev').addClass('show');
				}
	});

	$('#carousel-pod').trigger('owl.play', autoPlaySpeed1);
	$('#carousel-news').trigger('owl.play', autoPlaySpeed2);

	// остановка карусели при ховере
		$('.prod-pod').on('mouseover', function(event) {
			event.preventDefault();
			$('.prod-pod').trigger('owl.stop');
		});

	// запуск карусели после ховера
		$('#carousel-pod').on('mouseout', function(event) {
			event.preventDefault();
			$('#carousel-pod').trigger('owl.play', autoPlaySpeed1);
		});
	// запуск карусели после ховера
		$('#carousel-news').on('mouseout', function(event) {
			event.preventDefault();
			$('#carousel-news').trigger('owl.play', autoPlaySpeed2);
		});

}

function InitTooltips()
{
	$('[data-toggle="tooltip"]').tooltip({delay: { "show": 200, "hide": 100 }});
}

/*
* InitHash()
* Инициализация слежения за хешем в адресной строке
*/
function InitHash()
{
	var $hash = location.hash.substr(1);
	if ( $hash && $('.tab-content-box.'+$hash).length>0) {
		$targetTab = $('.tabs-box').find('.tab[data-content='+$hash+']');
		if (!$targetTab.hasClass('active')) {
			console.info('Opened tab - '+$hash);
			$targetTab.click();
		}
	}


}


/*
* InitTabs()
* Инициализация табов
*/
function InitTabs()
{
	$(document).on('click', '.tabs-box .tab', function(event) {
		event.preventDefault();
		if ( $(this).hasClass('active') ) {
			return false;
		}

		$contentClass = $(this).data('content');
		$parent = $(this).parent().parent();
		$contentBoxes = $parent.find('.tab-content-box');

		$curTab = $(this);
		$tabsBoxes = $parent.find('.tab');
		$tabsBoxes.each(function(index, el) {
			if ( (!$(this).hasClass('active')) && ($(this) != $curTab) ) {
				$(this).addClass('active');
			} else {
				$(this).removeClass('active');
			}
		});

		$contentBoxes.each(function(index, el) {
			if ( ($(this).hasClass($contentClass))&&(!$(this).hasClass('active')) ) {
				$(this).addClass('active');
			} else {
				$(this).removeClass('active');
			}
		});

		location.hash = $contentClass;

	});
}

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
			// UpdatePageCards();
		};

		cartList.rePrice = function()
		{
			var priceTemp = 0;
			for (var i in this.items) {
				priceTemp += 0+this.items[i].price * this.items[i].count;
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
			UpdatePageCards();
		};

		// clear all itemss
		cartList.clearCart = function()
		{
			this.items = [];
			this.count = 0;
			this.rePrice();
			console.log('cart cleared');
			UpdateCart();
			UpdatePageCards();
		}
	}


/*
* WindowListen()
*/
function WindowListen()
{
	$(window).resize(function(event) {
		if ($(window).width()!=winW) {
			winW = $(window).width();
			CountMenuPxls();
		}
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
/*	$(document).on('click', '.pclick', function(event) {
		event.preventDefault();
		return false;
	});*/
	// триггерирование стрелки
	$(document).on('click', '.arrow', function(event) {
		event.preventDefault();
		$(this).toggleClass('active');
		$(this).parent('li').toggleClass('active');
		$(this).children('ul.else-ul').toggleClass('hide-else-ul');
		$(this).parent('li').children('ul.side-sub-menu').toggleClass('opened');
	});

	$(document).on('click', '.arrow span.just-this-cat', function(event) {
		event.preventDefault();
		event.stopPropagation();
		location.href = $(this).parent('a').attr('href');
	});

	$(document).click(function(event) {
			// if ($(event.target).parents(".menu_text_units").length) {
				if ($(event.target).closest(".menu_text_units>li").length) return;
				// event.preventDefault();
				var $arrow = $('.menu_text_units .arrow.active');
				$arrow.toggleClass('active');
				$arrow.parent('li').toggleClass('active');
				$arrow.children('ul.else-ul').toggleClass('hide-else-ul');
				$arrow.parent('li').children('ul.side-sub-menu').toggleClass('opened');
				event.stopPropagation();
			// }
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
	var likeText = ['Добавить в избранное', 'Удалить из избранного'];

	$(document).on('click', '.heart', function(event) {
		event.preventDefault();
		if (!$(this).hasClass('fake-like')) {
			var prodId = $(this).closest('.prod-card').data('prodid');
			if ($(this).hasClass('liked')) {
				// $(this).removeClass('liked');
				$(this).attr('title', likeText[0]);
				$(this).attr('data-original-title', likeText[0]);
				updateHeart(prodId, 'delete');
			} else {
				// $(this).addClass('liked');
				$(this).attr('title', likeText[1]);
				$(this).attr('data-original-title', likeText[1]);
				updateHeart(prodId, 'add');
			}
		} else {
		}
	});

	$(document).on('click', '.like-this', function(event) {
		event.preventDefault();
		if (!$(this).hasClass('fake-like')) {
			var prodId = $(this).parent().parent().children('.prod-card').data('prodid');
			if ($(this).hasClass('liked')) {
				$(this).text(likeText[0]);
				$(this).attr('title', likeText[0]);
				$(this).attr('data-original-title', likeText[0]);
				updateHeart(prodId, 'delete');
			} else {
				$(this).text(likeText[1]);
				$(this).attr('title', likeText[1]);
				$(this).attr('data-original-title', likeText[1]);
				updateHeart(prodId, 'add');
			}
			$(this).toggleClass('liked');
		} else {
		}
	});

	$(document).on('click', '.fake-like', function(event) {
		event.preventDefault();
		showRegistrationExtended('Для использования этой функции нужно<br>войти или зарегистрироваться');
	});

	return 0;
}

function showRegistrationExtended($text)
{
	$modal = $('.modal.modal-profile');
	$modal.find('.modal-title[data-target=reg]').click();
	$modal.find('.reg-body').prepend('<h4 class="extended" style="margin-top:-10px; text-align:center">'+$text+'</h4>');
	$modal.find('.login-body').prepend('<h4 class="extended" style="margin-top:-10px; text-align:center">'+$text+'</h4>');
	$modal.modal();
	$modal.on('hidden.bs.modal', function(event) {
		event.preventDefault();
		$modal.find('h4.extended').remove();
		$modal.off('hidden.bs.modal');
	});
}

function updateHeart($prodid, $type)
{
	$.each($('.prod-card[data-prodid="'+$prodid+'"] .heart'), function(index, val) {
		if ($type=="delete") {
			$('.prod-card[data-prodid="'+$prodid+'"] .heart').eq(index).removeClass('liked').attr({
				title: 'Добавить в избранное',
				'data-original-title': 'Добавить в избранное'
			});
		} else
		if ($type=="add") {
			$('.prod-card[data-prodid="'+$prodid+'"] .heart').eq(index).addClass('liked').attr({
				title: 'Удалить из избраннного',
				'data-original-title': 'Удалить из избранного'
			});
		}
	});

	$.ajax({
		url: '/user/updateFavs',
		type: 'POST',
		data: {prodId: $prodid, type: $type},
	})
	.done(function(res) {
		console.log("success");
		console.log(res);
	})
	.fail(function(res) {
		console.error(res);
	})
	.always(function() {
		console.log("complete");
	});
}


/*
* BtnClickInit()
* Initialisation click to the button;
* @return 0
*/
function BtnClickInit()
{
	$(document).on('click', 'button', function(event) {
		console.log('btn clicked!');
		BtnClickHandler($(this));
	});
	$(document).on('click', '.like_button', function(event) {
		console.log('btn clicked!');
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
	var targetindex = obj.data().targetindex;
	var targetcallback = obj.data().targetcallback;
	if (typeof(publicProfEdit)==="undefined") {
		publicProfEdit = null;
	}
	switch(target) {
		case 'modal':
		var targetIndex = obj.data().targetindex;
		ModalInit(targetIndex);
		break;

		case 'login':
		var loginForm = $('#profile-form-login');
		TryLogin(loginForm);
		break;

		case 'registration':
		var registrationForm = $('#profile-form-reg');
		TryRegistration(registrationForm);
		break;

		case 'goProfile':
		var targetIndex = obj.data().targetindex;
		GoToProfile(targetIndex);
		break;

		case 'goCallback':
		var targetIndex = {};
		targetIndex.name = obj.parents('.modal-content').find('#callback-name').val();
		targetIndex.phone = obj.parents('.modal-content').find('#callback-phone').val();
		GoCallback(targetIndex, obj);
		break;

		case 'goLink':
		var targetIndex = obj.data().targetindex;
		console.log(targetIndex);
		location.href=targetIndex;
		break;

		case 'save_edit_photo':
			TryEditPhoto(obj, targetcallback);
		break;

		case 'save_edit_profile':
			TryEditProfile(obj, targetcallback);
		break;

		case 'reopen-prev':
			ReopenEditProfile(obj);
		break;

		case 'send-passw-check':
			switch (targetindex) {
				case 'save-profile-info':
					TrySendProfEdit(obj, publicProfEdit);
				break;
				case 'open-new-passw':
					TrySendProfEdit(obj, publicProfEdit);
				break;
			}
		break;

		case 'send-passw-new':
			TrySendPassEdit(obj);
		break;

		case 'delete-addr':
			var trObj = obj.parents('tr');
			var trOpacity = trObj.hasClass('op-25');
			if (trOpacity) {
				RestoreAddr(obj);
			} else {
				DeleteAddr(obj);
			}
		break;

		case "goSendOrder":
			goSendOrder(obj);
		break;

		default:
			console.log('nothing');
			return false;
		break;
	}

	return 0;
}

function goSendOrder(obj)
{
	console.log('send order');
	var order = {};
	order.uid = $('button.login.logged').data('targetindex');
	order.name = $('#order-name').val();
	order.phone = $('#order-phone').val();
	order.addr = $('#order-address').val();
	order.comm = $('#order-comment').val();
	order.cash = $('#order-cash').prop('checked');
	order.payonline = $('#order-cardonline').prop('checked');
	order.prods = cartList;
	if ((order.name=="")||(order.phone=="")||(order.addr=="")) {
		console.error('No');
		showModalOrderError("Пожалуйста, заполните контактные данные.");
		return false;
	}
	if (!$('#order-phone').inputmask("isComplete")) {
		console.error('No');
		showModalOrderError("Пожалуйста, укажите номер телефона.");
		return false;
	}
	if (order.prods.count == 0) {
		console.error('No');
		showModalOrderError("Нет товаров в заказе. Корзина пуста.");
		return false;
	}
	if (order.uid) {
		order.logged = true;
	} else
		order.logged = false;
	console.info(order);
	orderJson = JSON.stringify(order);
	$.ajax({
		url: '/user/cart/sendOrder',
		type: 'POST',
		data: {order: orderJson},
	})
	.done(function(res) {
		console.log("success");
		console.log(res);
		showModalAfterSended();
	})
	.fail(function(res) {
		console.error(res);
	})
	.always(function() {
		console.log("complete");
	});

}

function showModalAfterSended()
{
	var modal = $('.modal-order-ok');
	modal.modal();
	cartList.clearCart();
}

function showModalOrderError(text)
{
	var modal = $('.modal-order-error');
	if (text) {
		modal.find('.nothing').text(text);
	}
	modal.modal();
}

function RestoreAddr(obj)
{
	console.log('RestoreAddr()');
	var addrObj = obj.parents('tr');
	addrObj.removeClass('op-25');
	obj.text('Удалить');
	return 0;
}

function DeleteAddr(obj)
{
	console.log('DeleteAddr()');
	var addrObj = obj.parents('tr');
	addrObj.addClass('op-25');
	obj.text('Вернуть');
	return 0;
}

function TrySendPassEdit(obj)
{
	var btn = obj;
	var btnDissmiss = btn.parents('.modal-footer').find('.dismiss-button');
	DisableBtn(btn);
	DisableBtn(btnDissmiss);
	var modal = btn.parents('.modal-content');
	var form = modal.find('form');
	var passInput = form.find('input#passw-input');
	var passInputNew = form.find('input#passw-input-new');
	$.when(ValidatePass(form,passInput)).done(function(res){
		if (res=='ok') {
			console.info('OK!');
			$.when(ValidateLength(passInputNew, 6)).done(function(res){
					console.log('ok');
					unDisableBtn(btn);
					unDisableBtn(btnDissmiss);
					$.when(SendNewPass(passInput,passInputNew)).done(function(res){
						console.log(res);
						ShowGood(btn, "Сохраняем");
					}).fail(function(errors){
						console.error('SendNewPass() went wrong');
					});
				}).fail(function(errors){
					console.error('FAIL!');
					unDisableBtn(btn);
					unDisableBtn(btnDissmiss);
					ShowErrors(form, errors);
					return false;
				});
		} else {
			console.info('WRONG!');
		}
		return true;
	}).fail(function(errors){
		console.error('FAIL!');
		unDisableBtn(btn);
		unDisableBtn(btnDissmiss);
		ShowErrors(form, errors);
		return false;
	});
}

function SendNewPass(oldp, newp)
{
	var def = new $.Deferred();
	var oldp = oldp.val();
	var newp = newp.val();
	var data = {};
	data.oldp = oldp;
	data.newp = newp;
	data = JSON.stringify(data);

	$.ajax({
		url: '/user/newpass',
		type: 'POST',
		data: {target: 'newpass', data: data},
	})
	.done(function(res) {
		console.log(res);
		if (res == "true") {
			def.resolve(res);
		} else {
			def.reject(res);
		}
	})
	.fail(function(error) {
		console.log(error);
		def.reject(error);
	})
	.always(function() {
		console.log("complete");
	});
return def.promise();

}

function ValidateLength(inp, l)
{
	var def = new $.Deferred();
	var errors = []; // тут будем хранить объекты ошибок
	var inpObj ={}; // тут данные с формы

	inpObj.val = inp.val();
	inpObj.name = inp.attr('name');

	var error = {}; // объект ошибки

	if (inpObj.val.length < l) {
		error.name = inpObj.name;
		error.msg = 'Не менее '+l+' символов';
		errors.push(error);
		error = {}; // обнуляем объект ошибки
		def.reject(errors);
	} else
	{
		def.resolve();
	}
return def.promise();
}

function DisableBtn(btnObj)
{
	btnObj.attr('disabled', 'disabled');
}
function unDisableBtn(btnObj)
{
	btnObj.removeAttr('disabled');
}

function TrySendProfEdit(obj, publicProfEdit)
{
	var btn = obj;
	var btnDissmiss = btn.parents('.modal-footer').find('.dismiss-button');
	DisableBtn(btn);
	DisableBtn(btnDissmiss);
	var modal = btn.parents('.modal-content');
	var form = modal.find('form');
	var passInput = form.find('input#passw-input');
	$.when(ValidatePass(form,passInput)).done(function(res){
		if (res=='ok') {
			console.info('OK!');
			$.when(SendNewProf(publicProfEdit)).done(function(res){
				console.info(res);
				unDisableBtn(btn);
				unDisableBtn(btnDissmiss);
				ShowGood(btn, "Сохраняем");
			});
		} else {
			console.info('WRONG!');
		}
		return true;
	}).fail(function(errors){
		console.error('FAIL!');
		unDisableBtn(btn);
		unDisableBtn(btnDissmiss);
		ShowErrors(form, errors);
		return false;
	});

}

function SendNewProf(data)
{
	var def = new $.Deferred();
	if (!data || data == "") {
		def.reject();
	}
	var dataJson = JSON.stringify(data);
	$.ajax({
			url: '/user/upduser',
			type: 'POST',
			data: {target: 'upduser', data: dataJson},
		})
		.done(function(res) {
			console.log("SendNewProf() success");
			def.resolve(res);
		})
		.fail(function(err) {
			console.log("SendNewProf() error");
			def.reject(err);
		})
		.always(function() {
			console.log("SendNewProf() complete");
		});
	return def.promise();
}

function ValidatePass(form, pass)
{
	var def = new $.Deferred();
	var inputs = form.find('.form-group');
	inputs.each(function(index, el) {
		$(el).removeClass('has-error');
		$(el).find('.substring.red').remove();
	});
	// var emailReg = /.+@.+\..+/i; // регулярка для проверки почты
	var errors = []; // тут будем хранить объекты ошибок
	var passobj ={}; // тут данные с формы

	var passInput = form.find('input[name=pass]');

	passobj.password = passInput.val();

	var error = {}; // объект ошибки

	if (passobj.password.length <6) {
		error.name = 'pass';
		error.msg = 'Не менее 6 символов';
		errors.push(error);
		error = {}; // обнуляем объект ошибки
		def.reject(errors);
	} else
	$.when(CheckPass(passobj.password)).done(function(res){
		if (res =="false") {
			error.name = 'pass';
			error.msg = 'Неверный пароль';
			errors.push(error);
			error = {}; // обнуляем объект ошибки
			def.reject(errors);
		} else {
			def.resolve('ok');
		}
	});

return def.promise();

}

CheckPass = function (pass)
{
	var def = new $.Deferred();
	if (!pass || pass == "") {
		def.reject();
	}
	var data = pass;
	$.ajax({
			url: '/user/checkpass',
			type: 'POST',
			data: {target: 'checkpass', data: data},
		})
		.done(function(res) {
			console.log("CheckPass() success");
			if (publicProfEdit==null) {

			} else {
				publicProfEdit.p = data;
			}
			def.resolve(res);
		})
		.fail(function(err) {
			console.log("CheckPass() error");
			def.reject(err);
		})
		.always(function() {
			console.log("CheckPass() complete");
		});
	return def.promise();

}

function ReopenEditProfile(obj)
{
	var btn = obj;
	var modal = obj.parents('.modal');
	modal.modal('hide');
	modal.on('hidden.bs.modal', function(event) {
		var reopenModal = $('.modal-edit_profile');
		reopenModal.modal('show');
		modal.off('hidden.bs.modal');
	});
}

function TryEditPhoto(obj,callback)
{
	if (typeof(callback)==="undefined") {
		callback = null;
	}
	var btn = obj;
	var modal = obj.parents('.modal');
	var form = obj.parents('.modal-content').find('#upload_form');
	form.submit();
}

function TryEditProfile(obj,callback)
{
	if (typeof(callback)==="undefined") {
		callback = null;
	}
	var btn = obj;
	var modal = obj.parents('.modal');
	var form = obj.parents('.modal-content').find('#profile-form-edit');
	var profileEdit = {};
	profileEdit.name = form.find('input#profile-name').val();
	profileEdit.email = form.find('input#profile-email').val();
	profileEdit.phone = form.find('input#profile-phone').val();
	profileEdit.bd = form.find('input#profile-bd').val();
	modal.modal('hide');
	modal.on('hidden.bs.modal', function (e) {
		var passwModal = $('.modal-passw_check');
		publicProfEdit = profileEdit;
		passwModal.modal('show');
		PreventEnter(passwModal,"#passw-input","send-passw-check");
		passwModal.find('.dismiss-button').attr('data-target', callback);
		modal.off('hidden.bs.modal');
	})
}

function PreventPasswEnter(modal,input_id, btn_data_target)
{
	console.log('PreventEnter(modal);');
	var form = modal.find('form');
	var passInput = form.find('input'+input_id);
	passInput.on('keypress', function(event) {
		console.log('keypress');
		if (event.keyCode == 13){
			event.preventDefault();
			modal.find('button[data-target='+btn_data_target+']').click();
		}
	});
}

function GoCallback(callTo, obj)
{
	if (obj === undefined) {
		obj = null;
	}
	console.info('GoCallback()');
	// console.log(callTo.name);
	// console.log(callTo.phone);
	if (obj) {
		var form = obj.parents('.modal-content').find('form');
		console.log(form);
		if (CallbackValidate(form, callTo)) {
			obj.parents('.modal-content').find('.modal-footer').css('display', 'none');
			var modalObj = obj.parents('.modal-callback');
			var tempBody = obj.parents('.modal-content').find('.modal-body').html();
			obj.parents('.modal-content').find('.modal-body').html('<div class="mb-20" style="text-align:center; font-size:16px">Спасибо!<br>Мы перезвоним Вам в течение 15 минут.</div>');
			SendRecall(callTo);
			setTimeout(function(){
				modalObj.modal('hide');
				setTimeout(function(){
					obj.parents('.modal-content').find('.modal-footer').removeAttr('style');
					obj.parents('.modal-content').find('.modal-body').html(tempBody);
					InitInputMask($("#callback-phone"));
				},500);
			}, 3000);
		}
	}
}

function SendRecall(data)
{
	if (data===undefined) {
		data = null;
	}

	if (data) {
		data = JSON.stringify(data);
		$.ajax({
			url: '/user/recall',
			type: 'POST',
			data: {target: 'recall', data: data},
		})
		.done(function(res) {
			console.log("SendRecall(data) success");
			console.log(res);
		})
		.fail(function(err) {
			console.log("SendRecall(data) error");
			console.error(err);
		})
		.always(function() {
			console.log("SendRecall(data) complete");
		});

	} else {
		return false;
	}

}

function CallbackValidate(form, data)
{
	if (data===undefined) {
		data = null;
	}
	var inputs = form.find('.form-group');
	inputs.each(function(index, el) {
		$(el).removeClass('has-error');
		$(el).find('.substring.red').remove();
	});
	// var emailReg = /.+@.+\..+/i; // регулярка для проверки почты
	var errors = []; // тут будем хранить объекты ошибок
	var callback ={}; // тут данные с формы

	var phoneInput = form.find('input[name=phone]');

	if (data) {
		callback.name = data.name;
		callback.phone = data.phone;
	} else {
		callback.name = escapeHtml(form.find('input#callback-name').val());
		callback.phone = escapeHtml(form.find('input#callback-phone').val());
	}

	var error = {}; // объект ошибки

	if (callback.name == "" || !callback.name) {
		error.name = 'name';
		error.msg = 'Введите имя';
		errors.push(error);
		error = {}; // обнуляем объект ошибки
	}

	if (callback.phone == "" || !callback.phone) {
		error.name = 'phone';
		error.msg = 'Введите номер телефона';
		errors.push(error);
		error = {}; // обнуляем объект ошибки
	} else {
		if (!phoneInput.inputmask("isComplete")) {
			error.name = 'phone';
			error.msg = 'Введите корректный номер телефона';
			errors.push(error);
			error = {}; // обнуляем объект ошибки
		}
	}

	if (errors.length != 0) {
		ShowErrors(form, errors);
		return false;
	}

	return true;
}

function GoToProfile(uid)
{
	location.href = '/user/profile/'+uid;
}

function TryLogin(form)
{
	if (loginValidate(form)) {
		var login ={};
		login.email = escapeHtml(form.find('input#login-email').val());
		login.password = escapeHtml(form.find('input#login-passw').val());
		var jsonLogin = JSON.stringify(login);
		console.warn(jsonLogin);
		$.ajax({
			url: '/user/login',
			type: 'POST',
			data: {target: 'login', jsonLogin: jsonLogin},
		})
		.done(function(res) {
			if ( res && res!='false') {
				console.warn("TryLogin() done");
				console.info(res);
				$res = JSON.parse(res);
				$res ? console.info($res) : console.info(res);
				ShowGood(form.parents('.modal-content').find('button[data-target=login]'),"Входим");
			} else {
				console.error("TryLogin() response FALSE");
				console.error("jsonLogin: "+jsonLogin);
				console.error(res);
				var err = [{'name':"email",'msg':"Неверные данные"},{'name':"password",'msg':"Неверные данные"}];
				ShowErrors(form,err);
			}
		})
		.fail(function(res) {
			console.error("TryLogin() fail");
			console.error("jsonLogin: "+jsonLogin);
			console.error(res);
		})
		.always(function(res) {
			console.info("TryLogin() complete");
			// console.info(res);
		});

	}
}

function loginValidate(form)
{
	var inputs = form.find('.form-group');
	inputs.each(function(index, el) {
		$(el).removeClass('has-error');
		$(el).find('.substring.red').remove();
	});
	var emailReg = /.+@.+\..+/i; // регулярка для проверки почты
	var errors = []; // тут будем хранить объекты ошибок
	var login ={}; // тут данные с формы
	login.email = escapeHtml(form.find('input#login-email').val());
	login.password = escapeHtml(form.find('input#login-passw').val());


	var error = {}; // объект ошибки

	if (login.email == "" || !login.email) {
		error.name = 'email';
		error.msg = 'Введите email';
		errors.push(error);
		error = {}; // обнуляем объект ошибки
	} else {
		if ( !emailReg.test(login.email)) {
			error.name = 'email';
			error.msg = 'Введите корректный email';
			errors.push(error);
			error = {}; // обнуляем объект ошибки
		}
	}

	if (login.password == "" || !login.password) {
		error.name = 'password';
		error.msg = 'Введите пароль';
		errors.push(error);
		error = {}; // обнуляем объект ошибки
	} else {
		if (login.password.length < 6) {
			error.name = 'password';
			error.msg = 'Минимум 6 символов';
			errors.push(error);
			error = {}; // обнуляем объект ошибки
		}
	}

	if (errors.length != 0) {
		ShowErrors(form, errors);
		return false;
	}

	return true;
}


function TryRegistration(form)
{
	if (registrationValidate(form)) {
		var login ={};
		login.email = escapeHtml(form.find('input#reg-email').val());
		login.name = escapeHtml(form.find('input#reg-name').val());
		var jsonLogin = JSON.stringify(login);
		$.ajax({
			url: '/user/reg',
			type: 'POST',
			data: {target: 'registration', jsonLogin: jsonLogin},
		})
		.done(function(res) {
			if ( res && res!='false') {
				console.warn("TryRegistration() done");
				$res = JSON.parse(res);
				$res ? console.info($res) : console.info(res);
				ShowGood(form.parents('.modal-content').find('button[data-target=registration]'),"Входим");
			} else {
				console.error("TryRegistration() response FALSE");
				console.error("jsonLogin: "+jsonLogin);
				console.error(res);
				var err = [{'name':"email",'msg':"Неверные данные"},{'name':"name",'msg':"Неверные данные"}];
				ShowErrors(form,err);
			}
		})
		.fail(function(res) {
			console.error("TryRegistration() fail");
			console.error("jsonLogin: "+jsonLogin);
			console.error(res);
		})
		.always(function(res) {
			console.info("TryRegistration() complete");
			// console.info(res);
		});

	}
}

function registrationValidate(form)
{
	var inputs = form.find('.form-group');
	inputs.each(function(index, el) {
		$(el).removeClass('has-error');
		$(el).find('.substring.red').remove();
	});
	var emailReg = /.+@.+\..+/i; // регулярка для проверки почты
	var errors = []; // тут будем хранить объекты ошибок
	var login ={}; // тут данные с формы
	login.name = escapeHtml(form.find('input#reg-name').val());
	login.email = escapeHtml(form.find('input#reg-email').val());


	var error = {}; // объект ошибки

	if (login.email == "" || !login.email) {
		error.name = 'email';
		error.msg = 'Введите email';
		errors.push(error);
		error = {}; // обнуляем объект ошибки
	} else {
		if ( !emailReg.test(login.email)) {
			error.name = 'email';
			error.msg = 'Введите корректный email';
			errors.push(error);
			error = {}; // обнуляем объект ошибки
		}
	}

	if (login.name == "" || !login.name) {
		error.name = 'name';
		error.msg = 'Введите имя';
		errors.push(error);
		error = {}; // обнуляем объект ошибки
	} else {
		if (login.name.length < 2) {
			error.name = 'name';
			error.msg = 'Минимум 2 буквы';
			errors.push(error);
			error = {}; // обнуляем объект ошибки
		}
	}

	if (errors.length != 0) {
		ShowErrors(form, errors);
		return false;
	}

	return true;
}

function ShowErrors(f, err)
{
	var form = f;
	var errors = err;
	for (var i = 0; i < errors.length; i++) {
		console.warn(errors[i].msg);
		var form_group = form.find('input[name='+errors[i].name+']').parents('.form-group');
		var input = form.find('input[name='+errors[i].name+']');
		form_group.addClass('has-error');
		input.after('<span class="substring red">'+errors[i].msg+'</span>')
	}
	return false;
}

function ShowGood(btn,str)
{
	var b = btn;
	b.css('background-color', '#0dc95c');
	b.text(str);
	location.reload();
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
	var modalFooter = modalContent.children('.modal-footer');
	var btns = modalFooter.find('button');

	if ((index == "passw_new")) {
		PreventPasswEnter(modalBlock, "#passw-input-new", "send-passw-new");
	}


	if ((index == "cart") && (cartList.count > 0)) {
		btns.each(function(index, el) {
			$(el).removeAttr('disabled');
		});
		var cartBody = modalBody.find('.cart-body');
		cartBody.html("<div class='table-responsive'><table class='table table-hover'><thead></thead><tbody></tbody></table></div>");
		cartBody.find('.table thead').append("<tr><th>Название</th><th class='hidden-xxs'>Цена</th><th class='hidden-xxs'>Шт.</th><th>Итог</th></tr>");
		for (var i in cartList.items) {
			cartBody.find('.table tbody').append("<tr><td class='cart-prodname'><span class='mini-counter visible-xxs'>"+cartList.items[i].count+"x</span><a href="+ cartList.items[i].url +" title='"+cartList.items[i].name+"' rel='nofollow'>"+cartList.items[i].name+"</a></td><td class='cart-prodprice hidden-xxs'>"+cartList.items[i].price+" руб</td><td class='cart-prodcount hidden-xxs'>"+cartList.items[i].count+"</td><td class='cart-prodprice res'>" + (cartList.items[i].count*cartList.items[i].price) + " руб</td></tr>");
		}
		cartBody.find('.table tbody').append('<tr class="active"><td class="sum-price">Товаров на сумму </td><td class="hidden-xxs"></td><td class="hidden-xxs"></td><td class="cart-prodprice res">'+ cartList.sumPrice +' руб</td></tr>');
	} else
	if ((index == "cart") && (cartList.count == 0)) {
		btns.each(function(index, el) {
			$(el).attr('disabled', 'disabled');
		});
		var cartBody = modalBody.find('.cart-body');
		cartBody.html("<div class='nothing'>Тут пока ничего нет.</div><div class='nothing'>Посмотрите наш <a href='/catalog' rel='nofollow'>каталог</a>.</div>");
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
function InitInputMask(phoneInput)
{
	//var phoneInput = $("#callback-phone");
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


function InitInputDatepicker(dateInput)
{
	if (dateInput.length > 0) {
		dateInput.dateDropdowns({
			dayLabel: "День",
			monthLabel: "Месяц",
			yearLabel: "Год",
			daySuffixes: false,
			minYear: 1917,
			monthLongValues: ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'],
		});
	}
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
	modalHeader.on('click', 'h4.disable', function(event) {
		event.preventDefault();
		for (var i = 0; i < modalTabsCount; i++) {
			curTab = modalTabs.eq(i);
			curData = curTab.data('target');
			curTab.toggleClass('disable');
			$('.modal-body.'+curData+'-body').toggleClass('inactive');
			$('.modal-footer.'+curData+'-footer').toggleClass('inactive');
		}
	});

	return 0;
}

function SetActiveToCart($btn, $issession)
{
	if ($issession === undefined) {
		$issession = false;
	}
	var cardsArt = $btn.parents('.prod-card').data('art');
	var cardsArray = $('.prod-card[data-art='+cardsArt+']');
	cardsArray.each(function(index, el) {
		var $btn = $(el).find('.to-cart');
		var $art = $(el).data('art');
		if ( !$btn.hasClass('active') ) {
			$btn.addClass('active');
			$btn.attr({
				'data-toggle': 'tooltip',
				'data-placement': 'top',
				'title': 'В корзине'
			});
			$btn.html('');
			$incs = "<div class='incs'><div class='minus' data-toggle='tooltip' data-placement='top' title='Уменьшить'>-</div><input type='tel' value='1'><div class='plus' data-toggle='tooltip' data-placement='top' title='Добавить'>+</div></div>";
			$btn.after($incs);

			$prodBtnBlock = $btn.parents('.prod-btn-block');
			$prodBtnBlock.addClass('active');

			var inpt = $prodBtnBlock.find('input');
			inpt.inputmask('9{1,4}',{ "placeholder": "-" });
			if ($issession) inpt.val(cartList.items[$art].count);
			if (!$issession) SendCountItem(inpt);

			var card = (inpt.parents('.prod-card').length > 0) ? inpt.parents('.prod-card') : inpt.parents('.prod-day');
			card.find('.prod-avail').addClass('disabled');
			card.find('.prod-rev').addClass('disabled');

			InitTooltips();
		}

	});
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
	if ($(this).hasClass('active')) {
		$('button.cart').click();
	} else
	SetActiveToCart( $(this) );
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
	$(document).on('keyup change', '.prod-btn-block input', function(event) {
		event.preventDefault();
		if ($(this).val() != $(this).data('oldVal')) {
		if ((parseInt($(this).val()) <= 0) || ($(this).val() == "" ) || ($(this).val() == "-" )) {
				ResetToCart($(this)); // если пусто - reset
			} else {
				SendCountItem($(this)); // если не пусто - обновляем счетчик товара в корзине
			}
		$(this).data('oldVal', $(this).val());
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
	$(document).on('click', '.incs .plus', function(event) {
		event.preventDefault();
		var inpt = $(this).parent().find('input');
		var counts = parseInt(inpt.val())+1;
		var cardsArt = inpt.parents('.prod-card').data('art');
		var cardsArray = $('.prod-card[data-art='+cardsArt+']');
		cardsArray.each(function(index, el) {
			var inpt = $(el).find('.incs input');
			inpt.val(counts);
		});
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
	$(document).on('click', '.incs .minus', function(event) {
		event.preventDefault();
		var inpt = $(this).parent().find('input');
		var counts = parseInt(inpt.val())-1;
		var cardsArt = inpt.parents('.prod-card').data('art');
		var cardsArray = $('.prod-card[data-art='+cardsArt+']');
		if (counts == 0) {
			ResetToCart(inpt);
		} else {
			cardsArray.each(function(index, el) {
				var inpt = $(el).find('.incs input');
				inpt.val(counts);
			});
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
	var cardsArt = inpt.parents('.prod-card').data('art');
	var cardsArray = $('.prod-card[data-art='+cardsArt+']');
	cardsArray.each(function(index, el) {
		console.log('index: '+index);
		inpt = $(el).find('.incs input');
		var card = (inpt.parents('.prod-card').length > 0) ? inpt.parents('.prod-card') : inpt.parents('.prod-day');
		card.find('.minus').tooltip('destroy');
		card.find('.plus').tooltip('destroy');
		card.find('.incs').remove();
		toCart = card.find('.to-cart');
		toCart.tooltip('destroy');
		toCart.removeClass('active').html('Купить');
		card.find('.prod-avail').removeClass('disabled');
		card.find('.prod-rev').removeClass('disabled');

		var prodItem = {};
		prodItem.count = parseInt(inpt.val());
		prodItem.art = card.data('art');

		if (index == 0) cartList.deleteItem(prodItem);
	});

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
	var prodName = inpt.parents('.prod-card').find('[data-prodname=true]').text();
	if (!prodName || prodName=="") {
		console.info(prodName);
		prodName = $(document).find('.title-wide[data-prodname=true]').text();
	}
	prodItem.price = parseInt(prodPrice);
	prodItem.count = parseInt(inpt.val());
	prodItem.art = card.data('art');
	prodItem.name = $.trim(prodName);

	cartList.addItem(prodItem);

	UpdatePageCards();

}


/*
* UpdateCart()
* Изменение плавающей кнопки корзины
* @return 0
*/
function UpdateCart($issession)
{
	if ($issession === undefined) {
		$issession = false;
	}
	var cartBtn = $('button.cart');
	var cartCounter = cartBtn.find('.counter');
	var cartPrice = cartBtn.find('.price-counter .num');

	cartBtn.addClass('blink');
	var timing = 400;
	setTimeout(function(){
		cartBtn.removeClass('blink');
	}, timing);

	var jsonCart = JSON.stringify(cartList);

	if (!$issession) {
		$.ajax({
			url: '/cart',
			type: 'POST',
			data: {target: 'updCart', jsonCart: jsonCart},
		})
		.done(function(res) {
			savedList = JSON.parse(res);
			cartList.items = savedList.items == false ? {} : savedList.items;
		})
		.fail(function() {
			console.log("UpdateCart() error");
		})
		.always(function(){
			console.log("UpdateCart() done");
		});
	}

	cartCounter.text(cartList.count);
	cartPrice.text(cartList.sumPrice);
	UpdateCartView();
}


/*
* UpdateCartView()
* обновление вида страницы корзины после изменения кол-ва товара
* @return 0
*/
function UpdateCartView()
{
	var cartItems = $('.prod-card.cart-item');
	for (var i = 0; i < cartItems.length; i++) {
		var cartItem = cartItems.eq(i);
		var cartArt = cartItem.attr('data-art');
		if (typeof(cartList.items[cartArt])=='undefined') {
			cartItem.find('.incs').css('visibility','invisible');
			cartItem.find('.sum-number-price').text('0');
			cartItem.css('opacity', '.3');
		} else {
			var tempPrice = cartList.items[cartArt].count * cartList.items[cartArt].price;
			var sumPrice = cartItem.find('.sum-number-price');
			sumPrice.text(tempPrice);
		}
	}
	return 0;
}


/*
* CheckSessionCart()
* Проверка наличия товаров в сессии и изменение вывода в этой связи
* @return 0
*/
function CheckSessionCart()
{
	$.ajax({
		url: '/cart',
		type: 'POST',
		data: {target: 'checkCart'},
	})
	.done(function(res) {
		if (res === 'false' || !res || res == '') {
			console.warn('SessionCartStorage response: '+res);
		} else {
			console.warn(res);
			UpdateSessionCart(res);
		}
	})
	.fail(function() {
		console.log("CheckSessionCart() error");
	})
	.always(function() {
		console.log("CheckSessionCart() complete");
	});

}

function UpdateSessionCart(res)
{
	console.info('UpdateSessionCart(res)');
	console.info(res);
	var Result = {};
	Result = JSON.parse(res);
	cartList.count = Result.count;
	cartList.items = Result.items == false? {} : Result.items;
	cartList.sumPrice = Result.sumPrice;
	UpdateCart(true);
	UpdatePageCards();
}

function UpdatePageCards()
{
	var sumPriceLineNum = $('.sum-number-price.all');
	sumPriceLineNum.text(cartList.sumPrice);
	ReloadPageCart();

	console.info('UpdatePageCards()');
	var Cards = {};
	Cards.items = cartList.items;
	Cards.count = cartList.count;
	Cards.dom = {};
	for (var key in Cards.items) {
		console.log(key);
		var domElem = $('.prod-card[data-art='+key+']');
		var btnDom = domElem.find('.to-cart');
		SetActiveToCart(btnDom, true);
	};
}

function ReloadPageCart()
{
	var jsonCart = JSON.stringify(cartList);
	$.ajax({
		url: '/cart',
		type: 'POST',
		data: {target: 'updCartCards', jsonCart: jsonCart},
	})
	.done(function(res) {
		$('.cart-cards-reload').html(res);
		console.log("ReloadPageCart() success");
	})
	.fail(function(err) {
		console.error(err);
		console.log("ReloadPageCart() error");
	})
	.always(function() {
		console.log("ReloadPageCart() complete");
	});

	return 0;
}

function escapeHtml(text) {
	var map = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#039;'
	};

	// return text;

	return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function InitCommTextarea(wrapper)
{
	var $textBox = wrapper.find('textarea'),
			$countSpan = $('#count_letters'),
			$countNum = $('#count_num'),
			$commBtn = $('.comm-send'),
			maxTextLength = $textBox.attr('maxlength');
	$textBox.keyup(function(event) {
		var val = $(this).val();
		if (val.length == 0) {
			$countSpan.removeClass('on');
		} else {
			wrapper.find('.woops').remove();
			$countSpan.hasClass('on') ? {} : $countSpan.addClass('on');
			$countNum.text((maxTextLength - val.length).toLocaleString());
			if (val.length < (maxTextLength/2)) {
				$countSpan.removeClass('red1').removeClass('red2').removeClass('red3').removeClass('red4').removeClass('red5').removeClass('red-alert');
			}
			if (val.length >= (maxTextLength/2)) {
				$countSpan.removeClass('red1').removeClass('red2').removeClass('red3').removeClass('red4').removeClass('red5').removeClass('red-alert');
				$countSpan.addClass('red1');
			}
			if (val.length >= (maxTextLength-2*maxTextLength/5)) {
				$countSpan.removeClass('red1').removeClass('red2').removeClass('red3').removeClass('red4').removeClass('red5').removeClass('red-alert');
				$countSpan.addClass('red2');
			}
			if (val.length >= (maxTextLength-3*maxTextLength/10)) {
				$countSpan.removeClass('red1').removeClass('red2').removeClass('red3').removeClass('red4').removeClass('red5').removeClass('red-alert');
				$countSpan.addClass('red3');
			}
			if (val.length >= (maxTextLength-maxTextLength/5)) {
				$countSpan.removeClass('red1').removeClass('red2').removeClass('red3').removeClass('red4').removeClass('red5').removeClass('red-alert');
				$countSpan.addClass('red4');
			}
			if (val.length >= (maxTextLength-maxTextLength/10)) {
				$countSpan.removeClass('red1').removeClass('red2').removeClass('red3').removeClass('red4').removeClass('red5').removeClass('red-alert');
				$countSpan.addClass('red5');
			}
			if (val.length == maxTextLength) {
				$countSpan.removeClass('red1').removeClass('red2').removeClass('red3').removeClass('red4').removeClass('red5').removeClass('red-alert');
				$countSpan.addClass('red-alert');
			}
		}
	});
	$commBtn.on('click', function(event) {
		event.preventDefault();
		if ($textBox.val().length > 0) {
			var comment = {
				text: $textBox.val(),
				target_type: $(this).data('type'),
				target_id: $(this).data('prodid')
			};
			console.log(comment);
			comment = JSON.stringify(comment);
			$.ajax({
					url: '/user/addcomment',
					type: 'POST',
					data: {comment: comment}
				})
				.done(function(res) {
					console.log("comment added");
					console.log(res);
					comment = $.parseJSON(comment);
					reloadComments($('.comments-box'), comment.target_type, $commBtn.data('prodid'));
				})
				.fail(function(err) {
					console.log("comment error");
					console.log(err);
				});
		} else {
			wrapper.find('span.note').append('<span class="red5 woops">Кажется, Вы забыли написать отзыв.</span>');
			return false;
		}
	});
}

function reloadComments(comWrapper, type, prodid)
{
				$.ajax({
					url: '/catalog/reloadcomments',
					type: 'POST',
					data: {type: type, prodid: prodid}
				})
				.done(function(res) {
					console.log("comments reloaded");
					comWrapper.html(res);
					$('.comment_count').text(comWrapper.find('#newCountReviews').val());
					clearTextarea($('.comm-form'));
				})
				.fail(function(err) {
					console.log("comments reload error");
					console.log(err);
				});
}

function clearTextarea(wrapper)
{
	var textBox = wrapper.find('textarea');
	textBox.val("");
	textBox.removeAttr('style');
	wrapper.removeClass('on');
	$('#count_letters').removeClass('on');
}