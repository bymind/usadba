<script src="/js/admin/main.js"></script>
<!-- <script src="/js/admin/users.js"></script> -->
<script src="/js/admin/bugtracker.js"></script>
<script src="/js/admin/bootstrap-bt.min.js"></script>
<link rel="stylesheet" href="/css/admin/bootstrap-panel.min.css">
<div class="main-content">

<div class="container-fluid">

	<div class="row pt-0 pb-0">

		<div class="col-xs-12 col-sm-8 ">
			<div class="headers-block">
				<div class="headers-line">
					<h4 class="default blocked selected">
					<a href="/admin/bugtracker" >
						Все
					</a>
					</h4>
					<h4 class="new blocked">
						<a href="/admin/bugtracker/new" >
							Новые
						</a>
					</h4>
					<h4 class="active blocked">
						<a href="/admin/bugtracker/active" >
							В процессе
						</a>
					</h4>
					<h4 class="done blocked">
						<a href="/admin/bugtracker/done" >
							Готовые
						</a>
					</h4>
				</div>
			</div>
		</div>
		<div class=" col-xs-12 col-sm-4">
			<div class="btn btn-title btn-primary btn-lg new-ticket mt-10" data-toggle="button" data-loading-text="Отправляем..." data-success-text="Готово!" type="button">
			<!-- <span class="glyphicon glyphicon-exclamation-sign"></span> -->
			<span class="glyphicon glyphicon-comment"></span>
			<!-- <span class="glyphicon glyphicon-bullhorn"></span> -->
			<!-- <span class="glyphicon glyphicon-bell"></span> -->
			<!-- <span class="glyphicon glyphicon-pencil"></span> -->
			&nbsp;&nbsp;Создать тикет</div>
		</div>

	</div>

	<div class="row pt-0 mt-0">

	<div class="col-xs-12 col-md-4 col-md-push-8">
		<div class="new-ticket-form-over">
			<form role="form" id="newTicketForm">
				<div class="panel panel-primary">
				<div class="panel-body">
						<div class="form-group">
							<label for="ticketTitle">Заголовок</label>
						  <input type="text" id="ticketTitle" name="title" class="form-control" placeholder="Коротко">
						</div>
						<div class="form-group">
							<label for="ticketBody">Описание (обязательно)</label>
							<textarea name="body" id="ticketBody" placeholder="Полное описание проблемы" class="form-control" rows="3" ></textarea>
						</div>
				</div>
				<div class="panel-footer">
						<div class="form-group new-ticket-form-btns clearfix mb-0">
						  <button type="button" class="btn btn-sm btn-success new-ticket-form-btn new-ticket-send">Послать</button>
						  <button type="button" class="btn btn-xs btn-danger new-ticket-form-btn new-ticket-abort">Отменить</button>
						  <button type="reset" class="btn btn-xs btn-warning new-ticket-form-btn new-ticket-reset">Очистить</button>
						</div>
				</div>
				</div>
			</form>
		</div>
	</div>

	<div class="col-xs-12 col-md-8 col-md-pull-4 pt-0">

<?php

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
					break;

				default:
					$panelType = "info";
					break;
			}
?>



<div class="panel panel-<?php echo $panelType; ?>">
	<div class="panel-heading clearfix">
			<h3 class="panel-title">
				<!-- <div class="col-xs-2 col-sm-1"> -->
				<!-- </div> -->
				<!-- <div class="col-xs-10 col-sm-11"> -->
					<div class="card__number">#<span class='ticket-id'><?php echo $ticket['id']; ?></span></div>
					<div class="card__title"><?php echo $ticket['title']; ?></div>
				<!-- </div> -->
			</h3>
		<?php if (Controller_Admin::isSuper()){?>
			<div class="dropdown">
				<button id="dropdown<?php echo $ticket['id']; ?>" type="button" data-toggle="dropdown" class="dropdown btn btn-default btn-sm"><span class="glyphicon glyphicon-cog"></span></button>
				<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdown<?php echo $ticket['id']; ?>">
				  <li role="presentation" class="dropdown-header">Смена статуса</li>
				  <li role="presentation" class="<?php echo $active[0]; ?>"><a role="menuitem" tabindex="-1" href="#">Новый</a></li>
				  <li role="presentation" class="<?php echo $active[1]; ?>"><a role="menuitem" tabindex="-1" href="#">В процессе</a></li>
			    <li role="presentation" class="<?php echo $active[2]; ?>"><a role="menuitem" tabindex="-1" href="#">Готовый</a></li>
				  <li role="presentation" class="divider"></li>
				  <!-- <li role="presentation" class="dropdown-header">Название меню</li> -->
				  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Отправить в архив</a></li>
			    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Назначить ответственного</a></li>
				</ul>
			</div>
		<?php } ?>
	</div>
	<div class="panel-footer">
		<div class="card__time"><?php echo $ticket['time']; ?></div>
		<div class="card__files">
			<span class="glyphicon glyphicon-paperclip"></span>
		</div>
	</div>
	<div class="panel-body">
		<div class="card__desc"><?php echo $ticket['body']; ?></div>
	</div>
	<div class="panel-footer">
					<div class="card__author"><span class="hidden-xs lab">Автор: </span><a href="/admin/users/<?php echo $ticket['author'];?>"><?php echo $ticket['author']; ?></a></div>
					<div class="card__master hidden-xs "><span class="lab">Исполнитель: </span><?php echo $ticket['doer']; ?></div>
					<div class="card__status"><span class="lab">Статус: </span><?php echo $ticket['status']; ?></div>
					<div class="card__actions">
						<div class="btn btn-link btn-xs">Удалить</div>
					</div>
	</div>
</div>

	</div>
</div>

</div>
</div>