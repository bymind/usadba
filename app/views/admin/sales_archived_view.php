<script src="/js/admin/main.js"></script>
<script src="/js/admin/sales.js"></script>
<div class="main-content">

<div class="container-fluid">

	<div class="row pt-0 pb-0">
		<div class="col-md-12">
			<h4 class="blocked">
				<a href="/admin/sales" class="disabled">
				Действующие
				</a>
			</h4>
			<h1 class="blocked">
					Архив
			</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-md-9 pl-0">

			<?php
				if (!$posts) {
					?>
					<div class="col-md-6 col-md-offset-3" style="text-align:center;">
						<h2>Нет завершенных акций</h2>
					</div>
					<?php
				} else
				foreach ($posts as $post) {
			?>


			<div class="col-xs-12 col-sm-4 col-md-3">
			<div class="blog-item">
				<div class="poster" style="background-image: url(<?php echo $post['poster'] ?>);">
					<div class="controls hidden-xs">
						<a href="/admin/sales/edit/<?php echo $post['tech_name'] ?>" class="btn-edit">Редактировать</a>

						<a href="/admin/sales/delete/<?php echo $post['tech_name'] ?>" data-target="<?php echo htmlspecialchars($post['name']) ?>" data-id="<?php echo $post['id'] ?>" class="btn-delete" style="width: 90%;margin: 10px 5% 0;">Удалить</a>
					</div>
					<div class="controls visible-xs">
						<a href="/admin/sales/edit/<?php echo $post['tech_name'] ?>"></a>
					</div>
				</div>
				<div class="details">
					<div class="tags" style="display:none">
						<div class="tag-item"></div>
					</div>
					<div class="title"><a href="/admin/sales/edit/<?php echo $post['url'] ?>"><?php echo $post['name'] ?></a></div>
					<div class="post-date">Нач. <?php echo $post['start_time'] ?><br>Кон. <?php echo $post['end_time'] ?></div>
				</div>
			</div>
			</div>

			<?php
			}
			?>


		</div>


	</div>

</div>
</div>

<script>
	lookNew();
	lookDelete();
	lookUnArchive();
</script>