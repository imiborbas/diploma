<?php

abstract class mvcErrorHandler
{
  protected abstract function getErrorHandler();
  public abstract function handleError($errno, $errstr, $errfile, $errline);

  public function __construct()
  {
    $errorHandler = $this->getErrorHandler();

    if (is_callable($errorHandler))
    {
      set_error_handler($this->getErrorHandler());
    }
  }
}