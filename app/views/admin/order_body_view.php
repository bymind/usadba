<tr class="order-body order-<?php echo $order['stat_label']; ?>-row" data-orderid="<?php echo $order['id']; ?>">
							<td colspan="6">
								<div class="container-fluid">
									<div class="row mb-0 mt-0">

										<div class="col-xs-12 col-sm-4 pl-0 pr-0">
											<table class="order-details-table table table-hover table-bordered mb-sm-10">
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
														<nobr><?php echo $order['phone']; ?></nobr>
													</td>
												</tr>
												<tr>
													<th>Адрес</th>
													<td>
														<?php echo $order['addr']; ?>
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
										<div class="col-xs-12 col-sm-8 pl-sm-0 pl-10 pr-0">
											<table class="order-prods-table table table-bordered table-hover mb-sm-10">
											<thead>
												<tr>
													<th>Артикул</th>
													<th>Наименование</th>
													<th>Кол-во</th>
													<th class="ta-r">Цена</th>
													<th class="ta-r">Стоимость</th>
												</tr>
											</thead>
											<tbody>
											<?php
												foreach ($order['prod_list']['items'] as $prod) {
													?>
														<tr>
															<td><?php echo $prod['art']?><br><a target="_blank" title="ссылка на товар на сайте" href="<?php echo $prod['url']; ?>">[ссылка]</a></td>
															<td class="prod-name-cell"><?php echo $prod['name']; ?></td>
															<td><?php echo $prod['count']; ?></td>
															<td class="ta-r"><nobr><?php echo number_format($prod['price'],0,',',' '); ?> руб.<nobr></td>
															<td class="ta-r"><nobr><?php echo number_format($prod['price']*$prod['count'],0,',',' '); ?> руб.</nobr></td>
														</tr>
													<?php
												}
											?>
											<tr class="active">
												<th colspan="4">Итоговая сумма</th>
												<th class="ta-r"><nobr><?php echo number_format($order['prod_list']['sumPrice'],0,',',' '); ?> руб.</nobr></th>
											</tr>
											</tbody>
											</table>
										</div>
										<div class="col-xs-12 pl-0 pr-0 mb-5">
											<div class="pull-right">
												<div class="btn-group" role="group" aria-label="actions">
													<div class="btn-group dropup" role="group">
														<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Изменить статус&nbsp;
															<span class="caret"></span>
														</button>
														<ul class="dropdown-menu dropdown-menu-right status-edit" data-orderid="<?php echo $order['id']; ?>">
															<?php
																$statLabels = ['<span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>','<span class="glyphicon glyphicon-forward" aria-hidden="true"></span>','<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>','<span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>'];
																foreach ($order['stats'] as $i=>$stat) {
																	if ($stat==$order['stat_text']) {}
																		else {
																			?>
																			<li><span class="" data-setstat="<?=$i;?>"><?=$statLabels[$i]." ".$stat;?></span></li>
																			<?php
																		}
																}
															?>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</td>
						</tr>