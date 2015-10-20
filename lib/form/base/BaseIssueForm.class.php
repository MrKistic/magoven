<?php

/**
 * Issue form base class.
 *
 * @method Issue getObject() Returns the current form's model object
 *
 * @package    MagOven
 * @subpackage form
 * @author     Mark Simpson
 */
abstract class BaseIssueForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
      'publication_id'      => new sfWidgetFormPropelChoice(array('model' => 'Publication', 'add_empty' => false)),
      'name'                => new sfWidgetFormInputText(),
      'free'                => new sfWidgetFormInputCheckbox(),
      'product_id'          => new sfWidgetFormInputText(),
      'title'               => new sfWidgetFormInputText(),
      'info'                => new sfWidgetFormInputText(),
      'date'                => new sfWidgetFormDate(),
      'published'           => new sfWidgetFormInputCheckbox(),
      'cover'               => new sfWidgetFormInputText(),
      'url'                 => new sfWidgetFormInputText(),
      'upload_type'         => new sfWidgetFormInputText(),
      'uploaded_cover'      => new sfWidgetFormInputText(),
      'uploaded_hpub'       => new sfWidgetFormInputText(),
      'uploaded_zip'        => new sfWidgetFormInputText(),
      'allow_notification'  => new sfWidgetFormInputCheckbox(),
      'notified'            => new sfWidgetFormInputCheckbox(),
      'itunes_include'      => new sfWidgetFormInputCheckbox(),
      'itunes_summary'      => new sfWidgetFormInputText(),
      'itunes_coverart_url' => new sfWidgetFormInputText(),
      'itunes_published'    => new sfWidgetFormDateTime(),
      'itunes_updated'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'created_at'          => new sfValidatorDateTime(array('required' => false)),
      'updated_at'          => new sfValidatorDateTime(array('required' => false)),
      'publication_id'      => new sfValidatorPropelChoice(array('model' => 'Publication', 'column' => 'id', 'required' => true)),
      'name'                => new sfValidatorString(array('max_length' => 100, 'required' => true)),
      'free'                => new sfValidatorBoolean(array('required' => false)),
      'product_id'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'title'               => new sfValidatorString(array('max_length' => 100, 'required' => true)),
      'info'                => new sfValidatorString(array('max_length' => 500, 'required' => true)),
      'date'                => new sfValidatorDate(array('required' => true)),
      'published'           => new sfValidatorBoolean(array('required' => false)),
      'cover'               => new sfValidatorString(array('max_length' => 1024, 'required' => false)),
      'url'                 => new sfValidatorString(array('max_length' => 1024, 'required' => false)),
      'upload_type'         => new sfValidatorString(array('max_length' => 1, 'required' => true)),
      'uploaded_cover'      => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'uploaded_hpub'       => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'uploaded_zip'        => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'allow_notification'  => new sfValidatorBoolean(array('required' => false)),
      'notified'            => new sfValidatorBoolean(array('required' => false)),
      'itunes_include'      => new sfValidatorBoolean(array('required' => false)),
      'itunes_summary'      => new sfValidatorString(array('max_length' => 1024, 'required' => false)),
      'itunes_coverart_url' => new sfValidatorString(array('max_length' => 1024, 'required' => false)),
      'itunes_published'    => new sfValidatorDateTime(array('required' => false)),
      'itunes_updated'      => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('issue[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Issue';
  }


}
