<script src="/js/admin/main.js"></script>
<script src="/js/admin/worktable.js"></script>

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
				<table class="table table-hover table-orders mb-0">
					<thead>
						<tr>
							<th>#</th>
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
						<tr class="order-header" data-orderid="<?php echo $order['id']; ?>">
							<td class="order-id"><?php echo $order['id']; ?></td>
							<td class="order-stat order-<?php echo $order['stat_label']; ?>-row"><?php echo $order['stat_text']; ?></td>
							<td class="order-time"><?php echo $order['datetime']; ?></td>
							<td class="order-phone"><?php echo $order['phone']; ?></td>
							<td class="order-phone"><?php echo $order['name']; ?></td>
							<td class="order-price"><?php echo $order['prod_list']['sumPrice']; ?> руб.</td>
						</tr>
						<tr class="order-body order-<?php echo $order['stat_label']; ?>-row" data-orderid="<?php echo $order['id']; ?>">
							<td colspan="6">
								<div class="container-fluid">
								<div class="row mb-0 mt-0">
								<div class="col-xs-4 pl-0 pr-0">
									<table class="order-details-table table table-bordered">
										<tr>
											<th>№ заказа</th>
											<td><?php echo $order['id']?></td>
										</tr>
										<tr>
											<th>Заказ на имя</th>
											<td>
												<?php echo $order['name']; ?>
											</td>
										</tr>
										<tr>
											<th>Аккаунт</th>
											<td><?php
														if ($order['uid']>0) {
															?>
																<a href="/admin/users/<?php echo $order['uid']; ?>" target="_blank"><?php echo $order['user']['name']; ?></a>
															<?php
														} else {
															?>
																не зарегистрирован
															<?php
														}
											?></td>
										</tr>
										<tr>
											<th>Телефон</th>
											<td>
												<?php echo $order['phone']; ?>
											</td>
										</tr>
										<tr>
											<th>Оплата</th>
											<td>
												<?php if ($order['pay_type']=="cash") {
													echo "наличными";
												} if ($order['pay_type']=="online") {
													echo "картой онлайн";
												} ?>
											</td>
										</tr>
										<tr>
											<th>Комментарий</th>
											<td><?php echo $order['comm']; ?></td>
										</tr>
									</table>
								</div>
								<div class="col-xs-8 pl-10 pr-0">
									<table class="order-prods-table table table-bordered">
									<thead>
										<tr>
											<th>Артикул</th>
											<th>Наименование</th>
											<th>Кол-во</th>
											<th>Цена</th>
											<th>Стоимость</th>
										</tr>
									</thead>
									<?php
										foreach ($order['prod_list']['items'] as $prod) {
											?>
												<tr>
													<td><?php echo $prod['art']?></td>
													<td class="prod-name-cell"><?php echo $prod['name']; ?></td>
													<td><?php echo $prod['count']; ?></td>
													<td><?php echo $prod['price']; ?></td>
													<td><?php echo $prod['price']*$prod['count'] ?></td>
												</tr>
											<?php
										}
									?>
									</table>
								</div>
								</div>
								</div>
							</td>
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