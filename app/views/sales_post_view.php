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


	<div class="col-xxs-12 col-xs-12 col-sm-9 clearfix">
		<div class="shadow br-2 bg-f mb-20">
			<div class="sale-card" data-saleid="<?php echo $pageData['poster']?>">
				<div class="details">
					<div class="start">Акция запущена <?php echo $pageData['start_time']?></div>
					<?php
						$today = date('Y-m-d H:i:s');
						// echo $today."<br>".$pageData['raw_end_time'];
						if ($pageData['raw_end_time']<=$today) { ?>
						<div class="end">Завершилась <?php echo $pageData['end_time']?></div>
						<?php } else {
					?>
					<div class="end">Завершится <?php echo $pageData['end_time']?></div>
					<?php }
					?>
				</div>
			</div>
			<div class="description">
				<p>
					<?php echo $pageData['description'] ?>
				</p>
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