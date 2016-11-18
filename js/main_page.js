/*
* $ - Ready page function
*	Declarations and inits
*/

$(function() {
	VClickInit();
	BtnClickInit();
	InitOwlCarousel();
	$(window).resize(function(event) {
		InitOwlCarousel();
	});
	CatImageInit();
});


/*
* VClickInit()
* Initialisation click to the cover-image of video;
* @return 0
*/
function VClickInit() {
	$(document).on('click', '.video-plumb', function(event) {
		VClickHandler($(this));
	});
	return 0;
}


/*
* VClickHandler(obj)
* Handler click to the cover-image of video;
* @param obj
* @return 0
*/
function VClickHandler(obj) {
	var vStrBegin = '<iframe height="100%" width="100%" src="https://www.youtube.com/embed/';
	var vStrEnd = '?autoplay=1" frameborder="0" allowfullscreen></iframe>';
	var vStr = vStrBegin + obj.data().videoId + vStrEnd;
	var vContainer = obj.parent();
	vContainer.append(vStr);
	obj.remove();
	return 0;
}


/*
* BtnClickInit()
* Initialisation click to the button;
* @return 0
*/
function BtnClickInit() {
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
function BtnClickHandler(obj) {
	var target = obj.data().target;

	switch(target) {

		case 'preorder':
			ModalPreorderGo(obj);
			break;

		default:
			break;
	}

	return 0;
}


/*
* ModalPreorderGo(obj)
* Modal window for preorder
* @param obj
* @return 0
*/
function ModalPreorderGo(obj) {

	var target = obj.data().target;
	var mObj = $('#'+target);

	mObj.modal();

	console.log('Hello, modal!');

	return 0;
}

/*
* initOwlCarousel()
* Инициализация карусели для слайдера акции
* @return 0
*/
function InitOwlCarousel() {

	var autoPlaySpeed = 2000;

// пересчитываем высоту слайдера
	carouselW = $("#sales-carousel").width();
	countH = Math.floor(carouselW * 0.35);
	$('#sales-carousel .item a').height(countH);

// считаем отступ для стрелок слайдера
	arrowsTop = Math.floor((countH-100)/2);

// добавляем изображения
	for (var i = $('#sales-carousel .item').length - 1; i >= 0; i--) {
		curItem = $('#sales-carousel .item:eq('+i+') a');
		jpgName = "img/sales/" + curItem.data('saleid') + ".jpg";
		curItem.css({
			'background-image': 'url('+jpgName+')'
		});
	}

// инициализируем слайдер
	$("#sales-carousel").owlCarousel({
		navigation : true, // Show next and prev buttons
		navigationText: false,
		slideSpeed : 300,
		paginationSpeed : 400,
		singleItem : true,
		autoHeight : true,
		autoPlay : autoPlaySpeed,
	});

// инициализируем слайдер продукции
	$(".prod-line-outer").owlCarousel({
		navigation : true, // Show next and prev buttons
		navigationText: false,
		slideSpeed : 300,
		paginationSpeed : 400,
		pagination: false,
		items : 4,
		itemsCustom : false,
		itemsDesktop : [1200,4],
		itemsDesktopSmall : [960,3],
		itemsTablet: [768,3],
		itemsTabletSmall: false,
		itemsMobile : [320,2],
		afterMove : function(elem){
					$('.prod-line-outer .owl-prev').addClass('show');
				}
	});


// остановка карусели при ховере
	$('#sales-carousel').on('mouseover', function(event) {
		event.preventDefault();
		$('#sales-carousel').trigger('owl.stop');
	});

// запуск карусели после ховера
	$('#sales-carousel').on('mouseout', function(event) {
		event.preventDefault();
		$('#sales-carousel').trigger('owl.play', autoPlaySpeed);
	});

// ставим отступ для стрелок
	$('#sales-carousel .owl-prev').css('top', arrowsTop);
	$('#sales-carousel .owl-next').css('top', arrowsTop);

	return 0;
}

/*
* initOwlCarousel()
* Инициализация карусели для слайдера акции
* @return 0
*/
function InitOwlCarousel2() {

	var autoPlaySpeed = 2000;

// пересчитываем высоту слайдера
	carouselW = $("#sales-carousel").width();
	countH = Math.floor(carouselW * 0.35);
	$('#sales-carousel .item a').height(countH);

// считаем отступ для стрелок слайдера
	arrowsTop = Math.floor((countH-100)/2);

// добавляем изображения
	for (var i = $('#sales-carousel .item').length - 1; i >= 0; i--) {
		curItem = $('#sales-carousel .item:eq('+i+') a');
		jpgName = "img/sales/" + curItem.data('saleid') + ".jpg";
		curItem.css({
			'background-image': 'url('+jpgName+')'
		});
	}

// инициализируем слайдер
	$("#sales-carousel").owlCarousel({
		loop:true,
				nav:true,
				responsive:{
						0:{
								items:1
						},
						600:{
								items:1
						},
						1000:{
								items:1
						}
				}
	});

// остановка карусели при ховере
	$('#sales-carousel').on('mouseover', function(event) {
		event.preventDefault();
		$('#sales-carousel').trigger('owl.stop');
	});

// запуск карусели после ховера
	$('#sales-carousel').on('mouseout', function(event) {
		event.preventDefault();
		$('#sales-carousel').trigger('owl.play', autoPlaySpeed);
	});

// ставим отступ для стрелок
	$('.owl-prev').css('top', arrowsTop);
	$('.owl-next').css('top', arrowsTop);

// инициализируем слайдер продукции
/*	$(".prod-line-outer").owlCarousel({
		navigation : true, // Show next and prev buttons
		navigationText: false,
		slideSpeed : 300,
		paginationSpeed : 400,
		pagination: false,
		scrollPerPage: true,
	});*/

	prodOwl = $('.prod-line-outer');
	prodOwl.on('changed.owl.carousel', function(event) {
		console.log(event.item.index);
})

	return 0;
}

/*
* CatImageInit()
* Фон для категорий
* @return 0
*/
function CatImageInit(){
	catIds = $('.main-cat');
	for (var i = catIds.length - 1; i >= 0; i--) {
		var curCat = catIds.eq(i);
		var imageName = 'img/cat/'+curCat.data('catid')+'.jpg';
		curCat.css('background-image', 'url('+imageName+')');
	}
	return 0;
}