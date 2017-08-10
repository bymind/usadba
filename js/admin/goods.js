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

/*	$('.cat-iframe-btn').fancybox({
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
			});*/


			$('.sales-select-items .spoiler-title label').click(function(event) {
				event.preventDefault();
				event.stopPropagation();
				console.log('hi label');
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

function lookNewCat()
{
	$('button.add_cat').on('click', function(event) {
		var page = $(this).data('page');
		console.log('hi modal');
		var modal = $('.new-cat-modal');
		modal.modal();
		var insertPosterId = modal.find('input[name=poster]').attr('id');
		lookCatName(modal);
		lookParentSelect(modal);
		lookPosterEdit(modal, insertPosterId);
		lookNewCatSend(modal, page);
	});
}

function lookRedCat()
{
	$('button.red_cat').on('click', function(event) {
		var page = $(this).data('page');
		console.log('hi modal');
		var modal = $('.red-cat-modal');
		var insertPosterId = modal.find('input[name=poster]').attr('id');
		modal.modal();
		var redBox = modal.find('.red_cat_over');
		lookBtnMoveClick(modal, redBox);
		lookBtnDeleteClick(modal, redBox);
		lookRedCatSelectCat(modal, redBox);
		lookRedCatName(modal);
		lookPosterEdit(modal, insertPosterId);
		lookRedCatSend(modal, page);
	});
}

function lookBtnMoveClick(modal, redBox)
{
	modal.find('span div.move').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		console.log($(this).parent().data());
		if ($(this).hasClass('down')) {
			movePosition($(this).parent().data(), 'down');
		} else
		if ($(this).hasClass('up')) {
			movePosition($(this).parent().data(), 'up');
		}
	});
}

function movePosition(catdata, direction)
{
	switch (direction) {
		case 'down':
			catdata.direction = 1;
			break;

		case 'up':
			catdata.direction = -1;
			break;

		default:
			catdata.direction = 0;
			break;
	}
	$.when(movePositionAjax(catdata)).done(function(res){
		console.log(res);
		reloadModalCatsList('', 'prod_list');
		reloadCatsList('', 'prod_list');
	}).fail(function(res){
		console.log('movePositionAjax() fail');
		console.log(res);
	});

}

function movePositionAjax(catObj)
{
	var def = new $.Deferred();

	var catJSON = JSON.stringify(catObj);
	$.ajax({
		url: '/admin/cats/movecat',
		type: 'POST',
		data: {action: 'movecat', catJSON: catJSON},
	})
	.done(function(response) {
		def.resolve(response);
	})
	.fail(function(response) {
		console.log("error");
		def.reject(response);
	})
	.always(function() {

	});
	return def.promise();
}

function lookBtnDeleteClick(modal, redBox)
{
	modal.find('span.glyphicon-remove-circle').unbind('click').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		console.log('to delete');
		var delModal = $('.del-cat-modal');
		var catData = $(this).parents('button').parent().data();
		catData.name = $(this).parents('button').parent().find('span').eq(0).text();
		delModal.find('.modal-title span').text(catData.name);
		delModal.find('.modal-footer .btn-delete-cat').attr('data-catid',catData.catid);
		// delModalCreateCatList(delModal);
		delModal.modal();
		lookDelTypeChoose(delModal, catData);
	});
}


function lookDelTypeChoose(modal, cat)
{
	console.log(cat);
	modal.find('input[name=del_type]').on('change', function(event) {
		if (modal.find('input[name=del_type]').eq(0).prop('checked')) {
			modal.find('.moveto-cat-box').addClass('unactive');
		} else
			modal.find('.moveto-cat-box').removeClass('unactive');
	});
	if (cat.parent == "0") {
		modal.find('.if-parent').addClass('active');
	} else
		modal.find('.if-parent').removeClass('active');
	var $cats = modal.find('#moveto-cat option');
	$cats.prop('disabled', false).prop('selected', false).attr('title', '');
	$.each($cats, function(index, val) {
			if ($(val).val() ==cat.catid) {
				$cats.eq(index).prop('disabled', true).attr('title', 'Нельзя переместить позиции в категорию, которую удаляешь').css('cursor', 'not-allowed');
			}
			if ($(val).val() == cat.parent) {
				$cats.eq(index).prop('selected', true);
			}
	});
	modal.on('hidden.bs.modal', function(event) {
		$('body').addClass('modal-open');
		$(this).find('select').parents('.form-group').removeClass('has-error').find('span.substring').remove();
	});
	modal.find('select#moveto-cat').on('change', function(event) {
		event.preventDefault();
		$(this).parents('.form-group').removeClass('has-error').find('span.substring').remove();
	});
	var delBtn = modal.find('.modal-footer .btn-delete-cat');
	delBtn.unbind('click').on('click', function(event) {
		event.preventDefault();
			if (modal.find('input[name=del_type]').eq(0).prop('checked')) { // delete with all items
				deleteCatAll(modal,cat);
			} else
			if (modal.find('input[name=del_type]').eq(1).prop('checked')) { // delete with move to another category
				if (cat.parent == "0") {

					if ($('#moveto-cat').val() == null) {
						$('#moveto-cat').parents('.form-group').addClass('has-error').append('<span style="left:24px" class="substring red">Выберите категорию для переноса товаров</span>');
					} else {
						var moveTo = $('#moveto-cat').val();
						deleteCatMove(modal, cat, moveTo);
					}
				} else {
					var moveTo = $('#moveto-cat').val();
					deleteCatMove(modal, cat, moveTo);
				}
			}
	});
}

function deleteCatAll(modal,cat)
{
	console.log(cat);
	if (cat.parent == 0) {
		console.log('moveChildCats('+cat.catid+')');
		moveChildCats(cat.catid);
	} else {
	}
	console.log('delete prods from cat '+cat.catid);
	deleteProdsFromCat(cat.catid);
	console.log('delete all in category');
	deleteCategory(cat.catid, cat.position, cat.parent);
	modal.modal('hide');
	reloadModalCatsList(cat.name, 'prod_list');
	reloadCatsList(cat.name, 'prod_list');
}

function deleteCategory(catid, position, parent)
{
	$.ajax({
		url: '/admin/cats/deletecategory',
		type: 'POST',
		data: {action: 'deletecategory', catid: catid, position: position, parent: parent},
	})
	.done(function(r) {
		console.log("success");
		console.log(r);
	})
	.fail(function(r) {
		console.log("error");
		console.log(r);
	})
	.always(function() {
		console.log("complete");
	});
}

function moveChildCats(catid)
{
	$.ajax({
		url: '/admin/cats/childcatstoparent',
		type: 'POST',
		data: {action: 'childcatstoparent', catid: catid},
	})
	.done(function(r) {
		console.log("success");
		console.log(r);
	})
	.fail(function(r) {
		console.log("error");
		console.log(r);
	})
	.always(function() {
		console.log("complete");
	});
}

function deleteProdsFromCat(catid)
{
	$.ajax({
		url: '/admin/cats/deleteprodsformcat',
		type: 'POST',
		data: {action: 'deleteprodsformcat', catid: catid},
	})
	.done(function(r) {
		console.log("success");
		console.log(r);
	})
	.fail(function(r) {
		console.log("error");
		console.log(r);
	})
	.always(function() {
		console.log("complete");
	});
}

function deleteCatMove(modal, cat, moveto)
{
	console.log(cat);
	console.log('move prods from cat '+cat.catid+' to '+moveto);
	moveProdsFromCatToCat(cat.catid, moveto);
	console.log('delete category');
	deleteCategory(cat.catid, cat.position, cat.parent);
	modal.modal('hide');
	reloadModalCatsList(cat.name, 'prod_list');
	reloadCatsList(cat.name, 'prod_list');
}

function moveProdsFromCatToCat(fromcatid, tocatid)
{
	$.ajax({
		url: '/admin/cats/movefromcattocat',
		type: 'POST',
		data: {action: 'movefromcattocat', fromcatid: fromcatid, tocatid: tocatid},
	})
	.done(function(r) {
		console.log("success");
		console.log(r);
	})
	.fail(function(r) {
		console.log("error");
		console.log(r);
	})
	.always(function() {
		console.log("complete");
	});
}

function lookRedCatSelectCat(modal, redBox)
{
	modal.find('span.catname').unbind('click').on('click', function(event) {
		event.preventDefault();
		modal.find('.catname.active').removeClass('active');
		$(this).addClass('active');
		var category = {
			catid: $(this).data('catid'),
			cattechname: $(this).data('cattechname'),
			catname: $(this).find('span').text()
		};
		redBox.find('.red_cat_title').text(category.catname);
		$.ajax({
			url: '/admin/goods/getcat/'+category.cattechname,
			type: 'POST',
			data: {action: 'getcat'},
		})
		.done(function(response) {
			console.log("success");
			console.warn($.parseJSON(response));
			var catData = $.parseJSON(response);
			// формируем список род. категорий
			var inputParents = "<option value='0' >Без родителя (родительская категория)</option>";
			$.each(catData.parents_cats, function(index, val) {
				if (catData.cat.parent != "0") {
				redBox.find('select#red_parentInput').prop('disabled', false);
				redBox.find('input#red_show_popular').parents('label').addClass('disabled');
				redBox.find('input#red_show_popular').prop('clicked', false);
				redBox.find('input#red_show_popular').prop('disabled', true);
					if (catData.cat.parent == val.id) {
						inputParents += "<option selected value='"+val.id+"'>"+val.name+"</option>";
					} else
					inputParents += "<option value='"+val.id+"'>"+val.name+"</option>";
				} else {
				redBox.find('select#red_parentInput').prop('disabled', true);
				redBox.find('input#red_show_popular').prop('disabled', false);
				redBox.find('input#red_show_popular').parents('label').removeClass('disabled');
				if (val.id != category.catid) {
					inputParents += "<option value='"+val.id+"'>"+val.name+"</option>";
				}
				}
			});
			// формируем картинку
			var $poster = catData.cat.poster;
			if ($poster == '') {
				$poster = "/img/prod-default-cover.jpg";
			}
			redBox.find('.red_cat_body.active').removeClass('active');
			redBox.find('input#red_nameInput').val(catData.cat.name);
			redBox.find('input#red_idInput').val(catData.cat.id);
			redBox.find('input#tech_nameInput').val(catData.cat.tech_name);
			redBox.find('input#red_positionInput').val(catData.cat.position);
			redBox.find('select#red_parentInput').html(inputParents);
			redBox.find('input#red_cat_posterInput').val($poster);
			redBox.find('input#red_per_page').val(catData.cat.per_page);
			redBox.find('img#img-red_cat_posterInput').attr('src',$poster);
			redBox.find('.red_cat_body').addClass('active');
			if (catData.cat.show_big == '1') {
				redBox.find('input#red_show_big').prop('checked', true);
			} else
				redBox.find('input#red_show_big').prop('checked', false);
			if (catData.cat.show_popular=='1') {
				redBox.find('input#red_show_popular').prop('checked', true);
			} else
				redBox.find('input#red_show_popular').prop('checked', false);
		})
		.fail(function(response) {
			console.log("error");
			console.error(response);
		});

	});
}

function lookParentSelect(modal)
{
	var form = modal.find('form');
	form.find('select').on('change', function(event) {
		event.preventDefault();
		var target = $(event.target);
		var inputPopular = form.find('input#show_popular');
		var labelPopular = inputPopular.parents('label');
		if (target.val() != "0") {
			inputPopular.prop({
				checked: false,
				disabled: true
			});
			labelPopular.addClass('disabled');
		} else {
			inputPopular.prop({
				checked: true,
				disabled: false
			});
			labelPopular.removeClass('disabled');
		}
	});
}

function lookPosterEdit(modal, posterId)
{
	modal.find('.poster').click(function(event) {
		var akey = modal.find('a.upload').data('akey');
		open_popup('/js/responsive_filemanager/filemanager/dialog.php?popup=1&type=2&field_id='+posterId+'&relative_url=1&akey='+akey);
	});
}

function lookCatName(modal)
{
	var inputName = modal.find('input#nameInput');
	inputName.on( 'keyup', function(event) {
		if(event.keyCode == 13){
				event.preventDefault();
				console.info('enter submit stopped');
		} else {
			$(this).parents('.form-group').removeClass('has-error');
			$(this).parents('.form-group').find('.substring.red').remove();
			var techNameObj = modal.find('input#tech_nameInput');
			var inputText = $(this).val();
			$text = transliterate(inputText);
			techNameObj.val($text);
		}
	});
	modal.find('form').on('submit', function(event) {
		event.preventDefault();
		console.info('submit stopped');
	});
}

function lookRedCatName(modal)
{
	var inputName = modal.find('input#red_nameInput');
	inputName.on( 'keyup', function(event) {
		if(event.keyCode == 13){
				event.preventDefault();
				console.info('enter submit stopped');
		} else {
			$(this).parents('.form-group').removeClass('has-error');
			$(this).parents('.form-group').find('.substring.red').remove();
			var techNameObj = modal.find('input#tech_nameInput');
			var inputText = $(this).val();
			$text = transliterate(inputText);
			techNameObj.val($text);
		}
	});
	modal.find('form').on('submit', function(event) {
		event.preventDefault();
		console.info('submit stopped');
	});
}

function lookNewCatSend(modal, page)
{
	modal.find('button.btn-submit').unbind('click').on('click', function(event) {
		event.preventDefault();
		var form = modal.find('form');
		var btnSubmit = form.find('button.btn-submit');
		if (form.find('input[name=name]').val() == "") {
			$(this).addClass('btn-danger').removeClass('btn-default').text('Ошибка');
			setTimeout(function(){
				btnSubmit.removeClass('btn-danger').addClass('btn-default').text('Добавить категорию');
			},2000);
			var formGroup = form.find('input[name=name]').parents('.form-group');
			formGroup.addClass('has-error');
			formGroup.append('<span class="substring red">Введите название категории</span>');
		} else {
			var newCat = {};
			newCat.name = form.find('input[name=name]').val();
			newCat.tech_name = form.find('input[name=tech_name]').val();
			newCat.parent = form.find('select[name=parent]').val();
			newCat.poster = form.find('input[name=poster]').val();
			newCat.show_big = form.find('input#show_big').prop('checked') + 0;
			newCat.show_popular = form.find('input#show_popular').prop('checked') + 0;
			console.log(newCat);
			btnSubmit.attr('disabled', 'disabled');
			$.when(sendNewCat(newCat)).done(function(res){
				if (res!="Категория добавлена") {
					console.info(res);
					btnSubmit.addClass('btn-danger').removeClass('btn-default').text('Ошибка');
					setTimeout(function(){
						btnSubmit.removeClass('btn-danger').addClass('btn-default').text('Добавить категорию');
						btnSubmit.prop('disabled', false);
					},2000);
					var formGroup = form.find('input[name=name]').parents('.form-group');
					formGroup.addClass('has-error');
					formGroup.append('<span class="substring red">'+res+'</span>');
				} else {
					// Категория добавлена
					console.info(res);
					btnSubmit.prop('disabled', false);
					form.trigger('reset');
					modal.modal('hide');
					form.find('.poster img').attr('src', form.find('input#cat_posterInput').val());
					var inputPopular = form.find('input#show_popular');
					var labelPopular = inputPopular.parents('label');
					inputPopular.prop({
						checked: true,
						disabled: false
					});
					labelPopular.removeClass('disabled');
					reloadCatsList(newCat.name, page);
					reloadModalCatsList(newCat.name, page);
				}
			}).fail(function(res){
				console.info(res);
				btnSubmit.addClass('btn-danger').removeClass('btn-default').text('Ошибка');
				setTimeout(function(){
					btnSubmit.removeClass('btn-danger').addClass('btn-default').text('Добавить категорию');
					btnSubmit.prop('disabled', false);
				},2000);
				var formGroup = form.find('input[name=name]').parents('.form-group');
				formGroup.addClass('has-error');
				formGroup.append('<span class="substring red">'+res+'</span>');
			});
		}
	});
}

function lookRedCatSend(modal, page)
{
	modal.find('button.btn-save-red').unbind('click').on('click', function(event) {
		event.preventDefault();
		var form = modal.find('form');
		var btnSubmit = form.find('button.btn-save-red');
		if (form.find('input[name=name]').val() == "") {
			$(this).addClass('btn-danger').removeClass('btn-default').text('Ошибка');
			setTimeout(function(){
				btnSubmit.removeClass('btn-danger').addClass('btn-default').text('Сохранить изменения');
			},2000);
			var formGroup = form.find('input[name=name]').parents('.form-group');
			formGroup.addClass('has-error');
			formGroup.append('<span class="substring red">Введите название категории</span>');
		} else {
			var newCat = {};
			newCat.id = form.find('input[name=id]').val();
			newCat.name = form.find('input[name=name]').val();
			newCat.tech_name = form.find('input[name=tech_name]').val();
			newCat.parent = form.find('select[name=parent]').val();
			newCat.position = form.find('input#red_positionInput').val();
			newCat.poster = form.find('input[name=poster]').val();
			newCat.show_big = form.find('input#red_show_big').prop('checked') + 0;
			newCat.show_popular = form.find('input#red_show_popular').prop('checked') + 0;
			newCat.per_page = form.find('input#red_per_page').val();
			console.log(newCat);
			btnSubmit.attr('disabled', 'disabled');
			$.when(sendRedCat(newCat)).done(function(res){
				if (res!="Категория сохранена") {
					console.info(res);
					btnSubmit.addClass('btn-danger').removeClass('btn-default').text('Ошибка');
					setTimeout(function(){
						btnSubmit.removeClass('btn-danger').addClass('btn-default').text('Сохранить изменения');
						btnSubmit.prop('disabled', false);
					},2000);
					var formGroup = form.find('input[name=name]').parents('.form-group');
					formGroup.addClass('has-error');
					formGroup.append('<span class="substring red">'+res+'</span>');
				} else {
					// Категория добавлена
					console.info(res);
					btnSubmit.prop('disabled', false);
					// form.trigger('reset');
					// modal.modal('hide');
					// form.find('.poster img').attr('src', form.find('input#cat_posterInput').val());
					// var inputPopular = form.find('input#show_popular');
					// var labelPopular = inputPopular.parents('label');
					// inputPopular.prop({
					// 	checked: true,
					// 	disabled: false
					// });
					// labelPopular.removeClass('disabled');
					reloadModalCatsList(newCat.name, page);
					reloadCatsList(newCat.name, page);
				}
			}).fail(function(res){
				console.info(res);
				btnSubmit.addClass('btn-danger').removeClass('btn-default').text('Ошибка');
				setTimeout(function(){
					btnSubmit.removeClass('btn-danger').addClass('btn-default').text('Сохранить изменения');
					btnSubmit.prop('disabled', false);
				},2000);
				var formGroup = form.find('input[name=name]').parents('.form-group');
				formGroup.addClass('has-error');
				formGroup.append('<span class="substring red">'+res+'</span>');
			});
		}
	});
}

function reloadModalCatsList(catName, page)
{
	console.log(page);
	switch (page) {
		case 'prod_item':
			console.log('nope');
			break;

		case 'prod_list':
			var $catListBox = $('.red-cat-modal div.cat_links');
			var addCatId = "";
			$.ajax({
				url: '/admin/goods/getcattree_prodlist',
				type: 'POST',
				data: {action: 'prodlist'},
			})
			.done(function(response) {
				console.log("reload modal cat list success");
				$result = response;
				$result = $.parseJSON($result);
				var $goods = $result[0];
				var $cat_tree = $result[1];
				var $insert = "";
				var $addSelected = "";
				var $i = 0;
				// var $len = $cat_tree.tree.length -1;
				var $len = Object.keys($cat_tree.tree).length -1;
				$.each($cat_tree.tree, function(index, arr) {
					var $text = arr.name;
					var $link = "/admin/goods/cat/"+arr.id;
					if ($i>0 && $i<$len) {
						$insert += "<span class='catname' data-catid='"+arr.id+"' data-parent='"+arr.parent+"' data-cattechname='"+arr.tech_name+"' data-position='"+arr.position+"'><span>"+$text+"</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button><div class='move down' title='Переместить ниже'>&darr;</div><div class='move up' title='Переместить выше'>&uarr;</div></span>";
					} else
					if ($i == 0 && $len == 0) {
						$insert += "<span class='catname' data-catid='"+arr.id+"' data-parent='"+arr.parent+"' data-cattechname='"+arr.tech_name+"' data-position='"+arr.position+"'><span>"+$text+"</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button></span>";
					} else
					if ($i==0) {
						$insert += "<span class='catname' data-catid='"+arr.id+"' data-parent='"+arr.parent+"' data-cattechname='"+arr.tech_name+"' data-position='"+arr.position+"'><span>"+$text+"</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button><div class='move down' title='Переместить ниже'>&darr;</div></span>";
					} else
					if ($i==$len) {
						$insert += "<span class='catname' data-catid='"+arr.id+"' data-parent='"+arr.parent+"' data-cattechname='"+arr.tech_name+"' data-position='"+arr.position+"'><span>"+$text+"</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button><div class='move up' title='Переместить выше'>&uarr;</div></span>";
					}
					$i++;
					if ('child' in arr) {
						var $ii = 0;
						var $llen = arr.child.length -1;
						$.each(arr.child, function(index1, arr1) {
							$text = arr1.name;
							$link = '/admin/goods/cat/'+arr1.id;
							if ($ii>0 && $ii<$llen) {
								$insert += "<span class='catname subcat' data-catid='"+arr1.id+"' data-parent='"+arr1.parent+"' data-cattechname='"+arr1.tech_name+"' data-position='"+arr1.position+"'><span>"+$text+"</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button><div class='move down' title='Переместить ниже'>&darr;</div><div class='move up' title='Переместить выше'>&uarr;</div></span>";
							} else
							if ($ii == 0 && $llen == 0) {
								$insert += "<span class='catname subcat' data-catid='"+arr1.id+"' data-parent='"+arr1.parent+"' data-cattechname='"+arr1.tech_name+"' data-position='"+arr1.position+"'><span>"+$text+"</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button></span>";
							} else
							if ($ii==0) {
								$insert += "<span class='catname subcat' data-catid='"+arr1.id+"' data-parent='"+arr1.parent+"' data-cattechname='"+arr1.tech_name+"' data-position='"+arr1.position+"'><span>"+$text+"</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button><div class='move down' title='Переместить ниже'>&darr;</div></span>";
							} else
							if ($ii==$llen) {
								$insert += "<span class='catname subcat' data-catid='"+arr1.id+"' data-parent='"+arr1.parent+"' data-cattechname='"+arr1.tech_name+"' data-position='"+arr1.position+"'><span>"+$text+"</span><button type='button' class='btn btn-default delete-cat' aria-label='Удалить'><span class='glyphicon glyphicon-remove-circle' title='Удалить' aria-hidden='true'></span></button><div class='move up' title='Переместить выше'>&uarr;</div></span>";
							}
							$ii++;
						});
					}
				});
				$catListBox.html($insert);
				lookRedCatSelectCat($('.red-cat-modal'), $('.red-cat-modal div.red_cat_over'));
				lookBtnMoveClick($('.red-cat-modal'), $('.red-cat-modal div.red_cat_over'));
				lookBtnDeleteClick($('.red-cat-modal'), $('.red-cat-modal div.red_cat_over'));
				$('.red-cat-modal div.red_cat_over .red_cat_body').removeClass('active');
				$('.red-cat-modal div.red_cat_over .red_cat_title').html('<span>Сохранено!</span>');
			})
			.fail(function(response) {
				console.log("error");
				console.error(response);
			});
			break;
	}
}

function reloadCatsList(catName, page)
{
	console.log(page);
	switch (page) {
		case 'prod_item':
			var $select = $('select#prod-cat');
			$.ajax({
				url: '/admin/goods/getcattree',
				type: 'POST',
				data: {action: 'getcattree'},
			})
			.done(function(response) {
				console.log("success");
				$cattree = response;
				$cattree = $.parseJSON($cattree);
				console.warn($cattree.tree);
				var $insert = "";
				var $addSelected = "";
				$.each($cattree.tree, function(index, val) {
					if (val.name == catName) {
						$addSelected="selected";
					}
					$insert += "<option "+$addSelected+" value='"+val.id+"'>"+val.name+"</option>";
					$addSelected = "";
					if ('child' in val) {
						$.each(val.child, function(index1, val1) {
							if (val1.name == catName) {
								$addSelected="selected";
							}
							$insert += "<option "+$addSelected+" value='"+val1.id+"'>—"+val1.name+"</option>";
							$addSelected = "";
						});
					}
				});
				$select.html($insert);
			})
			.fail(function(response) {
				console.log("error");
				console.error(response);
			});
			break;

		case 'prod_list':
			var $catListBox = $('div.tags div.cat_links');
			var addCatId = "";
			if (location.href.split('/')[4] == "goods") {
				if (location.href.split('/')[5] == "cat") {
					if (typeof(location.href.split('/')[6] == "string") ) {
						addCatId = "/"+location.href.split('/')[6];
					}
				}
			}
			$.ajax({
				url: '/admin/goods/getcattree_prodlist'+addCatId,
				type: 'POST',
				data: {action: 'prodlist'},
			})
			.done(function(response) {
				console.log("success");
				$result = response;
				$result = $.parseJSON($result);
				var $goods = $result[0];
				var $cat_tree = $result[1];
				var $insert = "";
				var $addSelected = "";
				$.each($cat_tree.tree, function(index, val) {
					var $text = val.name;
					var $link = "/admin/goods/cat/"+val.id;
					if (val.id == $goods[0].cat_id) {
						$insert += "<a class='active' href='"+$link+"'>"+$text+"</a>";
					} else
						$insert += "<a href='"+$link+"'>"+$text+"</a>";
					if ('child' in val) {
						$.each(val.child, function(index1, val1) {
							var $text = val1.name;
							var $link = "/admin/goods/cat/"+val1.id;
							if (val1.id == $goods[0].cat_id) {
								$insert += "<a class='active' href='"+$link+"'>—"+$text+"</a>";
							} else
								$insert += "<a href='"+$link+"'>—"+$text+"</a>";
						});
					}
				});
				$catListBox.html($insert);
				var $plusCatSelect = $('.new-cat-modal select#parentInput');
				$insert = "<option value='0' >+ Без родителя (новая главная категория)</option>";
				$.each($cat_tree.tree, function(index, val) {
					if (val.parent == 0) {
						$insert += "<option value='"+val.id+"' >"+val.name+"</option>";
					}
				});
				$plusCatSelect.html($insert);
				var $select = $('.del-cat-modal').find('#moveto-cat');
				$insert = "";
				$.each($cat_tree.tree, function(index, val) {
					var $text = val.name;
					$insert += "<option value='"+val.id+"'>"+$text+"</option>";
					if ('child' in val) {
						$.each(val.child, function(index1, val1) {
							var $text = val1.name;
							$insert += "<option valie='"+val1.id+"'>—"+$text+"</option>";
						});
					}
				});
				$select.html($insert);

			})
			.fail(function(response) {
				console.log("error");
				console.error(response);
			});
			break;
	}
}

function sendRedCat(catObj)
{
	var catJSON = JSON.stringify(catObj);

	var def = new $.Deferred();

	$.ajax({
		url: '/admin/goods/redcat',
		type: 'POST',
		data: {jsonPost: catJSON, action: 'redcat'},
	})
	.done(function(response) {
		def.resolve(response);
	})
	.fail(function(response) {
		console.log("error");
		def.reject(response);
	})
	.always(function() {

	});
	return def.promise();
}

function sendNewCat(catObj)
{
	var catJSON = JSON.stringify(catObj);

	var def = new $.Deferred();

	$.ajax({
		url: '/admin/goods/newcat',
		type: 'POST',
		data: {jsonPost: catJSON, action: 'newcat'},
	})
	.done(function(response) {
		console.log("success");
		def.resolve(response);
	})
	.fail(function(response) {
		console.log("error");
		def.reject(response);
	})
	.always(function() {

	});
	return def.promise();
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
	var $pod = $('input#pod').prop('checked')+0;
	var $labels = $('.tags-items input[type=checkbox]');
	var $tags = '';
	for (var i = 0; i < $labels.length; i++) {
		if ($labels.eq(i).prop("checked")) {
			if (i>0) {
				$tags += ",";
			}
			$tags += $labels.eq(i).attr('id');
		}
	}
	console.log($tags);

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
		pod: $pod,
		stor_cond: $stor_cond,
		nut_val: $nut_val,
		energy_val: $energy_val,
		consist: $consist,
		price: $price,
		labels: $tags
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

function coverDelete()
{
	$('.cover .poster').click(function(event) {
		$(this).parent().find('a.upload').click();
	});
	var defaultUrl = "/img/prod-default-cover.jpg";
	$('.cover .controls .delete').click(function(event) {
		$("#img-cover-img").attr('src', defaultUrl).css('opacity', '.5');
		$("#cover-img").val(defaultUrl);
	});
}

function articlesScriptsInit()
{
	pinClick();
	lookForUrl();
	lookUrlEdit();
	lookUrlEditOpacity();
	lookNewCat();
	lookRedCat();
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
		transliteratePostTitle();
	});
}

function transliterate(text)
{
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
	return result;
}

function transliteratePostTitle()
{
	var text=document.getElementById('post-title').value;

	var result = transliterate(text);

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

function getRandom(min, max)
{
	return Math.floor(Math.random() * (max - min + 1)) + min;
}