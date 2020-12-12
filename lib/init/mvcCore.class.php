<?php

require_once MVC_LIB_DIR . '/init/mvcAutoloader.class.php';
require_once MVC_VENDOR_DIR . '/propel/Propel.php';

class mvcCore
{
  const PROPEL_CONF_FILE = 'propel-conf.php';

  private static $instance = null;

  private $router = null;
  private $request = null;
  private $session = null;
  private $routingData = null;
  private $errorHandler = null;
  private $exceptionHandler = null;

  /**
   * This method initializes the framework.
   * Configures autoloading, error and session handling.
   */
  public function __construct()
  {
    mvcAutoloader::register();
    mvcConfigStorage::init();

    $errorHandler = $this->checkClass('error_handler', 'mvcDefaultErrorHandler', 'mvcErrorHandler');
    $this->errorHandler = new $errorHandler();

    $exceptionHandler = $this->checkClass('exception_handler', 'mvcDefaultExceptionHandler', 'mvcExceptionHandler');
    $this->exceptionHandler = new $exceptionHandler();

    Propel::init(MVC_CONFIG_DIR . DIRECTORY_SEPARATOR . self::PROPEL_CONF_FILE);

    $this->router = new mvcRouter();

    $sessionClass = $this->checkClass('session_class', 'mvcSession', 'mvcSession');

    $this->session = new $sessionClass(mvcConfigStorage::get('session_storage', 'mvcDefaultSessionStorage'));
  }

  /**
   * Returns the sigleton instance of this class.
   *
   * @return mvcCore
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
   * This method does the actual request processing. Gets the routing data fom the router,
   * and executes the filter chain specified in the filters.yml configuration file.
   */
  public function dispatchRequest()
  {
    $this->routingData = $this->router->parseRoute();
    $this->request = new mvcRequest($this->routingData);

    $filterManager = new mvcFilterManager();
    $filterManager->beginExecution();
  }

  /**
   * Returns the current request object.
   *
   * @return mvcRequest
   */
  public function getRequest()
  {
    return $this->request;
  }

  /**
   * Returns the current session object.
   *
   * @return mvcSession
   */
  public function getSession()
  {
    return $this->session;
  }

  /**
   * Returns the data provided by the Router.
   *
   * @return array
   */
  public function getRoutingData()
  {
    return $this->routingData;
  }

  /**
   * Checks whether a class with a particular ancestor can be instantiated.
   *
   * @param  string  $configParam
   * @param  string  $defaultParam
   * @param  string  $ancestorClass
   * @return boolean
   */
  protected function checkClass($configParam, $defaultParam, $ancestorClass)
  {
    $class = mvcConfigStorage::get($configParam, $defaultParam);

    if (!class_exists($class))
    {
      throw new mvcException('A(z) ' . $class . ' osztály nem létezik.');
    }

    if (!is_subclass_of($class, $ancestorClass))
    {
      throw new mvcException('A(z) ' . $class . ' osztálynak ebből kell leszármaznia: ' . $ancestorClass . '.');
    }

    return $class;
  }
}