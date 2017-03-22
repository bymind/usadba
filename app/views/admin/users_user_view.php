<script src="/js/admin/main.js"></script>
<!-- <script src="/js/admin/users.js"></script> -->
<script src="/js/admin/bootstrap-bt.min.js"></script>

<div class="main-content">

<div class="container-fluid">

	<div class="row pt-0 pb-0">
		<div class="col-md-12">
			<h1 class="blocked">
				Аккаунт <?php echo $user['login'] ?>
			</h1>
		</div>
	</div>

	<div class="col-xs-12 col-sm-10 col-md-8 pt-0">
	<div class="row pt-0 mt-0">
	<div class="table-responsive">
			<table class="table table-hover table-bordered users-table">
					<thead>
						<tr>
							<th style="width:40px">id</th>
							<th>Логин</th>
							<?php if ($_SESSION['is_su']==1){ echo "<th>SU</th>"; }?>
							<th>E-mail</th>
							<th>Права</th>
							<!-- <th>Telegram token <button type="button" class="btn btn-xs btn-primary copy-token ml-10 " data-clipboard-text="<?php echo $user['telegram_token']; ?>" data-loading-text="Скопировано!">Скопировать</button></th> -->
						</tr>
					</thead>
					<tbody>
						<tr>
							<th style="vertical-align: middle;" scope="row"><?php echo $user['id'] ?></th>
							<td style="vertical-align: middle;"><?php echo $user['login'] ?></td>
							<?php if (Controller_Admin::isSuper()){ echo "<td style='vertical-align: middle;''>".$user['is_super']."</td>"; } ?>
							<td style="vertical-align: middle;"><?php echo $user['email'] ?></td>
							<td style="vertical-align: middle;"><?php echo ('Администратор'); ?></td> <!-- TODO: отображение прав администраторов -->
							<!-- <td style="vertical-align: middle;"><?php echo $user['telegram_token']; ?></td> -->
						</tr>

					</tbody>
				</table>
				</div>
	</div>
</div>

</div>
</div>

<script src="/js/admin/clipboard.min.js"></script>
<script>
	new Clipboard('.copy-token');
	var $btn = $('.copy-token');
	$btn.click(function(event) {
		$btn.button('loading');
		$btn.removeClass('btn-primary').addClass('btn-link');
		setTimeout(function(){
			$btn.button('reset');
			$btn.removeClass('btn-link').addClass('btn-primary');
		},3000)
	});
</script>