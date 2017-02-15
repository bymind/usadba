<?php

// DB config

/*$dbHost='a103460.mysql.mchost.ru';
$dbName='a103460_usadba';
$dbUser='a103460_usadba';
$dbPass='usadba';
*/
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

