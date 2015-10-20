<?php

/**
 * Skeleton subclass for representing a row from the  'publication' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * Tue May 14 18:14:52 2013
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class Publication extends BasePublication {

  /**
   * Initializes internal state of Issue object.
   * @see        parent::__construct()
   */
  public function __construct()
  {
    // Make sure that parent constructor is always invoked, since that
    // is where any default values for this object are set.
    parent::__construct();
  }

  public function __toString()
  {
    return $this->getName();
  }

  public function preSave(PropelPDO $con = null)
  {
    $this->setItunesUpdated(time());

    return true;
  }

  public function isSandbox()
  {
    return $this->getItunesProductionLevel() == PublicationPeer::ITUNES_SANDBOX;
  }

  /**
   * Returns true if the publication has any published issues which haven't had an APNS notification created
   *
   * @return bool
   */
  public function canNotify()
  {
    /** @var Issue $i */
    foreach ($this->getIssues() as $i) {
      if ($i->getPublished() && $i->getAllowNotification() && !$i->getNotified()) {
        return true;
      }
    }
  }

  public function getApnsMessageCount()
  {
    $c = new Criteria();
    $c->addJoin(ApnsMessagePeer::FK_DEVICE, ApnsDevicePeer::PID, Criteria::LEFT_JOIN);
    $c->add(ApnsDevicePeer::APPNAME, $this->getApplicationId());

    return ApnsMessagePeer::doCount($c);
  }

  public function getApnsDeviceCount()
  {
    $c = new Criteria();
    $c->add(ApnsDevicePeer::APPNAME, $this->getApplicationId());
    $c->add(ApnsDevicePeer::STATUS, ApnsDevicePeer::STATUS_ACTIVE);

    return ApnsDevicePeer::doCount($c);
  }

  public function getApnsDevices()
  {
    $c = new Criteria();
    $c->add(ApnsDevicePeer::APPNAME, $this->getApplicationId());
    $c->add(ApnsDevicePeer::STATUS, ApnsDevicePeer::STATUS_ACTIVE);

    return ApnsDevicePeer::doSelect($c);
  }

  public function ignoreApns()
  {
    foreach ($this->getIssues() as $issue) {
      $issue->setAllowNotification(false);
      $issue->save();
    }
  }

  public function createApnsNotifications()
  {
    $apns = new OvenAPNS($this->getApplicationId());
    $devices = $this->getApnsDevices();

    $issues = $this->getIssuesByDate();
    $issue = count($issues) ? $issues[0] : null;

    /** @var ApnsDevice $device */
    foreach ($devices as $device) {
      // Just a little check to make sure this device has the same production level as the publication, i.e. sandbox or production
      // Development devices could be registered against this publication in testing, but trying to send a notification for the wrong
      // APNS endpoint will throw a fatal error
      if ($device->getDevelopment() == $this->getItunesProductionLevel()) {
        $apns->newMessage($device->getPid());
        $apns->addMessageCustom('aps', array('content-available' => 1));
        // No content name for Publication APNS : $apns->addMessageCustom('content-name', $this->getName());
        $apns->queueMessage();
      }
    }

    foreach ($this->getIssues() as $issue) {
      $issue->setNotified(true);
      $issue->save();
    }

    return count($devices);
  }

  public function getIssuesByDate()
  {
    $c = new Criteria();
    $c->addDescendingOrderByColumn(IssuePeer::DATE);
    $c->addDescendingOrderByColumn(IssuePeer::CREATED_AT);
    return $this->getIssues($c);
  }

  public function generateSandboxPemFileName($file)
  {
    if ($file->getOriginalName()) {
      return $this->id . '_sandbox_pem_' . $file->getOriginalName();
    } else {
      return '';
    }
  }

  public function generateProductionPemFileName($file)
  {
    if ($file->getOriginalName()) {
      return $this->id . '_production_pem_' . $file->getOriginalName();
    } else {
      return '';
    }
  }

  public function getSandboxPemPath($fileName=NULL)
  {
    if (!$fileName) {
      $fileName = $this->getSandboxPem();
    }
    if ($fileName) {
      $dir = sfConfig::get('sf_upload_dir');
  //    return $dir . DIRECTORY_SEPARATOR . $this->id . '_sandbox_pem_' . $fileName;
      return $dir . DIRECTORY_SEPARATOR . $fileName;
    }
  }

  public function deleteExistingSandboxPem()
  {
    // remove existing file
    if ($this->getSandboxPem()) {
      $filePath = $this->getSandboxPemPath($this->getSandboxPem());
      file_exists($filePath) && is_file($filePath) && unlink($filePath);
    }
  }

  public function getProductionPemPath($fileName=NULL)
  {
    if (!$fileName) {
      $fileName = $this->getProductionPem();
    }
    if ($fileName) {
      $dir = sfConfig::get('sf_upload_dir');
//    return $dir . DIRECTORY_SEPARATOR . $this->id . '_production_pem_' . $fileName;
      return $dir . DIRECTORY_SEPARATOR . $fileName;
    }
  }

  public function deleteExistingProductionPem()
  {
    // remove existing file
    if ($this->getProductionPem()) {
      $filePath = $this->getProductionPemPath($this->getProductionPem());
      file_exists($filePath) && is_file($filePath) && unlink($filePath);
    }
  }

  public function getSandboxPemExpiryDate()
  {
    if (file_exists($this->getSandboxPemPath())) {
      $arr = openssl_x509_parse(file_get_contents($this->getSandboxPemPath()));
      $pemDate = strftime('%Y-%m-%d', $arr['validTo_time_t']);
      return $pemDate;
    }
  }

  public function getProductionPemExpiryDate()
  {
    if (file_exists($this->getProductionPemPath())) {
      $arr = openssl_x509_parse(file_get_contents($this->getProductionPemPath()));
      $pemDate = strftime('%Y-%m-%d', $arr['validTo_time_t']);
      return $pemDate;
    }
  }

  public function getSandboxPemExpiryDays()
  {
    $days = FALSE;
    $date = $this->getSandboxPemExpiryDate();
    if ($date) {
      $dateCurrent = new DateTime('now');
      $pemDate = new DateTime($date);
      $interval = $dateCurrent->diff($pemDate);
      $days = $interval->days;
    }
    return $days;
  }

  public function getProductionPemExpiryDays()
  {
    $days = FALSE;
    if (file_exists($this->getProductionPemPath())) {
      $arr = openssl_x509_parse(file_get_contents($this->getProductionPemPath()));
      $dateCurrent = new DateTime('now');
      $pemDate = new DateTime(strftime('%Y-%m-%d', $arr['validTo_time_t']));
      $interval = $dateCurrent->diff($pemDate);
      $days = $interval->days;
    }
    return $days;
  }

  public function getSandboxPemExpiryWarning()
  {
    $daysSandbox = $this->getSandboxPemExpiryDays();
    return ($daysSandbox !== FALSE && $daysSandbox < 60);
  }

  public function getProductionPemExpiryWarning()
  {
    $daysProduction = $this->getProductionPemExpiryDays();
    return ($daysProduction !== FALSE && $daysProduction < 60);
  }

} // Publication
