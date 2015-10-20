<?php

/**
 * ApnsDevice filter form base class.
 *
 * @package    MagOven
 * @subpackage filter
 * @author     Mark Simpson
 */
abstract class BaseApnsDeviceFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'clientid'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'appname'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'appversion'    => new sfWidgetFormFilterInput(),
      'deviceuid'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'devicetoken'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'devicename'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'devicemodel'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'deviceversion' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'pushbadge'     => new sfWidgetFormFilterInput(),
      'pushalert'     => new sfWidgetFormFilterInput(),
      'pushsound'     => new sfWidgetFormFilterInput(),
      'development'   => new sfWidgetFormFilterInput(),
      'status'        => new sfWidgetFormFilterInput(),
      'created'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'modified'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'clientid'      => new sfValidatorPass(array('required' => false)),
      'appname'       => new sfValidatorPass(array('required' => false)),
      'appversion'    => new sfValidatorPass(array('required' => false)),
      'deviceuid'     => new sfValidatorPass(array('required' => false)),
      'devicetoken'   => new sfValidatorPass(array('required' => false)),
      'devicename'    => new sfValidatorPass(array('required' => false)),
      'devicemodel'   => new sfValidatorPass(array('required' => false)),
      'deviceversion' => new sfValidatorPass(array('required' => false)),
      'pushbadge'     => new sfValidatorPass(array('required' => false)),
      'pushalert'     => new sfValidatorPass(array('required' => false)),
      'pushsound'     => new sfValidatorPass(array('required' => false)),
      'development'   => new sfValidatorPass(array('required' => false)),
      'status'        => new sfValidatorPass(array('required' => false)),
      'created'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'modified'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('apns_device_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ApnsDevice';
  }

  public function getFields()
  {
    return array(
      'pid'           => 'Number',
      'clientid'      => 'Text',
      'appname'       => 'Text',
      'appversion'    => 'Text',
      'deviceuid'     => 'Text',
      'devicetoken'   => 'Text',
      'devicename'    => 'Text',
      'devicemodel'   => 'Text',
      'deviceversion' => 'Text',
      'pushbadge'     => 'Text',
      'pushalert'     => 'Text',
      'pushsound'     => 'Text',
      'development'   => 'Text',
      'status'        => 'Text',
      'created'       => 'Date',
      'modified'      => 'Date',
    );
  }
}
