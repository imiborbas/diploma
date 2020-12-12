<?php

require 'om/BaseCredential.php';


/**
 * Skeleton subclass for representing a row from the 'credential' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Credential extends BaseCredential {

	/**
	 * Initializes internal state of Credential object.
	 * @see        parent::__construct()
	 */
	public function __construct()
	{
		// Make sure that parent constructor is always invoked, since that
		// is where any default values for this object are set.
		parent::__construct();
	}

  /**
   * Returns the filename of the xml form associated with this object.
   *
   * @return string
   */
  public function getFormFilename()
  {
    return MVC_FORM_DIR . DIRECTORY_SEPARATOR . 'credentialForm.xml';
  }

} // Credential
