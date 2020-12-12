<?php

class errorController extends mvcBaseController
{
  /**
   * Executes the 404 error action.
   *
   * @param mvcRequest $request
   */
  public function executeError404(mvcRequest $request)
  {
    header("HTTP/1.0 404 Not Found");
  }
}