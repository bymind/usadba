$(function() {
	orderRowClick();
	lookForStatusEdit();
	paginationOrders();
	statsInit();
	x = "";
});


function statsInit()
{
	if ($('.col-stats')===undefined) {} else
	{
		loadCurrentStats();

		$('.stats-box').on('click', 'ul.dropdown-menu li a', function(event) {
			event.preventDefault();

			// $(this).parents('ul').eq(0).find('.d-n').removeClass('d-n');
			$(this).parents('ul').eq(0).find('.disabled').removeClass('disabled');
			// $(this).addClass('d-n');
			$(this).parent().addClass('disabled');
			var statsBox = $('.'+$(this).parents('.stats-box').data('uniq'));
			var txt = $(this).text();
			$(this).parents('.stats-box').eq(0).find('button.dropdown-toggle span.txt').text(txt);
			var statData = {
				type: $(this).data('type'),
				period: $(this).data('period')
			}
			$.when(loadStatData(statData)).done(function(answ){
				console.table([answ.statData]);
				x = answ;
				pasteStatsData(statsBox,answ.statData.dataToPaste, statData.period);
				if (answ.statData.hasChart) {
					console.log(answ.statData.shartData);
					pasteStatsChartData(statsBox,answ.statData.chartData, statData.period);
				}
			}).fail(function(err){
				console.error(err);
			});

		});
	}
}

function loadCurrentStats()
{
	$.each($('.stats-box'), function(index, val) {
		var statsBox = $('.stats-box').eq(index);
		// var curItem = statsBox.find('.dropdown-menu a.d-n');
		var curItem = statsBox.find('.dropdown-menu li.disabled a');
		var statData = {
			type: curItem.data('type'),
			period: curItem.data('period')
		}
		$.when(loadStatData(statData)).done(function(answ){
			console.table([answ.statData]);
			x = answ;
			pasteStatsData(statsBox,answ.statData.dataToPaste, statData.period);
			if (answ.statData.hasChart) {
				console.log(answ.statData.chartData);
				pasteStatsChartData(statsBox,answ.statData.chartData, statData.period);
			}
		}).fail(function(err){
			console.error(err);
		});
	});
}

function pasteStatsData(box,data,period)
{
	box.find('tr.stats-chart-row').addClass('d-n').find('.stat-chart-div').html("");
	box.find('tr.stats-nochart-row').removeClass('d-n');
	$.each(data, function(index, val) {
		box.find('.stat-'+index).text(val);
	});

}

function pasteStatsChartData(box, chartData)
{
	var chartsDivs = box.find('.stat-chart-div');
	for (var i = chartsDivs.length - 1; i >= 0; i--) {
		var chartId = chartsDivs.eq(i).data('chartid');
		chartsDivs.eq(i).html("<canvas id="+chartId+"></canvas>")
	}
	box.find('tr.stats-chart-row').removeClass('d-n');
	box.find('tr.stats-nochart-row').addClass('d-n');
	if (chartData == undefined) {
		console.error("chartData undefined");
		return false;
	}

	// console.trace(chartData);
	$.each(chartData, function(index, val) {
		val.canvasId = "stat-"+index+"-chart";
		drawChart(val.canvasId, val.chartData);
	});

}

function drawChart(canvasId, data)
{
	var ctx = document.getElementById(canvasId);
	var chart = new Chart(ctx, {
			type: 'line',
			data: data,
			options: {
					animation: {
						duration: 0, // general animation time
					},
					hover: {
							animationDuration: 0, // duration of animations when hovering an item
					},
					responsiveAnimationDuration: 0, // animation duration after a resize
					elements:{
						point:{
							radius: 2,
							hitRadius: 5,
							hoverRadius: 3,
						},
						line: {
								tension: 0.3, // disables bezier curves
								borderWidth: 2
						}
					},
					maintainAspectRatio: false,
					animation: {
						duration: 400,
						easing: 'easeInOutQuart'
					},
					tooltips: {
						displayColors: false,
						cornerRadius: 4,
						caretPadding: 5,
						backgroundColor: 'rgba(0, 0, 0, .8)'
					},
					legend: {
						display: false
					},
					title: {
						display: true,
						text: data.title,
						fontSize: 16
					},
					scales: {
							yAxes: [{
									stacked: true,
									ticks: {
											beginAtZero:true
									}
							}]
					},
			}
	});
}

function loadStatData(statData)
{
	return $.Deferred(function(def){
		var dataJson = JSON.stringify(statData);
		$.ajax({
				url: '/admin/orders/getStatData',
				type: 'POST',
				data: {data: dataJson},
				success: function(res){
					console.log(res);
					var answ = $.parseJSON(res);
					if (answ.success) {
						def.resolve(answ);
					} else {
						def.reject(false);
					}
				},
				fail: function(err){
					def.reject(false);
				}
			});
	}).promise();
}


function paginationOrders()
{
	$('#content').on('click','ul.pagination li>a', function(event) {
		event.preventDefault();
		$curPage = $('ul.pagination').data('curpage');
		$target = $(this).data('target');
		if (($target==$curPage)||($(this).parent().hasClass('disabled'))) {} else
		switch ($target){
			case 'minus':
				if ($curPage>2) {
					loadOrdersPage($curPage-1);
				} else loadFirstPage();
				break;

			case 'plus':
				loadOrdersPage($curPage+1);
				break;

			case 1:
				loadFirstPage();
				break;

			default:
				loadOrdersPage($target);
				break;
		}
	});
	return true;
}

function loadFirstPage()
{
	console.log("loadFirstPage()");
	var $orderTbody = $('.table-orders tbody');
	var $orderContainer = $('.col-orders ');
	var $orderContainerHtml = "";
	$orderTbody.css('opacity', '.4');

	var $load = $.when(
	$.ajax({
		url: '/admin/orders/getFirstPage',
		type: 'POST',
		data: {token:'getPage'},
	})
	.done(function(dom) {
		$orderContainerHtml = dom;
	})
	.fail(function(answ) {
		console.log("error - "+answ);
	})
	);

	$load.done(function(){
		$orderContainer.html($orderContainerHtml);
		return true;
	});
}

function loadOrdersPage($pageNum)
{
	console.log("loadOrdersPage("+$pageNum+")");
	var startId = ($pageNum-1)*20;
	var $orderTbody = $('.table-orders tbody');
	var $orderContainer = $('.col-orders');
	var $orderContainerHtml = "";
	$orderTbody.css('opacity', '.4');

	var $load = $.when(
	$.ajax({
		url: '/admin/orders/getPage',
		type: 'POST',
		data: {token:'getPage', startId: startId},
	})
	.done(function(dom) {
		$orderContainerHtml = dom;
	})
	.fail(function(answ) {
		console.log("error - "+answ);
	})
	);

	$load.done(function(){
		$orderContainer.html($orderContainerHtml);
		return true;
	});
}

function lookForStatusEdit()
{
	$('#content').on('click', '.table-orders ul.status-edit li>span', function(event) {
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
		loadCurrentStats();
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
	$('#content').on('click', 'table.table-orders tr.order-header', function(event) {
		event.preventDefault();
		var $order = $(this).data('orderid');
		$(this).parent().find('tr.order-body[data-orderid='+$order+']').toggleClass('on');
	});
}