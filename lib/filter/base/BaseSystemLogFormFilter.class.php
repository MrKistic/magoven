<?php

/**
 * SystemLog filter form base class.
 *
 * @package    MagOven
 * @subpackage filter
 * @author     Mark Simpson
 */
abstract class BaseSystemLogFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'log_type'       => new sfWidgetFormFilterInput(),
      'message'        => new sfWidgetFormFilterInput(),
      'application_id' => new sfWidgetFormFilterInput(),
      'user_id'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'log_type'       => new sfValidatorPass(array('required' => false)),
      'message'        => new sfValidatorPass(array('required' => false)),
      'application_id' => new sfValidatorPass(array('required' => false)),
      'user_id'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('system_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SystemLog';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
      'log_type'       => 'Text',
      'message'        => 'Text',
      'application_id' => 'Text',
      'user_id'        => 'Text',
    );
  }
}
