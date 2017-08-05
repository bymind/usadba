$(function()
{
	editPhoto();
});

function editPhoto()
{
	var $modal = $('.modal.modal-edit_photo');
	$(document).on('click', '.modal.modal-edit_photo #ava-preview', function(event) {
		event.preventDefault();
		$modal.find('#btn-load-photo').click();
	});
	$(document).on('click', '.edit-photo', function(event) {
		event.preventDefault();
		console.log('edit photo');
		// $('#image_file').click();
		$modal.modal();
	});
	$('#upload_form').on('submit', function(e){
			e.preventDefault();
			var send_url = $('#image_file').attr('data-url');
			var fd = new FormData();

			that = $('#image_file').get(0);

			fd.append("image_file", that.files[0]);

			$.ajax({
					url: send_url,
					type: "POST",
					data: fd,
					processData: false,
					contentType: false
			}).then(function(res){
					console.log(res);
					$('.profile-avatar img').attr('src', res);
					$modal.modal('hide');
					$modal.on('hidden.bs.modal', function(event) {
						event.preventDefault();
						$modal.find('#image_file').attr('value', res);
						$modal.find('#ava-preview').css('background-image', 'url('+res+')');
						$modal.off('hidden.bs.modal');
						return true;
					});
			}, function(err){
					console.log('error');
					console.log(err);
			});
		});
	$modal.on('hidden.bs.modal', function(event) {
		event.preventDefault();
		oldAva = $('.profile-avatar img').attr('src');
		$modal.find('#image_file').attr('value', oldAva);
		$modal.find('#ava-preview').css('background-image', 'url('+oldAva+')');
		$modal.off('hidden.bs.modal');
	});
}

// convert bytes into friendly format
function bytesToSize(bytes) {
		var sizes = ['Bytes', 'KB', 'MB'];
		if (bytes == 0) return 'n/a';
		var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};

// check for selected crop region
function checkForm() {
		if (parseInt($('#w').val())) return true;
		$('.error').html('Please select a crop region and then press Upload').show();
		return false;
};

// update info by cropping (onChange and onSelect events handler)
function updateInfo(e) {
		$('#x1').val(e.x);
		$('#y1').val(e.y);
		$('#x2').val(e.x2);
		$('#y2').val(e.y2);
		$('#w').val(e.w);
		$('#h').val(e.h);
};

// clear info by cropping (onRelease event handler)
function clearInfo() {
		$('.info #w').val('');
		$('.info #h').val('');
};

function fileSelectHandler() {

		// get selected file
		var oFile = $('#image_file')[0].files[0];

		// hide all errors
		$('.error').hide();

		// check for image type (jpg and png are allowed)
		var rFilter = /^(image\/jpeg|image\/png|image\/gif)$/i;
		if (! rFilter.test(oFile.type)) {
				$('.error').html('Выберите файл изображения (например jpg, png или gif)').show();
				return;
		}

		// check for file size
		if (oFile.size > 20*1024*1024 ) {
				$('.error').html('Попробуйте файл поменьше').show();
				return;
		}

	// $('#btn-load-photo').css('display', 'none');

	// $('.step2').css('display', 'block');
		// preview element
		var oImage = document.getElementById('ava-preview');

		// prepare HTML5 FileReader
		var oReader = new FileReader();
				oReader.onload = function(e) {
					console.log('oReader.onload');
				// e.target.result contains the DataURL which we can use as a source of the image
				oImage.src = e.target.result;
				$('#ava-preview').css('background-image', 'url('+oImage.src+')');

		};

		// read selected file as DataURL
		oReader.readAsDataURL(oFile);
}