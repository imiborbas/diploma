<?php

class EmailErrorHandler extends mvcErrorHandler
{
  protected $message = '';

  /**
   * Returns the callback responsible for handling errors.
   *
   * @return callback
   */
  protected function getErrorHandler()
  {
    return array($this, 'handleError');
  }

  /**
   * Handles PHP errors by storing them in a string array for later processing.
   *
   * @param int    $errno
   * @param string $errstr
   * @param string $errfile
   * @param int    $errline
   */
  public function handleError($errno, $errstr, $errfile, $errline)
  {
    $message = '';

    switch ($errno)
    {
      case E_USER_ERROR:
        $message .= "<b>ERROR</b> [$errno] $errstr<br />\n";
        $message .= "  Fatal error on line $errline in file $errfile";
        $message .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        break;

      case E_USER_WARNING:
        $message .= "<b>WARNING</b> [$errno] $errstr<br />\n";
        break;

      case E_USER_NOTICE:
        $message .= "<b>NOTICE</b> [$errno] $errstr<br />\n";
        break;
    }

    if (!empty($message))
    {
      $this->message .= $message;
    }
  }

  /**
   * Sends an email to an email address specified in the mail.yml config file,
   * with the stored error messages.
   */
  public function __destruct()
  {
    if (!empty($this->message))
    {
      mvcMailer::sendMail(mvcConfigStorage::get('mail_error'), 'Error report', $this->message);
      $this->message = '';
    }
  }
}