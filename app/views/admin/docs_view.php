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
						<a href="/admin/docs">Главная</a>
					</li>
					<li>
						<a href="/admin/docs/worktable">Рабочий стол</a>
						<ul>
							<li>
								<a href="/admin/docs/orders">Рабочий стол: Заказы</a>
							</li>
							<li>
								<a href="/admin/docs/recalls">Рабочий стол: Заявки на перезвон</a>
							</li>
							<li>
								<a href="/admin/docs/charts">Рабочий стол: Статистика</a>
							</li>
						</ul>
					</li>
					<li>
						/Docs Homepage/goods-and-categories
						<ul>
							<li>
								--/Docs Homepage/goods
							</li>
							<li>
								--/Docs Homepage/categories
							</li>
						</ul>
					</li>
					<li>
						/Docs Homepage/sales
						<ul>
							<li>
								--/Docs Homepage/posters
							</li>
						</ul>
					</li>
					<li>
						/Docs Homepage/news
					</li>
					<li>
						/Docs Homepage/pages
					</li>
					<li>
						/Docs Homepage/files
					</li>
					<li>
						/Docs Homepage/users
						<ul>
							<li>
								--/Docs Homepage/admins-rights
							</li>
						</ul>
					</li>
					<li>
						/Docs Homepage/comments
					</li>
					<li>
						/Docs Homepage/configs
					</li>
					<li>
						/Docs Homepage/sound
					</li>
					<li>
						<a href="/admin/docs/glossary">Глоссарий</a>
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
						echo "<br>";
						var_dump($url);
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