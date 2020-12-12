<?php

class defaultController extends mvcBaseController
{
  /**
   * Executes the default index action.
   * 
   * @param mvcRequest $request
   */
  public function executeIndex(mvcRequest $request)
  {
    if (!$this->getSession()->isSignedIn())
    {
      $this->redirect('user/login');
    }

    $this->redirect('page/readList');
  }
}
