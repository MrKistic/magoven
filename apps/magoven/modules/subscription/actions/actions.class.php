<?php

/**
 * subscription actions.
 *
 * @package    MagOven
 * @subpackage subscription
 * @author     MrKistic
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class subscriptionActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
		$c = new Criteria();
		$pager = new sfPropelPager('Subscription', 10);
		$pager->setPage($request->getParameter('page', 1));
		$pager->setCriteria($c);
		$pager->init();
		$this->pager = $pager;
	}

  public function executeShow(sfWebRequest $request)
  {
    $this->Subscription = SubscriptionPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($this->Subscription);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new SubscriptionForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new SubscriptionForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($Subscription = SubscriptionPeer::retrieveByPk($request->getParameter('id')), sprintf('Object Subscription does not exist (%s).', $request->getParameter('id')));
    $this->form = new SubscriptionForm($Subscription);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($Subscription = SubscriptionPeer::retrieveByPk($request->getParameter('id')), sprintf('Object Subscription does not exist (%s).', $request->getParameter('id')));
    $this->form = new SubscriptionForm($Subscription);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

	public function executeDelete(sfWebRequest $request)
	{
		$request->checkCSRFProtection();

		$this->forward404Unless($Subscription = SubscriptionPeer::retrieveByPk($request->getParameter('id')), sprintf('Object Subscription does not exist (%s).', $request->getParameter('id')));
		$Subscription->delete();

		$this->getUser()->setFlash('notice', 'Subscription deleted.');
		$this->redirect('subscription/index');
	}

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()) {

      $Subscription = $form->save();
  	  $this->getUser()->setFlash('notice', 'Subscription saved.');
      $this->redirect('subscription/index');

    } else {

      $this->getUser()->setFlash('error', 'Please correct the errors in the form below.', false);

    }

  }
}
