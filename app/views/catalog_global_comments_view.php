<?php
	if (!$prodReviews) {
		echo "<h4 style='text-align: center'>Отзывов пока нет</h4>";
	} else {
	unset($prodReviews['count']);
	foreach ($prodReviews as $review) {
		?>
									<div class="comment-over clearfix br-2 shadow mb-10">
										<div class="avatar mini">
											<img src="<?php echo $review['author_avatar']?>" alt="<?php echo $review['author_name']?>">
										</div>
										<div class="com-details">
											<span class="author"><?php echo $review['author_name']?></span><span class="pub-time"><?php echo $review['pub_time']?></span>
											<div class="com-text">
												<?php echo $review['com_text']?>
											</div>
										</div>
									</div>
		<?php
		}
		?>
		<input type="hidden" id="newCountReviews" value="<?=$recounted?>"></input>
		<?php
	}