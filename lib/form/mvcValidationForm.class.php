<?php

class mvcValidationForm extends mvcForm
{
  protected $validators = array();

  /**
   * Processes validator nodes.
   *
   * @param DOMElement $node
   */
  public function processNodeValidator(DOMElement $node)
  {
    $input = $node->parentNode;
    $inputId = $input->getAttribute('id');

    if (!$node->hasAttribute('class'))
    {
      throw new mvcException('A valitátor elemnek kell hogy legyen class attribútuma!');
    }

    $class = $node->getAttribute('class');

    if (!class_exists($class))
    {
      throw new mvcException('A megadott validátor osztály nem létezik: ' . $class . '.');
    }

    $parameters = array();
    $parameterNodes = $node->getElementsByTagName('param');
    foreach ($parameterNodes as $parameterNode)
    {
      $key = $parameterNode->getAttribute('name');

      if ($parameterNode->hasAttribute('value'))
      {
        $val = $parameterNode->getAttribute('value');
      }
      else if ($parameterNode->hasAttribute('property'))
      {
        $val = $this->getObjectProperty($parameterNode->getAttribute('property'));
      }
      else
      {
        throw new mvcException('Egy validátor paraméternek kell vagy egy value vagy egy property attribútum.');
      }

      if ($val)
      {
        $parameters[$key] = $val;
      }
    }

    $errors = array();
    $errorNodes = $node->getElementsByTagName('error');
    foreach ($errorNodes as $errorNode)
    {
      $key = $errorNode->getAttribute('name');
      $val = $errorNode->nodeValue;

      $errors[$key] = $val;
    }

    $validator = new $class($parameters, $errors);

    if (!is_subclass_of($validator, 'mvcValidator'))
    {
      throw new mvcException('A megadott validátor osztálynak az mvcValidator ősosztályból kell származnia.');
    }

    $this->validators[$inputId] = array('validator' => $validator, 'input' => $input);
  }

  /**
   * Checks whether the values of the object associated with this form are valid.
   * This is done by calling the validators specified in the form xml.
   *
   * @return boolean
   */
  public function isValid()
  {
    $valid = true;

    foreach ($this->validators as $validator)
    {
      $validatorObject = $validator['validator'];
      $value = $this->getObjectProperty($validator['input']->getAttribute('propertyName'));
      
      if (!$validatorObject->isValid($value))
      {
        $valid = false;

        foreach ($validatorObject->getErrors($value) as $errorMessage)
        {
          $label = $this->getLabelForInput($validator['input']->getAttribute('id'), '');
          mvcMessageHandler::getInstance()->addErrorMessage("<b>$label:</b> $errorMessage");
        }
      }
    }

    return $valid;
  }
}