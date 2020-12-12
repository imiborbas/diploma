<?php

require 'om/BaseUserPeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'user' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class UserPeer extends BaseUserPeer {
  /**
   * Saves a user object, returns true on success, false on fail.
   *
   * @param  User    $user
   * @return boolean
   */
  public static function save(User $user)
  {
    $result = $user->save();
    
    return ($result == -1) ? false : $result;
  }

  /**
   * Deletes a user object, returns true on success, false on fail.
   *
   * @param  User    $user
   * @return boolean
   */
  public static function delete(User $user)
  {
    $user->delete();

    return true;
  }

  /**
   * Retrieves a user object by its username.
   *
   * @param  string  $username
   * @return User
   */
  public static function retrieveByUsername($username)
  {
    $c = new Criteria();

    $c->add(UserPeer::USERNAME, $username);

    return UserPeer::doSelectOne($c);
  }
} // UserPeer
