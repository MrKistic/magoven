<?php

/**
 * Subscription form base class.
 *
 * @method Subscription getObject() Returns the current form's model object
 *
 * @package    MagOven
 * @subpackage form
 * @author     Mark Simpson
 */
abstract class BaseSubscriptionForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'application_id'  => new sfWidgetFormInputText(),
      'user_id'         => new sfWidgetFormInputText(),
      'effective_date'  => new sfWidgetFormDateTime(),
      'expiration_date' => new sfWidgetFormDateTime(),
      'last_validated'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(array('required' => false)),
      'updated_at'      => new sfValidatorDateTime(array('required' => false)),
      'application_id'  => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'user_id'         => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'effective_date'  => new sfValidatorDateTime(array('required' => true)),
      'expiration_date' => new sfValidatorDateTime(array('required' => true)),
      'last_validated'  => new sfValidatorDateTime(array('required' => true)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'Subscription', 'column' => array('application_id', 'user_id')))
    );

    $this->widgetSchema->setNameFormat('subscription[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Subscription';
  }


}
