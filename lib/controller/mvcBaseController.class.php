<?php

abstract class mvcBaseController
{
  private $vars = array();
  protected $moduleName = null;

  /**
   * Initializes the controller.
   *
   * @param string $moduleName
   */
  public function __construct($moduleName)
  {
    $this->moduleName = $moduleName;
    $this->vars['mvc_base_url'] = $this->getRequest()->getBaseUrl();
  }

  /**
   * Gets a property.
   *
   * @param  string $name
   * @return mixed
   */
  public function __get($name)
  {
    return $this->vars[$name];
  }

  /**
   * Sets a property, mainly for the template.
   *
   * @param string $name
   * @param mixed  $value
   */
  public function __set($name, $value)
  {
    $this->vars[$name] = $value;
  }

  /**
   * Handles action calling.
   *
   * @param string $name
   * @param array  $arguments
   */
  public function __call($name, $arguments)
  {
	  $className = $this->moduleName . 'Controller';
    $functionName = 'execute' . ucfirst($name);

    if (!is_callable(array($className, $functionName)))
    {
      throw new mvcException('Ismeretlen művelet: ' . $this->moduleName . ':' . $name);
    }

    $this->module_name = $this->moduleName;
    $this->action_name = $name;

    $actionResult = $this->$functionName($this->getRequest());
    $templateName = ($actionResult ? $actionResult : $name);

    $this->error_messages = mvcMessageHandler::getInstance()->getErrorMessages();
    $this->success_messages = mvcMessageHandler::getInstance()->getSuccessMessages();

    mvcView::render($this->moduleName, $templateName, $this->vars);
  }

  /**
   * Returns the current module name.
   *
   * @return string
   */
  public function getModuleName()
  {
    return $this->moduleName;
  }

  /**
   * Returns the current request.
   *
   * @return mvcRequest
   */
  public function getRequest()
  {
    return mvcCore::getInstance()->getRequest();
  }

  /**
   * Returns the current session.
   *
   * @return mvcSession
   */
  public function getSession()
  {
    return mvcCore::getInstance()->getSession();
  }

  /**
   * Redirects the current action to another URI.
   *
   * @throws mvcRedirectException
   */
  public function redirect($uri)
  {
    throw new mvcRedirectException($uri);
  }
}
