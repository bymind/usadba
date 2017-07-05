<?php
	// header("X-Frame-Options: SAMEORIGIN");
	$dates=gmdate("D, d M Y H:i:s", time()+3600) . " GMT";
	header("Expires: " . $dates);
	if (isset($php_header)) {
		header($php_header);
	}
?>
<!DOCTYPE html>
<html lang="ru">
<head manifest="default.appcache">
	<meta charset="UTF-8">
	<title><?php echo $Title; ?> - Кулинария Усадьба-Центр</title>
	<?php include $Favicon; ?>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo $Description; ?>">
	<meta property="og:description" content="<?php echo $Description; ?>">
	<meta property="og:image" content="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$OgImage; ?>">
	<meta property="og:site_name" content="Кулинария Усадьба-Центр">
	<meta property="og:title" content="<?php echo $Title; ?> - Кулинария Усадьба-Центр">
	<meta property="og:type" content="<?php echo $OgType; ?>">
	<meta property="og:url" content="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]; ?>">
	<meta itemprop="image" content="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$OgImage; ?>">
	<meta name="twitter:card" content="summary">
	<!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700&amp;subset=cyrillic" rel="stylesheet"> -->
	<link href="/css/<?php echo $style; ?>" rel="stylesheet">
	<?php
		if (is_array($style_content)) {
			foreach ($style_content as $style) {
				?> <link href="/css/<?php echo $style; ?>" rel="stylesheet"> <?php
			}
		} else {
			?> <link href="/css/<?php echo $style; ?>" rel="stylesheet"> <?php
		}
	?>

</head>
<body>

	<div id="overlay">

	<?php include $HeadLine; ?>
	<?php include $Navigation; ?>

	<?php if ($BreadCrumbs) include $BreadCrumbs; ?>

		<div id="content">
			<?php include $Content; ?>
		</div>

	</div>

	<footer>
		<?php include $Footer; ?>
	</footer>


<?php
if (is_array($Modals)) {
	extract($Modals);
	foreach ($Modals as $Modal) {
		include $Modal;
	}
} else
include $Modals;
?>

<?php
	/*	echo "<style>";
		include "css/public/font-awesome.min.css";
		include "css/owl-carousel/owl.carousel.css";
		include "css/owl-carousel/owl.theme.css";
		include "css/owl-carousel/owl.transitions.css";
		echo "</style>";
	*/ ?>
<!-- <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script> -->
<script src="/js/jquery/jquery-3.1.1.min.js"></script>
<script src="/js/bootstrap/bootstrap.min.js"></script>
<?php
	if (is_array($scripts_content)) {
		foreach ($scripts_content as $script) {
			?><script src="<?php echo $script; ?>"></script><?php
		}
	} else {
		?><script src="<?php echo $scripts_content; ?>"></script><?php
	}
?>


</body>
</html>