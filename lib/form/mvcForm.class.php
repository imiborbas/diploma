<?php

class mvcForm
{
  const FILENAME_METHOD = 'getFormFilename';

  protected $filename = null;
  protected $object = null;
  protected $domDocument = null;

  /**
   * Initializes this object.
   *
   * @param Object $object
   */
  public function __construct($object)
  {
    $this->object = $object;

    $this->init();
  }

  /**
   * Returns the filename of the form xml file.
   *
   * @return string
   */
  public function getFilename()
  {
    return $this->filename;
  }

  /**
   * Returns the object which is associated with this form.
   *
   * @return Object
   */
  public function getObject()
  {
    return $this->object;
  }

  /**
   * This method starts processing the form xml file.
   */
  public function init()
  {
    if (!is_object($this->object))
    {
      throw new mvcException('Az objektum nem érvényes!');
    }
    
    if (!method_exists($this->object, self::FILENAME_METHOD))
    {
      throw new mvcException('Az átadott objektumnak implementálnia kell a ' . self::FILENAME_METHOD . ' metódust!');
    }
    
    $filenameMethod = self::FILENAME_METHOD;
    $this->filename = $this->object->$filenameMethod();

    if (!is_readable($this->filename))
    {
      throw new mvcException('Nem sikerült a következő állományt megnyitni: ' . $this->filename);
    }

    $this->domDocument = new DOMDocument('1.0');
    $this->domDocument->load($this->filename);
    $this->domDocument->formatOutput = true;

    $form = $this->domDocument->getElementsByTagName('form');

    if (count($form) != 1)
    {
      throw new mvcException('Kizárólag egy form elem engedélyezett!');
    }

    $form = $form->item(0);

    $this->processNodes($form);
  }

  /**
   * Returns an HTML representation of the form.
   *
   * @return string
   */
  public function exportHTML()
  {
    $document = clone $this->domDocument;
    $this->cleanDocument($document);

    $output = $document->saveXML();
    $output = preg_replace('/<\?xml.*\?>/', '', $output);

    $document = null;

	  return $output;
  }

  /**
   * This method iterates through the child nodes of the form,
   * and calls the corresponding processor method of the child node if available.
   *
   * @param DOMElement $root
   */
  protected function processNodes(DOMElement $root)
  {
    $nodes = $root->childNodes;

    foreach ($nodes as $node)
    {
      if ($node instanceof DOMElement)
      {
        $methodName = 'processNode' . ucfirst(strtolower($node->nodeName));
        
        if (is_callable(array($this, $methodName)))
        {
          $this->$methodName($node);
        }
      }
    }
  }

  /**
   * Processes an input node.
   *
   * @param DOMElement $node
   */
  protected function processNodeInput(DOMElement $node)
  {
    if (!$node->hasAttribute('type'))
    {
      throw new mvcException('Az input elemnek kell hogy legyen type attribútuma!');
    }

    $type = $node->getAttribute('type');

    if ($node->hasAttribute('propertyName'))
    {
      if (!$node->hasAttribute('id'))
      {
        throw new mvcException('Az input elemnek kell hogy legyen id attribútuma!');
      }

      $value = $this->getObjectProperty($node->getAttribute('propertyName'));

      if ($value)
      {
        switch ($type)
        {
          case 'checkbox':
          case 'radio':
            $node->setAttribute('checked', 'checked');
            break;

          default:
            $node->setAttribute('value', $value);
            break;
        }
      }
    }

    $this->processNodes($node);
  }

  /**
   * Processes a textarea node.
   *
   * @param DOMElement $node
   */
  protected function processNodeTextarea(DOMElement $node)
  {
    if ($node->hasAttribute('propertyName'))
    {
      $value = $this->getObjectProperty($node->getAttribute('propertyName'));

      if ($value)
      {
        $node->nodeValue = $value;
      }
    }
  }

  /**
   * Cleans the document from unwanted non-standard elements in order
   * for the html output to be as valid as possible.
   *
   * @param DOMDocument $document
   */
  protected function cleanDocument(DOMDocument $document)
  {
    $inputs = $document->getElementsByTagName('input');

    foreach ($inputs as $input)
    {
      while ($input->childNodes->length)
      {
        $node = $input->childNodes->item(0);
        $input->removeChild($node);
      }

      $input->removeAttribute('propertyName');
    }

    $textareas = $document->getElementsByTagName('textarea');

    foreach ($textareas as $textarea)
    {
      $input->removeAttribute('propertyName');
    }

    $document->normalizeDocument();
  }

  /**
   * Gets a property of the associated object if it is available.
   *
   * @param  string $propertyName
   * @return mixed
   */
  protected function getObjectProperty($propertyName)
  {
    $propertyName = 'get' . $propertyName;

    if (!is_callable(array($this->object, $propertyName)))
    {
      return null;
    }

    return $this->object->$propertyName();
  }

  /**
   * Gets the value (content node) of the label for an input if it is available.
   *
   * @param  string $inputId
   * @param  string $default
   * @return string
   */
  public function getLabelForInput($inputId, $default = null)
  {
    $ret = $default;

    foreach ($this->domDocument->getElementsByTagName('label') as $label)
    {
      if ($label->hasAttribute('for') && $label->getAttribute('for') == $inputId)
      {
        $ret = $label->nodeValue;

        break;
      }
    }

    return $ret;
  }

  /**
   * Converts the object to string. It returns the HTML representation.
   *
   * @return string
   */
  public function __toString()
  {
    return $this->exportHTML();
  }
}
