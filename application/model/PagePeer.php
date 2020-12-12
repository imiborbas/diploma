<?php

require 'om/BasePagePeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'page' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class PagePeer extends BasePagePeer {
  /**
   * Saves a page object, returns true on success, false on fail.
   *
   * @param  Page    $page
   * @return boolean
   */
  public static function save(Page $page)
  {
    $result = $page->save();

    return ($result == -1) ? false : $result;
  }

  /**
   * Deletes a page object, returns true on success, false on fail.
   *
   * @param  Page    $page
   * @return boolean
   */
  public static function delete(Page $page)
  {
    $page->delete();

    return true;
  }
} // PagePeer
