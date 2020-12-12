<?php

/**
 * A class for handling database operations wrapped in a transaction.
 */
class mvcTransaction
{
  private static $con = null; // Propel database connection
  
  /**
   * Calls $method with $params in a Propel transaction, then returns its value on success.
   * If there were errors, it rolls back the transaction, and returns false.
   *
   * @param  callback $method callback function to execute
   * @param  array    $params parameters of the callback function
   * @return mixed            return value of the callback function if there were no errors
   */
  public static function rollbackOnFalse($method, array $params = array())
  {
    if (is_null(self::$con))
    {
      self::init();
    }
    
    try
    {
      self::$con->beginTransaction();

      $ret = call_user_func_array($method, $params);
    }
    catch (Exception $e)
    {
      self::$con->rollback();
      
      throw $e;
    }
    
    if ($ret === false)
    {
      self::$con->rollback();
      
      return false;
    }
    else 
    {
      self::$con->commit();
      
      return $ret;
    }
  }
  
  /**
   * Obtains the database connection, and stores it.
   */
  private static function init()
  {
    self::$con = Propel::getConnection();
  }
}