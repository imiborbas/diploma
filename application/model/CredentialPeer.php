<?php

require 'om/BaseCredentialPeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'credential' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class CredentialPeer extends BaseCredentialPeer {
  /**
   * Saves a credential object, returns true on success, false on fail.
   *
   * @param  Credential $credential
   * @return boolean
   */
  public static function save(Credential $credential)
  {
    $result = $credential->save();

    return ($result == -1) ? false : $result;
  }

  /**
   * Deletes a credential object, returns true on success, false on fail.
   *
   * @param  Credential $credential
   * @return boolean
   */
  public static function delete(Credential $credential)
  {
    $credential->delete();

    return true;
  }
} // CredentialPeer
