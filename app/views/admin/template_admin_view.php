<!DOCTYPE html>
<html lang="ru">
<head  manifest="default.appcache">
	<meta charset="UTF-8">
	<title>Админка MacFix<?php echo $Title; ?></title>
	<?php include $Favicon; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/admin/common.css">
	<link rel="stylesheet" href="/css/<?php echo $Style; ?>">
	<script src="/js/jquery.min.js"></script>
	<!-- <script src="/js/prefixfree.min.js"></script> -->
	<script src="/js/jquery.dotdotdot.min.js"></script>
	<link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
	<script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script src="/js/admin/md.min.js"></script>

</head>
<body>
<div id="wrapper">
<div id="overlay">
	<nav >
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

<div class="modals-box">
<?php include $Modals; ?>
</div>

</body>
</html>