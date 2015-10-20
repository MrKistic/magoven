<?php

/**
 * ApnsDeviceHistory form base class.
 *
 * @method ApnsDeviceHistory getObject() Returns the current form's model object
 *
 * @package    MagOven
 * @subpackage form
 * @author     Mark Simpson
 */
abstract class BaseApnsDeviceHistoryForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'pid'           => new sfWidgetFormInputHidden(),
      'clientid'      => new sfWidgetFormInputText(),
      'appname'       => new sfWidgetFormInputText(),
      'appversion'    => new sfWidgetFormInputText(),
      'deviceuid'     => new sfWidgetFormInputText(),
      'devicetoken'   => new sfWidgetFormInputText(),
      'devicename'    => new sfWidgetFormInputText(),
      'devicemodel'   => new sfWidgetFormInputText(),
      'deviceversion' => new sfWidgetFormInputText(),
      'pushbadge'     => new sfWidgetFormInputText(),
      'pushalert'     => new sfWidgetFormInputText(),
      'pushsound'     => new sfWidgetFormInputText(),
      'development'   => new sfWidgetFormInputText(),
      'status'        => new sfWidgetFormInputText(),
      'archived'      => new sfWidgetFormDateTime(),
      'created'       => new sfWidgetFormDateTime(),
      'modified'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'pid'           => new sfValidatorChoice(array('choices' => array($this->getObject()->getPid()), 'empty_value' => $this->getObject()->getPid(), 'required' => false)),
      'clientid'      => new sfValidatorString(array('max_length' => 64, 'required' => true)),
      'appname'       => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'appversion'    => new sfValidatorString(array('max_length' => 25, 'required' => false)),
      'deviceuid'     => new sfValidatorString(array('max_length' => 40, 'required' => true)),
      'devicetoken'   => new sfValidatorString(array('max_length' => 64, 'required' => true)),
      'devicename'    => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'devicemodel'   => new sfValidatorString(array('max_length' => 100, 'required' => true)),
      'deviceversion' => new sfValidatorString(array('max_length' => 25, 'required' => true)),
      'pushbadge'     => new sfValidatorString(array('max_length' => 12, 'required' => false)),
      'pushalert'     => new sfValidatorString(array('max_length' => 12, 'required' => false)),
      'pushsound'     => new sfValidatorString(array('max_length' => 12, 'required' => false)),
      'development'   => new sfValidatorString(array('max_length' => 12, 'required' => true)),
      'status'        => new sfValidatorString(array('max_length' => 12, 'required' => true)),
      'archived'      => new sfValidatorDateTime(array('required' => true)),
      'created'       => new sfValidatorDateTime(array('required' => false)),
      'modified'      => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('apns_device_history[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ApnsDeviceHistory';
  }


}
