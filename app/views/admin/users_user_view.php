<script src="/js/admin/main.js"></script>
<script src="/js/admin/users.js"></script>
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

	<div class="col-sm-12 col-md-8 pt-0">
	<div class="row pt-0 mt-0">
	<div class="table-responsive">
			<table class="table table-hover table-bordered users-table">
					<thead>
						<tr>
							<th style="width:40px">id</th>
							<th>Логин</th>
							<th>Имя</th>
							<?php if (($_SESSION['user']['is_super']==1) && $user['isadmin']){ echo "<th>SU</th>"; }?>
							<th>E-mail</th>
							<th>Права</th>
							<?php if (!$user['isadmin']){
							?>
							<th>Забанен</th>
							<?php
							 } ?>
							<!-- <th>Telegram token <button type="button" class="btn btn-xs btn-primary copy-token ml-10 " data-clipboard-text="<?php echo $user['telegram_token']; ?>" data-loading-text="Скопировано!">Скопировать</button></th> -->
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row"><?php echo $user['id'] ?></th>
							<td><?php echo $user['login'] ?></td>
							<td><?php echo $user['name'] ?></td>
							<?php if (Controller_Admin::isSuper() && $user['isadmin']){ echo "<td>".$user['is_super']."</td>"; } ?>
							<td><?php echo $user['email'] ?></td>
							<td>
							<?php if ($user['isadmin']){ ?>
								<!-- <b>Администратор</b> -->
								<?php
									foreach ($user['admin_rights_texts'] as $right) {
										echo "$right<br>";
									}
								} else { echo "Пользователь"; } ?>
							</td> <!-- TODO: отображение прав администраторов -->
							<?php if (!$user['isadmin']){
							?>
							<td class="banned-col is-banned-<?=$user['banned']?>" data-status="<?=$user['banned']?>"><?php echo ( $user['banned']==0 ? "нет" : "да") ?><?php echo ( $user['banned']==0 ? "<span>забанить</span>" : "<span>разбанить</span>") ?></td>
							<?php
							 } ?>
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