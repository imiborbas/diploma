<?php

require 'om/BasePage.php';


/**
 * Skeleton subclass for representing a row from the 'page' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Page extends BasePage implements Resource {

	/**
	 * Initializes internal state of Page object.
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
    return MVC_FORM_DIR . DIRECTORY_SEPARATOR . 'pageForm.xml';
  }

  /**
   * Saves this object, sets CreatedAt property if it was new.
   *
   * @param PropelPDO $con
   */
  public function save(PropelPDO $con = null)
  {
    if ($this->isNew())
    {
      $this->setCreatedAt(time());
    }

    parent::save($con);
  }

  /**
   * Returns the id of this object.
   *
   * @return int
   */
  public function getResourceId()
  {
    return $this->getId();
  }

  /**
   * Returns the class name of this object.
   *
   * @return string
   */
  public function getResourceClass()
  {
    return __CLASS__;
  }
} // Page
