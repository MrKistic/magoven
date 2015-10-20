<?php

/**
 * purchases actions.
 *
 * @package    MagOven
 * @subpackage purchases
 * @author     MrKistic
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class purchasesActions extends sfActions
{

	public function executeIndex(sfWebRequest $request)
	{
		$c = new Criteria();
		$pager = new sfPropelPager('Purchase', 10);
		$pager->setPage($request->getParameter('page', 1));
		$pager->setCriteria($c);
		$pager->init();
		$this->pager = $pager;
	}

  public function executeShow(sfWebRequest $request)
  {
    $this->Purchase = PurchasePeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($this->Purchase);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PurchaseForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new PurchaseForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($Purchase = PurchasePeer::retrieveByPk($request->getParameter('id')), sprintf('Object Purchase does not exist (%s).', $request->getParameter('id')));
    $this->form = new PurchaseForm($Purchase);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($Purchase = PurchasePeer::retrieveByPk($request->getParameter('id')), sprintf('Object Purchase does not exist (%s).', $request->getParameter('id')));
    $this->form = new PurchaseForm($Purchase);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

	public function executeDelete(sfWebRequest $request)
	{
		$request->checkCSRFProtection();

		$this->forward404Unless($Purchase = PurchasePeer::retrieveByPk($request->getParameter('id')), sprintf('Object Purchase does not exist (%s).', $request->getParameter('id')));
		$Purchase->delete();

		$this->getUser()->setFlash('notice', 'Purchase deleted.');
		$this->redirect('purchases/index');
	}

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()) {

      $Purchase = $form->save();
  	  $this->getUser()->setFlash('notice', 'Purchase saved.');
      $this->redirect('purchases/index');

    } else {

      $this->getUser()->setFlash('error', 'Please correct the errors in the form below.', false);

    }

  }
}
