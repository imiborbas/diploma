<?php

class pageController extends mvcCrudController
{
  /**
   * Executes the read list action.
   * 
   * @param mvcRequest $request
   */
  public function executeReadList(mvcRequest $request)
  {
    $user = $this->getSession()->getUser();
    $pageIds = ResourceCredentialPeer::getAvailableResourceIDsForUser($user, 'Page');

    $c = new Criteria();
    $c->add(PagePeer::ID, $pageIds, Criteria::IN);

    $this->page_list = PagePeer::doSelect($c);
  }

  /**
   * Executes read action.
   *
   * @param mvcRequest $request
   */
  public function executeRead(mvcRequest $request)
  {
    $user = $this->getSession()->getUser();
    $this->page = PagePeer::retrieveByPK($request->getParameter('id', null));

    if (!$this->page)
    {
      $this->redirect('page/readList');
    }

    if (!ResourceCredentialPeer::userHasAccess($user, $this->page))
    {
      $this->redirect('user/unavailable');
    }
  }

  /**
   * Executes edit action.
   *
   * @param mvcRequest $request
   */
  public function executeEdit(mvcRequest $request)
  {
    parent::executeEdit($request);

    $this->available_credentials = ResourceCredentialPeer::getAvailableCredentialOptionsForResource($this->page);
    $this->credential_list = ResourceCredentialPeer::getResourceCredentialsForResource($this->page);
  }

  /**
   * Executes the action which assigns a Credential to a Page Resource.
   *
   * @param mvcRequest $request
   */
  public function executeAssignCredential(mvcRequest $request)
  {
    if (!$request->hasParameter('page_id') || !$request->hasParameter('credential_id'))
    {
      $this->redirect('page/index');
    }

    $pageId = $request->getParameter('page_id');
    $credentialId = $request->getParameter('credential_id');

    $page = PagePeer::retrieveByPK($pageId);

    $pageCredential = new ResourceCredential();
    $pageCredential->setResource($page);
    $pageCredential->setCredentialId($credentialId);

    if (mvcTransaction::rollbackOnFalse(array('ResourceCredentialPeer', 'save'), array($pageCredential)) === false)
    {
      mvcMessageHandler::getInstance()->addErrorMessage('A jogosultság létrehozása közben hiba történt.');
    }
    else
    {
      mvcMessageHandler::getInstance()->addSuccessMessage('A jogosultság sikeresen létrejött.');
    }

    $this->redirect('page/edit/id/' . $pageId);
  }

  /**
   * Executes the action which removes a ResourceCredential association.
   *
   * @param mvcRequest $request
   */
  public function executeRemoveCredential(mvcRequest $request)
  {
    if (!$request->hasParameter('page_id') || !$request->hasParameter('credential_id'))
    {
      $this->redirect('page/index');
    }

    $pageId = $request->getParameter('page_id');
    $credentialId = $request->getParameter('credential_id');

    $page = PagePeer::retrieveByPK($pageId);

    if (!$page)
    {
      $this->redirect('page/index');
    }

    if (mvcTransaction::rollbackOnFalse(array('ResourceCredentialPeer', 'delete'), array($page, $credentialId)) === false)
    {
      mvcMessageHandler::getInstance()->addErrorMessage('A jogosultság törlése közben hiba történt.');
    }
    else
    {
      mvcMessageHandler::getInstance()->addSuccessMessage('A jogosultság sikeresen törlődött.');
    }

    $this->redirect('page/edit/id/' . $pageId);
  }

  /**
   * Gets the name for the list variable.
   */
  protected function getListVariableName()
  {
    return 'page_list';
  }

  /**
   * Gets the name for the object variable.
   */
  protected function getObjectVariableName()
  {
    return 'page';
  }

  /**
   * Gets the name for the model class.
   */
  protected function getModelClassName()
  {
    return 'Page';
  }

  /**
   * Gets the name for the peer class.
   */
  protected function getPeerClassName()
  {
    return 'PagePeer';
  }

  /**
   * Adds conditions to the criteria.
   *
   * @param Criteria
   */
  protected function editListCriteria(Criteria $c)
  {
    $c->addAscendingOrderByColumn(PagePeer::NAME);
  }
}
