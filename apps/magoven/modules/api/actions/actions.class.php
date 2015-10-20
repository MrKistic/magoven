<?php

/**
 * api actions.
 *
 * @package    MagOven
 * @subpackage api
 * @author     MrKistic
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class apiActions extends sfActions
{

  public function preExecute()
  {
    error_reporting(E_ERROR);
    sfConfig::set('sf_web_debug', false);
    $this->setLayout('json');
    $this->setTemplate('json');
    $this->output = "";
  }

 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {

    $this->output = "<h1>API Placeholder</h1>";

  }

  /**
   * Retrieves a list of available issues for the App ID, for population of Baker Shelf
   *
   * e.g. /api/shelf/:app_id/:user_id
   *
   * You can add an optional ?environment=debug parameter to load all issues, not just published issues.
   *
   * @param sfWebRequest $request
   */
  public function executeGetShelf(sfWebRequest $request)
  {
    $result = array();

    $app_id = $request->getParameter('app_id');
    $user_id = $request->getParameter('user_id');

    $environment = $request->getParameter('environment');

    OvenLog::logInfo('Retrieving Shelf: ' . $environment, $app_id, $user_id);

    $this->forward404Unless($app_id && $user_id);

    // Query all issues for the incoming APP_ID
    $c = new Criteria();

    if ($environment != 'debug') {
      $c->add(IssuePeer::PUBLISHED, TRUE);
    }

    $c->addJoin(IssuePeer::PUBLICATION_ID, PublicationPeer::ID, Criteria::LEFT_JOIN);
    $c->add(PublicationPeer::APPLICATION_ID, $app_id);

    $c->addDescendingOrderByColumn(IssuePeer::DATE);
    $c->addDescendingOrderByColumn(IssuePeer::CREATED_AT);

    $issues = IssuePeer::doSelect($c);

    /** @var Issue $i */
    foreach ($issues as $i) {

      $data = array(
        'name' => $i->getName(),
        'title' => $i->getTitle(),
        'info' => $i->getInfo(),
        'date' => $i->getDate('Y-m-d H:i:s'),
        'cover' => $i->getCoverForShelf()
      );

      if ($i->getPublication()->getIssueDownloadSecurity()) {
        $data['url'] = "http://" . $request->getHost() . "/api/issue/" . $app_id . "/" . $user_id . "/" . $i->getName();
      }
      else {
        $data['url'] = $i->getUrlForShelf();
      }

      if (!$i->getFree())
      {
        $data['product_id'] = $i->getProductId();
      }

      $result[] = $data;

    }

    // Output
    $this->output = json_encode($result);
  }

  /**
   * Validates availability of download of a specific named issue, redirects to download if available
   *
   * e.g. /api/issue/:app_id/:user_id/:name
   *
   * @param sfWebRequest $request
   */
  public function executeGetIssuesByName(sfWebRequest $request)
  {
    $app_id = $request->getParameter('app_id');
    $user_id = $request->getParameter('user_id');
    $name = $request->getParameter('name');

    OvenLog::logInfo('Retrieving Issue by Name: ', $app_id, $user_id);

    $this->forward404Unless($app_id && $user_id && $name);

    // $result = $db->prepare("SELECT * FROM ISSUES WHERE APP_ID = '$app_id' AND NAME = '$name' LIMIT 0,1");

    // Query all issues for the incoming APP_ID
    $c = new Criteria();
    $c->add(IssuePeer::PUBLISHED, TRUE);
    $c->addJoin(IssuePeer::PUBLICATION_ID, PublicationPeer::ID, Criteria::LEFT_JOIN);
    $c->add(PublicationPeer::APPLICATION_ID, $app_id);
    $c->add(IssuePeer::NAME, $name);

    $issue = IssuePeer::doSelectOne($c);

    if ($issue) {

      if ($issue->canDownload($app_id, $user_id)) {

        OvenLog::logInfo("Downloading ISSUE: " . $name, $app_id, $user_id);
        $this->redirect($issue->getUrl(), 303);

      }

    } else {

      OvenLog::logInfo("Incorrect ISSUE: " . $name, $app_id, $user_id);
      $this->forward404('Incorrect Issue Name');

    }

  }

  /**
   * Retrieves a list of Purchased Product IDs
   *
   * e.g. /api/purchases/:app_id/:user_id
   *
   * @param sfWebRequest $request
   */
  public function executeGetPurchases(sfWebRequest $request)
  {
    $result = array();

    $app_id = $request->getParameter('app_id');
    $user_id = $request->getParameter('user_id');

    OvenLog::logInfo('Retrieving Purchases', $app_id, $user_id);

    $this->forward404Unless($app_id && $user_id);

    $subscribed = FALSE;

    try {

      // Retrieve latest receipt for Auto-Renewable-Subscriptions for the APP_ID, USER_ID combination
      $c = new Criteria();
      $c->add(ReceiptPeer::APPLICATION_ID, $app_id);
      $c->add(ReceiptPeer::USER_ID, $user_id);
      $c->add(ReceiptPeer::RECEIPT_TYPE, ReceiptPeer::TYPE_AUTO_RENEWABLE);
      $c->addDescendingOrderByColumn(ReceiptPeer::TRANSACTION_ID);

      $receipt = ReceiptPeer::doSelectOne($c);

      if ($receipt && $receipt->getReceiptType() == ReceiptPeer::TYPE_AUTO_RENEWABLE && $receipt->getBase64Receipt()) {

        $subscription = SubscriptionPeer::retrieveByAppUser($app_id, $user_id);

        if ($subscription) {

          $dateLastValidated = new DateTime($subscription->getLastValidated('Y-m-d H:i:s'));
          $dateExpiration = new DateTime($subscription->getExpirationDate('Y-m-d H:i:s'));
          $dateCurrent = new DateTime('now');
          $interval = $dateCurrent->diff($dateLastValidated);

          OvenLog::logInfo("Time since last validating receipt: " . $interval->format('%h hours %i minutes'), $app_id, $user_id);

          // Only refresh and re-verify receipt if greater than the iTunesCachingDuration - or greater than 1 whole day
          $publication = PublicationPeer::retrieveByApplicationId($app_id);
          $iDuration = $publication->getItunesRevalidationDuration();

          if (($iDuration == 0) || ($interval->format('%h') > $iDuration) || ($interval->format('%a') > 1)) {

            try {

              $data = ReceiptPeer::verifyReceipt($receipt->getBase64Receipt(), $app_id, $user_id);

            } catch(Exception $e) {

              // Status code 21007: This receipt is a sandbox receipt, but it was sent to the production service for verification.
              // Force retry this against the Sandbox URL
              if ($e->getCode() == SubscriptionPeer::APPLE_STATUS_SANDBOX) {
                OvenLog::logError('Sandbox receipt in Production app, retrying against Sandbox', $app_id, $user_id);
                $data = ReceiptPeer::verifyReceipt($receipt->getBase64Receipt(), $app_id, $user_id, TRUE);
              }

            }

            $this->markIssuesAsPurchased($data, $app_id, $user_id);

            // Check if there is an active subscription for the user.  Status=0 is true.
            $subscribed = ($data->status == 0);

          } else {

            // We aren't going to re-verify the receipt now, but we should determine if the Expiration Date <= now
            $result = $dateExpiration <= $dateCurrent;

          }

        }

      } else {

        // now check for a free subscription
        $c = new Criteria();
        $c->add(ReceiptPeer::APPLICATION_ID, $app_id);
        $c->add(ReceiptPeer::USER_ID, $user_id);
        $c->add(ReceiptPeer::RECEIPT_TYPE, ReceiptPeer::TYPE_FREE_SUBSCRIPTION);
        $c->addDescendingOrderByColumn(ReceiptPeer::TRANSACTION_ID);

        $receipt = ReceiptPeer::doSelectOne($c);

        if ($receipt && $receipt->getReceiptType() == ReceiptPeer::TYPE_FREE_SUBSCRIPTION && $receipt->getBase64Receipt()) {
          $subscribed = TRUE;
        }

      } // no receipt

      // Return list of purchased product_ids for the user
      $c = new Criteria();
      $c->add(PurchasePeer::APPLICATION_ID, $app_id);
      $c->add(PurchasePeer::USER_ID, $user_id);

      $purchases = PurchasePeer::doSelect($c);

      /** @var Purchase $p */
      foreach ($purchases as $p) {
        $result[] = $p->getProductId();
      }

      $this->output = json_encode(array(
        'issues' => $result,
        'subscribed' => $subscribed
      ));

    } catch (Exception $e) {

      OvenLog::logError($e->getMessage(), $app_id, $user_id);
      $this->output = '{"error":{"text":"' . $e->getMessage() . '"}}';

    }

  }

  /**
   * Returns a list of Issues in an iTunes ATOM Feed XML Format.  This can be hooked up to the FEED URL within
   * iTunes connect to display up to date information in the Newsstand App Store listing
   *
   * e.g. /api/itunes/:app_id
   *
   * @param sfWebRequest $request
   */
  public function executeGetITunes(sfWebRequest $request)
  {
    try {

      $app_id = $request->getParameter('app_id');

      OvenLog::logInfo('Retrieving iTunes ATOM', $app_id);

      $this->forward404Unless($app_id);

      $publication = PublicationPeer::retrieveByApplicationId($app_id);
      if ($publication) {

        $AtomXML = "<?xml version=\"1.0\" encoding=\"UTF-8\"" . "?>\n";
        $AtomXML .= "<feed xmlns=\"http://www.w3.org/2005/Atom\" xmlns:news=\"http://itunes.apple.com/2011/Newsstand\">\n";
        $AtomXML .= "<updated>" . $publication->getItunesUpdated(DateTime::ATOM) . "</updated>\n";

        // Now get all publication issues
        $c = new Criteria();
        $c->add(IssuePeer::PUBLISHED, TRUE);
        $c->add(IssuePeer::ITUNES_PUBLISHED, NULL, Criteria::NOT_EQUAL);
        $c->addDescendingOrderByColumn(IssuePeer::ITUNES_PUBLISHED);
        $c->addDescendingOrderByColumn(IssuePeer::DATE);

        $issues = $publication->getIssues($c);
        /** @var Issue $issue */
        foreach ($issues as $issue) {

          $AtomXML .= "<entry>\n";
          $AtomXML .= "<id><![CDATA[" . htmlentities($issue->getName()) . "]]></id>\n";
          $AtomXML .= "<updated>" . $issue->getItunesUpdated(DateTime::ATOM) . "</updated>\n";
          $AtomXML .= "<published>" . $issue->getItunesDate(DateTime::ATOM) . "</published>\n";
          $AtomXML .= "<summary><![CDATA[" . htmlentities($issue->getItunesSummary()) . "]]></summary>\n";
          $AtomXML .= "<news:cover_art_icons>\n";
          $AtomXML .= "<news:cover_art_icon size=\"SOURCE\" src=\"" . $issue->getItunesImageUrl() . "\"/>\n";
          $AtomXML .= "</news:cover_art_icons>\n";
          $AtomXML .= "</entry>\n";

        }

        $AtomXML .= "</feed>\n";

        $this->output = $AtomXML; // utf8_encode($AtomXML);

      } else {
        throw new Exception('Incorrect Publication ID');
      }

    } catch (Exception $e) {

      OvenLog::logError($e->getMessage(), $app_id, NULL);
      $this->output = '{"error":{"text":"' . $e->getMessage() . '"}}';

    }
  }

  /**
   * Confirms the purchase by validating the Receipt_Data received for the in app purchase. Records the receipt data
   * in the database and adds the available issues to the user's Purchased List
   *
   * e.g. /api/confirmpurchase/:app_id/:user_id
   *
   * @param sfWebRequest $request
   */
  public function executeConfirmPurchase(sfWebRequest $request)
  {
    $result = array();

    $app_id = $request->getParameter('app_id');
    $user_id = $request->getParameter('user_id');

    $receiptdata = $request->getParameter('receipt_data');
    $receipt_type = $request->getParameter('type');

    // @todo: remove when not testing
//    $receiptdata = "ewoJInNpZ25hdHVyZSIgPSAiQWtob1hWUEFFeU5LTEZzdFowL0c1WjNPS0l2Q0tXem5sTytzdnFtWS9CTG4zZFh2WEFGdnJuaDFFaENGcktOS1h5N2ZuZWhsczZycEZVNGZnNkFmb2tEVVh3K0NDMWhZd2s3aHFnOGk2NEZMeVRrbGRKWHQ3d2UzR1FCTmFrYmlrZEZYV2dsZjU4NTRDMWRRVGtsbGVMREsxUzd0SzJFanJLajd6MVUzbDZ2eUFBQURWekNDQTFNd2dnSTdvQU1DQVFJQ0NHVVVrVTNaV0FTMU1BMEdDU3FHU0liM0RRRUJCUVVBTUg4eEN6QUpCZ05WQkFZVEFsVlRNUk13RVFZRFZRUUtEQXBCY0hCc1pTQkpibU11TVNZd0pBWURWUVFMREIxQmNIQnNaU0JEWlhKMGFXWnBZMkYwYVc5dUlFRjFkR2h2Y21sMGVURXpNREVHQTFVRUF3d3FRWEJ3YkdVZ2FWUjFibVZ6SUZOMGIzSmxJRU5sY25ScFptbGpZWFJwYjI0Z1FYVjBhRzl5YVhSNU1CNFhEVEE1TURZeE5USXlNRFUxTmxvWERURTBNRFl4TkRJeU1EVTFObG93WkRFak1DRUdBMVVFQXd3YVVIVnlZMmhoYzJWU1pXTmxhWEIwUTJWeWRHbG1hV05oZEdVeEd6QVpCZ05WQkFzTUVrRndjR3hsSUdsVWRXNWxjeUJUZEc5eVpURVRNQkVHQTFVRUNnd0tRWEJ3YkdVZ1NXNWpMakVMTUFrR0ExVUVCaE1DVlZNd2daOHdEUVlKS29aSWh2Y05BUUVCQlFBRGdZMEFNSUdKQW9HQkFNclJqRjJjdDRJclNkaVRDaGFJMGc4cHd2L2NtSHM4cC9Sd1YvcnQvOTFYS1ZoTmw0WElCaW1LalFRTmZnSHNEczZ5anUrK0RyS0pFN3VLc3BoTWRkS1lmRkU1ckdYc0FkQkVqQndSSXhleFRldngzSExFRkdBdDFtb0t4NTA5ZGh4dGlJZERnSnYyWWFWczQ5QjB1SnZOZHk2U01xTk5MSHNETHpEUzlvWkhBZ01CQUFHamNqQndNQXdHQTFVZEV3RUIvd1FDTUFBd0h3WURWUjBqQkJnd0ZvQVVOaDNvNHAyQzBnRVl0VEpyRHRkREM1RllRem93RGdZRFZSMFBBUUgvQkFRREFnZUFNQjBHQTFVZERnUVdCQlNwZzRQeUdVakZQaEpYQ0JUTXphTittVjhrOVRBUUJnb3Foa2lHOTJOa0JnVUJCQUlGQURBTkJna3Foa2lHOXcwQkFRVUZBQU9DQVFFQUVhU2JQanRtTjRDL0lCM1FFcEszMlJ4YWNDRFhkVlhBZVZSZVM1RmFaeGMrdDg4cFFQOTNCaUF4dmRXLzNlVFNNR1k1RmJlQVlMM2V0cVA1Z204d3JGb2pYMGlreVZSU3RRKy9BUTBLRWp0cUIwN2tMczlRVWU4Y3pSOFVHZmRNMUV1bVYvVWd2RGQ0TndOWXhMUU1nNFdUUWZna1FRVnk4R1had1ZIZ2JFL1VDNlk3MDUzcEdYQms1MU5QTTN3b3hoZDNnU1JMdlhqK2xvSHNTdGNURXFlOXBCRHBtRzUrc2s0dHcrR0szR01lRU41LytlMVFUOW5wL0tsMW5qK2FCdzdDMHhzeTBiRm5hQWQxY1NTNnhkb3J5L0NVdk02Z3RLc21uT09kcVRlc2JwMGJzOHNuNldxczBDOWRnY3hSSHVPTVoydG04bnBMVW03YXJnT1N6UT09IjsKCSJwdXJjaGFzZS1pbmZvIiA9ICJld29KSW05eWFXZHBibUZzTFhCMWNtTm9ZWE5sTFdSaGRHVXRjSE4wSWlBOUlDSXlNREV6TFRBNExUSXlJREl3T2pFNE9qSTRJRUZ0WlhKcFkyRXZURzl6WDBGdVoyVnNaWE1pT3dvSkluQjFjbU5vWVhObExXUmhkR1V0YlhNaUlEMGdJakV6TnpjeU16QTNOVEV3TURBaU93b0pJblZ1YVhGMVpTMXBaR1Z1ZEdsbWFXVnlJaUE5SUNJeU1HVmhNamswTlRBM09EUTVNakEzTlRVNU9UUm1NRGxqTnpZNU5UVmxOR0l5WTJRME1qaGlJanNLQ1NKdmNtbG5hVzVoYkMxMGNtRnVjMkZqZEdsdmJpMXBaQ0lnUFNBaU5EUXdNREF3TURVd056ZzVOVEUwSWpzS0NTSmlkbkp6SWlBOUlDSXhMakFpT3dvSkltRndjQzFwZEdWdExXbGtJaUE5SUNJMk56UTVOemd5T0RjaU93b0pJblJ5WVc1ellXTjBhVzl1TFdsa0lpQTlJQ0kwTkRBd01EQXdOVEEzT1RReU56Y2lPd29KSW5GMVlXNTBhWFI1SWlBOUlDSXhJanNLQ1NKdmNtbG5hVzVoYkMxd2RYSmphR0Z6WlMxa1lYUmxMVzF6SWlBOUlDSXhNemMzTWpJM09UQTRNREF3SWpzS0NTSnBkR1Z0TFdsa0lpQTlJQ0kyTnpRNU56a3lPRE1pT3dvSkluWmxjbk5wYjI0dFpYaDBaWEp1WVd3dGFXUmxiblJwWm1sbGNpSWdQU0FpTVRVNE5qRTBNRGdpT3dvSkluQnliMlIxWTNRdGFXUWlJRDBnSW1OdmJTNXdaVzVuZFdsdUxuTjFZbk5qY21sd2RHbHZibk11Wm5KbFpTNVVaV0ZqYUdWeWMwRmpZV1JsYlhraU93b0pJbkIxY21Ob1lYTmxMV1JoZEdVaUlEMGdJakl3TVRNdE1EZ3RNak1nTURRNk1EVTZOVEVnUlhSakwwZE5WQ0k3Q2draWIzSnBaMmx1WVd3dGNIVnlZMmhoYzJVdFpHRjBaU0lnUFNBaU1qQXhNeTB3T0MweU15QXdNem94T0RveU9DQkZkR012UjAxVUlqc0tDU0ppYVdRaUlEMGdJbU52YlM1d1pXNW5kV2x1TG01bGQzTnpkR0Z1WkM1VVpXRmphR1Z5YzBGallXUmxiWGtpT3dvSkluQjFjbU5vWVhObExXUmhkR1V0Y0hOMElpQTlJQ0l5TURFekxUQTRMVEl5SURJeE9qQTFPalV4SUVGdFpYSnBZMkV2VEc5elgwRnVaMlZzWlhNaU93cDkiOwoJInBvZCIgPSAiNDQiOwoJInNpZ25pbmctc3RhdHVzIiA9ICIwIjsKfQ==";
//    $receipt_type = 'free-subscription';
//    $receiptdata = "ewoJInNpZ25hdHVyZSIgPSAiQWdFOTVsNjY5WFQrekEyeUhSaldjN2FVaVpwM2NzK3U1VFQ0M08zRS9mcThPK0d6WDREVGk5QytZcS9PTzFXNTNRMFkrOEVOczBlYkJidHRLRjBXUFM0Y3hobmdUNGQvRTE2YjNjV3diMkwxSWVoelhnVWNTckFBRCtCUEEzWSt2TzFTdkRIaFZmaGoyT2tnWVFVZUJxNEhtQnlxNVFQenJLK29UK2I0OUMxZkFBQURWekNDQTFNd2dnSTdvQU1DQVFJQ0NHVVVrVTNaV0FTMU1BMEdDU3FHU0liM0RRRUJCUVVBTUg4eEN6QUpCZ05WQkFZVEFsVlRNUk13RVFZRFZRUUtEQXBCY0hCc1pTQkpibU11TVNZd0pBWURWUVFMREIxQmNIQnNaU0JEWlhKMGFXWnBZMkYwYVc5dUlFRjFkR2h2Y21sMGVURXpNREVHQTFVRUF3d3FRWEJ3YkdVZ2FWUjFibVZ6SUZOMGIzSmxJRU5sY25ScFptbGpZWFJwYjI0Z1FYVjBhRzl5YVhSNU1CNFhEVEE1TURZeE5USXlNRFUxTmxvWERURTBNRFl4TkRJeU1EVTFObG93WkRFak1DRUdBMVVFQXd3YVVIVnlZMmhoYzJWU1pXTmxhWEIwUTJWeWRHbG1hV05oZEdVeEd6QVpCZ05WQkFzTUVrRndjR3hsSUdsVWRXNWxjeUJUZEc5eVpURVRNQkVHQTFVRUNnd0tRWEJ3YkdVZ1NXNWpMakVMTUFrR0ExVUVCaE1DVlZNd2daOHdEUVlKS29aSWh2Y05BUUVCQlFBRGdZMEFNSUdKQW9HQkFNclJqRjJjdDRJclNkaVRDaGFJMGc4cHd2L2NtSHM4cC9Sd1YvcnQvOTFYS1ZoTmw0WElCaW1LalFRTmZnSHNEczZ5anUrK0RyS0pFN3VLc3BoTWRkS1lmRkU1ckdYc0FkQkVqQndSSXhleFRldngzSExFRkdBdDFtb0t4NTA5ZGh4dGlJZERnSnYyWWFWczQ5QjB1SnZOZHk2U01xTk5MSHNETHpEUzlvWkhBZ01CQUFHamNqQndNQXdHQTFVZEV3RUIvd1FDTUFBd0h3WURWUjBqQkJnd0ZvQVVOaDNvNHAyQzBnRVl0VEpyRHRkREM1RllRem93RGdZRFZSMFBBUUgvQkFRREFnZUFNQjBHQTFVZERnUVdCQlNwZzRQeUdVakZQaEpYQ0JUTXphTittVjhrOVRBUUJnb3Foa2lHOTJOa0JnVUJCQUlGQURBTkJna3Foa2lHOXcwQkFRVUZBQU9DQVFFQUVhU2JQanRtTjRDL0lCM1FFcEszMlJ4YWNDRFhkVlhBZVZSZVM1RmFaeGMrdDg4cFFQOTNCaUF4dmRXLzNlVFNNR1k1RmJlQVlMM2V0cVA1Z204d3JGb2pYMGlreVZSU3RRKy9BUTBLRWp0cUIwN2tMczlRVWU4Y3pSOFVHZmRNMUV1bVYvVWd2RGQ0TndOWXhMUU1nNFdUUWZna1FRVnk4R1had1ZIZ2JFL1VDNlk3MDUzcEdYQms1MU5QTTN3b3hoZDNnU1JMdlhqK2xvSHNTdGNURXFlOXBCRHBtRzUrc2s0dHcrR0szR01lRU41LytlMVFUOW5wL0tsMW5qK2FCdzdDMHhzeTBiRm5hQWQxY1NTNnhkb3J5L0NVdk02Z3RLc21uT09kcVRlc2JwMGJzOHNuNldxczBDOWRnY3hSSHVPTVoydG04bnBMVW03YXJnT1N6UT09IjsKCSJwdXJjaGFzZS1pbmZvIiA9ICJld29KSW05eWFXZHBibUZzTFhCMWNtTm9ZWE5sTFdSaGRHVXRjSE4wSWlBOUlDSXlNREV6TFRFd0xUQXlJREU1T2pVek9qVTRJRUZ0WlhKcFkyRXZURzl6WDBGdVoyVnNaWE1pT3dvSkluVnVhWEYxWlMxcFpHVnVkR2xtYVdWeUlpQTlJQ0l3TURBd1lqQTBNekUzWWpnaU93b0pJbTl5YVdkcGJtRnNMWFJ5WVc1ellXTjBhVzl1TFdsa0lpQTlJQ0l4TURBd01EQXdNRGc0T1RnNU9EZ3lJanNLQ1NKaWRuSnpJaUE5SUNJeExqRWlPd29KSW5SeVlXNXpZV04wYVc5dUxXbGtJaUE5SUNJeE1EQXdNREF3TURnNU1USXdPVE0wSWpzS0NTSnhkV0Z1ZEdsMGVTSWdQU0FpTVNJN0Nna2liM0pwWjJsdVlXd3RjSFZ5WTJoaGMyVXRaR0YwWlMxdGN5SWdQU0FpTVRNNE1EYzJPRGd6T0RBd01DSTdDZ2tpZFc1cGNYVmxMWFpsYm1SdmNpMXBaR1Z1ZEdsbWFXVnlJaUE5SUNJM05EbEJNVVV3TXkwelJUaENMVFExUlRZdE9FTkJRaTAyUXpaRU5EbEZORFkzTVVZaU93b0pJbkJ5YjJSMVkzUXRhV1FpSUQwZ0ltTnZiUzV3Wlc1bmRXbHVMbTVsZDNOemRHRnVaQzUwWldGamFHVnljMkZqWVdSbGJYa3VkVzkzWDNKMWJpSTdDZ2tpYVhSbGJTMXBaQ0lnUFNBaU5qZzNOakF5TnpZNElqc0tDU0ppYVdRaUlEMGdJbU52YlM1d1pXNW5kV2x1TG01bGQzTnpkR0Z1WkM1VVpXRmphR1Z5YzBGallXUmxiWGtpT3dvSkluQjFjbU5vWVhObExXUmhkR1V0YlhNaUlEMGdJakV6T0RBNE5UTTRPVE16TmpFaU93b0pJbkIxY21Ob1lYTmxMV1JoZEdVaUlEMGdJakl3TVRNdE1UQXRNRFFnTURJNk16RTZNek1nUlhSakwwZE5WQ0k3Q2draWNIVnlZMmhoYzJVdFpHRjBaUzF3YzNRaUlEMGdJakl3TVRNdE1UQXRNRE1nTVRrNk16RTZNek1nUVcxbGNtbGpZUzlNYjNOZlFXNW5aV3hsY3lJN0Nna2liM0pwWjJsdVlXd3RjSFZ5WTJoaGMyVXRaR0YwWlNJZ1BTQWlNakF4TXkweE1DMHdNeUF3TWpvMU16bzFPQ0JGZEdNdlIwMVVJanNLZlE9PSI7CgkiZW52aXJvbm1lbnQiID0gIlNhbmRib3giOwoJInBvZCIgPSAiMTAwIjsKCSJzaWduaW5nLXN0YXR1cyIgPSAiMCI7Cn0=";
//    $receipt_type = 'issue';

    OvenLog::logInfo('Confirming purchase: ' . $receipt_type, $app_id, $user_id);

    $this->forward404Unless($app_id && $user_id && $receiptdata && $receipt_type);

    try {

      try {

        $iTunesReceiptInfo = $this->verifyReceipt($receiptdata, $app_id, $user_id);

      } catch (Exception $e) {

        // 21007 This receipt is a sandbox receipt, but it was sent to the production service for verification.
        // Force retry this against the Sandbox URL
        if ($e->getCode() == SubscriptionPeer::APPLE_STATUS_SANDBOX) {
          OvenLog::logError('Sandbox receipt in Production app, retrying against Sandbox: '. $response, $app_id, $user_id);
          $iTunesReceiptInfo = $this->verifyReceipt($receiptdata, $app_id, $user_id, TRUE);
        }

      }

      // Jailbroken Device Hack Check
      // Jailbroken devices often try to spoof purchases by using fake receipts
      // Compare expected APP_ID to the Receipt (BID) Bundle Identifier.
      if ($iTunesReceiptInfo && $iTunesReceiptInfo->receipt->bid == $app_id) {

        /** @var Receipt $receipt */
        if ($receipt = ReceiptPeer::retrieveUnique($app_id, $user_id, $iTunesReceiptInfo->receipt->transaction_id)) {

          $receipt->setUpdatedAt(time());

        } else {

          $receipt = new Receipt();
          $receipt->setApplicationId($app_id);
          $receipt->setQuantity($iTunesReceiptInfo->receipt->quantity);
          $receipt->setProductId($iTunesReceiptInfo->receipt->product_id);
          $receipt->setReceiptType($receipt_type);
          $receipt->setTransactionId($iTunesReceiptInfo->receipt->transaction_id);
          $receipt->setUserId($user_id);
          $receipt->setPurchaseDate($iTunesReceiptInfo->receipt->purchase_date);
          $receipt->setOriginalTransactionId($iTunesReceiptInfo->receipt->original_transaction_id);
          $receipt->setOriginalPurchaseDate($iTunesReceiptInfo->receipt->original_purchase_date);
          $receipt->setAppItemId($iTunesReceiptInfo->receipt->item_id);
          $receipt->setVersionExternalIdentifier($iTunesReceiptInfo->receipt->version_external_identifier);
          $receipt->setBid($iTunesReceiptInfo->receipt->bid);
          $receipt->setBvrs($iTunesReceiptInfo->receipt->bvrs);
          $receipt->setBase64Receipt($receiptdata);

        }

        $receipt->save();

        // If successful, record the user's purchase
        if ($receipt_type == 'auto-renewable-subscription') {

          $this->markIssuesAsPurchased($iTunesReceiptInfo, $app_id, $user_id);

        } else if($receipt_type == 'issue') {

          $this->markIssueAsPurchased($iTunesReceiptInfo->receipt->product_id, $app_id, $user_id);

        } else if($receipt_type == 'free-subscription') {

          // There is nothing to be done for a free subscription

        }

        $this->output = '{"success":"true"}';

      } else {

        // unknown error - we have no receipt info
        $this->output = '{"success":"false"}';

      }

    } catch(Exception $e) {

      OvenLog::logError($e->getMessage(), $app_id, $user_id);
      $this->output = '{"error":{"text":"' . $e->getMessage() . '"}}';

    }

  }

  /**
   * Stores the APNS Token in the database for the given App ID and User ID
   *
   * e.g. /api/apns/:app_id/:user_id
   *
   * @param sfWebRequest $request
   */
  public function executeRegisterAPNS(sfWebRequest $request)
  {
    $app_id = $request->getParameter('app_id');
    $user_id = $request->getParameter('user_id');
    $apns_token = $request->getParameter('apns_token');

    // EasyAPNS extra
    $app_version = $request->getParameter('appversion');
    $devicename = $request->getParameter('devicename');
    $deviceversion = $request->getParameter('deviceversion');
    $devicemodel = $request->getParameter('devicemodel');

    OvenLog::logInfo('Storing APNS Token: ' . $apns_token, $app_id, $user_id);

    $this->forward404Unless($app_id && $user_id && $apns_token);

//    try {

//      $this->logMessage('Fetching APNS');

      if ($apns = ApnsDevicePeer::retrieveUnique($app_id, $user_id)) {

//        $this->logMessage('Got APNS');

        // write history
        $apns->updateHistory();

      } else {

//        $this->logMessage('Creating APNS');

        $apns = new ApnsDevice();
        $apns->setApplicationId($app_id);
        $apns->setUserId($user_id);

      }

//      $this->logMessage('Saving APNS');

      // update data
      $apns->setDevicetoken($apns_token);
      $apns->setAppversion($app_version);
      $apns->setDevicename($devicename);
      $apns->setDeviceversion($deviceversion);
      $apns->setDevicemodel($devicemodel);

      if ($publication = PublicationPeer::retrieveByApplicationId($app_id)) {
        $apns->setDevelopment($publication->getDevelopmentMode() ? 'sandbox' : 'production');
      } else {
        $apns->setDevelopment('sandbox'); // we want to default to this!
      }

      $apns->setStatus('active');

      $apns->save();

      $this->output = '{"success":"true"}';

//    } catch (Exception $e) {
//
//      $this->logMessage($e->getMessage(), 'err');
//      OvenLog::logError($e->getMessage(), $app_id, $user_id);
//      $this->output = '{"error":{"text":"' . $e->getMessage() . '"}}';
//
//    }

  }

  public function executeApnsCron(sfWebRequest $request)
  {
    // check all publications for issued messages, process if available
    OvenLog::logInfo("Processing APNS Cron Job");

    $c = new Criteria();

    $c->add(PublicationPeer::DEVELOPMENT_MODE, false);

    $c->addJoin(ApnsDevicePeer::APPNAME, PublicationPeer::APPLICATION_ID);
    $c->addJoin(ApnsMessagePeer::FK_DEVICE, ApnsDevicePeer::PID);

    $c->addGroupByColumn(ApnsDevicePeer::APPNAME);

    $pubs = PublicationPeer::doSelect($c);

    /** @var Publication $publication */
    foreach ($pubs as $publication) {

      $request->setParameter('app_id', $publication->getApplicationId());
      $this->executeProcessApnsQueue($request);

    }

  }

  /**
   * Process up to 100 messages in the APNS queue.
   * Best called via cron. For example:
   *
   * * * * * * wget http://magoven.yourdomain.com/api/processApnsQueue/{Application ID} > /var/log/magoven/apns.log 2>&1 &
   *
   * @param sfWebRequest $request
   */
  public function executeProcessApnsQueue(sfWebRequest $request)
  {
    $app_id = $request->getParameter('app_id');

    OvenLog::logInfo("Processing APNS Queue", $app_id);

    $this->forward404Unless($app_id);

    $apns = new OvenAPNS($app_id);

    // @todo: This should return a value - display results
    $apns->processQueue();

    print "<h1>Done for ".$app_id."!</h1>";
  }

  /**
   * Validate InApp Purchase Receipt, by calling the Apple iTunes verifyReceipt method
   * Note that this seems to take between 2-4 seconds on average
   *
   * @param String $receipt
   * @param String $app_id
   * @param String $user_id
   * @param Publication $publication
   * @return Boolean
   */
  function verifyReceipt($receipt, $app_id, $user_id, $forceSandbox=NULL)
  {
    OvenLog::logInfo("Verifying receipt with Apple", $app_id, $user_id);

    $publication = PublicationPeer::retrieveByApplicationId($app_id);
    $sharedSecret = $publication->getItunesSharedSecret();

    if ($publication->isSandbox() || $forceSandbox) {
      $endpoint = SubscriptionPeer::ITUNES_URL_SANDBOX;
    }
    else {
      $endpoint = SubscriptionPeer::ITUNES_URL_PRODUCTION;
    }

    OvenLog::logInfo('Endpoint = ' . $endpoint, $app_id, $user_id);

    // If no shared secret exists, don't send it to the verifyReceipt call - but it should exist!
    if ($sharedSecret) {

      $postData = json_encode(array(
        'receipt-data' => $receipt,
        'password' => $sharedSecret)
      );

    } else {

      $postData = json_encode(array(
        'receipt-data' => $receipt)
      );

    }

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $response = curl_exec($ch);
    $errno = curl_errno($ch);
    $errmsg = curl_error($ch);
    curl_close($ch);

    if ($errno != 0) {
      OvenLog::logError('verifyReceipt::Curl Error: ' . $errmsg . '; ' . $errno, $app_id, $user_id);
      throw new Exception($errmsg, $errno);
    }

    $data = json_decode($response);

    if (!is_object($data)) {
      OvenLog::logError('verifyReceipt::Invalid Response Data: '. $response, $app_id, $user_id);
      throw new Exception('Invalid Response Data');
    }

    OvenLog::logInfo("Receipt Data: Status = {$data->status}", $app_id, $user_id);
    OvenLog::logInfo(print_r($data, true), $app_id, $user_id);

    if (!isset($data->status) || ($data->status != 0 && $data->status != SubscriptionPeer::APPLE_STATUS_EXPIRED)) {
      $product_id = $data->receipt->product_id;
      OvenLog::logWarning('verifyReceipt::Invalid receipt: PRODUCT ID = ' . $product_id  . '; STATUS = ' . $data->status, $app_id, $user_id);
      throw new Exception('Invalid Receipt', $data->status);
    }

    return $data;
  }

  /**
   * Mark all available (paid) issues as purchased for a given user
   *
   * @param $app_store_data
   * @param $app_id
   * @param $user_id
   */
  function markIssuesAsPurchased($app_store_data, $app_id, $user_id)
  {
    OvenLog::logInfo("Marking Issues as Purchased", $app_id, $user_id);

    $con = Propel::getConnection();

    $receipt = $app_store_data->receipt;
    $startDate = new DateTime($receipt->purchase_date_pst);

    if ($app_store_data->status == 0) {
      $endDate = new DateTime($app_store_data->latest_receipt_info->expires_date_formatted_pst);
    }
    else if ($app_store_data->status == SubscriptionPeer::APPLE_STATUS_EXPIRED) {
      $endDate = new DateTime($app_store_data->latest_expired_receipt_info->expires_date_formatted_pst);
    }

    // Now update the Purchases table with all Issues that fall within the subscription start and expiration date
    $startDateFormatted = $startDate->format('Y-m-d H:i:s');
    $endDateFormatted = $endDate->format('Y-m-d H:i:s');

    // Get First Day of the Month that the Receipt was generated for (Start)
    $issuesStartDateFormatted = $startDate->format('Y-m-01 00:00:00');
    // Get Last Day of the Month that the Receipt was generated for (Expiration)
    $issuesEndDateFormatted = $endDate->format('Y-m-t 23:59:59');

    // Update Subscriptions Table for user with current active subscription start and expiration date
    if ($this->updateSubscription($app_id, $user_id, $startDateFormatted, $endDateFormatted)) {

      $pub = PublicationPeer::retrieveByApplicationId($app_id);

      if ($pub->getItunesProductionLevel() == PublicationPeer::ITUNES_SANDBOX) {

        // If we are in Sandbox Mode, unlock all issues by default for testing purposes

//        $result = $db->query("SELECT PRODUCT_ID FROM ISSUES
//		  							 WHERE APP_ID = '$app_id'
//		  							 AND PRICING = 'paid'");

        $c = new Criteria();
        $c->add(IssuePeer::PUBLISHED, TRUE);
        $c->add(IssuePeer::FREE, FALSE);  // not free == paid

        $issues = IssuePeer::doSelect($c);

      } else {

        // if we are in Production - determine based on Subscription Behavior setting

        if ($pub->getSubscriptionBehavior() == PublicationPeer::SUBSCRIPTION_TERM) {

//          $result = $db->query("SELECT PRODUCT_ID FROM ISSUES
//								WHERE APP_ID = '$app_id'
//								AND `DATE` >= '$issuesStartDateFormatted'
//								AND `DATE` <= '$issuesEndDateFormatted'
//								AND PRICING = 'paid'
//								AND AVAILABILITY = 'published'");

          $c = new Criteria();
          $c->add(IssuePeer::PUBLISHED, TRUE);
          $c->add(IssuePeer::FREE, FALSE);
          $c->add(IssuePeer::DATE, $issuesStartDateFormatted, Criteria::GREATER_EQUAL);
          $c->add(IssuePeer::DATE, $issuesEndDateFormatted, Criteria::LESS_EQUAL);

          $issues = $pub->getIssues($c);

        } else { // PublicationPeer::SUBSCRIPTION_ALL or by default

//          $result = $db->query("SELECT PRODUCT_ID FROM ISSUES
//		  							 WHERE APP_ID = '$app_id'
//		  							 AND PRICING = 'paid'");

            $c = new Criteria();
            $c->add(IssuePeer::PUBLISHED, TRUE);
            $c->add(IssuePeer::FREE, FALSE);

            $issues = $pub->getIssues($c);

          }

      } // if sandbox

      foreach ($issues as $issue) {
        $product_ids_to_mark[] = $issue->getId();
      }

      $insert = sprintf("INSERT IGNORE INTO %s (%s, %s, %s) VALUES (:app_id, :user_id, :product_id)",
        PurchasePeer::TABLE_NAME,
        PurchasePeer::APPLICATION_ID,
        PurchasePeer::USER_ID,
        PurchasePeer::PRODUCT_ID
      );

      $stmt = $con->prepare($insert);
      $stmt->bindParam("app_id", $app_id);
      $stmt->bindParam("user_id", $user_id);

      foreach($product_ids_to_mark as $product_id) {
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
      }

    }

  }

  /**
   * Mark a single issue as purchased for a given user
   *
   * @param $product_id
   * @param $app_id
   * @param $user_id
   */
  function markIssueAsPurchased($product_id, $app_id, $user_id)
  {

    OvenLog::logInfo("Marking a single Issue as Purchased: " . $product_id, $app_id, $user_id);

    $con = Propel::getConnection();
    $sql = sprintf("INSERT IGNORE INTO %s (%s, %s, %s) VALUES (:app_id, :user_id, :product_id)",
      PurchasePeer::TABLE_NAME,
      PurchasePeer::APPLICATION_ID,
      PurchasePeer::USER_ID,
      PurchasePeer::PRODUCT_ID
    );

//  let the Exception bubble in this case
//    try {

      $stmt = $con->prepare($sql);
      $stmt->bindParam("app_id", $app_id);
      $stmt->bindParam("user_id", $user_id);
      $stmt->bindParam("product_id", $product_id);
      $stmt->execute();

//    } catch(PDOException $e) {
//
//      OvenLog::logError($e->getMessage(), $app_id, $user_id);
//      $this->output = '{"error":{"text":"' . $e->getMessage() . '"}}';
//
//    }

  }

  /**
   * Update the Subscription Record for a specific user with Effective Date and Expiration Date
   *
   * @param $app_id
   * @param $user_id
   * @param $effective_date
   * @param $expiration_date
   */
  function updateSubscription($app_id, $user_id, $effective_date, $expiration_date)
  {
    $currentDate = new DateTime('now');
    $lastValidated = $currentDate->format('Y-m-d H:i:s');

    OvenLog::logInfo("Updating subscription effective dates", $app_id, $user_id);

    $con = Propel::getConnection();
    $sql = sprintf("INSERT INTO %s (%s, %s, %s, %s, %s)
	    			VALUES (:app_id, :user_id, :effective_date, :expiration_date, :last_validated)
	    			ON DUPLICATE KEY UPDATE EFFECTIVE_DATE=:effective_date, EXPIRATION_DATE=:expiration_date, LAST_VALIDATED=:last_validated",
      SubscriptionPeer::TABLE_NAME,
      SubscriptionPeer::APPLICATION_ID,
      SubscriptionPeer::USER_ID,
      SubscriptionPeer::EFFECTIVE_DATE,
      SubscriptionPeer::EXPIRATION_DATE,
      SubscriptionPeer::LAST_VALIDATED
    );

    try {

      $stmt = $con->prepare($sql);
      $stmt->bindParam("app_id", $app_id);
      $stmt->bindParam("user_id", $user_id);
      $stmt->bindParam("effective_date", $effective_date);
      $stmt->bindParam("expiration_date", $expiration_date);
      $stmt->bindParam("last_validated", $lastValidated);
      $stmt->execute();

      return true;

    } catch(PDOException $e) {

      OvenLog::logError($e->getMessage(), $app_id, $user_id);
      throw new Exception($e->getMessage());

    }

  }

}
