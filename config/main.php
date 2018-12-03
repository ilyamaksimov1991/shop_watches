<?php

define('ROOT_DIR', dirname(__DIR__));
define('WWW', ROOT_DIR.'/web');
define('APP', ROOT_DIR.'/app');
define('WIDGETS_DIR', ROOT_DIR.'/app/widgets');
define('CACHE_DIR', ROOT_DIR.'/tmp/cache');
define('CONFIG_DIR', ROOT_DIR.'/config');
define('LAYOUT', 'watches');
define('SITE_URL', 'http://'.$_SERVER['HTTP_HOST']);
define('ADMIN_URL', SITE_URL . '/admin');

require_once ROOT_DIR . '/vendor/autoload.php';

