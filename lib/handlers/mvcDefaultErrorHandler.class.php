<?php

class mvcDefaultErrorHandler extends mvcErrorHandler
{
  /**
   * This is the default error handler, so it doesn't do anything, just lets the default PHP
   * error handler do the work.
   *
   * @return null
   */
  protected function getErrorHandler()
  {
    return null;
  }

  /**
   * This method does not do anything at all, and is doing it particularly well.
   *
   * @param int    $errno
   * @param string $errstr
   * @param string $errfile
   * @param int    $errline
   */
  public function handleError($errno, $errstr, $errfile, $errline)
  {
  }
}