<?php

/**
 * ApnsMessage form base class.
 *
 * @method ApnsMessage getObject() Returns the current form's model object
 *
 * @package    MagOven
 * @subpackage form
 * @author     Mark Simpson
 */
abstract class BaseApnsMessageForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'pid'       => new sfWidgetFormInputHidden(),
      'clientid'  => new sfWidgetFormInputText(),
      'fk_device' => new sfWidgetFormInputText(),
      'message'   => new sfWidgetFormInputText(),
      'delivery'  => new sfWidgetFormDateTime(),
      'status'    => new sfWidgetFormInputText(),
      'created'   => new sfWidgetFormDateTime(),
      'modified'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'pid'       => new sfValidatorChoice(array('choices' => array($this->getObject()->getPid()), 'empty_value' => $this->getObject()->getPid(), 'required' => false)),
      'clientid'  => new sfValidatorString(array('max_length' => 64, 'required' => true)),
      'fk_device' => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => true)),
      'message'   => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'delivery'  => new sfValidatorDateTime(array('required' => true)),
      'status'    => new sfValidatorString(array('max_length' => 12, 'required' => true)),
      'created'   => new sfValidatorDateTime(array('required' => false)),
      'modified'  => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('apns_message[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ApnsMessage';
  }


}
