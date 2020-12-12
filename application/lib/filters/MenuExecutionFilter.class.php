<?php

class MenuExecutionFilter extends mvcExecutionFilter
{
  /**
   * Executes the menu execution filter.
   *
   * @param  array $filterStack
   * @return boolean
   */
  public function executeFilter(array $filterStack)
  {
    $this->filterStack = $filterStack;

    $this->processExecution(array('menu' => $this->prepareMenu()));

    return $this->executeNextFilter();
  }

  /**
   * Returns an array which contains the menu items available to the
   * current user.
   *
   * @return array
   */
  protected function prepareMenu()
  {
    $menu = array();
    $menuItems = mvcConfigStorage::get('menu_items', array());

    foreach ($menuItems as $menuItemName => $menuItemParams)
    {
      $session = mvcCore::getInstance()->getSession();
      $user = $session->getUser();

      if (isset($menuItemParams['credential']) && !UserCredentialPeer::userHasCredential($user, $menuItemParams['credential']))
      {
        continue;
      }

      if (isset($menuItemParams['auth']) && $session->isSignedIn() != $menuItemParams['auth'])
      {
        continue;
      }

      $menu[$menuItemName] = $menuItemParams;
    }

    return $menu;
  }
}