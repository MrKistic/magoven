<?php

/**
 * Receipt form base class.
 *
 * @method Receipt getObject() Returns the current form's model object
 *
 * @package    MagOven
 * @subpackage form
 * @author     Mark Simpson
 */
abstract class BaseReceiptForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                          => new sfWidgetFormInputHidden(),
      'created_at'                  => new sfWidgetFormDateTime(),
      'updated_at'                  => new sfWidgetFormDateTime(),
      'application_id'              => new sfWidgetFormInputText(),
      'quantity'                    => new sfWidgetFormInputText(),
      'product_id'                  => new sfWidgetFormInputText(),
      'receipt_type'                => new sfWidgetFormInputText(),
      'transaction_id'              => new sfWidgetFormInputText(),
      'user_id'                     => new sfWidgetFormInputText(),
      'purchase_date'               => new sfWidgetFormInputText(),
      'original_transaction_id'     => new sfWidgetFormInputText(),
      'original_purchase_date'      => new sfWidgetFormInputText(),
      'app_item_id'                 => new sfWidgetFormInputText(),
      'version_external_identifier' => new sfWidgetFormInputText(),
      'bid'                         => new sfWidgetFormInputText(),
      'bvrs'                        => new sfWidgetFormInputText(),
      'base64_receipt'              => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                          => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'created_at'                  => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                  => new sfValidatorDateTime(array('required' => false)),
      'application_id'              => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'quantity'                    => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'product_id'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'receipt_type'                => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'transaction_id'              => new sfValidatorString(array('max_length' => 250, 'required' => true)),
      'user_id'                     => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'purchase_date'               => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'original_transaction_id'     => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'original_purchase_date'      => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'app_item_id'                 => new sfValidatorString(array('max_length' => 150, 'required' => false)),
      'version_external_identifier' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'bid'                         => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'bvrs'                        => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'base64_receipt'              => new sfValidatorString(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'Receipt', 'column' => array('application_id', 'user_id', 'transaction_id')))
    );

    $this->widgetSchema->setNameFormat('receipt[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Receipt';
  }


}
