<?php

abstract class mvcExceptionHandler
{
  protected abstract function getExceptionHandler();
  public abstract function handleException($exception);

  /**
   * This method constructs the object,
   * gets the exception handler method,
   * and sets the exception handler if it was a valid callback.
   */
  public function __construct()
  {
    $exceptionHandler = $this->getExceptionHandler();

    if (is_callable($exceptionHandler))
    {
      set_exception_handler($this->getExceptionHandler());
    }
  }
}