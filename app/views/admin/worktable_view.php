<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="/js/admin/main.js"></script>
<script src="/js/admin/worktable.js"></script>

<div class="jumbotron main-content" style="background-color: transparent;">
<div class="container-fluid">

	<div class="row mt-10">
		<div class="col-xs-12 col-lg-8 col-orders">

			<div class="pull-right" <?php if ($ordersCounter['all'] <= 20) {
				?> style="display:none;" <?php
			} ?> >
			<ul class="pagination mt-0 mb-0" data-curpage="<?=$curPage;?>">
				<li <?php if ($curPage==1) {echo "class='disabled'";} ?> >
					<a href="#" aria-label="previous" title="предыдущая" data-target="minus">
						<span aria-hidden="true">&lsaquo;</span>
					</a>
				</li>
				<?php
					$addClass="";
					for ($i=1; $i <= $pagesCount; $i++) {
						if ($curPage==$i) $addClass = "class='active'";
						?>
						<li <?=$addClass;?> ><a href="#page=<?=$i;?>" data-target="<?=$i;?>"><?=$i;?></a></li>
						<?php
						$addClass = "";
					}
				 ?>
				<li <?php if ($curPage==$pagesCount) {echo "class='disabled'";} ?> >
					<a href="#" aria-label="next" title="следующая" data-target="plus">
						<span aria-hidden="true">&rsaquo;</span>
					</a>
				</li>
			</ul>
			</div>
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
					<?php include "order_tbody_view.php"; ?>
					</tbody>
				</table>
			</div>

		</div>

		<div class="col-xs-12 col-lg-4 col-stats pl-0 pr-0 panel panel-stat">
			<div class="panel-heading">
			<h4>Статистика</h4>
			</div>
			<div class="panel-body pt-0">
			<div class="col-xs-12 pl-0 pr-0 stats-box stats-sales" data-uniq="stats-sales">
				<h4 class="clearfix"><span>Продажи за </span><div class="time-period dropdown"><button class="btn btn-default dropdown-toggle ml-5" type="button" id="dropdownSales" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="txt mr-5">сегодня</span><span class="caret"></span></button>
													<ul class="dropdown-menu" aria-labelledby="dropdownSales">
														<!-- <li><a href="#" data-type="sales" class="d-n" data-period="today">сегодня</a></li> -->
														<li class="disabled"><a href="#" data-type="sales" data-period="today">сегодня</a></li>
														<li><a href="#" data-type="sales" data-period="yesterday">вчера</a></li>
														<li><a href="#" data-type="sales" data-period="week">неделю</a></li>
														<li><a href="#" data-type="sales" data-period="month">месяц</a></li>
														<li><a href="#" data-type="sales" data-period="quarter">квартал</a></li>
														<!-- <li><a href="#" data-type="sales" data-period="year">год</a></li> -->
													</ul>
												</div>
				<span class='stat-periodTxt'></span>
</h4>
				<div class="stat-data">
					<div class="table mb-20" style="background:white">
						<table class="table table-bordered table-stats">
							<thead>
								<tr class="bg-primary">
									<th>Заказы</th>
									<th>Доход</th>
									<th>Ср. чек</th>
								</tr>
							</thead>
							<tbody>
								<tr class="stats-firstrow bg-primary">
									<td class="stat-countOrders">-</td>
									<td><span class="stat-profit">-</span> <small>руб.</small></td>
									<td><span class="stat-middleCheck">-</span> <small>руб.</small></td>
								</tr>
								<tr class="stats-chart-row d-n">
									<td colspan="3">
										<div class="stat-countOrders-chart mt-10 pl-10 pr-5">
											<div class="stat-chart-div" data-chartid="stat-countOrders-chart">
												<canvas id="stat-countOrders-chart"></canvas>
											</div>
										</div>
									<div class="stat-profit-chart mt-10 pl-10 pr-5">
											<div class="stat-chart-div" data-chartid="stat-profit-chart">
												<canvas id="stat-profit-chart"></canvas>
											</div>
										</div>
										<div class="stat-middleCheck-chart mt-10 pl-10 pr-5">
											<div class="stat-chart-div" data-chartid="stat-middleCheck-chart">
												<canvas id="stat-middleCheck-chart"></canvas>
											</div>
										</div>
									</td>
								</tr>
								<tr class="stats-nochart-row" style="display:none">
									<td>
										<div class="stat-countOrders-changes">
											-0.3%
										</div>
									</td>
									<td>
										<div class="stat-profit-changes">
											+34.3%
										</div>
									</td>
									<td>
										<div class="stat-middleCheck-changes">
											-4.3%
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		</div>

	</div>

</div>
</div>