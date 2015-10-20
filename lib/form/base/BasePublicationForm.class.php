<?php

/**
 * Publication form base class.
 *
 * @method Publication getObject() Returns the current form's model object
 *
 * @package    MagOven
 * @subpackage form
 * @author     Mark Simpson
 */
abstract class BasePublicationForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                           => new sfWidgetFormInputHidden(),
      'created_at'                   => new sfWidgetFormDateTime(),
      'updated_at'                   => new sfWidgetFormDateTime(),
      'application_id'               => new sfWidgetFormInputText(),
      'name'                         => new sfWidgetFormInputText(),
      'development_mode'             => new sfWidgetFormInputCheckbox(),
      'subscription_behavior'        => new sfWidgetFormInputText(),
      'issue_download_security'      => new sfWidgetFormInputCheckbox(),
      'itunes_revalidation_duration' => new sfWidgetFormInputText(),
      'itunes_production_level'      => new sfWidgetFormInputText(),
      'itunes_shared_secret'         => new sfWidgetFormInputText(),
      'itunes_updated'               => new sfWidgetFormDateTime(),
      'sandbox_pem'                  => new sfWidgetFormInputText(),
      'sandbox_password'             => new sfWidgetFormInputText(),
      'production_pem'               => new sfWidgetFormInputText(),
      'production_password'          => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                           => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'created_at'                   => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                   => new sfValidatorDateTime(array('required' => false)),
      'application_id'               => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'name'                         => new sfValidatorString(array('max_length' => 100, 'required' => true)),
      'development_mode'             => new sfValidatorBoolean(array('required' => false)),
      'subscription_behavior'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'issue_download_security'      => new sfValidatorBoolean(array('required' => false)),
      'itunes_revalidation_duration' => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'itunes_production_level'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'itunes_shared_secret'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'itunes_updated'               => new sfValidatorDateTime(array('required' => false)),
      'sandbox_pem'                  => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'sandbox_password'             => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'production_pem'               => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'production_password'          => new sfValidatorString(array('max_length' => 32, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('publication[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Publication';
  }


}
