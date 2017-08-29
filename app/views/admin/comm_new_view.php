<script src="/js/admin/main.js"></script>
<script src="/js/admin/users.js"></script>

<div class="main-content">

<div class="container-fluid">

	<div class="row pt-0 pb-0">
		<div class="col-md-12">
			<h1 class="blocked">
				Новые отзывы
			</h1>
			<h4 class="blocked">
				<a href="/admin/comm/approved">Одобренные</a>
			</h4>
			<h4 class="blocked">
				<a href="/admin/comm/banned">Удалённые</a>
			</h4>
		</div>
	</div>

	<div class="col-xs-12 col-md-8 pt-0">
	<div class="row pt-0 mt-0">
	<div class="table">
	<?php
		if (!$comments) {
			?>
			<table class="table table-hover users-table">
				<thead>
					<tr>
						<th style="text-align:center;background: #76b979;color: #fff;font-weight: 400;border-radius: 2px;border: 0;">
							Нет новых комментариев, всё прочитано ッ
						</th>
					</tr>
				</thead>
			</table>
			<?php
		} else {
	 ?>
			<table class="table table-hover table-bordered users-table">
					<thead>
						<tr>
							<th style="width:40px">id</th>
							<th>Аккаунт</th>
							<th>Страница</th>
							<th>Отзыв</th>
							<th>Действие</th>
						</tr>
					</thead>
					<tbody>
					<?php
						foreach ($comments as $comm) {
							// var_dump($comm['prod']);
						?>
						<tr class="new-comm <?=$comm['type']?>">
							<th scope="row"><div class="ghost" id="commId-<?=$comm['id']?>"></div><?=$comm['id']?></th>
							<td class="data"><?php echo "<a href='/admin/users/".$comm['uid']."'>".$comm['user']['name']."</a>" ?></td>
							<td class="data <?php if ($comm['type']=="review") {echo "site-review";} ?> " >
							<?php
							if ($comm['type']=="prod") {
								echo "<a href='".$comm['prod']['url']."'>".$comm['prod']['name']."</a>";
							} else
							if ($comm['type']=="review") {
								echo "<a href='/reviews'>Отзывы</a>";
							}
							?></td>
							<td class="data"><?php echo "<span class='pub-time'>".$comm['pub_time_text']."</span><br><div class='comm-text'>".nl2br($comm['com_text'])."</div>" ?></td>
							<td class="banned-col" data-status="<?=$comm['status']?>">
								<button class="btn btn-sm btn-block btn-success" data-action="approve" data-commid="<?= $comm['id']; ?>">Одобрить</button>
								<button class="btn btn-sm btn-block btn-danger" data-action="ban" data-commid="<?= $comm['id']; ?>">Удалить</button>
							</td>
						</tr>
						<?php
						}
					 ?>

					</tbody>
				</table>
				<?php } ?>
				</div>
	</div>
</div>

</div>
</div>