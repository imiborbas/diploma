<?php

abstract class mvcValidator
{
  protected $parameters = null;
  protected $messages = null;

  public abstract function configure(array $parameters, array $messages);
  public abstract function getErrors($value);

  /**
   * Calls $this->configure, not to be redefined.
   *
   * @param array $parameters
   * @param array $messages
   */
  public function __construct(array $parameters, array $messages)
  {
    $this->configure($parameters, $messages);
  }

  /**
   * Returns true if the validation was successful, false otherwise.
   *
   * @param  mixed $value
   * @return boolean
   */
  public function isValid($value)
  {
    return count($this->getErrors($value)) == 0;
  }

  /**
   * Transforms %params% to their actual value.
   *
   * @param array $errors
   */
  protected function transformErrors(&$errors)
  {
    $params = array();

    foreach ($this->parameters as $key => $val)
    {
      $params["%$key%"] = $val;
    }

    foreach ($errors as $key => $val)
    {
      $errors[$key] = strtr($val, $params);
    }
  }
}