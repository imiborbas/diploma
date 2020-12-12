<?php

class CredentialSecurityFilter extends mvcExecutionFilter
{
  /**
   * Executes the credential security filter.
   *
   * @param  array $filterStack
   * @return boolean
   */
  public function executeFilter(array $filterStack)
  {
    $this->filterStack = $filterStack;

    if(!$this->doSecurityCheck())
    {
      return false;
    }

    return $this->executeNextFilter();
  }

  /**
   * Determines whether the currently logged-in user has access to the
   * requested module/action. The rules come from the security.yml config file.
   *
   * @return boolean
   */
  protected function doSecurityCheck()
  {
    $ret = true;

    $securityData = mvcConfigStorage::get('security_actions', array());
    $routingData = mvcCore::getInstance()->getRoutingData();

    $action = $routingData['module'] . '/' . $routingData['action'];

    $session = mvcCore::getInstance()->getSession();
    $user = $session->getUser();

    $securityParams = $securityData['default'];

    if (array_key_exists($action, $securityData))
    {
      $securityParams = $securityData[$action];
    }

    if (isset($securityParams['credential']) && !UserCredentialPeer::userHasCredential($user, $securityParams['credential']))
    {
      $ret = false;
    }

    if (isset($securityParams['auth']) && $session->isSignedIn() != $securityParams['auth'])
    {
      $ret = false;
    }

    if (!$ret)
    {
      $url = mvcCore::getInstance()->getRequest()->getBaseUrl() . mvcConfigStorage::get('security_error_action', 'user/unavailable');

      header('Location: ' . $url);
    }

    return $ret;
  }
}