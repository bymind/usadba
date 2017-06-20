<script src="/js/admin/main.js"></script>
<script src="/js/admin/files.js"></script>

<div class="jumbotron main-content" style="background-color: transparent;">
<div class="container-fluid">

	<div class="row pt-0 pb-10">

		<div class="col-xs-12">

			<h1 style="font-size:2em">Файлы</h1>

			<div class="row mt-10">
				<div class="col-md-12">
					<div class="file-freim">
						<div class="spinner">
						  <div class="bounce1"></div>
						  <div class="bounce2"></div>
						  <div class="bounce3"></div>
						</div>
					<iframe id="idIframe" onload="iframeLoaded()" src="/js/responsive_filemanager/filemanager/dialog.php?type=0&akey=<?php echo $access_key ?>" frameborder="0" style="width:100%; display: block;"></iframe>
					</div>
				</div>
			</div>

		</div>

	</div>


</div>
</div>