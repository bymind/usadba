<script src="/js/admin/main.js"></script>
<!-- <script src="/js/admin/config.js"></script> -->

<div class="main-content">

<div class="container-fluid">

	<div class="row ">

	<div class="col-xs-12 col-md-push-8 col-md-4 pl-xs-5 pr-xs-5">
		<div class="panel panel-default spoiler open">
			<div class="panel-heading spoiler-title">
				Содержание <small style="opacity: .5;margin-left: 10px;float: right;display: inline-block;line-height: 22px;">[свернуть/развернуть]</small>
			</div>
			<div class="panel-body spoiler-body">
				<ul class="docs-nav">
					<li>
						<a href="/admin/docs#Как пользоваться сайтом"><b>#1</b> +Главная</a>
					</li>
					<li>
						<a href="/admin/docs/worktable#Рабочий стол"><b>#2</b> +Рабочий стол</a>
						<ul>
							<li>
								<a href="/admin/docs/orders#Рабочий стол"><b>#2.1</b> +Рабочий стол: Заказы</a>
							</li>
							<li>
								<a href="/admin/docs/recalls#Рабочий стол"><b>#2.2</b> +Рабочий стол: Заявки на перезвон</a>
							</li>
							<li>
								<a href="/admin/docs/charts"><b>#2.3</b> Рабочий стол: Статистика</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="/admin/docs/goods-and-categories"><b>#3</b> Каталог товаров</a>
						<ul>
							<li>
								<a href="/admin/docs/goods"><b>#3.1</b> Каталог товаров: Товары</a>
							</li>
							<li>
								<a href="/admin/docs/categories"><b>#3.2</b> Каталог товаров: Категории</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="/admin/docs/sales"><b>#4</b> Акции</a>
						<ul>
							<li>
								<a href="/admin/docs/posters"><b>#4.1</b> Акции: Обложка</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="/admin/docs/news"><b>#5</b> Новости</a>
					</li>
					<li>
						<a href="/admin/docs/pages"><b>#6</b> Страницы</a>
					</li>
					<li>
						<a href="/admin/docs/files"><b>#7</b> Файлы</a>
					</li>
					<li>
						<a href="/admin/docs/users"><b>#8</b> Аккаунты</a>
						<ul>
							<li>
								<a href="/admin/docs/admins-rights"><b>#8.1</b> Аккаунты: Права администраторов</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="/admin/docs/comments"><b>#9</b> Комментарии</a>
					</li>
					<li>
						<a href="/admin/docs/configs"><b>#10</b> Настройки</a>
					</li>
					<li>
						<a href="/admin/docs/sound"><b>#11</b> Звук оповещения</a>
					</li>
					<li>
						<a href="/admin/docs/glossary"><b>#12</b> +Глоссарий</a>
					</li>
				</ul>
			</div>
			<div class="panel-footer">

			</div>
		</div>
	</div>

	<div class="col-xs-12 col-md-8 col-md-pull-4 pt-0 pl-xs-5 pr-xs-5">

		<div class="panel panel-primary">
		<i class="anchor" id="<?=$curPage['url']?>"></i>
			<div class="panel-heading clearfix">
				<h4 style="color:#fff"><small style="margin-left:10px; font-size:10px; line-height:12px; color:#fff;float:right;text-align:right;margin-top:-5px;">автор <a style="color:inherit;" href="<?=$curPage['author_url']?>" target="_blank" title="<?=$curPage['author_url_title']?>">@<?=$curPage['author_name']?></a><br><span style="opacity:.8">публикация <?=$curPage['pubtime']?></span><br><span style="opacity:.8">редакция <?=$curPage['edittime']?></span></small><?=$curPage['name']?></h4>
			</div>
			<div class="panel-body">
				<div class="docs-body">
					<?php 
						include $url;
						// echo "<br>";
						// var_dump($url);
					 ?>
					<!-- <?=$curPage['body']?> -->
				</div>
			</div>
		</div>


	</div>


	</div>
</div>

</div>
</div>