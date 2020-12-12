<?php

require 'om/BaseUser.php';


/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser {

	/**
	 * Initializes internal state of User object.
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
    return MVC_FORM_DIR . DIRECTORY_SEPARATOR . 'userForm.xml';
  }

  /**
   * Loads data into this object from a given array.
   *
   * @param array  $arr
   * @param string $keyType
   */
  public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = UserPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUsername($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPassword($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setSalt($arr[$keys[3]]);

		if (array_key_exists($keys[4], $arr))
    {
      $this->setIsActive($arr[$keys[4]]);
    }
    else
    {
      $this->setIsActive(false);
    }

		if (array_key_exists($keys[5], $arr)) $this->setLastLogin($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setCreatedAt($arr[$keys[6]]);
	}

  /**
   * Sets the password hash and salt for this object.
   *
   * @param string $password
   */
  public function setPassword($password)
  {
    if (empty($password))
    {
      return;
    }
    
    $salt = md5(microtime());
    $hash = sha1($password . $salt);

    $this->setSalt($salt);
    parent::setPassword($hash);
  }

  /**
   * Checks a given password against the stored password hash.
   *
   * @param  string $password
   * @return boolean
   */
  public function checkPassword($password)
  {
    return sha1($password . $this->getSalt()) == $this->getPassword();
  }
} // User
