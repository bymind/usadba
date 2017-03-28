<script src="/js/admin/main.js"></script>
<!-- <script src="/js/admin/users.js"></script> -->
<script src="/js/admin/bugtracker.js"></script>
<script src="/js/admin/bootstrap-bt.min.js"></script>
<link rel="stylesheet" href="/css/admin/bootstrap-panel.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.9.0/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.9.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<div class="main-content">
<script>
	$tJson = <?php echo json_encode($ticket); ?>;
</script>

<?php

			$active = ['Новый','В процессе','Готов'];
			switch ($ticket['status']) {
				case 'new':
					$panelType = "primary";
					$actualId = 0;
					break;
				case 'active':
					$panelType = "warning";
					$actualId = 1;
					break;
				case 'done':
					$panelType = "success";
					$actualId = 2;
					break;

				default:
					$panelType = "info";
					break;
			}
?>

<div class="container-fluid">

	<div class="row pt-0 pb-0 mb-0">

		<div class="col-xs-12 col-sm-7 ">
			<div class="headers-block">
				<div class="headers-line">
				<!-- <h4 class="mt-0 mb-0"><small>Заголовок</small></h4> -->
				<h3 class="mt-0 mb-10"><?php echo "#<span class='ticket-id'>".$ticket['id']."</span>: ".$ticket['title']."<br>"; ?>
				</h3>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-5">
		<div class="desc__labels clearfix">
			<div class="col-xs-12">
			<?php echo "<span class='label label-".$panelType."'>".$active[$actualId]."</span>"; ?>
					<span class="time-title ml-10">
						<span class="glyphicon glyphicon-time"></span><?php echo $ticket['time']; ?>
					</span>
			</div>
			<div class="col-xs-12 mt-5">
					<span class="author-title ml-0" title="Автор">
						<span class="glyphicon glyphicon-user"></span><a href="/admin/users/<?php echo $ticket['author'];?>"><?php echo $ticket['author']; ?></a>
					</span>
					<span class="author-title" title="Исполнитель">
						<span class="glyphicon glyphicon-pushpin"></span>
						<span class="doer-header-block">
						<?php if ($ticket['doer_name']==NULL) {
							echo "не назначен";
							} else {?>
						<a href="/admin/users/<?php echo $ticket['doer_name'];?>"><?php echo $ticket['doer_name']; ?></a>
						<?php } ?>
						</span>
					</span>
			</div>
		</div>
		</div>

	</div>

	<div class="row pt-0 mt-0">
	<div class="col-xs-12 col-md-8 pt-0">

<!-- <h4 class="mt-10 mb-0"><small>Описание</small></h4> -->
<div class="panel panel-default <?php echo $panelType; ?> mt-10">
	<div class="panel-body">
		<div class="card__description one-ticket"><?php echo $ticket['body']; ?></div>
	</div>
	<div class="panel-footer clearfix success">
		<div class="card__description one-ticket answer-ticket deleted">
		<code class="success">Выполнено <?php echo $ticket['answer_time'];?> пользователем <span class="author-title ml-0" title="Исполнитель"><span class="glyphicon glyphicon-user"></span><a href="/admin/users/<?php echo $ticket['doer_name'];?>"><?php echo $ticket['doer_name']; ?></a></span></code>
		<blockquote>
			<p style="margin-bottom:0">
				<small><code>Комментарий исполнителя</code></small>
				<br><div class="markdown"><?php echo $ticket['answer'];?></div>
			</p>
		</blockquote>
		</div>
	</div>
	<div class="panel-footer clearfix">
					<div class="card__actions">
						<?php if (Controller_Admin::isSuper()){?>
							<div class="btn btn-default btn-xs card__btn__edit"><span class="glyphicon glyphicon-pencil mr-5"></span>Редактировать</div>
							<?php if ($ticket['doer']=="не назначен") {
							?>
							<div class="btn-group" style="float:left; margin-right:10px;">
							<div class="btn btn-default btn-xs card__btn__link dropdown-toggle" type="button" data-toggle="dropdown"><span class="glyphicon glyphicon-pushpin mr-5"></span>Назначить исполнителя <span class="caret"></span></div>
								<ul class="dropdown-menu">
								<?php
									$usersDoer = Model_Admin::getUsers();
									foreach ($usersDoer as $uDoer) {
									?>
									<li class="doer-name" data-userid="<?php echo $uDoer['id']; ?>"><?php echo $uDoer['login']; ?></li>
									<?php
									}
								?>
								</ul>
								<ul class="hidden-doer">
									<li class="set-doer">Изменить</li>
								</ul>
							</div>
							<?php
							} else {?>
							<div class="btn-group" style="float:left; margin-right:10px;">
							<div class="btn btn-default btn-xs card__btn__link dropdown-toggle" type="button" data-toggle="dropdown"><span class="glyphicon glyphicon-pushpin mr-5"></span>Исполнитель: <?php echo $ticket['doer'];?></div>
								<ul class="dropdown-menu">
									<li class="set-doer">Изменить</li>
								</ul>
								<ul class="hidden-doer">
								<?php
									$usersDoer = Model_Admin::getUsers();
									foreach ($usersDoer as $uDoer) {
									?>
									<li class="doer-name" data-userid="<?php echo $uDoer['id']; ?>"><?php echo $uDoer['login']; ?></li>
									<?php
									}
								?>
								</ul>
							</div>
							<?php
								}
							?>
							<div class="btn btn-default btn-xs card__btn__answer"><span class="glyphicon glyphicon-edit mr-5"></span>Написать ответ</div>
							<?php } ?>
							<?php if (Controller_Admin::isSuper() || ($_SESSION['user']['name']==$ticket['author']) ){?>
							<div class="btn btn-default btn-xs card__btn__delete"><span class="glyphicon glyphicon-trash mr-5"></span>Удалить</div>
						<?php } ?>
						<div class="btn btn-default btn-xs card__btn__files"><span class="glyphicon glyphicon-paperclip mr-5"></span>0 файлов</div>
					</div>
	</div>
</div>

	</div>
</div>

</div>
</div>

<script>
$(function() {
	formatMarkdownHeaders();
});
</script>