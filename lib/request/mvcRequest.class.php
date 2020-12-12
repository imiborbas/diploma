<?php

class mvcRequest
{
  const METHOD_GET  = 'GET';
  const METHOD_POST = 'POST';

  protected $parameters = array();
  protected $moduleName = null;
  protected $actionName = null;

  /**
   * Initializes the Request object with data from the routing.
   *
   * @param array $routingData
   */
  public function __construct(array $routingData)
  {
    $this->parameters = array_merge($_POST, $routingData['params']);
    $this->moduleName = $routingData['module'];
    $this->actionName = $routingData['action'];
  }

  /**
   * Returns the request parameter $name if available, otherwise returns $default.
   *
   * @param  string $name
   * @param  mixed  $default
   * @return mixed
   */
  public function getParameter($name, $default = null)
  {
    if (isset($this->parameters[$name]))
    {
      return $this->parameters[$name];
    }

    return $default;
  }

  /**
   * Checks the availability of a parameter in the request.
   *
   * @param  string $name
   * @return boolean
   */
  public function hasParameter($name)
  {
    return isset($this->parameters[$name]);
  }

  /**
   * Returns the method of the current request. Can be checked against class constants
   * mvcRequest::METHOD_GET and mvcRequest::METHOD_POST
   *
   * @return string
   */
  public function getMethod()
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  /**
   * Returns the currently requested module name.
   *
   * @return string
   */
  public function getModuleName()
  {
    return $this->moduleName;
  }

  /**
   * Returns the currently requested action name.
   *
   * @return string
   */
  public function getActionName()
  {
    return $this->actionName;
  }

  /**
   * Returns the absolute base URL of the web application.
   *
   * @return string
   */
  public function getBaseUrl()
  {
    $protocol = 'http' . (!empty($_SERVER['HTTPS']) ? 's' : '') . '://';
    $host = $_SERVER['HTTP_HOST'];
    $path = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);

    return $protocol . $host . $path;
  }
}