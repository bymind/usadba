<script src="/js/admin/main.js"></script>
<script src="/js/admin/bugtracker.js"></script>
<script src="/js/admin/bootstrap-bt.min.js"></script>
<link rel="stylesheet" href="/css/admin/bootstrap-panel.min.css">
<link rel="stylesheet" href="/css/admin/animate.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.9.0/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.9.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>


<div class="main-content">

<div class="container-fluid pl-5 pr-5">
<div class="row mt-0 mb-0 pl-25 pr-25 personal-btns-bg">

			<div class="personal-tickets pt-10">
				<div class="filter-icon mb-10 mr-10">
					<span class="glyphicon g-spin glyphicon-filter" title="Фильтр"></span>
				</div>
				<div class="btn-group ticket-personal-tag mb-10 mr-10" data-toggle="buttons">
					<a href="#all" class="btn btn-default btn-sm all <?php if (isset($_SESSION['user']['personalTickets']) && $_SESSION['user']['personalTickets']=='0' ) { echo 'active'; } ?>" role="button" data-attr="all">Все тикеты</a>
					<a href="#personal" class="btn btn-default personal btn-sm <?php if (isset($_SESSION['user']['personalTickets']) && $_SESSION['user']['personalTickets']=='1' ) { echo 'active'; } ?>" role="button" data-attr="personal">Мои</a>
				</div>
				<div class="btn-group ticket-personal-my-tag mb-10" data-toggle="buttons">
					<a href="#all" class="btn btn-default btn-sm my-all <?php if (isset($_SESSION['user']['personalTicketsMy']) && $_SESSION['user']['personalTicketsMy']=='0' ) { echo 'active'; } ?>" role="button" data-attr="myall">Все мои</a>
					<a href="#all" class="btn btn-default btn-sm my-author <?php if (isset($_SESSION['user']['personalTicketsMy']) && $_SESSION['user']['personalTicketsMy']=='1' ) { echo 'active'; } ?>" role="button" data-attr="myauthor">Я автор</a>
					<a href="#personal" class="btn btn-default my-doer btn-sm <?php if (isset($_SESSION['user']['personalTicketsMy']) && $_SESSION['user']['personalTickets']=='1' ) { echo 'active'; } ?>" role="button" data-attr="mydoer">Я исполнитель</a>
				</div>
			</div>
</div>
</div>

<div class="container-fluid">

	<div class="row mt-10 pt-0 pb-0">

		<div class="col-xs-12">
		</div>

		<div class="col-xs-12 col-sm-8 ">

			<div class="headers-block visible-xs mb-10">

			<div class="btn-group ticket-tag" data-toggle="buttons">
				<label class="btn btn-sm btn-default active btn-tickets-headers" link-attr="default">
					<input type="radio" name="toplink"><span class="glyphicon glyphicon-align-left"></span>Лента
				</label>
				<label class="btn btn-sm btn-primary btn-tickets-headers" link-attr="new">
					<input type="radio" name="toplink"><span class="glyphicon glyphicon-send"></span>Новые
				</label>
				<label class="btn btn-sm btn-warning btn-tickets-headers" link-attr="active">
					<input type="radio" name="toplink"><span class="glyphicon glyphicon-time"></span>В процессе
				</label>
				<label class="btn btn-sm btn-success btn-tickets-headers" link-attr="done">
					<input type="radio" name="toplink"><span class="glyphicon glyphicon-check"></span>Готовые
				</label>
			</div>

			</div>

			<div class="hidden-xs">
				<div class="btn-group ticket-tag" data-toggle="buttons">
					<label class="btn btn-default active btn-tickets-headers" link-attr="default">
						<input type="radio" name="toplink"><span class="glyphicon glyphicon-align-left"></span>Лента
					</label>
					<label class="btn btn-primary btn-tickets-headers" link-attr="new">
						<input type="radio" name="toplink"><span class="glyphicon glyphicon-send"></span>Новые
					</label>
					<label class="btn btn-warning btn-tickets-headers" link-attr="active">
						<input type="radio" name="toplink"><span class="glyphicon glyphicon-time"></span>В процессе
					</label>
					<label class="btn btn-success btn-tickets-headers" link-attr="done">
						<input type="radio" name="toplink"><span class="glyphicon glyphicon-check"></span>Готовые
					</label>
				</div>
			</div>
		</div>
		<div class=" col-xs-12 col-sm-4">
			<div class="btn btn-title btn-danger new-ticket" data-toggle="button" data-loading-text="Отправляем..." data-success-text="Готово!" type="button">
			<span class="glyphicon glyphicon-send"></span>
			&nbsp;&nbsp;Создать тикет</div>
		</div>

	</div>

	<div class="row pt-0 mt-0">

	<div class="col-xs-12 col-md-4 col-md-push-8">
		<div class="new-ticket-form-over">
			<form role="form" id="newTicketForm" class="mb-20">
				<div class="panel panel-primary">
				<div class="panel-body">
						<div class="form-group">
							<label style="display:block;">Раздел</label>
							<div class="btn-group ticket-tag" data-toggle="buttons">
								<label class="btn btn-default btn-sm">
									<input type="radio" name="tag" id="site"><span class="glyphicon glyphicon-globe"></span>&nbsp;&nbsp;Сайт
								</label>
								<label class="btn btn-default btn-sm active">
									<input type="radio" name="tag" id="admin"><span class="glyphicon glyphicon-bookmark" style="top:2px;"></span>&nbsp;&nbsp;Админка
								</label>
								<label class="btn btn-default btn-sm">
									<input type="radio" name="tag" id="noname"><span class="glyphicon glyphicon-fire"></span>&nbsp;&nbsp;хзчо
								</label>
							</div>
							<span class="help-block">В каком месте проблема</span>
						</div>
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
							<button type="button" class="btn  btn-success new-ticket-form-btn new-ticket-send">Послать</button>
							<button type="button" class="btn  btn-danger new-ticket-form-btn new-ticket-abort">Отменить</button>
						</div>
				</div>
				</div>
			</form>
		</div>
	</div>

	<div class="col-xs-12 col-md-8 col-md-pull-4 pt-0 cards__list">


	<!-- cadrs list here -->
	<?php include 'app/views/admin/bugtracker_cards_list_view.php'; ?>


	</div>
</div>

</div>
</div>