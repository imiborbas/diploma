<?php

class mvcSession
{
  protected $values = array();

  /**
   * Creates the object, initializes the storage, and starts session.
   *
   * @param string $storageClass
   */
  public function __construct($storageClass)
  {
    $storage = new $storageClass();
    $storage->init();
    
    session_start();
    
    $this->values = &$_SESSION;
  }

  /**
   * Returns the value of key $name from the session. If there is no such keyin the session,
   * it returns $default.
   *
   * @param  string $name
   * @param  mixed  $default
   * @return mixed
   */
  public function getValue($name, $default = null)
  {
    if (isset($this->values[$name]))
    {
      return $this->values[$name];
    }

    return $default;
  }

  /**
   * Sets the value for key $name in the session.
   *
   * @param string $name
   * @param mixed  $value
   */
  public function setValue($name, $value)
  {
    $this->values[$name] = $value;
  }

  /**
   * Checks whether a value with key $name exists in the session.
   *
   * @param  string  $name
   * @return boolean
   */
  public function hasValue($name)
  {
    return isset($this->values[$name]);
  }
}