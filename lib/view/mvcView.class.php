<?php

require_once MVC_VENDOR_DIR . '/Twig/Autoloader.php';
Twig_Autoloader::register();

class mvcView
{
  const TEMPLATE_EXTENSION = '.html';

  const RESULT_SUCCESS = '';
  const RESULT_FAIL = 'Fail';

  /**
   * Renders the template for the given action/module with $templateVars.
   *
   * @param string $moduleName
   * @param string $templateName
   * @param array  $templateVars
   */
  public static function render($moduleName, $templateName, array $templateVars)
  {
    $templateDir = MVC_VIEW_DIR;
    $templateFile = $templateName . self::TEMPLATE_EXTENSION;

    $loader = new Twig_Loader_Filesystem($templateDir);
    $twig = new Twig_Environment($loader, array(
      'cache' => MVC_CACHE_DIR,
    ));

    $template = $twig->loadTemplate($moduleName . DIRECTORY_SEPARATOR . $templateFile);
    $template->display($templateVars);
  }
}
