  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()) {

      $<?php echo $this->getSingularName() ?> = $form->save();
  	  $this->getUser()->setFlash('notice', '<?php echo $this->getModelClass() ?> saved.');
<?php if (isset($this->params['route_prefix']) && $this->params['route_prefix']): ?>
      $this->redirect('@<?php echo $this->getUrlForAction('index') ?>);
<?php else: ?>
      $this->redirect('<?php echo $this->getModuleName() ?>/index');
<?php endif; ?>

    } else {

      $this->getUser()->setFlash('error', 'Please correct the errors in the form below.', false);

    }

  }
