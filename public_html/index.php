<?php

error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', 'On');

define('MVC_ROOT_DIR', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'));

define('MVC_CONFIG_DIR', MVC_ROOT_DIR . DIRECTORY_SEPARATOR . 'config');
define('MVC_CACHE_DIR', MVC_ROOT_DIR . DIRECTORY_SEPARATOR . 'cache');
define('MVC_WEB_DIR', dirname(__FILE__));
define('MVC_LIB_DIR', MVC_ROOT_DIR . DIRECTORY_SEPARATOR . 'lib');
define('MVC_VENDOR_DIR', MVC_LIB_DIR . DIRECTORY_SEPARATOR . 'vendor');
define('MVC_APPLICATION_DIR', MVC_ROOT_DIR . DIRECTORY_SEPARATOR . 'application');
define('MVC_CONTROLLER_DIR', MVC_APPLICATION_DIR . DIRECTORY_SEPARATOR . 'controller');
define('MVC_VIEW_DIR', MVC_APPLICATION_DIR . DIRECTORY_SEPARATOR . 'view');
define('MVC_FORM_DIR', MVC_APPLICATION_DIR . DIRECTORY_SEPARATOR . 'form');

require_once MVC_ROOT_DIR . '/lib/init/mvcCore.class.php';

mvcCore::getInstance()->dispatchRequest();

?>