$(function() {

	articlesScriptsInit();

	$('.pins-list .pin').dotdotdot({
		ellipsis:'...',
		watch		: true,
	});

	$('.iframe-btn').fancybox({
		'type':'iframe',
		'autoSize':true,
		'minWidth':900,
		'minHeight':500,
		'iframe': {
		scrolling : 'visible', // 'auto', 'yes', 'no' or 'visible'
		preload   : true
	},
	'title':'Смена картинки',
	helpers:  {
		title : {
						type : 'float' // 'float', 'inside', 'outside' or 'over'
					},
					overlay : {
						showEarly : true
					}
				},
				'wrapCSS':'fancy-wrap'
			});

});

function articlesEditInit($key)
{
	coverDelete();
	tinymceInitialization($key);
	btnsInit();
}

function btnsInit()
{
	lookNew();
	lookSave();
	lookAbort();
	lookDelete();
	lookArchive();
	lookNewArchive();
	lookUnArchive();
}

function lookNewArchive()
{
	$('.btn-archive-new').click(function(event) {
		$(this).addClass('active');
		$(this).text('Сохраняем');
		newArchiveBtnClick();
	});
}

function newArchiveBtnClick()
{
	btnSave = $('.btn-archive-new');
	mainContent = $('.main-content');
	tinyMCE.triggerSave();
	var $id = $('#post-id').val();
	var $url = $('#post-url').val();
	var $title = $('#post-title').val();
	var $poster = $('#cover-img').val();
	var $anons = $('#post-anons').val();
	var $text = $('#post-body').val();

	if (($url=="")||($title=="")||($poster=="")||($anons=="")||($text=="")) {
		$('.success-modal-md').on('show.bs.modal', function(event) {
			var modal = $(this);
			modal.find('.modal-title').text('Не получилось');
			modal.find('.modal-body .text').hide();
			modal.find('.modal-body .response').text('Не все поля заполнены!');
		});
		$('.success-modal-md').modal();
		btnSave.removeClass('active');
		btnSave.text('Сохранить в архив');
		return false;
	};

	var post = {
		id: $id,
		url: $url,
		title: $title,
		poster: $poster,
//		date: "",
anons: $anons,
text: $text,
archived: 1
};

var jsonPost = JSON.stringify(post);

mainContent.animate({opacity:'.3'}, 200);

$.ajax({
	url: '/admin/goods/save',
	type: 'POST',
	data: {jsonPost: jsonPost, action: 'new'},
})
.done(function(response) {
	console.log("success");
	console.log(response);
	$('.success-modal-md').on('show.bs.modal', function(event) {
		var modal = $(this);
		modal.find('.modal-title').text('Успешно');
		modal.find('.modal-body .text').hide();
		modal.find('.modal-body .response').text(response);
	});
	$('.success-modal-md').modal();
})
.fail(function(response) {
	console.log("error");
	$('.success-modal-md').on('show.bs.modal', function(event) {
		var modal = $(this);
		modal.find('.modal-title').text('Ошибка');
		modal.find('.modal-body .text').hide();
		modal.find('.modal-body .response').text(response);
	});
	$('.success-modal-md').modal();
})
.always(function() {
	console.log("ajax complete");
	btnSave.removeClass('active');
	btnSave.text('Сохранить в архив');
	$('.success-modal-md').on('hidden.bs.modal', function(event) {
		location.href="/admin/goods/edit/"+$url;
	});
});

}

function lookUnArchive()
{
	$('.btn-unarchive').click(function(event) {
		event.preventDefault();
		mainContent = $('.main-content');
		$post_title = $(this).data('target');
		$post_id = $(this).data('id');
		if (!$post_title) {
			$post_id = $('#post-id').val();
			$post_title = $('#post-title').val();
		};
		modalBox = $('.hide-modal-md');
		modalBox.on('show.bs.modal', function(event) {
			var modal = $(this);
			modal.find('.modal-title').text('Публикация товара');
			modal.find('.modal-body .text').hide();
			modal.find('.modal-body .response').html('Опубликовать товар "<strong>'+$post_title+'</strong>"?');
			modal.find('.modal-footer .go-archive').text("Опубликовать");
			modal.find('.modal-footer .go-archive').attr("data-id", $post_id);
		});
		modalBox.modal();

		var post = {
			id: $post_id,
			title: $post_title
		};

		jsonPost = JSON.stringify(post);
		console.log(jsonPost);
		modalBox.find('button.go-archive').click(function(event) {
			modalBox.modal('hide');
			mainContent.animate({opacity:'.3'}, 200);
			$.ajax({
				url: '/admin/goods/unarchive/'+$post_title,
				type: 'POST',
				data: {jsonPost: jsonPost},
			})
			.done(function(response) {
				console.log("success");
				console.log(response);
				$('.success-modal-md').on('show.bs.modal', function(event) {
					var modal = $(this);
					modal.find('.modal-title').text('Успешно');
					modal.find('.modal-body .text').hide();
					modal.find('.modal-body .response').text(response);
				});
				$('.success-modal-md').modal();
				$('.success-modal-md').on('hidden.bs.modal', function(event) {
					location.href="/admin/goods";
				});
			})
			.fail(function(response) {
				console.log("error");
				$('.success-modal-md').on('show.bs.modal', function(event) {
					var modal = $(this);
					modal.find('.modal-title').text('Ошибка');
					modal.find('.modal-body .text').hide();
					modal.find('.modal-body .response').text(response);
				});
				$('.success-modal-md').modal();
			})
			.always(function() {
				console.log("ajax complete");
			});

		});
	});
}

function lookArchive()
{
	$('.btn-archive').click(function(event) {
		event.preventDefault();
		mainContent = $('.main-content');
		$post_title = $(this).data('target');
		$post_id = $(this).data('id');
		if (!$post_title) {
			$post_id = $('#post-id').val();
			$post_title = $('#post-title').val();
		};
		modalBox = $('.hide-modal-md');
		modalBox.on('show.bs.modal', function(event) {
			var modal = $(this);
			modal.find('.modal-title').text('Скрыть товар');
			modal.find('.modal-body .text').hide();
			modal.find('.modal-body .response').html('Скрыть товар "<strong>'+$post_title+'</strong>"?');
			modal.find('.modal-footer .go-archive').attr("data-id", $post_id);
		});
		modalBox.modal();

		var post = {
			id: $post_id,
			title: $post_title
		};

		jsonPost = JSON.stringify(post);
		console.log(jsonPost);
		modalBox.find('button.go-archive').click(function(event) {
			modalBox.modal('hide');
			mainContent.animate({opacity:'.3'}, 200);
			$.ajax({
				url: '/admin/goods/archive/'+$post_title,
				type: 'POST',
				data: {jsonPost: jsonPost},
			})
			.done(function(response) {
				console.log("success");
				console.log(response);
				$('.success-modal-md').on('show.bs.modal', function(event) {
					var modal = $(this);
					modal.find('.modal-title').text('Успешно');
					modal.find('.modal-body .text').hide();
					modal.find('.modal-body .response').text(response);
				});
				$('.success-modal-md').modal();
				$('.success-modal-md').on('hidden.bs.modal', function(event) {
					location.href="/admin/goods";
				});
			})
			.fail(function(response) {
				console.log("error");
				$('.success-modal-md').on('show.bs.modal', function(event) {
					var modal = $(this);
					modal.find('.modal-title').text('Ошибка');
					modal.find('.modal-body .text').hide();
					modal.find('.modal-body .response').text(response);
				});
				$('.success-modal-md').modal();
			})
			.always(function() {
				console.log("ajax complete");
			});

		});
	});
}

function lookDelete()
{
	$('.btn-delete').click(function(event) {
		event.preventDefault();
		mainContent = $('.main-content');
		$post_title = $(this).data('target');
		$post_id = $(this).data('id');
		if (!$post_title) {
			$post_id = $('#post-id').val();
			$post_title = $('#post-title').val();
		};
		modalBox = $('.info-modal-md');
		modalBox.on('show.bs.modal', function(event) {
			var modal = $(this);
			modal.find('.modal-title').text('Удаление товара');
			modal.find('.modal-body .text').hide();
			modal.find('.modal-body .response').html('Удалить позицию "<strong>'+$post_title+'</strong>"?');
			modal.find('.modal-footer .go-delete').attr("data-id", $post_id);
		});
		modalBox.modal();

		var post = {
			id: $post_id,
			title: $post_title
		};

		jsonPost = JSON.stringify(post);
		console.log(jsonPost);
		modalBox.find('button.go-delete').click(function(event) {
			modalBox.modal('hide');
			mainContent.animate({opacity:'.3'}, 200);
			$.ajax({
				url: '/admin/goods/delete/'+$post_title,
				type: 'POST',
				data: {jsonPost: jsonPost},
			})
			.done(function(response) {
				console.log("success");
				console.log(response);
				$('.success-modal-md').on('show.bs.modal', function(event) {
					var modal = $(this);
					modal.find('.modal-title').text('Успешно');
					modal.find('.modal-body .text').hide();
					modal.find('.modal-body .response').text(response);
				});
				$('.success-modal-md').modal();
				$('.success-modal-md').on('hidden.bs.modal', function(event) {
					location.href="/admin/goods";
				});
			})
			.fail(function(response) {
				console.log("error");
				$('.success-modal-md').on('show.bs.modal', function(event) {
					var modal = $(this);
					modal.find('.modal-title').text('Ошибка');
					modal.find('.modal-body .text').hide();
					modal.find('.modal-body .response').text(response);
				});
				$('.success-modal-md').modal();
			})
			.always(function() {
				console.log("ajax complete");
			});

		});
	});
}

function lookNew()
{
	$('.new-post').click(function(event) {
		location.href="/admin/goods/new";
	});
}

function lookAbort()
{
	$('.post-abort').click(function(event) {
		location.href='/admin/goods';
	});
}

function lookSave()
{
	$('.post-save').click(function(event) {
		$(this).addClass('active');
		$(this).text('Сохраняем');
		if ($(this).hasClass('new')) {
			saveBtnClick('new');
		};
		if ($(this).hasClass('edit')) {
			saveBtnClick('edit');
		};
	});
}

function saveBtnClick($attr)
{
	btnSave = $('.post-save');
	mainContent = $('.main-content');
	tinyMCE.triggerSave();
	var $id = $('#post-id').val();
	var $art = $('#post-url').val();
	var $cat = $('#prod-cat').val();
	var $name = $('#post-title').val();
	var $tech_name = $('#post-tech_name').val();
	var $poster = $('#cover-img').val();
	var $mini_desc = $('#post-subtitle').val();
	var $text = $('#post-body').val();
	var $weight = $('#post-weight').val();
	var $country = $('#post-country').val();
	var $stor_cond = $('#post-stor_cond').val();
	var $nut_val = $('#post-nut_val').val();
	var $energy_val = $('#post-energy_val').val();
	var $consist = $('#post-consist').val();
	var $price = $('input#price').val();

	if (($art=="")||($name=="")||($poster=="")||($cat=="")||($text=="")||($price=="")) {
		$('.success-modal-md').on('show.bs.modal', function(event) {
			var modal = $(this);
			modal.find('.modal-title').text('Не получилось');
			modal.find('.modal-body .text').hide();
			modal.find('.modal-body .response').text('Не все поля заполнены!');
		});
		$('.success-modal-md').modal();
		btnSave.removeClass('active');
		btnSave.text('Сохранить');
		return false;
	};

	var post = {
		id: $id,
		art: $art,
		cat: $cat,
		name: $name,
		tech_name: $tech_name,
		images: $poster,
		mini_desc: $mini_desc,
		description: $text,
		weight: $weight,
		country: $country,
		stor_cond: $stor_cond,
		nut_val: $nut_val,
		energy_val: $energy_val,
		consist: $consist,
		price: $price
};

console.log(post);

var jsonPost = JSON.stringify(post);

mainContent.animate({opacity:'.3'}, 200);

$.ajax({
	url: '/admin/goods/save',
	type: 'POST',
	data: {jsonPost: jsonPost, action: $attr},
})
.done(function(response) {
	console.log("success");
	console.log(response);
	$('.success-modal-md').on('show.bs.modal', function(event) {
		var modal = $(this);
		modal.find('.modal-title').text('Успешно');
		modal.find('.modal-body .text').hide();
		modal.find('.modal-body .response').text(response);
	});
	$('.success-modal-md').modal();
})
.fail(function(response) {
	console.log("error");
	$('.success-modal-md').on('show.bs.modal', function(event) {
		var modal = $(this);
		modal.find('.modal-title').text('Ошибка');
		modal.find('.modal-body .text').hide();
		modal.find('.modal-body .response').text(response);
	});
	$('.success-modal-md').modal();
})
.always(function() {
	console.log("ajax complete");
	btnSave.removeClass('active');
	btnSave.text('Сохранить');
	$('.success-modal-md').on('hidden.bs.modal', function(event) {
		if ($attr=="edit") {
			mainContent.animate({opacity:1}, 200);
		} else if ($attr="new") {
			location.href="/admin/goods/edit/"+$art;
		};
	});
});

}

function responsive_filemanager_callback(field_id){
	$('#'+field_id).val('/upload/'+$('#'+field_id).val());
	var url=jQuery('#'+field_id).val();
	$("#img").attr('src', url).css('opacity', '1');;
}

function coverDelete()
{
	$('.cover .poster').click(function(event) {
		$(this).parent().find('a.upload').click();
	});
	var defaultUrl = "/img/prod-default-cover.jpg";
	$('.cover .controls .delete').click(function(event) {
		$("#img").attr('src', defaultUrl).css('opacity', '.5');
		$("#cover-img").val(defaultUrl);
	});
}

function articlesScriptsInit()
{
	pinClick();
	lookForUrl();
	lookUrlEdit();
	lookUrlEditOpacity();
}

function lookUrlEditOpacity()
{
	var re = $('.really-edit');
	var pu = $('#post-url');
	if (pu.val() != "") {
		re.addClass('on');
	} else {
		re.removeClass('on');
	}
	$('#post-title').keypress(function(event) {
		if (pu.val() != "") {
			re.addClass('on');
		} else {
			re.removeClass('on');
		}
	});
/*	pu.change(function(event) {
		if ($(this).val() != "") {
			re.addClass('on');
		} else {
			re.removeClass('on');
		}
	});*/
}

function lookUrlEdit()
{
	$('.really-edit').click(function(event) {
		$target = $('#'+($(this).parent().attr('for')) );
		if ($target.attr('disabled')) {
			$target.removeAttr('disabled').css('opacity', '1');
		};
	});
}

function lookForUrl()
{
	$('#post-title').keyup(function(event) {
		transliterate();
	});
}

function transliterate()
{
	var text=document.getElementById('post-title').value;
	var transl=new Array();
	transl['А']='a';			transl['а']='a';
	transl['Б']='b';			transl['б']='b';
	transl['В']='v';			transl['в']='v';
	transl['Г']='g';			transl['г']='g';
	transl['Д']='d';			transl['д']='d';
	transl['Е']='e';			transl['е']='e';
	transl['Ё']='yo';			transl['ё']='yo';
	transl['Ж']='zh';			transl['ж']='zh';
	transl['З']='z';			transl['з']='z';
	transl['И']='i';			transl['и']='i';
	transl['Й']='j';			transl['й']='j';
	transl['К']='k';			transl['к']='k';
	transl['Л']='l';			transl['л']='l';
	transl['М']='m';			transl['м']='m';
	transl['Н']='n';			transl['н']='n';
	transl['О']='o';			transl['о']='o';
	transl['П']='p';			transl['п']='p';
	transl['Р']='r';			transl['р']='r';
	transl['С']='s';			transl['с']='s';
	transl['Т']='t';			transl['т']='t';
	transl['У']='u';			transl['у']='u';
	transl['Ф']='f';			transl['ф']='f';
	transl['Х']='x';			transl['х']='x';
	transl['Ц']='c';			transl['ц']='c';
	transl['Ч']='ch';			transl['ч']='ch';
	transl['Ш']='sh';			transl['ш']='sh';
	transl['Щ']='shh';		transl['щ']='shh';
	transl['Ъ']='';			transl['ъ']='';
	transl['Ы']='y';		transl['ы']='y';
	transl['Ь']='';			transl['ь']='';
	transl['Э']='e';		transl['э']='e';
	transl['Ю']='yu';			transl['ю']='yu';
	transl['Я']='ya';			transl['я']='ya';
	transl[' ']='-';
	transl['"']='';
	transl["'"]='';
	transl["."]='';
	transl[","]='';
	transl["?"]='';
	transl["!"]='';
	transl["<"]='';
	transl[">"]='';
	transl["@"]='';
	transl["&"]='';
	transl["$"]='';
	transl["#"]='';
	transl["№"]='';
	transl["`"]='';
	transl[";"]='';
	transl[":"]='';
	transl["^"]='';
	transl["*"]='';
	transl["("]='-';
	transl[")"]='-';
	transl["["]='-';
	transl["]"]='-';
	transl["\\"]='_';
	transl["|"]='_';
	transl["/"]='_';

	var result='';
	for(i=0;i<text.length;i++) {
		if(transl[text[i]]!=undefined) { result+=transl[text[i]]; }
		else { result+=text[i]; }
	}
	res = result.substr(0,3);
	nowDate = new Date();
	rand = getRandom(1000,9999);
	timeStamp = "-" + nowDate.getDate() + "-" + (nowDate.getMonth()+1) + "-" + nowDate.getFullYear();
	document.getElementById('post-url').value=res+rand;
	document.getElementById('post-tech_name').value=result+"_"+rand;
}

function tinymceInitialization(key)
{
	tinymce.init({
		selector:'textarea#post-body',
		skin: "chokky",
		language: "ru",
		body_class: "post-container",
		plugins: [
		"advlist autosave autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
		"searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
		"save table contextmenu directionality emoticons template paste textcolor responsivefilemanager",
		"imagetools",
		"autoresize"
		],
		content_css: "/css/admin/tinymce.css",
		//content_css: "/css/public/dest.css",
		toolbar: "responsivefilemanager | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | forecolor backcolor | bullist numlist outdent indent | link media image | preview | code",
		imagetools_toolbar: 'rotateleft rotateright | flipv fliph | editimage imageoptions | responsivefilemanager ',
		autosave_ask_before_unload: true,
		image_advtab: true ,
		plugin_preview_width: "960",
		external_filemanager_path:"/js/responsive_filemanager/filemanager/",
		filemanager_title:"Filemanager" ,
		filemanager_access_key: key ,
		external_plugins: { "filemanager" : "/js/responsive_filemanager/filemanager/plugin.min.js"}
	});

	tinymce.init({
		selector:'textarea#post-anons',
		skin: "chokky",
		language: "ru",
		body_class: "post-container",
		plugins: [
		"advlist autosave autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
		"searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
		"save table contextmenu directionality emoticons template paste textcolor responsivefilemanager",
		"imagetools",
		"autoresize"
		],
		content_css: "/css/admin/tinymce.css",
		toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | preview | code",
		imagetools_toolbar: 'rotateleft rotateright | flipv fliph | editimage imageoptions | responsivefilemanager ',
		menubar: false,
		autosave_ask_before_unload: true,
		max_height: 150,
		height: 100,
		image_advtab: true ,
		plugin_preview_width: "960",
		external_filemanager_path:"/js/responsive_filemanager/filemanager/",
		filemanager_title:"Filemanager" ,
		filemanager_access_key: key ,
		external_plugins: { "filemanager" : "/js/responsive_filemanager/filemanager/plugin.min.js"}
	});
}

function pinClick()
{
	pin = $('.pin');
	pin.click(function(event) {
		$(this).parent().parent().find('span').html($(this).html()).parent().addClass('pinned');
	});

	unpin = $('.unpin');
	unpin.click(function(event) {
		spanUnpin = $(this).parent().find('span');
		liUnpin = $(this).parent();
		textUnpin = $(this).parent().find('span').attr('data-attr');
		spanUnpin.html(textUnpin);
		liUnpin.removeClass('pinned');
		return false;
	});
}

function getRandom(min, max)
{
	return Math.floor(Math.random() * (max - min + 1)) + min;
}