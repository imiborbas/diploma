<?php

class mvcFilterManager
{
  protected $filterStack = array();

  /**
   * Gets the array of filters from the config file, and initializes the filter stack.
   */
  public function __construct()
  {
    $filters = mvcConfigStorage::get('filter_chain', array());

    foreach ($filters as $filter)
    {
      if (!class_exists($filter))
      {
        throw new mvcException('A(z) ' . $filter . ' osztály nem létezik.');
      }

      $filterObject = new $filter();

      if (!($filterObject instanceof mvcChainFilter))
      {
        throw new mvcException($filter . ' oszálynak implementálnia kell a mvcChainFilter interfészt.');
      }

      $this->filterStack[] = $filterObject;
    }
  }

  /**
   * Executes the filters in the filter stack from top to bottom.
   *
   * @return boolean
   */
  public function beginExecution()
  {
    if (empty($this->filterStack))
    {
      return true;
    }

    $filter = array_shift($this->filterStack);
    
    return $filter->executeFilter($this->filterStack);
  }
}