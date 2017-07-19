$(function() {
	$('input[type=tel]').inputmask("8(999)999-99-99");

	initSaveBtns();
	posterEdit("cover-img");
});

function posterEdit(posterId)
{
	$('.poster-group .poster').on('click',function(event) {
		var akey = $('.poster-group').find('a.upload').data('akey');
		open_popup('/js/responsive_filemanager/filemanager/dialog.php?popup=1&type=2&field_id='+posterId+'&relative_url=1&akey='+akey);
	});
}

function initSaveBtns()
{
	$('button.btn-save').on('click', function(event) {
		event.preventDefault();
		var $btnEl = $(this);
		var btnData = {};
		btnData.section = $btnEl.data("configsect");

		collectConfigData(btnData.section);
		return false;
	});
}

function collectConfigData(section)
{
	var sect = section;
	var data ={};
	switch (sect) {
			case 'primary':
					data.ids = ["inputSiteName", "cover-img", "inputSitePhone", "inputAddress", "inputCopyright"];
					data.valid = ["longer-3", "notDefaultPoster", "phoneMask", "longer-5", "longer-3"];
					data.vals = [];
					$.each(data.ids, function(index, val) {
						 data.vals[index] = $("#"+val).val();
					});
					//TODO: move out switch
					if (dataValid(data)) {
						$.when(dataSend(data)).done(function(res){
							log(res);
							$('.btn[data-configsect='+sect+']').removeClass('btn-primary').removeClass('btn-danger').addClass('btn-success').text('Сохранить');
						}).fail(function(res){
							log(res);
							$('.btn[data-configsect='+sect+']').removeClass('btn-primary').removeClass('btn-success').addClass('btn-danger').text('Ошибка сохранения');
						});

						dataSend(data);
						return false;
					} else {
						return false;
					}
					break;

			case 'contacts':
					log("contacts collectiong");
					break;
/*
			case '':
					break;
			*/

			default:
					log("Config section missing");
					break;
	}
}

function dataSend(data)
{
	var def = new $.Deferred();

	var dataForSend = data;
	delete dataForSend.valid;
	var dataJson = JSON.stringify(dataForSend);

	$.ajax({
		url: '/admin/config/update',
		type: 'POST',
		data: {dataJSON: dataJson},
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

function dataValid(data)
{
	var validData = data;
	var errs = {
			id : [],
			val : [],
			error : []
		};
	$.each(validData.valid, function(index, val) {
		var splt = val.split('-');
		if (splt.length>1 ){
			if (splt[0]=="longer") {
				var validVal = validData.vals[index];
				if (validVal.length >= splt[1]) {
					// return true;
				} else {
					errs.id.push(validData.ids[index]);
					errs.val.push(validData.vals[index]);
					errs.error.push("Must be longer than "+splt[1]);
				}
			}
		} else
			if (val.length == 0) {
				errs.id.push(validData.ids[index]);
				errs.val.push(validData.vals[index]);
				errs.error.push("Data missed");
		} else {
			switch(val){
				case 'phoneMask':
					if ( $("#"+validData.ids[index]).inputmask("isComplete") ) {
						// return true;
					} else {
						errs.id.push(validData.ids[index]);
						errs.val.push(validData.vals[index]);
						errs.error.push("Incorrect phone");
					}
					break;

				case 'notDefaultPoster':
					if ($('#'+validData.ids[index]).val()=="/upload/prod-default-cover.jpg") {
						errs.id.push(validData.ids[index]);
						errs.val.push(validData.vals[index]);
						errs.error.push("Set another picture");
					}
					break;

					default:
						log("default case");

						break;
			}
		}
	});
	if (errs.id.length >0) {
		showErrors(validData, errs);
		return false;
	} else {
		log("good");
		clearErrs(validData);
		return true;
	}
}

function clearErrs(validData)
{
	$.each(validData.ids, function(index, val) {
		$('#'+val).closest('.form-group').removeClass('has-error');
		$('#'+val).closest('.btn-save').removeClass('btn-danger').addClass('btn-primary').text('Сохранить');
	});
}

function showErrors(validData, errs)
{
	log(errs);
	$.each(validData.ids, function(index, val) {
		$('#'+val).closest('.form-group').removeClass('has-error');
		$('#'+val).closest('.btn-save').removeClass('btn-danger').addClass('btn-primary').text('Сохранить');
	});
	$.each(errs.id, function(index, val) {
		$('#'+val).closest('.form-group').addClass('has-error');
		$('#'+val).closest('.btn-save').removeClass('btn-primary').addClass('btn-danger').text('Сохранить');
	});
}