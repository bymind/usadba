<script src="/js/admin/main.js"></script>

<div class="jumbotron main-content" style="background-color: transparent;">
<div class="container-fluid">

	<div class="row">
		<div class="col-xs-12 col-md-8">
			<h3>Заказы
			<span class="badge danger" title="новые">2 новых</span>
			<span class="badge warning" title="в пути">0 в пути</span>
			<span class="badge success" title="доставлены">0 доставлены</span>
			<span class="badge fail" title="отмена">0 отмена</span>
			<span class="badge info" title="всего">2 всего</span>
			</h3>
			<div class="table-responsive" style="background:white;">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Статус</th>
							<th>Время</th>
							<th>Телефон</th>
							<th>Имя</th>
							<th>Сумма</th>
						</tr>
					</thead>
					<tbody>
					<?php
						foreach ($orders as $order) {
						?>
						<tr class="order-<?php echo $order['stat']; ?>-row">
							<td class="order-stat"><?php echo $order['stat']; ?></td>
							<td class="order-time"><?php echo $order['datetime']; ?></td>
							<td class="order-phone"><?php echo $order['phone']; ?></td>
							<td class="order-phone"><?php echo $order['name']; ?></td>
							<td class="order-price"><?php echo $order['prod_list']['sumPrice']; ?> руб.</td>
						</tr>
						<?php
						}
					 ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>
</div>