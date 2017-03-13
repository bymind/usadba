<script src="/js/admin/main.js"></script>
<script src="/js/admin/users.js"></script>

<div class="main-content">

<div class="container-fluid">

	<div class="row pt-0 pb-0">
		<div class="col-md-12">
			<h1 class="blocked">
				Аккаунты администраторов
			</h1>
		</div>
	</div>

	<div class="col-xs-12 col-md-6 pt-0">
	<div class="row pt-0 mt-0">
	<div class="table">
			<table class="table table-hover table-bordered users-table">
					<thead>
						<tr>
							<th style="width:40px">id</th>
							<th>Логин</th>
							<?php if ($_SESSION['is_su']==1){ echo "<th>SU</th>"; }?>
							<th>E-mail</th>
						</tr>
					</thead>
					<tbody>
					<?php
						foreach ($users as $user) {
						?>
						<tr>
							<th scope="row"><?php echo $user['id'] ?></th>
							<td><?php echo $user['login'] ?></td>
							<?php if (Controller_Admin::isSuper()){ echo "<td>".$user['is_super']."</td>"; } ?>
							<td><?php echo $user['email'] ?></td>
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