$(document).ready(function() {
	if ( $('.comm-form').get(0) ) {
		InitCommTextarea($('.comm-form'));
	}
});

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
				target_type: 'product',
				target_id: $(this).data('prodid')
			};
			comment = JSON.stringify(comment);
			$.ajax({
					url: '/user/addcomment',
					type: 'POST',
					data: {comment: comment}
				})
				.done(function(res) {
					console.log("comment added");
					console.log(res);
					reloadComments($('.comments-box'), $commBtn.data('prodid'));
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

function reloadComments(comWrapper, prodid)
{
				$.ajax({
					url: '/catalog/reloadcomments',
					type: 'POST',
					data: {prodid: prodid}
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