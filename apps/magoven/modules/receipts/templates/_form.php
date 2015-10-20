<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<div class="container">

<?php include_partial('flashes') ?>


  <form class="form-horizontal" action="<?php echo url_for('receipts/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
	  <fieldset class="well">

<?php if (!$form->getObject()->isNew()): ?>
		  <input type="hidden" name="sf_method" value="put" />
<?php endif; ?>

		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>
		<?php echo $form['created_at']->renderRow() ?>
		<?php echo $form['updated_at']->renderRow() ?>
		<?php echo $form['application_id']->renderRow() ?>
		<?php echo $form['quantity']->renderRow() ?>
		<?php echo $form['product_id']->renderRow() ?>
		<?php echo $form['receipt_type']->renderRow() ?>
		<?php echo $form['transaction_id']->renderRow() ?>
		<?php echo $form['user_id']->renderRow() ?>
		<?php echo $form['purchase_date']->renderRow() ?>
		<?php echo $form['original_transaction_id']->renderRow() ?>
		<?php echo $form['original_purchase_date']->renderRow() ?>
		<?php echo $form['app_item_id']->renderRow() ?>
		<?php echo $form['version_external_identifier']->renderRow() ?>
		<?php echo $form['bid']->renderRow() ?>
		<?php echo $form['bvrs']->renderRow() ?>
		<?php echo $form['base64_receipt']->renderRow() ?>

      <div class="control-group">
        <div class="controls">

			    <a class="btn" href="<?php echo url_for('receipts/index') ?>">Cancel</a>

	  		  <button class="btn btn-info" type="submit">Save</button>

        </div>
      </div>

	  </fieldset>
  </form>

</div>
