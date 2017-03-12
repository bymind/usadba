<div class="cards__new"></div>
<?php
	$countItems = array_pop($tickets);
	if ($countItems == 0) {
	?>
		<div class="no-tikets">
			<h2>Нет тикетов!</h2>
		</div>
	<?php
	} else {
	?>
		<div class="mb-10"><i>Найдено тикетов: <?php echo $countItems; ?></i></div>
	<?php
	foreach ($tickets as $ticket) {

			$active = ['','',''];
			switch ($ticket['status']) {
				case 'new':
					$panelType = "primary";
					$active[0] = "active";
					break;
				case 'active':
					$panelType = "warning";
					$active[1] = "active";
					break;
				case 'done':
					$panelType = "success";
					$active[2] = "active";
					$ticket['time'] = 'Выполнено '.$ticket['answer_time'];
					break;

				default:
					$panelType = "info";
					break;
			}
?>



<div class="panel card panel-<?php echo $panelType; ?>" data-cardid="<?php echo $ticket['id']; ?>">
	<div class="panel-heading clearfix" title="Открыть тикет #<?php echo $ticket['id']; ?>" data-id="<?php echo $ticket['id']; ?>" data-url="/admin/bugtracker/ticket/<?php echo $ticket['id']; ?>">
			<h3 class="panel-title">
					<div class="card__number">#<?php echo $ticket['id']; ?></div>
					<div class="card__title"><?php echo $ticket['title']; ?> <span class="glyphicon glyphicon-link"></span></div>
			</h3>
	</div>
	<div class="panel-footer card__underheader">
		<div class="card__time"><?php echo $ticket['time']; ?></div>
	</div>
	<div class="panel-body">
		<div class="card__desc"><?php echo $ticket['body']; ?></div>
	</div>
	<div class="panel-footer card__footer">
		<div class="card__author"><span class="hidden-xs lab">Автор: </span><a href="/admin/users/<?php echo $ticket['author'];?>"><?php echo $ticket['author']; ?></a></div>
		<div class="card__master hidden-xs "><span class="lab">Исполнитель: </span>
		<?php
		$doerStr = ($ticket['doer']=='не назначен') ? $ticket['doer'] : '<a href="/admin/users/'.$ticket['doer'].'">'.$ticket['doer'].'</a>';
		echo $doerStr; ?>
		</div>
		<div class="card__status"><span class="lab">Статус: </span><?php echo $ticket['status']; ?></div>
		<div class="card__tag"><small>
			<code class="default"><span class="glyphicon glyphicon-tag" style="top:2px; margin-left:2px"></span> <?php echo $ticket['tag'];?></code>
			</small>
		</div>
	</div>
	<?php if (Controller_Admin::isSuper()){?>
		<div class="dropdown" id="noAction<?php echo $ticket['id']; ?>">
			<button id="dropdown<?php echo $ticket['id']; ?>" type="button" data-toggle="dropdown" class="dropdown btn btn-default btn-sm"><span class="glyphicon glyphicon-cog"></span></button>
			<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdown<?php echo $ticket['id']; ?>">
				<li role="presentation" class="dropdown-header">Смена статуса</li>
				<li role="presentation" class="<?php echo $active[0]; ?>"><a role="menuitem" tabindex="-1" href="#">Новый</a></li>
				<li role="presentation" class="<?php echo $active[1]; ?>"><a role="menuitem" tabindex="-1" href="#">В процессе</a></li>
				<li role="presentation" class="<?php echo $active[2]; ?>"><a role="menuitem" tabindex="-1" href="#">Готовый</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Отправить в архив</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Назначить ответственного</a></li>
			</ul>
		</div>
	<?php } ?>
</div>

<?php }
} ?>