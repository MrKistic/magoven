<?php

/**
 * ApnsMessage filter form base class.
 *
 * @package    MagOven
 * @subpackage filter
 * @author     Mark Simpson
 */
abstract class BaseApnsMessageFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'clientid'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'fk_device' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'message'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'delivery'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'status'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'modified'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'clientid'  => new sfValidatorPass(array('required' => false)),
      'fk_device' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'message'   => new sfValidatorPass(array('required' => false)),
      'delivery'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'status'    => new sfValidatorPass(array('required' => false)),
      'created'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'modified'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('apns_message_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ApnsMessage';
  }

  public function getFields()
  {
    return array(
      'pid'       => 'Number',
      'clientid'  => 'Text',
      'fk_device' => 'Number',
      'message'   => 'Text',
      'delivery'  => 'Date',
      'status'    => 'Text',
      'created'   => 'Date',
      'modified'  => 'Date',
    );
  }
}
