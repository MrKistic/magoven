<?php

/**
 * OvenLog class
 *
 * Provide logging functionality for MagOven. Use the Symfony logs if enabled, also write entries into the
 * DB for Publications in Development mode.
 *
 *
 * @package    MagOven
 * @subpackage Vendor.Penguin
 * @author     MrKistic
 */
class OvenLog {

  /**
   * Wrapper method for getting a symfony logger object
   *
   * @return object
   */
  public static function getLogger()
  {
    return sfContext::getInstance()->getLogger();
  }

  /**
   * Wrapper method for logging debug message
   *
   * @param string $message
   */
  public static function logInfo($message, $app_id = null, $user_id = null)
  {
    if (sfConfig::get('sf_logging_enabled')) {
      self::getLogger()->info($message . ' - ' . $app_id . ' - ' . $user_id);
    }

    // DB Log - only if Application is in Debug Mode
    if (!$app = PublicationPeer::retrieveByApplicationId($app_id)) {

      // missing Publication - log error
      OvenLog::logError('MISSING PUBLICATION: ' . $message, $app_id, $user_id);

    } else {

      if ($app->getDevelopmentMode()) {
        $log = new SystemLog();
        $log->setLogType('Info');
        $log->setMessage($message);
        $log->setApplicationId($app_id);
        $log->setUserId($user_id);
        $log->save();
      }

    }
  }

  /**
   * Wrapper method for logging an error
   *
   * @param string $message
   */
  public static function logError($message, $app_id, $user_id)
  {
    if (sfConfig::get('sf_logging_enabled')) {
      self::getLogger()->err($message . ' - ' . $app_id . ' - ' . $user_id);
    }

    $log = new SystemLog();
    $log->setLogType('Error');
    $log->setMessage($message);
    $log->setApplicationId($app_id);
    $log->setUserId($user_id);
    $log->save();
  }

  /**
   * Wrapper method for logging a warning
   *
   * @param string $message
   */
  public static function logWarning($message, $app_id, $user_id)
  {
    if (sfConfig::get('sf_logging_enabled')) {
      self::getLogger()->warning($message . ' - ' . $app_id . ' - ' . $user_id);
    }

    $log = new SystemLog();
    $log->setLogType('Warning');
    $log->setMessage($message);
    $log->setApplicationId($app_id);
    $log->setUserId($user_id);
    $log->save();
  }

}
