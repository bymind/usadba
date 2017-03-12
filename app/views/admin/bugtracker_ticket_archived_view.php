<script src="/js/admin/main.js"></script>
<!-- <script src="/js/admin/users.js"></script> -->
<script src="/js/admin/bugtracker.js"></script>
<script src="/js/admin/bootstrap-bt.min.js"></script>
<link rel="stylesheet" href="/css/admin/bootstrap-panel.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.9.0/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.9.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<div class="main-content">


<?php
			$ticket['title'] = 'Тикет удален';
			$active = ['Новый','В процессе','Готов','NULL'];
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
					$actualId = 3;
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
			<?php echo "<span class='label label-danger'>Deleted</span>"; ?>
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
						<?php if ($ticket['doer']=="не назначен") {
							echo $ticket['doer'];
							} else {?>
						<a href="/admin/users/<?php echo $ticket['doer'];?>"><?php echo $ticket['doer']; ?></a>
						<?php } ?>
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
		<div class="card__description deleted"><code class="danger">Удалено <?php echo $ticket['answer_time'];?> пользователем <span class="author-title ml-0" title="Исполнитель"><span class="glyphicon glyphicon-user"></span><a href="/admin/users/<?php echo $ticket['doer'];?>"><?php echo $ticket['doer']; ?></a></span></code><blockquote><p style="margin-bottom:0">
		<small><code>Причина</code></small>
		<br><div class="markdown"><?php echo $ticket['answer'];?></div></p></blockquote></div>
	</div>
	<div class="panel-footer clearfix">
					<div class="card__actions">
					<?php if (Controller_Admin::isSuper() || ($_SESSION['name']==$ticket['author']) ){?>
						<div class="btn btn-default btn-xs card__btn__restore"><span class="glyphicon glyphicon-repeat mr-5"></span>Восстановить</div>
						<?php
					} ?>
					</div>
	</div>
</div>

	</div>
</div>

</div>
</div>