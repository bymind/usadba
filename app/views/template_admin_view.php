<!DOCTYPE html>
<html lang="ru">
<head  manifest="default.appcache">
	<meta charset="UTF-8">
	<title><?php echo $Title; ?> - <?=CONFIG_SITE_NAME?></title>
		<?php include $Favicon; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/css/<?php echo $Style; ?>">
	<script src="/js/jquery/jquery-3.1.1.min.js"></script>
	<script src="/js/prefixfree/prefixfree.min.js"></script>

</head>
<body>
<div id="wrapper">
<div id="overlay">
	<nav class="clearfix">
		<?php include $Navigation; ?>
	</nav>

	<div id="content">
		<link rel="stylesheet" href="/css/<?php echo $Style_content; ?>">
		<?php include $Content; ?>
	</div>
</div>
	<footer>
		<?php include $Footer; ?>
	</footer>
</div>
</body>
</html>