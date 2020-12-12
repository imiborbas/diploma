<?php

class credentialController extends mvcCrudController
{
  /**
   * Gets the name for the list variable.
   */
  protected function getListVariableName()
  {
    return 'credential_list';
  }

  /**
   * Gets the name for the object variable.
   */
  protected function getObjectVariableName()
  {
    return 'credential';
  }

  /**
   * Gets the name for the model class.
   */
  protected function getModelClassName()
  {
    return 'Credential';
  }

  /**
   * Gets the name for the peer class.
   */
  protected function getPeerClassName()
  {
    return 'CredentialPeer';
  }

  /**
   * Adds conditions to the criteria.
   *
   * @param Criteria
   */
  protected function editListCriteria(Criteria $c)
  {
    $c->addAscendingOrderByColumn(CredentialPeer::NAME);
  }
}
