<script src="/js/admin/main.js"></script>
<!-- <script src="/js/admin/config.js"></script> -->

<div class="main-content">

<div class="container-fluid">

	<div class="row ">

	<div class="col-xs-12 col-md-push-8 col-md-4">
		<div class="panel panel-default spoiler open">
			<div class="panel-heading spoiler-title">
				Содержание <small style="opacity: .5;margin-left: 10px;float: right;display: inline-block;line-height: 22px;">[свернуть/развернуть]</small>
			</div>
			<div class="panel-body spoiler-body">
				/Docs Homepage/$name<br>
				==================================<br>
				/Docs Homepage/<br>
				/Docs Homepage/worktable<br>
				--/Docs Homepage/orders<br>
				--/Docs Homepage/recalls<br>
				--/Docs Homepage/charts<br>
				/Docs Homepage/goods-and-categories<br>
				--/Docs Homepage/goods<br>
				--/Docs Homepage/categories<br>
				/Docs Homepage/sales<br>
				--/Docs Homepage/posters<br>
				/Docs Homepage/news<br>
				/Docs Homepage/pages<br>
				/Docs Homepage/files<br>
				/Docs Homepage/users<br>
				--/Docs Homepage/admins-rights<br>
				/Docs Homepage/comments<br>
				/Docs Homepage/configs<br>
				/Docs Homepage/sound<br>
				----/Docs Homepage/glossary<br>
			</div>
			<div class="panel-footer">

			</div>
		</div>
	</div>

	<div class="col-xs-12 col-md-8 col-md-pull-4 pt-0">

		<div class="panel panel-primary">
		<i class="anchor" id="<?=$curPage['url']?>"></i>
			<div class="panel-heading">
				<h4 style="color:#fff"><?=$curPage['name']?><small style="margin-left:10px; font-size:10px; line-height:12px; color:#fff;float:right;text-align:right;margin-top:-5px;">автор <a style="color:inherit;" href="<?=$curPage['author_url']?>" target="_blank" title="<?=$curPage['author_url_title']?>">@<?=$curPage['author_name']?></a><br><span style="opacity:.8">публикация <?=$curPage['pubtime']?></span><br><span style="opacity:.8">редакция <?=$curPage['edittime']?></span></small></h4>
			</div>
			<div class="panel-body">
				<div class="docs-body">
					<?=$curPage['body']?>
				</div>
			</div>
		</div>


	</div>


	</div>
</div>

</div>
</div>