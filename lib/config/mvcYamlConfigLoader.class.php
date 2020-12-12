<?php

class mvcYamlConfigLoader extends mvcAbstractConfigLoader
{
  /**
   * Loads configuration data from all .yml files located in the config directory.
   *
   * @return array
   */
  public static function load()
  {
    $ret = array();

    $ds = DIRECTORY_SEPARATOR;
    $path = MVC_CONFIG_DIR;

    $dir = scandir($path, 1);
    foreach($dir as $file)
    {
      if(is_file($path . $ds . $file) && substr($file, -4) == '.yml')
      {
        $ret = array_merge($ret, sfYaml::load($path . $ds . $file));
      }
    }

    return $ret;
  }
}