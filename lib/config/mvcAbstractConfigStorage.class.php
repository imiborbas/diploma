<?php

abstract class mvcAbstractConfigStorage
{
  protected static $storage = null;

  public static abstract function set($key, $value);
  public static abstract function get($key, $default = null);
  public static abstract function init();
}