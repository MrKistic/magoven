<?php

/**
 * publications actions.
 *
 * @package    MagOven
 * @subpackage publications
 * @author     MrKistic
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class publicationsActions extends sfActions
{
  public function preExecute()
  {
    $referer = $this->getRequest()->getReferer();
    if (!strpos($referer, 'publications/show') && !strpos($referer, 'publications/edit') && !strpos($referer, 'publications/update')) {
      $this->referer = $referer;
      $this->getUser()->setAttribute('referer', $referer);
    } else {
      $this->referer = $this->getUser()->getAttribute('referer');
    }
  }

  private function redirectReferer(sfWebRequest $request) {

    if (empty($this->referer) && $request->getParameter('id')) {
      $this->Publication = PublicationPeer::retrieveByPk($request->getParameter('id'));
      $this->referer = '/publications/show/id/' . $this->Publication->getId();
    }
    $this->redirect($this->referer);

  }

  public function executeIndex(sfWebRequest $request)
	{
    $c = new Criteria();
    $c->addDescendingOrderByColumn(PublicationPeer::CREATED_AT);
		$pager = new sfPropelPager('Publication', 10);
		$pager->setPage($request->getParameter('page', 1));
		$pager->setCriteria($c);
		$pager->init();
		$this->pager = $pager;
	}

  public function executeShow(sfWebRequest $request)
  {
    $this->Publication = PublicationPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($this->Publication);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PublicationForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new PublicationForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($Publication = PublicationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object Publication does not exist (%s).', $request->getParameter('id')));
    $this->form = new PublicationForm($Publication);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($Publication = PublicationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object Publication does not exist (%s).', $request->getParameter('id')));
    $this->form = new PublicationForm($Publication);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

	public function executeDelete(sfWebRequest $request)
	{
		$request->checkCSRFProtection();

		$this->forward404Unless($Publication = PublicationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object Publication does not exist (%s).', $request->getParameter('id')));
		$Publication->delete();

		$this->getUser()->setFlash('notice', 'Publication deleted.');
    $this->redirectReferer($request);
	}

  public function executeSendApns(sfWebRequest $request)
  {
    $this->forward404Unless($publication = PublicationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object Issue does not exist (%s).', $request->getParameter('id')));
    $count = $publication->createApnsNotifications();

    $this->getUser()->setFlash('notice', 'Notifications created for '.$count.' devices for '.$publication->getName().'.');
    $this->redirectReferer($request);
  }

  public function executeIgnoreApns(sfWebRequest $request)
  {
    $this->forward404Unless($publication = PublicationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object Issue does not exist (%s).', $request->getParameter('id')));
    $publication->ignoreApns();

    $this->getUser()->setFlash('notice', 'All issue notifications ignored for '.$publication->getName().'.');
    $this->redirectReferer($request);
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    // @todo: Need to ensure that the Application ID is unique

    $files = $request->getFiles($form->getName());
    $values = $request->getParameter($form->getName());
    $form->bind($values, $files);

    if ($form->isValid()) {

      // check here whether file fields are missing, if so we're actively deleting them
      if (!isset($files['sandbox_pem'])) {
        $form->getObject()->deleteExistingSandboxPem();
        $form->getObject()->setSandboxPem(null);
      }
      if (!isset($files['production_pem'])) {
        $form->getObject()->deleteExistingProductionPem();
        $form->getObject()->setProductionPem(null);
      }

      // save it
      $publication = $form->save();

      // update permissions on .pem files
      if (file_exists($form->getObject()->getSandboxPemPath())) {
        chmod($form->getObject()->getSandboxPemPath(), 0644);
      }
      if (file_exists($form->getObject()->getProductionPemPath())) {
        chmod($form->getObject()->getProductionPemPath(), 0644);
      }

      $this->getUser()->setFlash('notice', 'Publication saved.');
      $this->redirectReferer($request);

    } else {

      if ($files['sandbox_pem']['size'] == 0) {
        // no upload... fill the data
        $values['sandbox_pem'] = $form->getDefault('sandbox_pem');
        $form->bind($values, $files);
      }
      if ($files['production_pem']['size'] == 0) {
        // no upload... fill the data
        $values['production_pem'] = $form->getDefault('production_pem');
        $form->bind($values, $files);
      }

      $this->getUser()->setFlash('error', 'Please correct the errors in the form below.', false);

    }

  }

}
