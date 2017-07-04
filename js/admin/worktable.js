$(function() {
	orderRowClick();
});

function orderRowClick()
{
	$('table.table-orders').on('click', 'tr.order-header', function(event) {
		event.preventDefault();
		var $order = $(this).data('orderid');
		$(this).parent().find('tr.order-body[data-orderid='+$order+']').toggleClass('on');
	});
}