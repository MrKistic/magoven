<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<div class="container">

<?php include_partial('flashes') ?>


  <form class="form-horizontal" action="<?php echo url_for('publications/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" enctype="multipart/form-data">
	  <fieldset class="well">

<?php if (!$form->getObject()->isNew()): ?>
		  <input type="hidden" name="sf_method" value="put" />
<?php endif; ?>

		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>
		<?php // echo $form['created_at']->renderRow() ?>
		<?php // echo $form['updated_at']->renderRow() ?>
    <?php echo $form['name']->renderRow() ?>
		<?php echo $form['application_id']->renderRow() ?>
		<?php echo $form['development_mode']->renderRow() ?>
		<?php echo $form['subscription_behavior']->renderRow() ?>
    <?php echo $form['issue_download_security']->renderRow() ?>

    <div class="pem-help controls">
      Need certificate help? <a href="http://www.apptuitions.com/generate-pem-file-for-push-notification/" target="_blank">APNS Certificate Tutorial</a>
    </div>

    <?php echo $form['sandbox_pem']->renderRow() ?>
    <?php if ($form->getObject()->getSandboxPemExpiryDate()) { ?>
      <div class="pem-expires controls <?php echo $form->getObject()->getSandboxPemExpiryWarning() ? 'warning' : ''; ?>">
        Expires: <?php print $form->getObject()->getSandboxPemExpiryDate(); ?>
      </div>
    <?php } ?>
    <?php echo $form['sandbox_password']->renderRow() ?>
    <?php echo $form['production_pem']->renderRow() ?>
    <?php if ($form->getObject()->getProductionPemExpiryDate()) { ?>
      <div class="pem-expires controls <?php echo $form->getObject()->getProductionPemExpiryWarning() ? 'warning' : ''; ?>">
        Expires: <?php print $form->getObject()->getProductionPemExpiryDate(); ?>
      </div>
    <?php } ?>
    <?php echo $form['production_password']->renderRow() ?>

		<?php echo $form['itunes_revalidation_duration']->renderRow() ?>
		<?php echo $form['itunes_production_level']->renderRow() ?>
		<?php echo $form['itunes_shared_secret']->renderRow() ?>
		<?php echo $form['itunes_updated']->renderRow() ?>

      <div class="control-group">
        <div class="controls">

          <a class="btn"href="<?php echo $referer; ?>">Cancel</a>
	  		  <button class="btn btn-info" type="submit">Save</button>

        </div>
      </div>

	  </fieldset>
  </form>

</div>
