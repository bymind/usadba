<script src="/js/admin/main.js"></script>
<!-- <script src="/js/admin/worktable.js"></script> -->

<div class="jumbotron main-content" style="background-color: transparent;">
<div class="container-fluid">

	<div class="row mt-10">
		<div class="col-xs-12 col-lg-8 col-orders">

			<h3>Ваша область доступа ограничена разделами:</h3>
			<p>
				<?php
				// var_dump($user);
				foreach ($user['admin_rights'] as $right) {
					switch ($right) {
						case 'orders':
							echo "<a href='/admin'>Рабочий стол (заказы)</a><br>";
							break;

						case 'goods':
							echo "<a href='/admin/goods'>Товары</a><br>";
							echo "<a href='/admin/sales'>Акции</a><br>";
							break;

						case 'news':
							echo "<a href='/admin/articles'>Новости</a><br>";
							break;

						case 'pages':
							echo "<a href='/admin/pages'>Страницы</a><br>";
							break;

						case 'users':
							echo "<a href='/admin/users/all'>Аккаунты</a><br>";
							break;

						default:
							# code...
							break;
					}
				}
				 ?>
			</p>

		</div>

		<div class="col-xs-12 col-lg-4 col-stats">

		<!-- 	<h3>Статистика</h3>
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
		</div> -->

		</div>

	</div>

</div>
</div>