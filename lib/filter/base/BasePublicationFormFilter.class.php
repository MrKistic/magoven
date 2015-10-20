<?php

/**
 * Publication filter form base class.
 *
 * @package    MagOven
 * @subpackage filter
 * @author     Mark Simpson
 */
abstract class BasePublicationFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'created_at'                   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at'                   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'application_id'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'                         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'development_mode'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'issue_download_security'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'itunes_revalidation_duration' => new sfWidgetFormFilterInput(),
      'itunes_production_level'      => new sfWidgetFormFilterInput(),
      'itunes_shared_secret'         => new sfWidgetFormFilterInput(),
      'itunes_updated'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'created_at'                   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'                   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'application_id'               => new sfValidatorPass(array('required' => false)),
      'name'                         => new sfValidatorPass(array('required' => false)),
      'development_mode'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'issue_download_security'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'itunes_revalidation_duration' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'itunes_production_level'      => new sfValidatorPass(array('required' => false)),
      'itunes_shared_secret'         => new sfValidatorPass(array('required' => false)),
      'itunes_updated'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('publication_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Publication';
  }

  public function getFields()
  {
    return array(
      'id'                           => 'Number',
      'created_at'                   => 'Date',
      'updated_at'                   => 'Date',
      'application_id'               => 'Text',
      'name'                         => 'Text',
      'development_mode'             => 'Boolean',
      'issue_download_security'      => 'Boolean',
      'itunes_revalidation_duration' => 'Number',
      'itunes_production_level'      => 'Text',
      'itunes_shared_secret'         => 'Text',
      'itunes_updated'               => 'Date',
    );
  }
}
