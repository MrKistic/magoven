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
class sfWidgetFormBootstrapRadioOption extends sfWidgetFormSelectRadio
{
  public function formatter($widget, $inputs)
  {
    $rows = array();
    foreach ($inputs as $input)
    {
      $class = strpos($input['input'], 'checked') !== FALSE ? 'active' : '';
      $rows[] = '
<label class="radio inline btn '.$class.'">
  ' . $input['input'] . $input['label'] . '
</label>
';
    }

    return !$rows ? '' : $this->renderContentTag('div', implode($rows), array('class' => $this->getOption('class')));
  }

}
