<?php

define('DEBUG', 1);
define('ROOT', dirname(__DIR__));
define('WWW', ROOT.'/web');
define('APP', ROOT.'/app');
define('WIDGETS', ROOT.'/app/widgets');
define('CORE', ROOT.'/vendor/framework/core');
define('LIBS', ROOT.'/vendor/framework/core/libs');
define('CASHE', ROOT.'/tmp/cache');
define('CONFIG', ROOT.'/config');
define('LAYOUT', 'watches');
define('PATH', 'http://'.$_SERVER['HTTP_HOST']);
define('ADMIN', PATH . '/admin');

require_once ROOT . '/vendor/autoload.php';

