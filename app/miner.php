<?php

//TODO move session_start() to any controller
// session_start();


// define ("INPUT_SITE_NAME", $ds['inputSiteName']);

// include core files (core classes)

require_once 'core/config.php'; // config file
require_once 'core/model.php'; // models parent class
require_once 'core/view.php'; // views parent class
require_once 'core/controller.php'; // controllers parent class
require_once 'core/route.php'; // routing
Route::start(); // starting routing