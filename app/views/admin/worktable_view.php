<script src="/js/admin/main.js"></script>
<script src="/js/admin/worktable.js"></script>

<div class="jumbotron main-content" style="background-color: transparent;">
<div class="container-fluid">

	<div class="row mt-10">
		<div class="col-xs-12 col-lg-8">
			<?php include "order_title_view.php"; ?>
			<div class="table" style="background:white;">
				<table class="table table-hover table-orders mb-0">
					<thead>
						<tr>
							<th>#</th>
							<th>Статус</th>
							<th>Время</th>
							<th>Телефон</th>
							<th>Имя</th>
							<th class="ta-r">Сумма</th>
						</tr>
					</thead>
					<tbody>
					<?php
						foreach ($orders as $order) {
							?>
							<tr class="order-header" data-orderid="<?php echo $order['id']; ?>">
							<?php include "order_header_view.php"; ?>
							</tr>
							<?php include "order_body_view.php";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>
</div>