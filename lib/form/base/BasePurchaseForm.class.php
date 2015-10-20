<?php

/**
 * Purchase form base class.
 *
 * @method Purchase getObject() Returns the current form's model object
 *
 * @package    MagOven
 * @subpackage form
 * @author     Mark Simpson
 */
abstract class BasePurchaseForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
      'application_id' => new sfWidgetFormInputText(),
      'user_id'        => new sfWidgetFormInputText(),
      'product_id'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'created_at'     => new sfValidatorDateTime(array('required' => false)),
      'updated_at'     => new sfValidatorDateTime(array('required' => false)),
      'application_id' => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'user_id'        => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'product_id'     => new sfValidatorString(array('max_length' => 255, 'required' => true)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'Purchase', 'column' => array('application_id', 'user_id', 'product_id')))
    );

    $this->widgetSchema->setNameFormat('purchase[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Purchase';
  }


}
