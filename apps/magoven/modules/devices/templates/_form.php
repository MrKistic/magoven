<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<div class="container">

<?php include_partial('flashes') ?>


  <form class="form-horizontal" action="<?php echo url_for('devices/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?pid='.$form->getObject()->getPid() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
	  <fieldset class="well">

<?php if (!$form->getObject()->isNew()): ?>
		  <input type="hidden" name="sf_method" value="put" />
<?php endif; ?>

		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>
		<?php echo $form['clientid']->renderRow() ?>
		<?php echo $form['appname']->renderRow() ?>
		<?php echo $form['appversion']->renderRow() ?>
		<?php echo $form['deviceuid']->renderRow() ?>
		<?php echo $form['devicetoken']->renderRow() ?>
		<?php echo $form['devicename']->renderRow() ?>
		<?php echo $form['devicemodel']->renderRow() ?>
		<?php echo $form['deviceversion']->renderRow() ?>
		<?php echo $form['pushbadge']->renderRow() ?>
		<?php echo $form['pushalert']->renderRow() ?>
		<?php echo $form['pushsound']->renderRow() ?>
		<?php echo $form['development']->renderRow() ?>
		<?php echo $form['status']->renderRow() ?>
		<?php echo $form['created']->renderRow() ?>
		<?php echo $form['modified']->renderRow() ?>

      <div class="control-group">
        <div class="controls">

			    <a class="btn" href="<?php echo url_for('devices/index') ?>">Cancel</a>

	  		  <button class="btn btn-info" type="submit">Save</button>

        </div>
      </div>

	  </fieldset>
  </form>

</div>
