<?php

require 'om/BaseResourceCredential.php';


/**
 * Skeleton subclass for representing a row from the 'resource_credential' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class ResourceCredential extends BaseResourceCredential {

	/**
	 * Initializes internal state of ResourceCredential object.
	 * @see        parent::__construct()
	 */
	public function __construct()
	{
		// Make sure that parent constructor is always invoked, since that
		// is where any default values for this object are set.
		parent::__construct();
	}

  /**
   * Sets the ResourceId and ResourceClass for this object.
   *
   * @param Resource $res
   */
  public function setResource(Resource $res)
  {
    $this->setResourceId($res->getResourceId());
    $this->setResourceClass($res->getResourceClass());
  }
} // ResourceCredential
