<?php

class mvcMessageHandler
{
  const SESSION_KEY = 'mvcMessageHandler';

  private static $instance = null;
  protected $session = null;

  /**
   * Creates the object.
   */
  public function __construct()
  {
    $this->session = mvcCore::getInstance()->getSession();
  }

  /**
   * Returns the singleton instance for this class.
   *
   * @return mvcMessageHandler
   */
  public static function getInstance()
  {
    if (!self::$instance)
    {
      $class = __CLASS__;
      self::$instance = new $class();
    }

    return self::$instance;
  }

  /**
   * Adds an error message into the message queue.
   *
   * @param string $msg
   */
  public function addErrorMessage($msg)
  {
    $this->addMessage($msg, 'error');
  }

  /**
   * Adds a success message into the message queue.
   *
   * @param string $msg
   */
  public function addSuccessMessage($msg)
  {
    $this->addMessage($msg, 'success');
  }

  /**
   * Adds a message into the message queue with the given type.
   *
   * @param string $msg
   * @param string $type
   */
  protected function addMessage($msg, $type)
  {
    $messages = $this->session->getValue(self::SESSION_KEY, array());
    $messages[$type][] = $msg;

    $this->session->setValue(self::SESSION_KEY, $messages);
  }

  /**
   * Returns an array of the added error messages.
   *
   * @return array
   */
  public function getErrorMessages()
  {
    return $this->getMessages('error');
  }

  /**
   * Returns an array of the added success messages.
   *
   * @return array
   */
  public function getSuccessMessages()
  {
    return $this->getMessages('success');
  }

  /**
   * Returns an array of the added messages.
   *
   * @param  string $type
   * @return array
   */
  protected function getMessages($type)
  {
    $messages = $this->session->getValue(self::SESSION_KEY, array());

    if (!array_key_exists($type, $messages))
    {
      return array();
    }

    $ret = $messages[$type];
    unset($messages[$type]);

    $this->session->setValue(self::SESSION_KEY, $messages);

    return $ret;
  }
}