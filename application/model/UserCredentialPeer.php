<?php

require 'om/BaseUserCredentialPeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'user_credential' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class UserCredentialPeer extends BaseUserCredentialPeer {
  /**
   * Returns the available credentials for a given user in an array.
   *
   * @param  User  $user
   * @return array
   */
  public static function getAvailableCredentialOptionsForUser(User $user)
  {
    $con = Propel::getConnection();

    $sql =
      "SELECT " . CredentialPeer::ID . ", " . CredentialPeer::NAME . " FROM " . CredentialPeer::TABLE_NAME . " " .
      "WHERE " .CredentialPeer::ID . " NOT IN (SELECT " . UserCredentialPeer::CREDENTIAL_ID . " FROM " .
       UserCredentialPeer::TABLE_NAME . " WHERE " . UserCredentialPeer::USER_ID . " = " . $user->getId() . ")";

    $stmt = $con->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Saves a user credential object, returns true on success, false on fail.
   *
   * @param  UserCredential $userCredential
   * @return boolean
   */
  public static function save(UserCredential $userCredential)
  {
    $result = $userCredential->save();

    return ($result == -1) ? false : $result;
  }

  /**
   * Deletes a user credential object, returns true on success, false on fail.
   *
   * @param  int     $userId
   * @param  int     $credentialId
   * @return boolean
   */
  public static function delete($userId, $credentialId)
  {
    $c = new Criteria();

    $c->add(UserCredentialPeer::USER_ID, $userId);
    $c->add(UserCredentialPeer::CREDENTIAL_ID, $credentialId);

    $result = UserCredentialPeer::doDelete($c);

    return $result > 0;
  }

  /**
   * Determines whether a given user has a particular credential.
   *
   * @param  User    $user
   * @param  string  $credentialName
   * @return boolean
   */
  public static function userHasCredential($user, $credentialName)
  {
    if (!$user)
    {
      return false;
    }
    
    $c = new Criteria();

    $c->add(UserCredentialPeer::USER_ID, $user->getId());
    $c->add(CredentialPeer::NAME, $credentialName);
    $c->addJoin(UserCredentialPeer::CREDENTIAL_ID, CredentialPeer::ID, Criteria::INNER_JOIN);

    $result = UserCredentialPeer::doCount($c);

    return $result > 0;
  }
} // UserCredentialPeer
