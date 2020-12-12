<?php

/**
 * Defines a uniform interface for identifying resources.
 */
interface Resource
{
  public function getResourceId();
  public function getResourceClass();
}