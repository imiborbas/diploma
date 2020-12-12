<?php

class userController extends mvcCrudController
{
  /**
   * Executes the edit action.
   * 
   * @param mvcRequest $request
   */
  public function executeEdit(mvcRequest $request)
  {
    parent::executeEdit($request);

    $this->available_credentials = UserCredentialPeer::getAvailableCredentialOptionsForUser($this->user);
    $this->credential_list = $this->user->getUserCredentialsJoinCredential();
  }

  /**
   * Executes the action which assigns a Credential to a User.
   *
   * @param mvcRequest $request
   */
  public function executeAssignCredential(mvcRequest $request)
  {
    if (!$request->hasParameter('user_id') || !$request->hasParameter('credential_id'))
    {
      $this->redirect('user/index');
    }

    $userId = $request->getParameter('user_id');
    $credentialId = $request->getParameter('credential_id');

    $userCredential = new UserCredential();
    $userCredential->setUserId($userId);
    $userCredential->setCredentialId($credentialId);

    if (mvcTransaction::rollbackOnFalse(array('UserCredentialPeer', 'save'), array($userCredential)) === false)
    {
      mvcMessageHandler::getInstance()->addErrorMessage('A jogosultság hozzáadásakor hiba történt.');
    }
    else
    {
      mvcMessageHandler::getInstance()->addSuccessMessage('Jogosultság sikeresen hozzáadva.');
    }

    $this->redirect('user/edit/id/' . $userId);
  }

  /**
   * Executes the action which removes a UserCredential association.
   *
   * @param mvcRequest $request
   */
  public function executeRemoveCredential(mvcRequest $request)
  {
    if (!$request->hasParameter('user_id') || !$request->hasParameter('credential_id'))
    {
      $this->redirect('user/index');
    }

    $userId = $request->getParameter('user_id');
    $credentialId = $request->getParameter('credential_id');

    if (mvcTransaction::rollbackOnFalse(array('UserCredentialPeer', 'delete'), array($userId, $credentialId)) === false)
    {
      mvcMessageHandler::getInstance()->addErrorMessage('A jogosultság eltávolítása közben hiba történt.');
    }
    else
    {
      mvcMessageHandler::getInstance()->addSuccessMessage('Jogosultság sikeresen eltávolítva.');
    }

    $this->redirect('user/edit/id/' . $userId);
  }

  /**
   * Executes user login action.
   *
   * @param mvcRequest $request
   */
  public function executeLogin(mvcRequest $request)
  {
    $session = $this->getSession();

    if ($session->isSignedIn())
    {
      $this->redirect('');
    }

    if (
      $request->getMethod() == mvcRequest::METHOD_POST &&
      $request->hasParameter('username') &&
      $request->hasParameter('password')
    )
    {
      $username = $request->getParameter('username');
      $password = $request->getParameter('password');
      $word = $request->getParameter('word', '');

      $this->username = $username;

      $user = UserPeer::retrieveByUsername($username);

      if (!$user)
      {
        mvcMessageHandler::getInstance()->addErrorMessage('A felhasználó nem létezik.');

        return 'login';
      }

      if (!$user->checkPassword($password))
      {
        mvcMessageHandler::getInstance()->addErrorMessage('Helytelen jelszó.');

        return 'login';
      }

      if (!$user->getIsActive())
      {
        mvcMessageHandler::getInstance()->addErrorMessage('A felhasználó nincs engedélyezve.');

        return 'login';
      }

      if (!$this->validateCaptcha($word))
      {
        mvcMessageHandler::getInstance()->addErrorMessage('Érvénytelen CAPTCHA kód.');

        return 'login';
      }

      $session->signIn($user);

      $this->redirect('');
    }
  }

  /**
   * Executes user logout action.
   *
   * @param mvcRequest $request
   */
  public function executeLogout(mvcRequest $request)
  {
    $this->getSession()->signOut();

    $this->redirect('');
  }

  /**
   * Executes the action which displays an 'Unavailable' message.
   * 
   * @param mvcRequest $request
   */
  public function executeUnavailable(mvcRequest $request)
  {
  }

  /**
   * Gets the name for the list variable.
   */
  protected function getListVariableName()
  {
    return 'user_list';
  }

  /**
   * Gets the name for the object variable.
   */
  protected function getObjectVariableName()
  {
    return 'user';
  }

  /**
   * Gets the name for the model class.
   */
  protected function getModelClassName()
  {
    return 'User';
  }

  /**
   * Gets the name for the peer class.
   */
  protected function getPeerClassName()
  {
    return 'UserPeer';
  }

  /**
   * Adds conditions to the criteria.
   *
   * @param Criteria
   */
  protected function editListCriteria(Criteria $c)
  {
    $c->addAscendingOrderByColumn(UserPeer::USERNAME);
  }

  /**
   * Validates a captcha token.
   *
   * @param  string $word
   * @return boolean
   */
  protected function validateCaptcha($word)
  {
    $session = $this->getSession();
    $ret = false;
    
    if($session->getValue('freecap_word_hash', false) && !empty($word))
    {
      $hashFunc = $session->getValue('hash_func', null);
      if($hashFunc(strtolower($word)) == $session->getValue('freecap_word_hash'))
      {
        $session->setValue('freecap_attempts', 0);
        $session->setValue('freecap_word_hash', false);

        $ret = true;
      }
    }

    return $ret;
  }
}
