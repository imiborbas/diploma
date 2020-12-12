<?php

/**
 * This class implements a basic routing algorithm, which can be extended if needed.
 *
 * The route is parsed like this:
 *   module/action/param1/value1/param2/value2
 */
class mvcRouter
{
  const ROUTE_PARAM = 'route'; // the key of the $_GET array containing the route to be parsed
  const ROUTE_DELIM = '/';     // the delimiter which separates route tokens

  /**
   * Parses the specified URL which comes from a GET parameter.
   * The output looks like this:
   * array(
   *   'module' => 'moduleName',
   *   'action' => 'actionName',
   *   'params' => array(
   *     'param1' => 'value1',
   *     'param2' => 'value2'
   *   )
   * )
   *
   * @return array
   */
  public function parseRoute()
  {
    $ret = mvcConfigStorage::get(
      'default_route',
      array('module' => 'default', 'action' => 'index')
    );

    $ret['params'] = array();

    if (!isset($_GET[self::ROUTE_PARAM]))
    {
      return $ret;
    }

    $route = $_GET[self::ROUTE_PARAM];
    $routeTokens = explode(self::ROUTE_DELIM, $route);

    $ret['module'] = isset($routeTokens[0]) ? $routeTokens[0] : $ret['module'];
    $ret['action'] = isset($routeTokens[1]) ? $routeTokens[1] : $ret['action'];

    $paramIndex = isset($routeTokens[2]) ? 2 : null;

    while ($paramIndex)
    {
      $valueIndex = $paramIndex + 1;
      $nextParamIndex = $paramIndex + 2;

      if (isset($routeTokens[$valueIndex]))
      {
        $ret['params'][$routeTokens[$paramIndex]] = $routeTokens[$valueIndex];
      }

      $paramIndex = isset($routeTokens[$nextParamIndex]) ? $nextParamIndex : null;
    }

    return $ret;
  }
}