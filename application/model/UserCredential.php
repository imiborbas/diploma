<?php

require 'om/BaseUserCredential.php';


/**
 * Skeleton subclass for representing a row from the 'user_credential' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class UserCredential extends BaseUserCredential {

	/**
	 * Initializes internal state of UserCredential object.
	 * @see        parent::__construct()
	 */
	public function __construct()
	{
		// Make sure that parent constructor is always invoked, since that
		// is where any default values for this object are set.
		parent::__construct();
	}

} // UserCredential
