<?php

/**
 * issues actions.
 *
 * @package    MagOven
 * @subpackage issues
 * @author     MrKistic
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class issuesActions extends sfActions
{
  public function preExecute()
  {
    $referer = $this->getRequest()->getReferer();
    if (!strpos($referer, 'issues/new') && !strpos($referer, 'issues/show') && !strpos($referer, 'issues/edit') && !strpos($referer, 'issues/update')) {
      $this->referer = $referer;
      $this->getUser()->setAttribute('referer', $referer);
    } else {
      $this->referer = $this->getUser()->getAttribute('referer');
    }
    $this->pubId = isset($_GET['pub']) ? $_GET['pub'] : '';
  }

  private function redirectReferer(sfWebRequest $request)
  {
    if (empty($this->referer) && $request->getParameter('id')) {
      $this->Issue = IssuePeer::retrieveByPk($request->getParameter('id'));
      $this->referer = '/publications/show/id/' . $this->Issue->getPublication()->getId();
    }
    $this->redirect($this->referer);
  }

	public function executeIndex(sfWebRequest $request)
	{
		$c = new Criteria();
    $c->addDescendingOrderByColumn(IssuePeer::ID);
		$pager = new sfPropelPager('Issue', 10);
		$pager->setPage($request->getParameter('page', 1));
		$pager->setCriteria($c);
		$pager->init();
		$this->pager = $pager;
	}

  public function executeShow(sfWebRequest $request)
  {
    $this->Issue = IssuePeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($this->Issue);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new IssueForm();
    $url = 'issues/create';
    if ($this->pubId) {
      $url .= '?pub=' . $this->pubId;
    }
    $this->form_action = $url;
  }

  public function executeCreate(sfWebRequest $request)
  {
    $url = 'issues/create';
    if ($this->pubId) {
      $url .= '?pub=' . $this->pubId;
    }
    $this->form_action = $url;
    $this->forward404Unless($request->isMethod('post'));
    $this->form = new IssueForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->pubId = isset($_GET['pub']) ? $_GET['pub'] : '';
    $this->forward404Unless($Issue = IssuePeer::retrieveByPk($request->getParameter('id')), sprintf('Object Issue does not exist (%s).', $request->getParameter('id')));
    $this->form = new IssueForm($Issue);
    $url = 'issues/update?id=' . $request->getParameter('id');
    if ($this->pubId) {
      $url .= '&pub=' . $this->pubId;
    }
    $this->form_action = $url;
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $url = 'issues/update?id=' . $request->getParameter('id');
    if ($this->pubId) {
      $url .= '&pub=' . $this->pubId;
    }
    $this->form_action = $url;
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($Issue = IssuePeer::retrieveByPk($request->getParameter('id')), sprintf('Object Issue does not exist (%s).', $request->getParameter('id')));
    $this->form = new IssueForm($Issue);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

	public function executeDelete(sfWebRequest $request)
	{
		$request->checkCSRFProtection();

		$this->forward404Unless($Issue = IssuePeer::retrieveByPk($request->getParameter('id')), sprintf('Object Issue does not exist (%s).', $request->getParameter('id')));
		$Issue->delete();

		$this->getUser()->setFlash('notice', 'Issue deleted.');
    $this->redirectReferer($request);
	}

  public function executeSendApns(sfWebRequest $request)
  {
    $this->forward404Unless($issue = IssuePeer::retrieveByPk($request->getParameter('id')), sprintf('Object Issue does not exist (%s).', $request->getParameter('id')));
    $count = $issue->createApnsNotifications();

    $this->getUser()->setFlash('notice', 'Notifications created for '.$count.' devices for '.$issue->getTitle().'.');
    $this->redirectReferer($request);
  }

  public function executeIgnoreApns(sfWebRequest $request)
  {
    $this->forward404Unless($issue = IssuePeer::retrieveByPk($request->getParameter('id')), sprintf('Object Issue does not exist (%s).', $request->getParameter('id')));
    $issue->ignoreApns();

    $this->getUser()->setFlash('notice', 'Notifications ignored for '.$issue->getTitle().'.');
    $this->redirectReferer($request);
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $files = $request->getFiles($form->getName());
    $values = $request->getParameter($form->getName());
    $form->bind($values, $files);

    if ($form->isValid()) {

      // check here whether file fields are missing, if so we're actively deleting them
      if ($values['upload_type'] != IssuePeer::UPLOAD_TYPE_HPUB || !isset($files['uploaded_hpub'])) {
        $form->getObject()->deleteExistingUploadedHpub();
        $form->getObject()->setUploadedHpub(null);
      }
      if ($values['upload_type'] != IssuePeer::UPLOAD_TYPE_HPUB || !isset($files['uploaded_cover'])) {
        $form->getObject()->deleteExistingUploadedCover();
        $form->getObject()->setUploadedCover(null);
      }

      // save it
      $issue = $form->save();

  	  $this->getUser()->setFlash('notice', 'Issue saved.');
      $this->redirectReferer($request);

    } else {

      if ($files['uploaded_cover']['size'] == 0) {
        // no upload... fill the data
        $values['uploaded_cover'] = $form->getDefault('uploaded_cover');
        $form->bind($values, $files);
      }
      if ($files['uploaded_hpub']['size'] == 0) {
        // no upload... fill the data
        $values['uploaded_hpub'] = $form->getDefault('uploaded_hpub');
        $form->bind($values, $files);
      }

      $this->getUser()->setFlash('error', 'Please correct the errors in the form below.', false);

    }

  }

}
