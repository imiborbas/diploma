<?php

class mvcCrudController extends mvcBaseController
{
  /**
   * Executes the list action.
   *
   * @param mvcRequest $request
   */
  public function executeIndex(mvcRequest $request)
  {
    $listVar = $this->getListVariableName();
    $peerClass = $this->getPeerClassName();
    
    $c = new Criteria();
    $this->editListCriteria($c);

    $this->$listVar = call_user_func_array(array($peerClass, 'doSelect'), array($c));
  }

  /**
   * Executes the edit action.
   *
   * @param mvcRequest $request
   */
  public function executeEdit(mvcRequest $request)
  {
    $objVar = $this->getObjectVariableName();
    $modelClass = $this->getModelClassName();
    $peerClass = $this->getPeerClassName();

    $this->$objVar = new $modelClass();

    if ($request->hasParameter('id'))
    {
      $this->$objVar = call_user_func_array(array($peerClass, 'retrieveByPK'), array($request->getParameter('id')));
    }

    $this->form = new mvcForm($this->$objVar);
  }

  /**
   * Executes the create action.
   *
   * @param mvcRequest $request
   */
  public function executeCreate(mvcRequest $request)
  {
    $objVar = $this->getObjectVariableName();
    $modelClass = $this->getModelClassName();

    $this->$objVar = new $modelClass();

    $this->form = new mvcForm($this->$objVar);
  }

  /**
   * Executes the save action
   *
   * @param  mvcRequest $request
   * @return mixed
   */
  public function executeSave(mvcRequest $request)
  {
    $objVar = $this->getObjectVariableName();
    $modelClass = $this->getModelClassName();
    $peerClass = $this->getPeerClassName();

    if (!$request->hasParameter('data'))
    {
      $this->redirect($this->getModuleName() . '/index');
    }

    $data = $request->getParameter('data');
    $this->$objVar = call_user_func_array(array($peerClass, 'retrieveByPK'), array($request->getParameter('id')));

    if (!$this->$objVar)
    {
      $this->$objVar = new $modelClass();
    }

    $this->$objVar->fromArray($data, BasePeer::TYPE_FIELDNAME);

    $this->form = new mvcValidationForm($this->$objVar);

    if (!$this->form->isValid())
    {
      return $this->form->getObject()->isNew() ? 'create' : 'edit';
    }

    if (mvcTransaction::rollbackOnFalse(array($peerClass, 'save'), array($this->$objVar)) === false)
    {
      mvcMessageHandler::getInstance()->addErrorMessage('A mentés során hiba történt.');
    }
    else
    {
      mvcMessageHandler::getInstance()->addSuccessMessage('Sikeres mentés.');
    }

    $this->redirect($this->getModuleName() . '/index');
  }

  /**
   * Executes the delete action.
   *
   * @param mvcRequest $request
   */
  public function executeDelete(mvcRequest $request)
  {
    $peerClass = $this->getPeerClassName();

    if (!$request->hasParameter('id'))
    {
      $this->redirect($this->getModuleName() . '/index');
    }

    $object = call_user_func_array(array($peerClass, 'retrieveByPK'), array($request->getParameter('id')));

    if (!$object)
    {
      $this->redirect($this->getModuleName() . '/index');
    }

    if (mvcTransaction::rollbackOnFalse(array($peerClass, 'delete'), array($object)) === false)
    {
      mvcMessageHandler::getInstance()->addErrorMessage('A törlés során hiba történt.');
    }
    else
    {
      mvcMessageHandler::getInstance()->addSuccessMessage('Sikeres törlés.');
    }

    $this->redirect($this->getModuleName() . '/index');
  }

  /**
   * Gets the name for the list variable.
   */
  protected function getListVariableName()
  {
    throw new mvcException('Ez a metódus nincs implementálva.');
  }

  /**
   * Gets the name for the object variable.
   */
  protected function getObjectVariableName()
  {
    throw new mvcException('Ez a metódus nincs implementálva.');
  }

  /**
   * Gets the name for the model class.
   */
  protected function getModelClassName()
  {
    throw new mvcException('Ez a metódus nincs implementálva.');
  }

  /**
   * Gets the name for the peer class.
   */
  protected function getPeerClassName()
  {
    throw new mvcException('Ez a metódus nincs implementálva.');
  }

  /**
   * Adds conditions to the criteria.
   *
   * @param Criteria
   */
  protected function editListCriteria(Criteria $c)
  {
    throw new mvcException('Ez a metódus nincs implementálva.');
  }
}
