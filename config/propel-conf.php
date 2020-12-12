<?php

return array_merge_recursive(array (
  'propel' => 
  array (
    'datasources' => 
    array (
      'propel' => 
      array (
        'adapter' => 'mysql',
        'connection' => 
        array (
          'classname' => 'PropelPDO',
          'dsn' => 'mysql:host=localhost;port=3306;dbname=diploma',
          'user' => 'diploma',
          'password' => 'diploma',
          'settings' => 
          array (
            'charset' => 
            array (
              'value' => 'utf8',
            ),
          ),
        ),
      ),
      'default' => 'propel',
    ),
    'generator_version' => '1.3.0-dev',
  ),
), include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classmap.php'));