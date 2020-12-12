<?php

class UserValidator extends mvcStringValidator
{
  /**
   * Configures the validator with the given parameters and messages.
   *
   * @param array $parameters
   * @param array $messages
   */
  public function configure(array $parameters, array $messages)
  {
    // set defaults
    $this->parameters['user_id'] = null;
    $this->messages['unique_error'] = 'Ez a felhasználónév már létezik.';

    $this->parameters = array_merge($this->parameters, $parameters);
    $this->messages = array_merge($this->messages, $messages);
  }

  /**
   * Returns validation errors if there were any.
   *
   * @param  string $value
   * @return boolean
   */
  public function getErrors($value)
  {
    $errors = parent::getErrors($value);

    $userId = $this->parameters['user_id'];
    $userByName = UserPeer::retrieveByUsername($value);

    if ($userByName && $userId != $userByName->getId())
    {
      $errors[] = $this->messages['unique_error'];
    }

    return $errors;
  }
}