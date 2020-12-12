<?php

class mvcDefaultExceptionHandler extends mvcExceptionHandler
{
  /**
   * Returns null because there is no need of redefining the exception handler
   * in the default case.
   *
   * @return null
   */
  protected function getExceptionHandler()
  {
    return null;
  }

  /**
   * Handles the exception by not doing a thing.
   *
   * @param Exception $exception
   */
  public function handleException($exception)
  {
  }
}