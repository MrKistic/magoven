<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormInput represents an HTML text input tag.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormInputText.class.php 30762 2010-08-25 12:33:33Z fabien $
 */
class sfWidgetFormInputBootstrapFileUpload extends sfWidgetFormInput
{
  /**
   * Configures the current widget.
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
  }

  /**
   * Renders the widget.
   *
   * @param  string $name        The element name
   * @param  string $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $attributes = $this->fixFormId($attributes);

    $class = is_null($value) ? 'fileupload-new' : 'fileupload-exists';
    $filename = basename($value);
    $title = isset($attributes['title']) ? $attributes['title'] : '';

    $s = sprintf('<div class="fileupload %s" data-provides="fileupload" title="%s">', $class, $title);

    if ($this->getOption('type') == 'image') {

      // Image Preview
      // @todo: What is the preview path here?!?
      $s .= '<div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;">';
      $s .= '<img src="http://'.$_SERVER['SERVER_NAME'].'/uploads/'.$value.'" />';
      $s .= '</div><div>';
      $s .= '<span class="btn btn-file">';
      $s .= '<span class="fileupload-new">Select image</span>';
      $s .= '<span class="fileupload-exists">Change</span>';
      $s .= sprintf('<input name="%s" type="file" value="%s" %s />', $name, $value, $this->attributesToHtml($attributes));
      $s .= '</span>';
      $s .= '<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>';
      $s .= '</div>';

    } else {

      $s .= '<div class="input-append file">';
      $s .= '<div class="uneditable-input span3">';
      $s .= '<i class="icon-file fileupload-exists"></i>';
      $s .= sprintf('<span class="fileupload-preview">%s</span>', $filename);
      $s .= '</div>';
      $s .= '<span class="btn btn-file">';
      $s .= '<span class="fileupload-new">Select file</span>';
      $s .= '<span class="fileupload-exists">Change</span>';
      $s .= sprintf('<input name="%s" type="file" value="%s" %s />', $name, $value, $this->attributesToHtml($attributes));
      $s .= '</span>';
      $s .= '<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>';
      // $s .= sprintf('<input name="%s" type="text" %s value="%s" />', $name, $this->attributesToHtml($attributes), $value);
      // $s .= '<span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>';
      $s .= '</div>';

    }

    $s .= '</div>';

    return $s;
  }

}
