<?php

/**
 * Receipt filter form base class.
 *
 * @package    MagOven
 * @subpackage filter
 * @author     Mark Simpson
 */
abstract class BaseReceiptFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'created_at'                  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at'                  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'application_id'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'quantity'                    => new sfWidgetFormFilterInput(),
      'product_id'                  => new sfWidgetFormFilterInput(),
      'receipt_type'                => new sfWidgetFormFilterInput(),
      'transaction_id'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_id'                     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'purchase_date'               => new sfWidgetFormFilterInput(),
      'original_transaction_id'     => new sfWidgetFormFilterInput(),
      'original_purchase_date'      => new sfWidgetFormFilterInput(),
      'app_item_id'                 => new sfWidgetFormFilterInput(),
      'version_external_identifier' => new sfWidgetFormFilterInput(),
      'bid'                         => new sfWidgetFormFilterInput(),
      'bvrs'                        => new sfWidgetFormFilterInput(),
      'base64_receipt'              => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'created_at'                  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'                  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'application_id'              => new sfValidatorPass(array('required' => false)),
      'quantity'                    => new sfValidatorPass(array('required' => false)),
      'product_id'                  => new sfValidatorPass(array('required' => false)),
      'receipt_type'                => new sfValidatorPass(array('required' => false)),
      'transaction_id'              => new sfValidatorPass(array('required' => false)),
      'user_id'                     => new sfValidatorPass(array('required' => false)),
      'purchase_date'               => new sfValidatorPass(array('required' => false)),
      'original_transaction_id'     => new sfValidatorPass(array('required' => false)),
      'original_purchase_date'      => new sfValidatorPass(array('required' => false)),
      'app_item_id'                 => new sfValidatorPass(array('required' => false)),
      'version_external_identifier' => new sfValidatorPass(array('required' => false)),
      'bid'                         => new sfValidatorPass(array('required' => false)),
      'bvrs'                        => new sfValidatorPass(array('required' => false)),
      'base64_receipt'              => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('receipt_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Receipt';
  }

  public function getFields()
  {
    return array(
      'id'                          => 'Number',
      'created_at'                  => 'Date',
      'updated_at'                  => 'Date',
      'application_id'              => 'Text',
      'quantity'                    => 'Text',
      'product_id'                  => 'Text',
      'receipt_type'                => 'Text',
      'transaction_id'              => 'Text',
      'user_id'                     => 'Text',
      'purchase_date'               => 'Text',
      'original_transaction_id'     => 'Text',
      'original_purchase_date'      => 'Text',
      'app_item_id'                 => 'Text',
      'version_external_identifier' => 'Text',
      'bid'                         => 'Text',
      'bvrs'                        => 'Text',
      'base64_receipt'              => 'Text',
    );
  }
}
