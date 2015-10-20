<?php

/**
 * Publication form.
 *
 * @package    MagOven
 * @subpackage form
 * @author     MrKistic
 */
class PublicationForm extends BasePublicationForm
{
  public function configure()
  {
    // use our custom Bootstrap Date/Time widget
    // $this->setWidget('itunes_updated', new sfWidgetFormInputBootstrapDateTime(array('type' => 'datetime'), array('data-format' => "yyyy-MM-dd hh:mm:ss")));
    // actually, this should be hidden, it's automated
    $this->setWidget('itunes_updated', new sfWidgetFormInputHidden());

    // set file upload widgets
    $this->setWidget('sandbox_pem', new sfWidgetFormInputBootstrapFileUpload(array(), array('accept' => '.pem')));
    $this->setWidget('production_pem', new sfWidgetFormInputBootstrapFileUpload(array(), array('accept' => '.pem')));

    // @todo: validate these as .pem only
    $this->validatorSchema['sandbox_pem'] = new sfValidatorFile(array(
      'required' => false,
      'path' => sfConfig::get('sf_upload_dir')
    ));
    $this->validatorSchema['production_pem'] = new sfValidatorFile(array(
      'required' => false,
      'path' => sfConfig::get('sf_upload_dir')
    ));

    // create selectors for booleans - note: leave blank for 0 value or it won't work - yes, that is annoying.
    $this->setWidget('development_mode', new sfWidgetFormSelect(array('choices' => array(
      '1' => 'Development',
      '' => 'Production',
    ))));
    $this->setWidget('issue_download_security', new sfWidgetFormSelect(array('choices' => array(
      '' => 'None',
      '1' => 'Use Security',
    ))));
    $this->setWidget('itunes_production_level', new sfWidgetFormSelect(array('choices' => array(
      'sandbox' => 'Sandbox',
      'production' => 'Production',
    ))));
    $this->setWidget('subscription_behavior', new sfWidgetFormSelect(array('choices' => array(
      'all' => 'Unlock all Issues',
      'term' => 'Unlock Issues only within subscription term',
    ))));
    $this->setWidget('itunes_revalidation_duration', new sfWidgetFormSelect(array('choices' => array(
      '0' => 'No Caching',
      '1' => '1 Hour',
      '6' => '6 Hours',
      '12' => '12 Hours',
    ))));

    // add some styling
    $this->widgetSchema['application_id']->setAttribute('class', 'span6');
    $this->widgetSchema['name']->setAttribute('class', 'span6');
    $this->widgetSchema['itunes_shared_secret']->setAttribute('class', 'span6');

    // update labels last
    $this->widgetSchema->setLabels(array(
      'application_id' => 'Application ID',
      'development_mode' => 'Development Mode',
      'issue_download_security' => 'Issue Security',
      'itunes_revalidation_duration' => 'iTunes Cache Duration',
      'itunes_production_level' => 'iTunes Production Level',
      'itunes_shared_secret' => 'iTunes Shared Secret'
    ));

  }
}
