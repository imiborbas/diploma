<?php

class mvcExecutionFilter extends mvcChainFilter
{
  /**
   * Executes this filter.
   *
   * @param  array $filterStack
   * @return boolean
   */
  public function executeFilter(array $filterStack)
  {
    $this->filterStack = $filterStack;

    $this->processExecution();

    return $this->executeNextFilter();
  }

  /**
   * This method does the actual work. It executes the requested action/module coming from the routing data,
   * and then renders the corresponding template.
   *
   * @param  array   $customAttributes
   * @return mixed
   */
  protected function processExecution(array $customAttributes = array())
  {
    $routingData = mvcCore::getInstance()->getRoutingData();

    $actionName = $routingData['action'];
    $moduleName = $routingData['module'];

    $controllerClassName = $moduleName . 'Controller';
    $controllerMethodName = 'execute' . ucfirst($actionName);

    if (!class_exists($controllerClassName) || !is_callable(array($controllerClassName, $controllerMethodName)))
    {
      $controllerClassName = 'errorController';
      $moduleName = 'error';
      $actionName = 'error404';
    }

    $controllerObject = new $controllerClassName($moduleName);

    foreach ($customAttributes as $customAttribute => $customValue)
    {
      $controllerObject->$customAttribute = $customValue;
    }

    try
    {
      $controllerObject->$actionName();
    }
    catch (mvcRedirectException $redirection)
    {
      $url = mvcCore::getInstance()->getRequest()->getBaseUrl() . $redirection->getUri();

      header('Location: ' . $url);

      return true;
    }
  }
}