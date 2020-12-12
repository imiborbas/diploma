<?php

class EmailExceptionHandler extends mvcExceptionHandler
{
  protected $message = '';

  /**
   * Returns the callback responsible for handling exceptions.
   *
   * @return callback
   */
  protected function getExceptionHandler()
  {
    return array($this, 'handleException');
  }

  /**
   * Handles exceptions by storing them in a string array for later processing.
   *
   * @param Exception $exception
   */
  public function handleException($exception)
  {
    $this->message .= '<pre>' . $exception->getMessage() . '</pre>';
  }

  /**
   * Sends an email to an email address specified in the mail.yml config file,
   * with the stored error messages.
   */
  public function __destruct()
  {
    if (!empty($this->message))
    {
      mvcMailer::sendMail(mvcConfigStorage::get('mail_error'), 'Exception report', $this->message);
      $this->message = '';
    }
  }
}