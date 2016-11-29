/*
* $ - Ready page function
*	Declarations and inits
*/

$(function() {
	InitOwlCarousel();
	$(window).resize(function(event) {
		InitOwlCarousel();
	});
	CatImageInit();
});


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

// посмотрим на ширину экрана, если маленькая - то загружаем мини-картинки
	deviceW = $(document).width();

// добавляем изображения
	for (var i = $('#sales-carousel .item').length - 1; i >= 0; i--) {
		curItem = $('#sales-carousel .item:eq('+i+') a');
		if (deviceW <= 600) {
			jpgName = "img/sales/" + curItem.data('saleid') + "-min.jpg";
		} else {
			jpgName = "img/sales/" + curItem.data('saleid') + ".jpg";
		}
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

// инициализируем слайдер новинок
	$(".prod-new").owlCarousel({
		navigation : true, // Show next and prev buttons
		navigationText: false,
		slideSpeed : 300,
		paginationSpeed : 400,
		pagination: false,
		items : 5,
		itemsCustom : false,
		itemsDesktop : [1200,4],
		itemsDesktopSmall : [960,3],
		itemsTablet: [768,3],
		itemsTabletSmall: [480,2],
		itemsMobile : [320,1],
		afterMove : function(elem){
					$('.prod-new .owl-prev').addClass('show');
				}
	});

// инициализируем слайдер популярных
	$(".prod-popular").owlCarousel({
		navigation : true, // Show next and prev buttons
		navigationText: false,
		slideSpeed : 300,
		paginationSpeed : 400,
		pagination: false,
		items : 5,
		itemsCustom : false,
		itemsDesktop : [1200,4],
		itemsDesktopSmall : [960,3],
		itemsTablet: [768,3],
		itemsTabletSmall: [480,2],
		itemsMobile : [320,1],
		afterMove : function(elem){
					$('.prod-popular .owl-prev').addClass('show');
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