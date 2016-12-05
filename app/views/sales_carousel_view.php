<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div id="sales-carousel" class="owl-carousel sales-theme">

<?php
	/*echo "<pre>";
	var_dump($pageSales);
	echo "</pre>";*/
	foreach ($pageSales as $sale) {
?>
				<div class="item"><a href="/sales/<?= $sale['tech_name']?>" class="sales-link" data-saleid="<?= $sale['poster']?>"></a></div>
<?php
	}

?>
			</div>
		</div>
	</div>
</div>