<?php

require_once MVC_VENDOR_DIR . DIRECTORY_SEPARATOR . 'swift' . DIRECTORY_SEPARATOR . 'Swift.php';

class mvcMailer
{
  /**
   * Sends an e-mail with the given parameters.
   *
   * @param string $to
   * @param string $subject
   * @param string $body
   * @param string $contentType
   * @param string $from
   */
  public static function sendMail($to, $subject, $body, $contentType = 'text/html', $from = null)
  {
    try
    {
      if (!$from)
      {
        $from = mvcConfigStorage::get('mail_from');
      }

      Swift_ClassLoader::load('Swift_Connection_SMTP');

      $smtp = new Swift_Connection_SMTP(
        mvcConfigStorage::get('mail_server'),
        Swift_Connection_SMTP::PORT_SECURE, Swift_Connection_SMTP::ENC_TLS
      );
      
      $smtp->setUsername(mvcConfigStorage::get('mail_user'));
      $smtp->setPassword(mvcConfigStorage::get('mail_pass'));

      $mailer = new Swift($smtp);
      $message = new Swift_Message($subject, $body, $contentType);

      $mailer->send($message, $to, $from);
      $mailer->disconnect();
    }
    catch (Exception $e)
    {
      if (isset($mailer) && $mailer != null)
      {
        $mailer->disconnect();
      }
    }
  }
}