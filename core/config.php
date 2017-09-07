<?php
// DB config

$dbHost='localhost';
$dbName='usadba';
$dbUser='localhost';
$dbPass='';

// DB connection
$db = mysql_connect($dbHost,$dbUser,$dbPass);

// report
if (!$db) { echo ("Не могу подключиться к серверу базы данных!"); }
//TODO rewrite with catch

// DB selection
mysql_select_db($dbName,$db);

// DB charset configuration
mysql_query("SET NAMES utf8");

// date_default_timezone_set('Europe/Moscow');

$select = mysql_query("SELECT * FROM config WHERE archived = 0 ")or die(mysql_error());
while ($r = mysql_fetch_assoc($select)) {
	$ds[$r['name']] = $r['value'];
}

define ("CONFIG_SITE_NAME", $ds['inputSiteName']);
define ("CONFIG_SITE_LOGO", $ds['cover-img']);
define ("CONFIG_SITE_PHONE", $ds['inputSitePhone']);
define ("CONFIG_SITE_WORKTIME", $ds['inputWorkTime']);
define ("CONFIG_SITE_ADDRESS", $ds['inputAddress']);
define ("CONFIG_SITE_COPYRIGHT", $ds['inputCopyright']);
define ("CONFIG_SITE_ADMIN", $ds['inputEmailAdmin']);
define ("CONFIG_SITE_ADMIN_ORDERS", $ds['inputEmailOrders']);
define ("CONFIG_SITE_ADMIN_COMM", $ds['inputEmailComm']);
define ("CONFIG_SITE_ADMIN_REV", $ds['inputEmailRev']);
define ("CONFIG_SITE_ADMIN_RECALL", $ds['inputEmailRecall']);

define ("CONFIG_SITE_LAST_NEWS_NUM", 5);

$select = mysql_query("SELECT * FROM config WHERE archived = 0 AND section = 'links'")or die(mysql_error());
while ($r = mysql_fetch_assoc($select)) {
	$r[$r['name']] =$r['value'];
	$ds[$r['name']] = $r;
}

$footerLinks = array();
for ($i = 1; $i <= 10; $i++){
	$footerLinks[$i] = explode("\n",$ds['footerLink'.$i]['value']);
}
define ("CONFIG_FOOTER_LINKS", serialize($footerLinks));
