<?php

class mvcConfigStorage extends mvcAbstractConfigStorage
{
  const LOADER_CLASS = 'mvcYamlConfigLoader';

  /**
   * Sets a key-value pair in the config storage.
   *
   * @param string $key
   * @param mixed  $value
   */
  public static function set($key, $value)
  {
    self::$storage[$key] = $value;
  }

  /**
   * Gets a value from the config storage.
   *
   * @param  string $key
   * @param  mixed  $default
   * @return mixed
   */
  public static function get($key, $default = null)
  {
    if (isset(self::$storage[$key]))
    {
      return self::$storage[$key];
    }

    return $default;
  }

  /**
   * Calls the loader classes' load function.
   */
  public static function init()
  {
    self::$storage = call_user_func(array(self::LOADER_CLASS, 'load'));
  }
}