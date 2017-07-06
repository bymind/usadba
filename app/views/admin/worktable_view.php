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

		<div class="col-xs-12 col-lg-4 col-stats">

			<h3>Статистика</h3>
			<div class="table mb-20" style="background:white">
				<table class="table table-bordered table-stats">
					<thead>
						<tr>
							<th>Заказы</th>
							<th>Доход</th>
							<th>Ср. чек</th>
						</tr>
					</thead>
					<tbody>
						<tr class="stats-firstrow">
							<td>31</td>
							<td>45 233 <small>руб.</small></td>
							<td>310 <small>руб.</small></td>
						</tr>
					</tbody>
				</table>
			</div>

			<h3>Статистика</h3>
			<div class="table mb-20" style="background:white">
				<table class="table table-bordered table-stats">
					<thead>
						<tr>
							<th>Заказы</th>
							<th>Доход</th>
							<th>Ср. чек</th>
						</tr>
					</thead>
					<tbody>
						<tr class="stats-firstrow">
							<td>31</td>
							<td>45 233 <small>руб.</small></td>
							<td>310 <small>руб.</small></td>
						</tr>
					</tbody>
				</table>
			</div>

			<h3>Статистика</h3>
			<div class="table mb-20" style="background:white">
				<table class="table table-bordered table-stats">
					<thead>
						<tr>
							<th>Заказы</th>
							<th>Доход</th>
							<th>Ср. чек</th>
						</tr>
					</thead>
					<tbody>
						<tr class="stats-firstrow">
							<td>31</td>
							<td>45 233 <small>руб.</small></td>
							<td>310 <small>руб.</small></td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>

	</div>

</div>
</div>