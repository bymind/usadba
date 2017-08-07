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
			<div class="container-fluid global-reviews">
				<div class="row">

				<div class="title-wide mb-20">
							<a href="#" class="global-reviews-link give-review<?php if (!$isLogged) {?> fake-like<?php }?>">Оставить отзыв</a>
					<h1><?=$pageDataView['title']?></h1>
				</div>

				</div>

		<?php
			if ($isLogged) {
				?>
							<div class="comment-over comm-form clearfix br-2 shadow mb-20">
								<div class="avatar mini">
									<img src="<?php echo $_SESSION['user']['avatar']; ?>" alt="<?php echo $_SESSION['user']['name'];?>">
								</div>
								<div class="com-details">
									<span class="author"><?php echo $_SESSION['user']['name'];?></span>
									<div class="com-text">
										<textarea name="comment" id="comment-text" maxlength="2000" rows="3"></textarea>
										<div class="row mt-10 mb-10">
											<div class="col-xxs-12 col-xs-6 col-sm-3 pr-10">
												<button type="button" class="btn btn-primary comm-send" data-type="reviews" data-prodid="<?=$currentProduct['id']?>">Отправить</button>
											</div>
											<div class="col-sm-9 hidden-xs note-wrapper pl-10">
												<span class="note">Не более 2 000 символов. <span id="count_letters">Осталось <span id="count_num"></span>.</span><br>Отзыв, превышающий это ограничение, будет обрезан.</span>
											</div>
										</div>
									</div>
								</div>
							</div>
				<?php
			}
		?>

	<div class="comments-box" data-prodid="<?=$currentProduct['id']?>">
		<?php
			if (!$reviews || $reviews['count']==0) {
				echo "<h4 style='text-align: center'>Отзывов пока нет</h4>";
			} else {
			unset($reviews['count']);
			foreach ($reviews as $review) {
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
		}
		?>
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