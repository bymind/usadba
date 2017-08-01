<div class="hidden-xs side-news shadow br-2 mb-20">
	<div class="side-news-title">
		<a href="/news">Новости</a>
	</div>

	<div id="carousel-news" class="owl-carousel pod-theme prod-pod">
	<?php
		foreach ($sideNews as $post) {
			?>
		<div class="item">
			<div class="news-img box-img display"><a href="/news/<?=$post['url'];?>" class="news-link" title="<?=$post['title'];?>">
				<img src="<?=$post['poster'];?>" alt="<?=$post['url'];?>">
			</a>
			</div>
			<div class="news-title"><a href="/news/<?=$post['url'];?>"><?=$post['title'];?></a></div>
			<?php
			if ((isset($post['anons'])) && ($post['anons']!="") ) {
				?>
			<div class="news-anons"><?=$post['anons'];?><a class="more" href="/news/<?=$post['url'];?>">подробнее</a></div>
				<?php
			} else {
				$anonsBody = mb_substr(strip_tags($post['body']), 0, 90, "UTF-8")."...";
				?>
			<div class="news-anons"><?=$anonsBody;?><a class="more" href="/news/<?=$post['url'];?>">подробнее</a></div>
				<?php
			}
			?>
			</div>
			<?php
		}
	 ?>
	</div>

</div>