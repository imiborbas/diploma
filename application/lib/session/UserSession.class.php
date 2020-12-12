<?php

class UserSession extends mvcSession
{
  protected $user = null;

  /**
   * Checks whether the actual user is authenticated.
   *
   * @return boolean
   */
  public function isSignedIn()
  {
    return $this->getValue('userSignedIn', false);
  }

  /**
   * Sets the userSignedIn session attribute to the given value.
   *
   * @param boolean $value
   */
  protected function setSignedIn($value)
  {
    $this->setValue('userSignedIn', $value);
  }

  /**
   * Associates the given authenticated user with this session.
   *
   * @param User $user
   */
  public function signIn(User $user)
  {
    $this->user = $user;
    $this->setValue('user_id', $user->getId());

    $this->user->setLastLogin(time());
    $this->user->save();

    $this->setSignedIn(true);
  }

  /**
   * Removes the association between a User and the session,
   * and then destroys the session.
   *
   * @param User $user
   */
  public function signOut()
  {
    $this->user = null;
    $this->setValue('user_id', null);

    $this->setSignedIn(false);

    session_destroy();
  }

  /**
   * Returns the user associated to this session.
   *
   * @return User
   */
  public function getUser()
  {
    if ($this->user)
    {
      return $this->user;
    }

    $this->user = UserPeer::retrieveByPK($this->getValue('user_id', null));

    return $this->user;
  }

  /**
   * Checks whether the currently authenticated user has a particular credential.
   *
   * @param  string $credentialName
   * @return boolean
   */
  public function hasCredential($credentialName)
  {
    return UserCredentialPeer::userHasCredential($this->getUser(), $credentialName);
  }

  /**
   * Checks whether the currently authenticated user has access
   * to a particular resource.
   *
   * @param  Resource $res
   * @return boolean
   */
  public function hasAccessToResource(Resource $res)
  {
    return ResourceCredentialPeer::userHasAccess($this->getUser(), $res);
  }
}