$(function() {

	articlesScriptsInit();
	// dateInit();

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
	'title':'Смена обложки поста',
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

function dateInit()
{
	var d = new Date();
	var curr_date = d.getDate();
	if (curr_date<10) {
		curr_date = '0'+curr_date;
	}
	var curr_month = d.getMonth() + 1;
	if (curr_month<10) {
		curr_month = '0'+curr_month;
	}
	var endM = new Date();
	endM.setMonth(endM.getMonth()+2);
	var end_month = endM.getMonth();
	if (end_month<10) {
		end_month = '0'+end_month;
	}
	console.log(end_month);
	var curr_year = d.getFullYear();
	var nowDayBox = $('.date-dropdowns.start_time');
	nowDayBox.find('select.day option[value='+curr_date+']').prop('selected', true);
	nowDayBox.find('select.month option[value='+curr_month+']').prop('selected', true);
	nowDayBox.find('select.year option[value='+curr_year+']').prop('selected', true);
	var endDayBox = $('.date-dropdowns.end_time');
	endDayBox.find('select.day option[value='+curr_date+']').prop('selected', true);
	endDayBox.find('select.month option[value='+end_month+']').prop('selected', true);
	endDayBox.find('select.year option[value='+curr_year+']').prop('selected', true);

}

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
		url: '/admin/articles/save',
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
				location.href="/admin/articles/edit/"+$url;
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
		modalBox = $('.archive-modal-md');
		modalBox.on('show.bs.modal', function(event) {
			var modal = $(this);
			  modal.find('.modal-title').text('Публикация поста из архива');
			  modal.find('.modal-body .text').hide();
			  modal.find('.modal-body .response').html('Опубликовать пост "<strong>'+$post_title+'</strong>"?');
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
				url: '/admin/articles/unarchive/'+$post_title,
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
					location.href="/admin/articles";
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
		modalBox = $('.archive-modal-md');
		modalBox.on('show.bs.modal', function(event) {
			var modal = $(this);
			  modal.find('.modal-title').text('Отправить пост в архив');
			  modal.find('.modal-body .text').hide();
			  modal.find('.modal-body .response').html('Архивировать пост "<strong>'+$post_title+'</strong>"?');
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
				url: '/admin/articles/archive/'+$post_title,
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
					location.href="/admin/articles";
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
			  modal.find('.modal-title').text('Удаление поста');
			  modal.find('.modal-body .text').hide();
			  modal.find('.modal-body .response').html('Удалить пост "<strong>'+$post_title+'</strong>"?');
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
				url: '/admin/articles/delete/'+$post_title,
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
					location.href="/admin/articles";
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
		location.href="/admin/articles/new";
	});
}

function lookAbort()
{
	$('.post-abort').click(function(event) {
		location.href='/admin/articles';
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
	var $url = $('#post-url').val();
	var $title = $('#post-title').val();
	var $subtitle = $('#post-subtitle').val();
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
		btnSave.text('Сохранить');
		return false;
	};

	var post = {
		id: $id,
		url: $url,
		title: $title,
		subtitle: $subtitle,
		poster: $poster,
//		date: "",
		anons: $anons,
		text: $text
//		tags: ""
	};

	var jsonPost = JSON.stringify(post);

	mainContent.animate({opacity:'.3'}, 200);

	$.ajax({
		url: '/admin/articles/save',
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
				location.href="/admin/articles/edit/"+$url;
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
	var defaultUrl = "/img/macfix-ultra-big-blur.jpg";
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
			transl['А']='A';			transl['а']='a';
			transl['Б']='B';			transl['б']='b';
			transl['В']='V';			transl['в']='v';
			transl['Г']='G';			transl['г']='g';
			transl['Д']='D';			transl['д']='d';
			transl['Е']='E';			transl['е']='e';
			transl['Ё']='Yo';			transl['ё']='yo';
			transl['Ж']='Zh';			transl['ж']='zh';
			transl['З']='Z';			transl['з']='z';
			transl['И']='I';			transl['и']='i';
			transl['Й']='J';			transl['й']='j';
			transl['К']='K';			transl['к']='k';
			transl['Л']='L';			transl['л']='l';
			transl['М']='M';			transl['м']='m';
			transl['Н']='N';			transl['н']='n';
			transl['О']='O';			transl['о']='o';
			transl['П']='P';			transl['п']='p';
			transl['Р']='R';			transl['р']='r';
			transl['С']='S';			transl['с']='s';
			transl['Т']='T';			transl['т']='t';
			transl['У']='U';			transl['у']='u';
			transl['Ф']='F';			transl['ф']='f';
			transl['Х']='X';			transl['х']='x';
			transl['Ц']='C';			transl['ц']='c';
			transl['Ч']='Ch';			transl['ч']='ch';
			transl['Ш']='Sh';			transl['ш']='sh';
			transl['Щ']='Shh';		transl['щ']='shh';
			transl['Ъ']='';			transl['ъ']='';
			transl['Ы']='Y';		transl['ы']='y';
			transl['Ь']='';			transl['ь']='';
			transl['Э']='E';		transl['э']='e';
			transl['Ю']='Yu';			transl['ю']='yu';
			transl['Я']='Ya';			transl['я']='ya';
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
			nowDate = new Date();
			timeStamp = "-" + nowDate.getDate() + "-" + (nowDate.getMonth()+1) + "-" + nowDate.getFullYear();
			document.getElementById('post-url').value=result+timeStamp;
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
		content_css: "/css/admin/tinymce_new.css",
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
	content_css: "/css/admin/tinymce_new.css",
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