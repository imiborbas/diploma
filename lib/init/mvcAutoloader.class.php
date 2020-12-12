<?php

/**
 * This class defines the function for autoloading required classes.
 */
class mvcAutoloader
{
  /**
   * Registers the mvcAutoloader as an SPL autoloader.
   */
  static public function register()
  {
    self::setIncludeDirs();
    
    spl_autoload_register(array(__CLASS__, 'autoload'));
  }

  /**
   * Implements the autoloading.
   *
   * @param  string  $class  class name to load
   * @return boolean         true on success, false otherwise
   */
  static public function autoload($class)
  {
    $includeDirs = explode(PATH_SEPARATOR, get_include_path());

    foreach ($includeDirs as $includeDir)
    {
      if (is_file($includeDir . DIRECTORY_SEPARATOR . $class . '.class.php'))
      {
        require_once $class . '.class.php';

        return true;
      }

      if (is_file($includeDir . DIRECTORY_SEPARATOR . $class . '.php'))
      {
        require_once $class . '.php';

        return true;
      }
    }

    return false;
  }

  /**
   * Scans the entire project directory for all subdirectories,
   * and adds them to the include path in order to ease autoloading.
   */
  public static function setIncludeDirs()
  {
    $dirs = array();
    $it = new RecursiveDirectoryIterator(MVC_ROOT_DIR);

    foreach (new RecursiveIteratorIterator($it) as $path => $file)
    {
      $dir = dirname($file);

      $parts = explode(DIRECTORY_SEPARATOR, $dir);
      foreach ($parts as $part)
      {
        if (strlen($part) > 0 && $part[0] === '.') {
          continue 2;
        }
      }

      $dirs[$dir] = $dir;
    }

    usort($dirs, function($a, $b) {
      if (strpos($a, MVC_VENDOR_DIR) === 0 && strpos($b, MVC_VENDOR_DIR) === false) {
        return 1;
      }

      if (strpos($a, MVC_VENDOR_DIR) === false && strpos($b, MVC_VENDOR_DIR) === 0) {
        return 0;
      }

      return strcmp($a, $b);
    });

    $dirs = implode(PATH_SEPARATOR, array_unique($dirs));

    set_include_path(get_include_path() . PATH_SEPARATOR . $dirs);
  }
}