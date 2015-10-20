<?php

/**
 * Issue filter form base class.
 *
 * @package    MagOven
 * @subpackage filter
 * @author     Mark Simpson
 */
abstract class BaseIssueFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'publication_id'      => new sfWidgetFormPropelChoice(array('model' => 'Publication', 'add_empty' => true)),
      'name'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'free'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'product_id'          => new sfWidgetFormFilterInput(),
      'title'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'info'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'date'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'published'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'cover'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'url'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'uploaded_cover'      => new sfWidgetFormFilterInput(),
      'uploaded_hpub'       => new sfWidgetFormFilterInput(),
      'allow_notification'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'notified'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'itunes_summary'      => new sfWidgetFormFilterInput(),
      'itunes_coverart_url' => new sfWidgetFormFilterInput(),
      'itunes_published'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'itunes_updated'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'publication_id'      => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Publication', 'column' => 'id')),
      'name'                => new sfValidatorPass(array('required' => false)),
      'free'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'product_id'          => new sfValidatorPass(array('required' => false)),
      'title'               => new sfValidatorPass(array('required' => false)),
      'info'                => new sfValidatorPass(array('required' => false)),
      'date'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'published'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'cover'               => new sfValidatorPass(array('required' => false)),
      'url'                 => new sfValidatorPass(array('required' => false)),
      'uploaded_cover'      => new sfValidatorPass(array('required' => false)),
      'uploaded_hpub'       => new sfValidatorPass(array('required' => false)),
      'allow_notification'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'notified'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'itunes_summary'      => new sfValidatorPass(array('required' => false)),
      'itunes_coverart_url' => new sfValidatorPass(array('required' => false)),
      'itunes_published'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'itunes_updated'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('issue_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Issue';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
      'publication_id'      => 'ForeignKey',
      'name'                => 'Text',
      'free'                => 'Boolean',
      'product_id'          => 'Text',
      'title'               => 'Text',
      'info'                => 'Text',
      'date'                => 'Date',
      'published'           => 'Boolean',
      'cover'               => 'Text',
      'url'                 => 'Text',
      'uploaded_cover'      => 'Text',
      'uploaded_hpub'       => 'Text',
      'allow_notification'  => 'Boolean',
      'notified'            => 'Boolean',
      'itunes_summary'      => 'Text',
      'itunes_coverart_url' => 'Text',
      'itunes_published'    => 'Date',
      'itunes_updated'      => 'Date',
    );
  }
}
