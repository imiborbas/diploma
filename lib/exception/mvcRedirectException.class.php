<?php

class mvcRedirectException extends Exception
{
  private $uri = null;

  /**
   * Constructs the redirection.
   *
   * @param string $uri
   */
  public function __construct($uri)
  {
    $this->uri = $uri;
  }

  /**
   * Returns the redirection URI.
   *
   * @return string
   */
  public function getUri()
  {
    return $this->uri;
  }
}