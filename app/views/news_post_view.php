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
					<div class="newspost-layer shadow br-2 bg-f clearfix">

						<!-- TITLE -->
						<div class="title-wide"><?php echo $pageData['title'] ?></div>
						<div class="mini-desc mb-10"><?php echo $pageData['datetime'] ?></div>

						<div class="hidden-xxs sidecat prevcat mb-20">
							<span class="prevcat">
								<a href="/news">Назад к новостям</a>
							</span>
						</div>

						<div class="col-xs-12 mb-20">
						<div class="row">

						<!-- IMAGE -->
							<div class="col-xxs-12">
								<div class="text">
									<p>
										<img class="main mr-20 mb-20" src="<?php echo $pageData['poster'] ?>" alt="<?php echo $pageData['title'] ?>">
										<?php echo $pageData['body'] ?>
									</p>
								</div>
							</div>

						</div>
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