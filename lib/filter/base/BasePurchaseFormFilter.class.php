<?php

/**
 * Purchase filter form base class.
 *
 * @package    MagOven
 * @subpackage filter
 * @author     Mark Simpson
 */
abstract class BasePurchaseFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'application_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_id'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'product_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'application_id' => new sfValidatorPass(array('required' => false)),
      'user_id'        => new sfValidatorPass(array('required' => false)),
      'product_id'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('purchase_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Purchase';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
      'application_id' => 'Text',
      'user_id'        => 'Text',
      'product_id'     => 'Text',
    );
  }
}
