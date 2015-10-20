<?php

/**
 * OvenAPNS class
 *
 * Encapsulate EasyAPNS and override certain methods to provide required functionality for MagOven.
 *
 * @package    MagOven
 * @subpackage Vendor.Penguin
 * @author     MrKistic
 */
class OvenAPNS extends APNS {

  private $app_id;

  /**
   * Passphrase for Private Key with passphrase
   *
   * @var string
   * @access private
   */
  private $passphrase = '';

  /**
   * Passphrase for Private Key with passphrase
   *
   * @var string
   * @access private
   */
  private $sandboxPassphrase = '';

  /**
   * Constructor.
   *
   * Initializes a database connection and perfoms any tasks that have been assigned.
   * Overriding to correctly assign Symfony app.yml settings for certificate options, DB settings etc.
   *
   * Create a new PHP file named apns.php on your website...
   *
   * <code>
   * <?php
   * $db = new DbConnect('localhost','dbuser','dbpass','dbname');
   * $db->show_errors();
   * $apns = new APNS($db);
   * ?>
   * </code>
   *
   * Alternate for Different Certificates
   *
   * <code>
   * <?php
   * $db = new DbConnect('localhost','dbuser','dbpass','dbname');
   * $db->show_errors();
   * $apns = new APNS($db, NULL, '/usr/local/apns/alt_apns.pem', '/usr/local/apns/alt_apns-dev.pem');
   * ?>
   * </code>
   *
   * Your iPhone App Delegate.m file will point to a PHP file with this APNS Object.  The url will end up looking something like:
   * https://secure.yourwebsite.com/apns.php?task=register&appname=My%20App&appversion=1.0.1&deviceuid=e018c2e46efe185d6b1107aa942085a59bb865d9&devicetoken=43df9e97b09ef464a6cf7561f9f339cb1b6ba38d8dc946edd79f1596ac1b0f66&devicename=My%20Awesome%20iPhone&devicemodel=iPhone&deviceversion=3.1.2&pushbadge=enabled&pushalert=disabled&pushsound=enabled
   *
   * @param array $args Optional arguments passed through $argv or $_GET
   * @access 	public
   */
  public function __construct($app_id, $args=NULL)
  {
    ini_set('display_errors', 'on');
    error_reporting(E_ALL & ~E_NOTICE);

    $this->app_id = $app_id;

    // Get the publication
    $publication = PublicationPeer::retrieveByApplicationId($app_id);
    if (!$publication) {
      OvenLog::logError('OvenAPNS::construct Publication not found', $app_id, NULL);
      die('OvenAPNS::construct Publication not found for app_id = ' . $app_id);
    }
    $this->productionLevel = $publication->getItunesProductionLevel();

    // Get database settings
    $propel_config = Propel::getConfiguration();

    preg_match('/dbname=([^;]+);/', $propel_config['datasources']['propel']['connection']['dsn'], $matches);
    $databaseName = $matches[1];

    preg_match('/host=([^;]+)/', $propel_config['datasources']['propel']['connection']['dsn'], $matches);
    $host = $matches[1];

    $user = $propel_config['datasources']['propel']['connection']['user'];
    $password = $propel_config['datasources']['propel']['connection']['password'];

    $db = new DbConnect($host, $user, $password, $databaseName);

    // Fetch the certificate configs
    $sandboxCertificate = $publication->getSandboxPemPath();
    $certificate = $publication->getProductionPemPath();

    if (!file_exists($certificate)) {
      $certificate = $sandboxCertificate;
    }

    parent::__construct($db, $args, $certificate, $sandboxCertificate, NULL);

    // set passphrases
    $this->sandboxPassphrase = $publication->getSandboxPassword();
    $this->passphrase = $publication->getProductionPassword();

  }

  /**
   * Trigger Error
   *
   * Use PHP error handling to trigger User Errors or Notices.  If logging is enabled, errors will be written to the log as well.
   * Overriding to use built in Symfony logging and error_reporting levels.
   *
   * @param string $error Error String
   * @param int $type Type of Error to Trigger
   * @access private
   */
  protected function _triggerError($error, $type=E_USER_NOTICE)
  {
    $backtrace = debug_backtrace();
    $backtrace = array_reverse($backtrace);
    $error .= "\n";
    $i = 1;

    foreach ($backtrace as $errorcode) {
      $file = ($errorcode['file']!='') ? "-> File: ".basename($errorcode['file'])." (line ".$errorcode['line'].")":"";
      $error .= "\n\t".$i.") ".$errorcode['class']."::".$errorcode['function']." {$file}";
      $i++;
    }
    $error .= "\n\n";

    // Log with Symfony
    if (sfConfig::get('sf_logging_enabled')) {

      $level = error_reporting();

      switch ($type) {

        case E_USER_ERROR:
          sfContext::getInstance()->getLogger()->err($error);
          if ($level & E_ERROR) {
            trigger_error($error, $type);
          }
          break;
        case E_USER_NOTICE:
          sfContext::getInstance()->getLogger()->notice($error);
          if ($level & E_NOTICE) {
            trigger_error($error, $type);
          }
          break;
        case E_USER_WARNING:  // just in case
          sfContext::getInstance()->getLogger()->warning($error);
          if ($level & E_WARNING) {
            trigger_error($error, $type);
          }
          break;
        default:
          sfContext::getInstance()->getLogger()->info($error);
          if ($level & E_NOTICE) {
            trigger_error($error, $type);
          }
          break;

      }

    }

  }

  /**
   * Connect the SSL stream (sandbox or production)
   * Overriding because Easy APNS does not deal with the Passphrase correctly
   *
   * @param $development string Development environment - sandbox or production
   * @return bool|resource status whether the socket connected or not.
   * @access private
   */
  protected function _connectSSLSocket($development)
  {
    $ctx = $this->_getContext($development);
    $this->sslStreams[$development] = stream_socket_client(
      $this->apnsData[$development]['ssl'],
      $error,
      $errorString,
      100,
      (STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT),
      $ctx
    );

    if (!$this->sslStreams[$development]) {
      $this->_triggerError("Failed to connect to APNS: {$error} {$errorString}.");
      unset($this->sslStreams[$development]);
      return false;
    }

    return $this->sslStreams[$development];
  }

  /**
   * Fetch APNS Messages
   *
   * This gets called automatically by _pushMessage.  This will check with APNS for any invalid tokens and disable them from receiving further notifications.
   * Overriding because Easy APNS does not deal with the Passphrase correctly
   *
   * @param string $development Which SSL to connect to, Sandbox or Production
   * @access protected
   */
  protected function _checkFeedback($development)
  {
    $ctx = $this->_getContext($development);

    stream_context_set_option($ctx, 'ssl', 'verify_peer', false);
    $fp = stream_socket_client($this->apnsData[$development]['feedback'], $error, $errorString, 100, (STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT), $ctx);

    if (!$fp) {
      $this->_triggerError("Failed to connect to device: {$error} {$errorString}.");
    }

    while ($devcon = fread($fp, 38)) {
      $arr = unpack("H*", $devcon);
      $rawhex = trim(implode("", $arr));
      $token = substr($rawhex, 12, 64);
      if (!empty($token)) {
        $this->_unregisterDevice($token);
        $this->_triggerError("Unregistering Device Token: {$token}.");
      }
    }

    fclose($fp);
  }

  private function _getContext($development)
  {
    //@todo: Deal with native passphrase functionality better

    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', $this->apnsData[$development]['certificate']);

    $passphrase = $development ? $this->sandboxPassphrase : $this->passphrase;

    if (!empty($passphrase)) {
      stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
    }

    return $ctx;
  }

  /**
   * Fetch Messages
   *
   * This gets called by a cron job that runs as often as you want.  You might want to set it for every minute.
   *
   * @access protected
   */
  protected function _fetchMessages(){
    // only send one message per user... oldest message first
    $sql = "SELECT
				`apns_messages`.`pid`,
				`apns_messages`.`message`,
				`apns_devices`.`devicetoken`,
				`apns_devices`.`development`
			FROM `apns_messages`
			LEFT JOIN `apns_devices` ON (`apns_devices`.`pid` = `apns_messages`.`fk_device` AND `apns_devices`.`clientid` = `apns_messages`.`clientid`)
			WHERE `apns_messages`.`status`='queued'
				AND `apns_messages`.`delivery` <= NOW()
				AND `apns_devices`.`status`='active'
				AND `apns_devices`.`appname`='{$this->app_id}'
				AND `apns_devices`.`development` = '{$this->productionLevel}'
			GROUP BY `apns_messages`.`fk_device`
			ORDER BY `apns_messages`.`created` ASC
			LIMIT 100;";

    $this->_iterateMessages($sql);
  }

  /**
   * Flush Messages
   *
   * This gets called by a cron job that runs as often as you want.  You might want to set it for every minute.
   * Like _fetchMessages, but sends all the messages for each device (_fetchMessage sends only the first message for device)
   *
   * @access protected
   */
  protected function _flushMessages(){
    // only send one message per user... oldest message first
    $sql = "SELECT
				`apns_messages`.`pid`,
				`apns_messages`.`message`,
				`apns_devices`.`devicetoken`,
				`apns_devices`.`development`
			FROM `apns_messages`
			LEFT JOIN `apns_devices` ON (`apns_devices`.`pid` = `apns_messages`.`fk_device` AND `apns_devices`.`clientid` = `apns_messages`.`clientid`)
			WHERE `apns_messages`.`status`='queued'
				AND `apns_messages`.`delivery` <= NOW()
				AND `apns_devices`.`status`='active'
				AND `apns_devices`.`appname`='{$this->app_id}'
			ORDER BY `apns_messages`.`created` ASC
			LIMIT 100;";

    $this->_iterateMessages($sql);
  }

}
