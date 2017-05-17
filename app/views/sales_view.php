<div class="container mb-20">
	<div class="row">

	<div class="hidden-xs col-sm-3">

		<?php
			if (is_array($sidebar)) {
				foreach ($sidebar as $sidebar_item) {
					include $sidebar_item;
				}
			} else {
				include $sidebar;
			}
		?>

	</div>

	<div class="col-xs-12 col-sm-9">
		<div class="cart-box mb-20 mb-xxs-10">
			<div class="container-fluid">
				<div class="row">

				<div class="col-xs-12">
					<div class="title-wide mb-20 mb-xs-10">
						<span>Действующие акции</span>
					</div>
				</div>
			</div>

<?php
					$saleItems = $pageData;
					$saleCount = 1;
					foreach ($saleItems as $saleItem) {
?>
					<div class="row">
						<div class="shadow br-2 bg-f sale-card mb-20" data-saleid="<?php echo $saleItem['poster']?>">
						<a href="/sales/<?php echo $saleItem['tech_name']?>" class="sale-overlay"></a>
							<div class="details">
								<div class="start">Акция запущена <?php echo $saleItem['start_time']?></div>
								<div class="end">Завершится <?php echo $saleItem['end_time']?></div>
							</div>
						</div>
					</div>
<?php
					}
?>
				</div>
			</div>
		</div>

	<div class="col-xs-12 visible-xs">

		<?php
			if (is_array($sidebar)) {
				foreach ($sidebar as $sidebar_item) {
					include $sidebar_item;
				}
			} else {
				include $sidebar;
			}
		?>

	</div>

	</div>
</div>