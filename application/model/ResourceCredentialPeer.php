<?php

require 'om/BaseResourceCredentialPeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'resource_credential' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class ResourceCredentialPeer extends BaseResourceCredentialPeer {
  /**
   * Returns the available credentials for a given resource in an array.
   *
   * @param  Resource $res
   * @return array
   */
  public static function getAvailableCredentialOptionsForResource(Resource $res)
  {
    $con = Propel::getConnection();

    $sql =
      "SELECT " . CredentialPeer::ID . ", " . CredentialPeer::NAME . " FROM " . CredentialPeer::TABLE_NAME . " " .
      "WHERE " .CredentialPeer::ID . " NOT IN (SELECT " . ResourceCredentialPeer::CREDENTIAL_ID . " FROM " .
       ResourceCredentialPeer::TABLE_NAME . " WHERE " . ResourceCredentialPeer::RESOURCE_ID . " = " . $res->getResourceId() . " " .
      "AND " . ResourceCredentialPeer::RESOURCE_CLASS . " = '" . $res->getResourceClass() . "')";

    $stmt = $con->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Saves a resource credential object, returns true on success, false on fail.
   *
   * @param  ResourceCredential $resCredential
   * @return boolean
   */
  public static function save(ResourceCredential $resCredential)
  {
    $result = $resCredential->save();

    return ($result == -1) ? false : $result;
  }

  /**
   * Deletes a resource credential object, returns true on success, false on fail.
   *
   * @param  ResourceCredential $resCredential
   * @return boolean
   */
  public static function delete(Resource $res, $credentialId)
  {
    $c = new Criteria();

    $c->add(ResourceCredentialPeer::RESOURCE_ID, $res->getResourceId());
    $c->add(ResourceCredentialPeer::RESOURCE_CLASS, $res->getResourceClass());
    $c->add(ResourceCredentialPeer::CREDENTIAL_ID, $credentialId);

    $result = ResourceCredentialPeer::doDelete($c);

    return $result > 0;
  }

  /**
   * Determines whether a given user has access to a particular resource.
   *
   * @param  User     $user
   * @param  Resource $res
   * @return boolean
   */
  public static function userHasAccess(User $user, Resource $res)
  {
    $c = new Criteria();

    $c->add(UserCredentialPeer::USER_ID, $user->getId());
    $c->add(ResourceCredentialPeer::RESOURCE_ID, $res->getResourceId());
    $c->add(ResourceCredentialPeer::RESOURCE_CLASS, $res->getResourceClass());
    $c->addJoin(UserCredentialPeer::CREDENTIAL_ID, ResourceCredentialPeer::CREDENTIAL_ID, Criteria::INNER_JOIN);

    $result = ResourceCredentialPeer::doCount($c);

    return $result > 0;
  }

  /**
   * Returns all resource credentials associated with a particular resource.
   *
   * @param  Resource $res
   * @return array
   */
  public static function getResourceCredentialsForResource(Resource $res)
  {
    $c = new Criteria();

    $c->add(ResourceCredentialPeer::RESOURCE_ID, $res->getResourceId());
    $c->add(ResourceCredentialPeer::RESOURCE_CLASS, $res->getResourceClass());

    return ResourceCredentialPeer::doSelect($c);
  }

  /**
   * Returns available resource id-s for a given user and resource class.
   *
   * @param  User   $user
   * @param  string $resourceClass
   * @return array
   */
  public static function getAvailableResourceIDsForUser(User $user, $resourceClass)
  {
    $c = new Criteria();

    $c->add(ResourceCredentialPeer::RESOURCE_CLASS, $resourceClass);
    $c->add(UserCredentialPeer::USER_ID, $user->getId());
    $c->addJoin(UserCredentialPeer::CREDENTIAL_ID, ResourceCredentialPeer::CREDENTIAL_ID);
    $c->addJoin(CredentialPeer::ID, ResourceCredentialPeer::CREDENTIAL_ID);

    $c->clearSelectColumns();
    $c->addSelectColumn(ResourceCredentialPeer::RESOURCE_ID);

    $stmt = ResourceCredentialPeer::doSelectStmt($c);

    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    $ret = array();

    foreach ($result as $row)
    {
      $ret[] = $row[0];
    }

    return $ret;
  }
} // ResourceCredentialPeer
