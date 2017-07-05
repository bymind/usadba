$(function() {
	orderRowClick();
	lookForStatusEdit();
});

function lookForStatusEdit()
{
	$('.table-orders').on('click', 'ul.status-edit li>span', function(event) {
		event.preventDefault();
		var editOrder = {};
		editOrder.id = $(this).closest('.status-edit').data('orderid');
		editOrder.status = $(this).data('setstat');

		$('.table-orders [data-orderid='+editOrder.id+']').css('opacity', '.5');

		sendNewOrderStatus(editOrder);
	});
}

function sendNewOrderStatus(orderStatus)
{
	newStatJson = JSON.stringify(orderStatus);

	$.ajax({
		url: '/admin/orders/newStat',
		type: 'POST',
		data: {token:'ajaxCount', newStat: newStatJson},
	})
	.done(function(answ) {
		if (answ=="ok") {
			reloadOrder(orderStatus.id);
		};
		console.log(answ);
		// $('.table-orders [data-orderid='+orderStatus.id+']').css('opacity', '1');
	})
	.fail(function(answ) {
		console.log("error - "+answ);
		// $('.table-orders [data-orderid='+orderStatus.id+']').css('opacity', '1');
	});
}

function newOrdersRender(prevId, lastId)
{
	console.info(prevId+" -> "+lastId);
	for (var i = (prevId*1 + 1); i <= lastId; i++) {
		console.log('orderId= '+i);
		renderOrder(i);
	}
	return true;
}

function renderTitle()
{
	var $orderTitleHtml = "";

	$.ajax({
		url: '/admin/orders/getOrderTitle',
		type: 'POST',
		data: {token:'getOrder'},
	})
	.done(function(domTitle) {
		$orderTitleHtml = domTitle;
		$('.table-orders').parent('.table').siblings('h3').remove();
		$('.table-orders').parent('.table').before($orderTitleHtml);
		console.log('renderTitle finished');
		return true;
	})
	.fail(function(answ) {
		console.log("error - "+answ);
	})
}

function renderOrder(orderId)
{
	var $orderHeaderHtml = "<tr class='order-header' data-orderid='"+orderId+"'>";
	var $orderBodyHtml = "<tr class='order-body order-new-row' data-orderid='"+orderId+"''>";

	var $promise = $.when(
		$.ajax({
			url: '/admin/orders/getOrderHeader',
			type: 'POST',
			data: {token:'getOrder', orderId: orderId},
		})
		.done(function(domHeader) {
			$orderHeaderHtml += domHeader;
		})
		.fail(function(answ) {
			console.log("error - "+answ);
		}),
		$.ajax({
			url: '/admin/orders/getOrderBody',
			type: 'POST',
			data: {token:'getOrder', orderId: orderId},
		})
		.done(function(domBody) {
			$orderBodyHtml += domBody;
		})
		.fail(function(answ) {
			console.log("error - "+answ);
		})
	);

	$promise.done(function(){
		$orderHeaderHtml += "</tr>";
		$orderBodyHtml += "</tr>";
		$('.table-orders tbody tr:eq(0)').before($orderHeaderHtml);
		$('.table-orders tbody tr:eq(0)').after($orderBodyHtml);
		console.log('renderOrder finished');
		renderTitle();
		return true;
	});
}

function reloadOrder(orderId)
{
	var $orderHeader = $('.order-header[data-orderid='+orderId+']');
	var $orderBody = $('.order-body[data-orderid='+orderId+']');
	var $orderHeaderHtml = "";
	var $orderBodyHtml = "";
	var $orderTitleHtml = "";

	var $promise = $.when(
		$.ajax({
			url: '/admin/orders/getOrderHeader',
			type: 'POST',
			data: {token:'getOrder', orderId: orderId},
		})
		.done(function(domHeader) {
			$orderHeaderHtml = domHeader;
		})
		.fail(function(answ) {
			console.log("error - "+answ);
		}),
		$.ajax({
			url: '/admin/orders/getOrderBody',
			type: 'POST',
			data: {token:'getOrder', orderId: orderId},
		})
		.done(function(domBody) {
			$orderBodyHtml = domBody;
		})
		.fail(function(answ) {
			console.log("error - "+answ);
		}),
		$.ajax({
			url: '/admin/orders/getOrderTitle',
			type: 'POST',
			data: {token:'getOrder'},
		})
		.done(function(domTitle) {
			$orderTitleHtml = domTitle;
		})
		.fail(function(answ) {
			console.log("error - "+answ);
		})
	);

	$promise.done(function(){
		console.log('promise done');
		$orderHeader.html($orderHeaderHtml);
		$orderHeader.after($orderBodyHtml);
		$('.order-body[data-orderid='+orderId+']').addClass('on');
		$orderBody.remove();
		$('.table-orders').parent('.table').siblings('h3').remove();
		$('.table-orders').parent('.table').before($orderTitleHtml);
		$('.table-orders [data-orderid='+orderId+']').css('opacity', '1');
		newOrdersCounter();
		return true;
	});
}

function orderRowClick()
{
	$('table.table-orders').on('click', 'tr.order-header', function(event) {
		event.preventDefault();
		var $order = $(this).data('orderid');
		$(this).parent().find('tr.order-body[data-orderid='+$order+']').toggleClass('on');
	});
}