<?php

/**
 * Issue form.
 *
 * @package    MagOven
 * @subpackage form
 * @author     MrKistic
 */
class IssueForm extends BaseIssueForm
{
  public function configure()
  {
    // use our custom Bootstrap Date/Time widget
    $this->setWidget('date', new sfWidgetFormInputBootstrapDate(array('type' => 'datetime'), array('data-format' => "yyyy-MM-dd hh:mm:ss")));

    $this->setWidget('itunes_published', new sfWidgetFormInputBootstrapDateTime(array('type' => 'datetime'), array('data-format' => "yyyy-MM-dd hh:mm:ss")));
    // $this->setWidget('itunes_updated', new sfWidgetFormInputBootstrapDateTime(array('type' => 'datetime'), array('data-format' => "yyyy-MM-dd hh:mm:ss")));
    $this->setWidget('itunes_updated', new sfWidgetFormInputHidden());
    $this->setWidget('notified', new sfWidgetFormInputHidden());

    $this->setWidget('itunes_summary', new sfWidgetFormTextarea());

    // set file upload widgets
    $this->setWidget('uploaded_cover', new sfWidgetFormInputBootstrapFileUpload(array('type' => 'image')));
    $this->setWidget('uploaded_hpub', new sfWidgetFormInputBootstrapFileUpload());
    $this->setWidget('uploaded_zip', new sfWidgetFormInputBootstrapFileUpload());

    // @todo: validate these as images only and .hpub only
    $this->validatorSchema['uploaded_cover'] = new sfValidatorFile(array(
      'required' => false,
      'path' => sfConfig::get('sf_upload_dir')
    ));
    $this->validatorSchema['uploaded_hpub'] = new sfValidatorFile(array(
      'required' => false,
      'path' => sfConfig::get('sf_upload_dir')
    ));
    $this->validatorSchema['uploaded_zip'] = new sfValidatorFile(array(
      'required' => false,
      'path' => sfConfig::get('sf_upload_dir')
    ));

    // create selectors for booleans - note: leave blank for 0 value or it won't work - yes, that is annoying.
    $this->setWidget('free', new sfWidgetFormSelect(array('choices' => array(
      '1' => 'Free',
      '' => 'Paid'
    ))));
    $this->setWidget(
      'published',
      new sfWidgetFormSelect(
        array('choices' => array(
          '1' => 'Published',
          '' => 'Editing'
        ))
      )
    );

    // radio options for upload type
    $this->widgetSchema['upload_type'] = new sfWidgetFormChoice(
      array(
        'choices' => IssuePeer::getUploadTypes(),
        'expanded' => TRUE,
        'renderer_class' => 'sfWidgetFormBootstrapRadioOption'
      )
    );
    $this->validatorSchema['upload_type'] = new sfValidatorChoice(
      array(
        'choices' => array_keys(IssuePeer::getUploadTypes())
      )
    );
    // set radio choice widget
    // $this->setWidget('upload_type', new sfWidgetFormBootstrapRadioOption());

    // change labels
    $this->widgetSchema->setLabels(array(
      'application_id' => 'Application ID',
      'product_id' => 'Product ID',
      'free' => 'Pricing',
      'published' => 'Availability',
      'url' => '.hpub URL',
      'cover' => 'Cover Image URL',
      'uploaded_cover' => 'Cover Image',
      'uploaded_hpub' => 'Compiled .hpub File',
      'itunes_include' => 'Include in iTunes feed',
      'itunes_published' => 'iTunes Publish Date',
      'itunes_coverart_url' => 'iTunes Cover Art URL',
      'itunes_summary' => 'iTunes Summary',
    ));

    $this->widgetSchema->setAttributes(array(
      'free' => 'Select issue pricing: free or paid',
      'published' => 'Published issues will be listed in the shelf',
      'cover' => 'Public URL for your cover image',
      'url' => 'Public URL for your .hpub file',
      'uploaded_cover' => 'Select a cover image to upload',
      'uploaded_hpub' => 'Select a .hpub file to upload',
      'uploaded_zip' => 'Select a .zip file to upload',
      'allow_notification' => 'If selected you can send Push Notifictions for this issue',

      'product_id' => 'Your iTunes connect Product ID for this issue',
      'name' => 'Unique internal name for this Issue',
      'title' => 'Issue title displayed in app issue list',
      'info' => 'Issue info displayed in app issue list',
      'date' => 'The date this issue was published',

      'itunes_published' => 'Optional: set the date iTunes will display this issue (defaults to Date above)'
    ));

    // set help fields
    $this->widgetSchema->setHelps(array(
      'free' => 'Select issue pricing: free or paid',
      'published' => 'Published issues will be listed in your application',
      'cover' => 'Public URL for your cover image',
      'url' => 'Public URL for your .hpub file',
      'uploaded_cover' => 'Select a cover image to upload',
      'uploaded_hpub' => 'Select a .hpub file to upload',
      'uploaded_zip' => 'Select a .zip file to upload',
      'allow_notification' => 'If selected you can send Push Notifications for this issue',

      'product_id' => 'Your iTunes connect Product ID for this issue',
      'name' => 'Internal name for this issue',
      'title' => 'Title to display in your Newsstand app list',
      'info' => 'Info to display in your Newsstand app list',
      'date' => 'The date this issue was published',

      'itunes_summary' => 'Summary for display in iTunes store',
      'itunes_published' => 'Optional: set the date iTunes will publish this issue (defaults to Date above)',
      'itunes_include' => 'Include this issue in your iTunes store'
    ));

    // bung the help fields into the title for the tooltips and the placeholder
    foreach ($this->widgetSchema->getFields() as $name => $form) {
      if ($this->widgetSchema->getHelp($name)) {
        $form->setAttribute('title', $this->widgetSchema->getHelp($name));
      }
    }

    // add placeholders where appropriate
    $this->widgetSchema['product_id']->setAttribute('placeholder', 'com.company.magazine.issues.name');

    // extend width on longer fields
    $this->widgetSchema['name']->setAttribute('class', 'span6');
    $this->widgetSchema['product_id']->setAttribute('class', 'span6');
    $this->widgetSchema['title']->setAttribute('class', 'span6');
    $this->widgetSchema['info']->setAttribute('class', 'span6');
    $this->widgetSchema['cover']->setAttribute('class', 'span6');
    $this->widgetSchema['url']->setAttribute('class', 'span6');
    $this->widgetSchema['itunes_coverart_url']->setAttribute('class', 'span6');
    $this->widgetSchema['itunes_summary']->setAttribute('class', 'span6');

    // set conditional validation
    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this->getObject(), 'validateUploadType')))
    );

  }

}
