<script src="/js/admin/main.js"></script>
<!-- <script src="/js/admin/users.js"></script> -->
<script src="/js/admin/bugtracker.js"></script>
<script src="/js/admin/bootstrap-bt.min.js"></script>
<link rel="stylesheet" href="/css/admin/bootstrap-panel.min.css">
<div class="main-content">


<?php
			$ticket['title'] = 'Этого тикета нет';
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

		<div class="col-xs-12 col-sm-8 ">
			<div class="headers-block">
				<div class="headers-line">
				<h4 class="mt-0 mb-0"><small>Заголовок</small></h4>
				<h3 class="mt-0 mb-10"><?php echo "#".$ticket['id'].": ".$ticket['title']."<br>"; ?>
				</h3>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-4">
		<div class="desc__labels clearfix">
			<div class="col-xs-12">
				<?php echo "<span class='label label-".$panelType."'>".$active[$actualId]."</span>"; ?>
			</div>
			<div class="col-xs-12 mt-5">
			</div>
		</div>
		</div>

	</div>

	<div class="row pt-0 mt-0">
	<div class="col-xs-12 col-md-8 pt-0">

<h4 class="mt-10 mb-0"><small>Описание</small></h4>
<div class="panel panel-default <?php echo $panelType; ?>">
	<div class="panel-body">
		<div class="card__description"><h3>Да нету здесь нихуя, че смотришь?!</h3></div>
	</div>
	<div class="panel-footer clearfix">
					<div class="card__actions">
						<div class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil mr-5"></span>И с этим нихуя не поделаешь</div>
					</div>
	</div>
</div>

	</div>
</div>

</div>
</div>