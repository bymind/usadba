$(function() {
	InitSalesBg();
});

function InitSalesBg()
{
	for (var i = 0; i < $('.sale-card').length; i++) {
		curItem = $('.sale-card:eq('+i+')');
		if (deviceW <= 600) {
			// jpgName = "/img/sales/" + curItem.data('saleid') + "-min.jpg";
			jpgName = "" + curItem.data('saleid') + "";
		} else {
			jpgName = "" + curItem.data('saleid') + "";
		}
		curItem.css({
			'background-image': 'url('+jpgName+')'
		});
	}
}