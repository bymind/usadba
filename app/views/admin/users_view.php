<script src="/js/admin/main.js"></script>
<script src="/js/admin/users.js"></script>

<div class="main-content">

<div class="container-fluid">

	<div class="row pt-0 pb-0">
		<div class="col-md-12">
			<h4 class="blocked">
				<a href="/admin/users/all">Пользователи</a>
			</h4>
			<h1 class="blocked">
				Администраторы
			</h1>
		</div>
	</div>

	<div class="col-xs-12 col-md-8 pt-0">
	<div class="row pt-0 mt-0">
	<div class="table">
			<table class="table table-hover table-bordered users-table">
					<thead>
						<tr>
							<th style="width:40px">id</th>
							<th>Логин</th>
							<th>Имя</th>
							<?php if ($_SESSION['user']['is_super']==1){ echo "<th>SU</th>"; }?>
							<th>E-mail</th>
							<th>Права</th>
						</tr>
					</thead>
					<tbody>
					<?php
						foreach ($users as $user) {
						?>
						<tr class="admin-users">
							<th scope="row"><?php echo $user['id'] ?></th>
							<td class="data"><?php echo $user['login'] ?></td>
							<td class="data"><?php echo $user['name'] ?></td>
							<?php if (Controller_Admin::isSuper()){ echo "<td class='data'>".$user['is_super']."</td>"; } ?>
							<td class="data"><?php echo $user['email'] ?></td>
							<td class="data"><?php
									foreach ($user['admin_rights_texts'] as $right) {
										echo "$right<br>";
									}?>
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