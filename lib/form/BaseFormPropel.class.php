<?php

/**
 * Project form base class.
 *
 * @package    MagOven
 * @subpackage form
 * @author     MrKistic
 */
abstract class BaseFormPropel extends sfFormPropel
{
  public function setup()
  {
    unset(
      $this['created_at'],
      $this['updated_at']
    );
  }
}
