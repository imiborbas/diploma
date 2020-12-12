<?php

class mvcStringValidator extends mvcValidator
{
  /**
   * Initializes the validator, sets the default values and messages.
   *
   * @param array $parameters
   * @param array $messages
   */
  public function configure(array $parameters, array $messages)
  {
    // set defaults
    $this->parameters = array(
      'required'   => false,
      'min_length' => null,
      'max_length' => null
    );

    $this->messages = array(
      'required_error' => 'A mezőt kötelező kitölteni.',
      'min_error'      => 'A mezőnek legalább %min_length% karakter hosszúnak kell lennie.',
      'max_error'      => 'A mezőnek legfeljebb %max_length% karakter hosszúnak kell lennie.'
    );

    $this->parameters = array_merge($this->parameters, $parameters);
    $this->messages = array_merge($this->messages, $messages);
  }

  /**
   * Returns an array of error messages if there were any.
   *
   * @param  string $value
   * @return array
   */
  public function getErrors($value)
  {
    $errors = array();

    if (!$this->parameters['required'] && empty($value))
    {
      return $errors;
    }

    if ($this->parameters['required'] && empty($value))
    {
      $errors[] = $this->messages['required_error'];
    }

    if ($this->parameters['min_length'] && strlen($value) < $this->parameters['min_length'])
    {
      $errors[] = $this->messages['min_error'];
    }

    if ($this->parameters['max_length'] && strlen($value) > $this->parameters['max_length'])
    {
      $errors[] = $this->messages['max_error'];
    }

    $this->transformErrors($errors);

    return $errors;
  }
}