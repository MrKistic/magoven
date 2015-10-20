[?php include_stylesheets_for_form($form) ?]
[?php include_javascripts_for_form($form) ?]

<div class="container">

[?php include_partial('flashes') ?]

<?php $form = $this->getFormObject() ?>
<?php if (isset($this->params['route_prefix']) && $this->params['route_prefix']): ?>
[?php echo form_tag_for($form, '@<?php echo $this->params['route_prefix'] ?>') ?]
<?php else: ?>

  <form class="form-horizontal" action="[?php echo url_for('<?php echo $this->getModuleName() ?>/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?<?php echo $this->getPrimaryKeyUrlParams('$form->getObject()', true) ?> : '')) ?]" method="post" [?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?]>
	  <fieldset class="well">

[?php if (!$form->getObject()->isNew()): ?]
		  <input type="hidden" name="sf_method" value="put" />
[?php endif; ?]
<?php endif;?>

<?php if (!isset($this->params['non_verbose_templates']) || !$this->params['non_verbose_templates']): ?>
		[?php echo $form->renderHiddenFields() ?]
<?php endif; ?>
<?php if (isset($this->params['non_verbose_templates']) && $this->params['non_verbose_templates']): ?>
		[?php echo $form ?]
<?php else: ?>
		[?php echo $form->renderGlobalErrors() ?]
<?php  foreach ($form as $name => $field): if ($field->isHidden()) continue ?>
		[?php echo $form['<?php echo $name ?>']->renderRow() ?]
<?php endforeach; ?>

      <div class="control-group">
        <div class="controls">

<?php if (isset($this->params['route_prefix']) && $this->params['route_prefix']): ?>
			    <a class="btn" href="[?php echo url_for('<?php echo $this->getUrlForAction('index') ?>') ?]">Cancel</a>
<?php else: ?>
			    <a class="btn" href="[?php echo url_for('<?php echo $this->getModuleName() ?>/index') ?]">Cancel</a>
<?php endif; ?>

	  		  <button class="btn btn-info" type="submit">Save</button>
<?php endif; ?>

        </div>
      </div>

	  </fieldset>
  </form>

</div>
