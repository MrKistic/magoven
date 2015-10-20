<?php

/**
 * receipts actions.
 *
 * @package    MagOven
 * @subpackage receipts
 * @author     MrKistic
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class receiptsActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
		$c = new Criteria();
		$pager = new sfPropelPager('Receipt', 10);
		$pager->setPage($request->getParameter('page', 1));
		$pager->setCriteria($c);
		$pager->init();
		$this->pager = $pager;
	}

  public function executeShow(sfWebRequest $request)
  {
    $this->Receipt = ReceiptPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($this->Receipt);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ReceiptForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new ReceiptForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($Receipt = ReceiptPeer::retrieveByPk($request->getParameter('id')), sprintf('Object Receipt does not exist (%s).', $request->getParameter('id')));
    $this->form = new ReceiptForm($Receipt);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($Receipt = ReceiptPeer::retrieveByPk($request->getParameter('id')), sprintf('Object Receipt does not exist (%s).', $request->getParameter('id')));
    $this->form = new ReceiptForm($Receipt);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

	public function executeDelete(sfWebRequest $request)
	{
		$request->checkCSRFProtection();

		$this->forward404Unless($Receipt = ReceiptPeer::retrieveByPk($request->getParameter('id')), sprintf('Object Receipt does not exist (%s).', $request->getParameter('id')));
		$Receipt->delete();

		$this->getUser()->setFlash('notice', 'Receipt deleted.');
		$this->redirect('receipts/index');
	}

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()) {

      $Receipt = $form->save();
  	  $this->getUser()->setFlash('notice', 'Receipt saved.');
      $this->redirect('receipts/index');

    } else {

      $this->getUser()->setFlash('error', 'Please correct the errors in the form below.', false);

    }

  }
}
