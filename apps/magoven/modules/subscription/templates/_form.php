<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<div class="container">

<?php include_partial('flashes') ?>


  <form class="form-horizontal" action="<?php echo url_for('subscription/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
	  <fieldset class="well">

<?php if (!$form->getObject()->isNew()): ?>
		  <input type="hidden" name="sf_method" value="put" />
<?php endif; ?>

		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>
		<?php echo $form['created_at']->renderRow() ?>
		<?php echo $form['updated_at']->renderRow() ?>
		<?php echo $form['application_id']->renderRow() ?>
		<?php echo $form['user_id']->renderRow() ?>
		<?php echo $form['effective_date']->renderRow() ?>
		<?php echo $form['expiration_date']->renderRow() ?>
		<?php echo $form['last_validated']->renderRow() ?>

      <div class="control-group">
        <div class="controls">

			    <a class="btn" href="<?php echo url_for('subscription/index') ?>">Cancel</a>

	  		  <button class="btn btn-info" type="submit">Save</button>

        </div>
      </div>

	  </fieldset>
  </form>

</div>
