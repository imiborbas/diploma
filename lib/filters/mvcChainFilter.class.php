<?php

abstract class mvcChainFilter
{
  protected $filterStack = null;

  public abstract function executeFilter(array $filterStack);

  /**
   * Executes the next filter in the filter chain.
   *
   * @return boolean
   */
  protected function executeNextFilter()
  {
    if (empty($this->filterStack))
    {
      return true;
    }

    $nextFilter = array_shift($this->filterStack);
    return $nextFilter->executeFilter($this->filterStack);
  }
}