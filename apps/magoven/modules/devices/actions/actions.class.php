<?php

/**
 * devices actions.
 *
 * @package    MagOven
 * @subpackage devices
 * @author     Mark Simpson
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class devicesActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
		$c = new Criteria();
		$pager = new sfPropelPager('ApnsDevice', 10);
		$pager->setPage($request->getParameter('page', 1));
		$pager->setCriteria($c);
		$pager->init();
		$this->pager = $pager;
	}

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ApnsDeviceForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new ApnsDeviceForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ApnsDevice = ApnsDevicePeer::retrieveByPk($request->getParameter('pid')), sprintf('Object ApnsDevice does not exist (%s).', $request->getParameter('pid')));
    $this->form = new ApnsDeviceForm($ApnsDevice);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($ApnsDevice = ApnsDevicePeer::retrieveByPk($request->getParameter('pid')), sprintf('Object ApnsDevice does not exist (%s).', $request->getParameter('pid')));
    $this->form = new ApnsDeviceForm($ApnsDevice);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

	public function executeDelete(sfWebRequest $request)
	{
		$request->checkCSRFProtection();

		$this->forward404Unless($ApnsDevice = ApnsDevicePeer::retrieveByPk($request->getParameter('pid')), sprintf('Object ApnsDevice does not exist (%s).', $request->getParameter('pid')));
		$ApnsDevice->delete();

		$this->getUser()->setFlash('notice', 'ApnsDevice deleted.');
		$this->redirect('devices/index');
	}

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()) {

      $ApnsDevice = $form->save();
  	  $this->getUser()->setFlash('notice', 'ApnsDevice saved.');
      $this->redirect('devices/index');

    } else {

      $this->getUser()->setFlash('error', 'Please correct the errors in the form below.', false);

    }

  }
}
