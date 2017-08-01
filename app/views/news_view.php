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
						<span>Наши новости</span>
					</div>
				</div>
			</div>

<?php

					 // Controller::dump($pageData);
					$newsItems = $pageData;
					$newsCount = 1;
					// var_dump($pageData);
					foreach ($newsItems as $newsItem) {
?>
					<div class="row">
					<div class="col-xs-12">
						<div class="shadow br-2 bg-f news-card mb-20" data-newsid="<?php echo $newsItem['id']?>">
							<div class="row">
								<div class="col-xs-5 poster-overlay">
									<a href="/news/<?php echo $newsItem['url']?>"><img src="<?php echo $newsItem['poster']?>" alt="<?php echo $newsItem['title']?>" class="news-poster"></a>
								</div>
								<div class="col-xs-7">
									<div class="news-info pl-20">
										<h3 class="news-title">
										<a href="/news/<?php echo $newsItem['url']?>"><?php echo $newsItem['title']?></a>
										</h3>
										<p class="news-anons">
										<?php
										if ((isset($newsItem['anons'])) && ($newsItem['anons']!="") ) {
											echo $newsItem['anons'];
										} else {
											$fakeAnons = mb_substr(strip_tags($newsItem['body']), 0, 120, "UTF-8")."...";
											echo $fakeAnons;
										}
										?>
										</p>
										<a class="more" href="/news/<?php echo $newsItem['url']?>">подробнее</a>
										<div class="sub-details mt-10">
											<span class="datetime"><?php echo $newsItem['datetime']?></span>
											<span class="comments" style='display:none'><?php echo $newsItem['datetime']?></span>
										</div>
									</div>
								</div>
							</div>
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